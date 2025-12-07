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
        $request->validate([
            'name.name_en' => 'required|string|max:255',
            'name.name_ar' => 'required|string|max:255',
            'type' => 'required|in:worker,customer',
        ]);

        CompletionType::create([
            'name' => [
                'name_en' => $request->name['name_en'],
                'name_ar' => $request->name['name_ar'],
            ],
            'type' => $request->type,
        ]);

        return response()->json(['success' => true]);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name.name_en' => 'required|string|max:255',
            'name.name_ar' => 'required|string|max:255',
            'type' => 'required|in:worker,customer',
        ]);

        $completionType = CompletionType::findOrFail($id);

        $completionType->update([
            'name' => [
                'name_en' => $request->name['name_en'],
                'name_ar' => $request->name['name_ar'],
            ],
            'type' => $request->type,
        ]);

        return response()->json(['success' => true]);
    }
    public function destroy($id)
    {
        $completion_type = CompletionType::findOrFail($id);
        $completion_type->delete();
        return response()->json(['success' => true]);
    }
}
