<?php

namespace App\Http\Controllers;

use App\Models\Worker;
use App\Models\Department;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class WorkerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $workers = Worker::with('department')->select('workers.*');
            return DataTables::of($workers)
                ->addIndexColumn()
                ->addColumn('department', fn($row) => $row->department->name_lang ?? '-')

                ->addColumn('action', function ($row) {
                    $buttons = '';
                    if(auth()->user()->can('edit workers')) {
                        $buttons .= '<button onclick="editWorker(' . $row->id . ', \'' . e($row->name) . '\', \'' . e($row->job_title) . '\', \'' . e($row->phone) . '\', ' . $row->department_id . ')" class="btn btn-sm btn-warning">' . __('Edit') . '</button> ';
                    }
                    if(auth()->user()->can('delete workers')) {
                        $buttons .= '<button onclick="deleteWorker(' . $row->id . ')" class="btn btn-sm btn-danger">' . __('Delete') . '</button>';
                    }
                    return $buttons;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $departments = Department::all();
        return view('workers.index', compact('departments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'department_id' => 'required|exists:departments,id',
        ]);

        Worker::create($request->all());
        return response()->json(['success' => true]);
    }

    public function update(Request $request, Worker $worker)
    {
        $request->validate([
            'name' => 'required|string',
            'department_id' => 'required|exists:departments,id',
        ]);

        $worker->update($request->all());
        return response()->json(['success' => true]);
    }

    public function destroy(Worker $worker)
    {
        $worker->delete();
        return response()->json(['success' => true]);
    }
}
