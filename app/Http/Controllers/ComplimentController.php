<?php

namespace App\Http\Controllers;

use App\Exports\ComplimentsExport;
use App\Models\CompletionType;
use App\Models\Compliment;
use App\Models\Department;
use App\Models\Status;
use App\Models\User;
use App\Models\Worker;
use App\Notifications\SystemNotification;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;

class ComplimentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Compliment::with(['department', 'careUser', 'completion_type', 'status'])
                ->select('compliments.*');

            // ✅ Apply filters if provided
            if ($request->filled('department_id')) {
                $data->whereIn('department_id', $request->department_id);
            }
            if ($request->filled('completion_type_id')) {
                $data->whereIn('completion_type_id', $request->completion_type_id);
            }
            if ($request->filled('status_id')) {
                $data->whereIn('status_id', $request->status_id);
            }
            if ($request->filled('care_user_id')) {
                $data->whereIn('care_user_id', $request->care_user_id);
            }
            if ($request->filled('target_type')) {
                $data->whereIn('target_type', $request->target_type);
            }
            if ($request->filled('date_from') && $request->filled('date_to')) {
                $data->whereBetween('created_at', [$request->date_from, $request->date_to]);
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('customer_name', fn($row) => $row->customer_name ?? '-')
                ->addColumn('department', fn($row) => $row->department->name_lang ?? '-')
                ->addColumn('code', fn($row) => $row->department->code ?? '-')
                ->addColumn('phone', fn($row) => $row->phone ?? '-')
                ->addColumn('plate_number', fn($row) => $row->plate_number ?? '-')
                ->addColumn('created_at', fn($row) => $row->created_at->format('Y-m-d H:i') ?? '-')
                ->addColumn('completion_type', fn($row) => $row->completion_type->name_lang ?? '-')
                ->addColumn('care_user', fn($row) => $row->careUser->name ?? '-')
                ->addColumn('status', fn($row) => $row->status->name_lang ?? '-')
                ->addColumn('action', function ($row) {
                    return view('compliments.actions', compact('row'))->render();
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        // ✅ Regular view load
        return view('compliments.index', [
            'departments' => Department::all(),
            'statuses' => Status::all(),
            'completionTypes' => CompletionType::all(),
            'careUsers' => User::where('role', 'Customer Care')->get(),
        ]);
    }

    // ✅ Export to Excel
    public function export(Request $request)
    {
        return Excel::download(new ComplimentsExport($request), 'compliments.xlsx');
    }

    public function create()
    {
        $departments = Department::all();
        $completionTypes = CompletionType::all();
        return view('compliment-form', compact('departments', 'completionTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name'     => 'required|string|max:255',
            'phone'             => 'required|string|max:20',
            'plate_number'      => 'nullable|string|max:20',
            'department_id'     => 'required|exists:departments,id',
            'completion_type_id' => 'required|exists:completion_types,id',
            'comment'           => 'required|string|max:1000',
            'target_type'       => 'required|string|max:50',
            'worker_id'         => 'nullable|exists:workers,id',
            'images.*'          => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imagePaths[] = $image->store('compliments', 'public');
            }
        }

        Compliment::create([
            ...$validated,
            'images' => $imagePaths ?: null,
            'status_id' => 1,
            'created_at' => now(),
        ]);

        return redirect()
            ->back()
            ->with('success', 'Thank you! Your compliment has been submitted successfully.');
    }

    public function show(Compliment $compliment)
    {
        $statuses = Status::all();

        return view('compliments.show', compact('compliment', 'statuses'));
    }
    public function edit($id)
    {
        $compliment = Compliment::findOrFail($id);
        $statuses = Status::all();
        return view('compliments.edit', compact('compliment', 'statuses'));
    }

    public function update(Request $request, $id)
    {
        $compliment = Compliment::findOrFail($id);
        $compliment->update($request->all());
        return response()->json(['success' => 'Compliment updated successfully.']);
    }
    public function assignCareUser(Request $request, Compliment $compliment)
    {
        $request->validate([
            'care_user_id' => 'required|exists:users,id',
        ]);

        $compliment->update([
            'care_user_id' => $request->care_user_id,
        ]);

        return redirect()->route('compliments.show', $compliment)->with('success', 'Care user assigned successfully.');
    }


    public function createCustomer()
    {
        $completionTypes = CompletionType::where('type', 'customer')->get();
        $department = Department::find(request('department_id'));
        return view('compliments.customer_form', compact('completionTypes', 'department'));
    }

    public function storeCustomer(Request $request)
    {
        $validated = $request->validate([
            'customer_name'      => 'required|string|max:255',
            'phone'              => 'required|string|max:20',
            'completion_type_id' => 'required|exists:completion_types,id',
            'plate_number'       => 'nullable|string|max:50',
            'comment'            => 'required|string|max:1000',
            'images.*'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'audio'              => 'nullable|file|mimetypes:audio/mpeg,audio/mp3,audio/webm|max:10240', // 10MB
            'video'              => 'nullable|file|mimetypes:video/mp4,video/webm|max:51200', // 50MB
            'department_id'      => 'required|exists:departments,id',
        ]);

        /* ------------------------------------
        HANDLE IMAGES (UP TO 3)
    ------------------------------------ */
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $images[] = $file->store('compliments', 'public');
            }
        }

        /* ------------------------------------
        HANDLE AUDIO
    ------------------------------------ */
        $audioPath = null;
        if ($request->hasFile('audio')) {
            $audioPath = $request->file('audio')->store('compliments/audio', 'public');
        }

        /* ------------------------------------
        HANDLE VIDEO
    ------------------------------------ */
        $videoPath = null;
        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('compliments/video', 'public');
        }

        /* ------------------------------------
        SAVE COMPLIMENT
    ------------------------------------ */
        $compliment = Compliment::create([
            'customer_name'      => $validated['customer_name'],
            'phone'              => $validated['phone'],
            'plate_number'       => $validated['plate_number'] ?? null,
            'completion_type_id' => $validated['completion_type_id'],
            'comment'            => $validated['comment'],
            'department_id'      => $validated['department_id'],
            'target_type'        => 'customer',
            'images'             => json_encode($images),
            'audio'              => $audioPath,       // <---- NEW
            'video'              => $videoPath,       // <---- NEW
            'status_id'          => 1,
        ]);

        /* ------------------------------------
        SEND NOTIFICATION TO ADMINS
    ------------------------------------ */
        $users = User::whereIn('role', ['Admin','Supervisor','Customer Care'])->get();

        Notification::send($users, new SystemNotification(
            'New customer compliment submitted!',
            ['type' => 'compliment', 'id' => $compliment->id]
        ));

        return redirect()->back()->with('success', 'Your compliment has been submitted successfully!');
    }


    public function createWorker(Request $request)
    {
        $departmentId = $request->get('department_id');
        $workers = Worker::where('department_id', $departmentId)->get();
        $completionTypes = CompletionType::where('type', 'worker')->get();
        $department = Department::find($departmentId);
        return view('compliments.worker_form', compact('workers', 'completionTypes', 'departmentId','department'));
    }

    public function storeWorker(Request $request)
    {
        $validated = $request->validate([
            'worker_id'          => 'required|exists:workers,id',
            'department_id'      => 'required|exists:departments,id',
            'completion_type_id' => 'required|exists:completion_types,id',
            'plate_number'       => 'nullable|string|max:50',
            'missed_pay'         => 'nullable|string|max:100',
            'comment'            => 'required|string|max:1000',

            // Images
            'images.*'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',

            // Base64 video/audio
            'video'              => 'nullable|string',
            'audio'              => 'nullable|string',
        ]);


        /* ---------------------------------------------------------
     *  HANDLE IMAGES (MAX 3)
     * --------------------------------------------------------- */
        $images = [];
        if ($request->hasFile('images')) {
            $files = $request->file('images');

            if (count($files) > 3) {
                return back()->withErrors(['images' => 'You can upload a maximum of 3 images.']);
            }

            foreach ($files as $file) {
                $images[] = $file->store('compliments/images', 'public');
            }
        }


        /* ---------------------------------------------------------
     *  HANDLE VIDEO BASE64 → FILE
     * --------------------------------------------------------- */
        $videoPath = null;

        if (!empty($validated['video'])) {
            $videoData = $validated['video'];

            if (preg_match('/^data:video\/\w+;base64,/', $videoData)) {
                $videoData = substr($videoData, strpos($videoData, ',') + 1);
            }

            $videoBinary = base64_decode($videoData);

            $videoName = 'compliments/videos/video_' . time() . '.webm';
            Storage::disk('public')->put($videoName, $videoBinary);

            $videoPath = $videoName;
        }


        /* ---------------------------------------------------------
     *  HANDLE AUDIO BASE64 → FILE
     * --------------------------------------------------------- */
        $audioPath = null;

        if (!empty($validated['audio'])) {
            $audioData = $validated['audio'];

            if (preg_match('/^data:audio\/\w+;base64,/', $audioData)) {
                $audioData = substr($audioData, strpos($audioData, ',') + 1);
            }

            $audioBinary = base64_decode($audioData);

            $audioName = 'compliments/audio/audio_' . time() . '.webm';
            Storage::disk('public')->put($audioName, $audioBinary);

            $audioPath = $audioName;
        }


        /* ---------------------------------------------------------
     *  CREATE COMPLIMENT RECORD
     * --------------------------------------------------------- */
        Compliment::create([
            'worker_id'          => $validated['worker_id'],
            'department_id'      => $validated['department_id'],
            'completion_type_id' => $validated['completion_type_id'],
            'plate_number'       => $validated['plate_number'] ?? null,
            'missed_pay'         => $validated['missed_pay'] ?? null,
            'comment'            => $validated['comment'],

            'target_type'        => 'worker',
            'status_id'          => 1,

            'images'             => json_encode($images),
            'video'              => $videoPath,
            'audio'              => $audioPath,
        ]);


        $users = User::whereIn('role', ['Admin','Supervisor','Customer Care'])->get();
        Notification::send($users, new SystemNotification(
            'New worker compliment submitted!',
            ['type' => 'compliment', 'id' => Compliment::latest()->first()->id]
        ));
        return redirect()->back()->with('success', 'Worker compliment submitted successfully!');
    }



    public function exportPdf(Request $request)
    {
        // Fetch data with filters
        $data = Compliment::with(['department', 'careUser', 'completion_type', 'status'])
            ->select('compliments.*');

        if ($request->filled('department_id')) {
            $data->whereIn('department_id', $request->department_id);
        }
        if ($request->filled('completion_type_id')) {
            $data->whereIn('completion_type_id', $request->completion_type_id);
        }
        if ($request->filled('status_id')) {
            $data->whereIn('status_id', $request->status_id);
        }
        if ($request->filled('care_user_id')) {
            $data->whereIn('care_user_id', $request->care_user_id);
        }
        if ($request->filled('target_type')) {
            $data->whereIn('target_type', $request->target_type);
        }
        if ($request->filled('date_from') && $request->filled('date_to')) {
            $data->whereBetween('created_at', [$request->date_from, $request->date_to]);
        }

        $compliments = $data->get();

        // Render Blade view to HTML
        $html = view('compliments.pdf', compact('compliments'))->render();

        // Create new mPDF instance
        $mpdf = new Mpdf([
            'format' => 'A4-L', // A4 Landscape
            'tempDir' => storage_path('app/temp'), // optional: avoid permission issues
        ]);

        $mpdf->WriteHTML($html);

        // Output PDF as download
        return response()->streamDownload(function () use ($mpdf) {
            echo $mpdf->Output('', 'S'); // Send to browser
        }, 'compliments.pdf');
    }
}
