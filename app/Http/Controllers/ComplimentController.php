<?php

namespace App\Http\Controllers;

use App\Models\CompletionType;
use App\Models\Compliment;
use App\Models\Department;
use App\Models\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ComplimentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Compliment::with(['department', 'careUser'])
                ->select('compliments.*');

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('customer_name', fn($row) => $row->customer_name ?? '-')
                ->addColumn('department', fn($row) => $row->department->name ?? '-')
                ->addColumn('code', fn($row) => $row->department->code ?? '-')
                ->addColumn('phone', fn($row) => $row->phone ?? '-')
                ->addColumn('plate_number', fn($row) => $row->plate_number ?? '-')
                ->addColumn('created_at', fn($row) => $row->created_at ?? '-')
                ->addColumn('completion_type', fn($row) => $row->completion_type->name ?? '-')
                ->addColumn('care_user', fn($row) => $row->careUser->name ?? '-')
                ->addColumn('status', fn($row) => $row->status->name ?? '-')
                ->addColumn('action', function ($row) {
                    return view('compliments.actions', compact('row'))->render();
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('compliments.index', [
            'departments' => Department::all(),
            'statuses' => Status::all(),
            'completionTypes' => CompletionType::all(),
            'careUsers' => User::where('role', 'customer_care')->get(),
        ]);
    }

    public function create()
    {
        $departments = Department::all();
        $completionTypes = CompletionType::all();
        return view('compliment-form', compact('departments', 'completionTypes'));
    }

    public function store(Request $request)
    {
        // ✅ Validate input
        $validated = $request->validate([
            'customer_name'   => 'required|string|max:255',
            'phone'           => 'required|string|max:20',
            'plate_number'    => 'nullable|string|max:20',
            'department_id'   => 'required|exists:departments,id',
            'completion_type_id'   => 'required|exists:completion_types,id',
            'comment'         => 'required|string|max:1000',
            'target_type'     => 'required|string|max:50',
        ]);

        // ✅ Create compliment record
        $compliment = Compliment::create([
            'customer_name'     => $validated['customer_name'],
            'phone'             => $validated['phone'],
            'plate_number'      => $validated['plate_number'] ?? null,
            'department_id'     => $validated['department_id'],
            'comment'           => $validated['comment'],
            'target_type'       => $validated['target_type'],
            'completion_type_id'       => $validated['completion_type_id'],
            'status_id'         => 1, // e.g. default status = "New"
            'created_at'        => now(),
        ]);

        // ✅ Optional: redirect with success message
        return redirect()
            ->back()
            ->with('success', 'Thank you! Your compliment has been submitted successfully.');
    }

    public function show(Compliment $compliment)
    {
                $statuses = Status::all();

        return view('compliments.show', compact('compliment', 'statuses'));
    }
    public function edit($id){
        $compliment = Compliment::findOrFail($id);
        $statuses = Status::all();
        return view('compliments.edit', compact('compliment', 'statuses'));
    }

    public function update(Request $request, $id){
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

}
