<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StatusController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Status::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', fn($row) => view('statuses.actions', compact('row'))->render())
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('statuses.index');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        Status::create($request->all());
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $status = Status::findOrFail($id);
        $status->delete();
        return response()->json(['success' => true]);
    }
}
