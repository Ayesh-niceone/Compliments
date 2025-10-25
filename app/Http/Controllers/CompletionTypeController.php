<?php

namespace App\Http\Controllers;

use App\Models\CompletionType;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CompletionTypeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = CompletionType::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', fn($row) => view('completion_types.actions', compact('row'))->render())
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('completion_types.index');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255']);
        CompletionType::create($request->all());
        return response()->json(['success' => true]);
    }

    public function update(Request $request, CompletionType $completionType)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $completionType->update(['name' => $request->name]);
        return response()->json(['success' => true]);
    }
    public function destroy($id)
    {
        $completion_type = CompletionType::findOrFail($id);
        $completion_type->delete();
        return response()->json(['success' => true]);
    }
}
