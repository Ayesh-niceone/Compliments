<?php

namespace App\Http\Controllers;

use App\Exports\ComplimentsExport;
use App\Models\CompletionType;
use App\Models\Compliment;
use App\Models\Department;
use App\Models\Status;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class ComplimentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Compliment::with(['department', 'careUser', 'completion_type', 'status'])
                ->select('compliments.*');

            // ✅ Apply filters if provided
            if ($request->filled('department_id')) {
                $data->where('department_id', $request->department_id);
            }
            if ($request->filled('completion_type_id')) {
                $data->where('completion_type_id', $request->completion_type_id);
            }
            if ($request->filled('status_id')) {
                $data->where('status_id', $request->status_id);
            }
            if ($request->filled('care_user_id')) {
                $data->where('care_user_id', $request->care_user_id);
            }
            if ($request->filled('target_type')) {
                $data->where('target_type', $request->target_type);
            }
            if ($request->filled('date_from') && $request->filled('date_to')) {
                $data->whereBetween('created_at', [$request->date_from, $request->date_to]);
            }

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('customer_name', fn($row) => $row->customer_name ?? '-')
                ->addColumn('department', fn($row) => $row->department->name ?? '-')
                ->addColumn('code', fn($row) => $row->department->code ?? '-')
                ->addColumn('phone', fn($row) => $row->phone ?? '-')
                ->addColumn('plate_number', fn($row) => $row->plate_number ?? '-')
                ->addColumn('created_at', fn($row) => $row->created_at->format('Y-m-d H:i') ?? '-')
                ->addColumn('completion_type', fn($row) => $row->completion_type->name ?? '-')
                ->addColumn('care_user', fn($row) => $row->careUser->name ?? '-')
                ->addColumn('status', fn($row) => $row->status->name ?? '-')
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
            'careUsers' => User::where('role', 'customer_care')->get(),
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
        $completionTypes = CompletionType::all();
        return view('compliments.customer_form', compact('completionTypes'));
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
            'department_id'      => 'required|exists:departments,id',

        ]);

        // Handle up to 3 image uploads
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $images[] = $file->store('compliments', 'public');
            }
        }

        Compliment::create([
            'customer_name'      => $validated['customer_name'],
            'phone'              => $validated['phone'],
            'plate_number'       => $validated['plate_number'] ?? null,
            'completion_type_id' => $validated['completion_type_id'],
            'comment'            => $validated['comment'],
            'department_id'      => $validated['department_id'],
            'target_type'        => 'customer',
            'images'             => json_encode($images),
            'status_id'          => 1, // default "New"
        ]);

        return redirect()->back()->with('success', 'Your compliment has been submitted successfully!');
    }

    public function createWorker(Request $request)
    {
        $departmentId = $request->get('department_id');
        $workers = Worker::where('department_id', $departmentId)->get();
        $completionTypes = CompletionType::all();

        return view('compliments.worker_form', compact('workers', 'completionTypes', 'departmentId'));
    }

    public function storeWorker(Request $request)
    {
        $validated = $request->validate([
            'worker_id'          => 'required|exists:workers,id',
            'department_id'      => 'required|exists:departments,id',
            'completion_type_id' => 'required|exists:completion_types,id',
            'plate_number'       => 'nullable|string|max:50',
            'comment'            => 'required|string|max:1000',
            'images.*'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Handle up to 3 images
        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $images[] = $file->store('compliments', 'public');
            }
        }

        Compliment::create([
            'worker_id'          => $validated['worker_id'],
            'department_id'      => $validated['department_id'],
            'completion_type_id' => $validated['completion_type_id'],
            'plate_number'       => $validated['plate_number'] ?? null,
            'comment'            => $validated['comment'],
            'target_type'        => 'worker',
            'images'             => json_encode($images),
            'status_id'          => 1,
        ]);

        return redirect()->back()->with('success', 'Worker compliment submitted successfully!');
    }
}
