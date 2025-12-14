<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Brand::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', fn($row) => view('brands.actions', compact('row'))->render())
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('brands.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name.name_en' => 'required|string|max:255',
            'name.name_ar' => 'required|string|max:255',
        ]);

        Brand::create([
            'name' => [
                'name_en' => $request->name['name_en'],
                'name_ar' => $request->name['name_ar'],
            ],
        ]);

        return response()->json(['success' => true]);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name.name_en' => 'required|string|max:255',
            'name.name_ar' => 'required|string|max:255',
        ]);

        $brand = Brand::findOrFail($id);

        $brand->update([
            'name' => [
                'name_en' => $request->name['name_en'],
                'name_ar' => $request->name['name_ar'],
            ],
        ]);

        return response()->json(['success' => true]);
    }
    public function destroy($id)
    {
        $completion_type = Brand::findOrFail($id);
        $completion_type->delete();
        return response()->json(['success' => true]);
    }
}
