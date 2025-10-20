<?php

namespace App\Http\Controllers;

use App\Models\Compliment;
use App\Models\Department;
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
                ->addColumn('department', fn($row) => $row->department->name ?? '-')
                ->addColumn('care_user', fn($row) => $row->careUser->name ?? '-')
                ->addColumn('status', fn($row) => view('compliments.partials.status', compact('row'))->render())
                ->addColumn('action', function($row) {
                    return view('compliments.partials.actions', compact('row'))->render();
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('compliments.index');
    }

    public function create()
    {
        $departments = Department::all();
        $careUsers = User::where('role', 'customer_care')->get();
        return view('compliments.create', compact('departments', 'careUsers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:departments,id',
            'comment' => 'required|string',
            'target_type' => 'required|in:customer,worker',
            'phone' => 'required|string',
            'email' => 'required|email',
        ]);

        Compliment::create([
            'department_id' => $request->department_id,
            'comment' => $request->comment,
            'status' => 'new',
            'target_type' => $request->target_type,
            'phone' => $request->phone,
            'email' => $request->email,
        ]);

        return redirect()->route('compliments.index')
            ->with('success', 'Compliment created successfully!');
    }
}
