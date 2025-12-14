<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Department;
use Illuminate\Http\Request;
use Mpdf\Tag\B;
use Mpdf\Tag\Br;
use Yajra\DataTables\Facades\DataTables;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Department::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('brand', fn($row) => $row->brand->name_lang ?? '-')
                ->addColumn('action', fn($row) => view('departments.actions', compact('row'))->render())
                ->rawColumns(['action'])
                ->make(true);
        }

        $brands = Brand::all();

        return view('departments.index', compact('brands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name.name_en' => 'required|string|max:255',
            'name.name_ar' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'brand_id' => 'required|exists:brands,id'
        ]);

        Department::create([
            'name' => [
                'name_en' => $request->name['name_en'],
                'name_ar' => $request->name['name_ar'],
            ],
            'code' => $request->code,
            'brand_id' => $request->brand_id,
        ]);

        return response()->json(['success' => true]);
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'name.name_en' => 'required|string|max:255',
            'name.name_ar' => 'required|string|max:255',
            'code' => 'required|string|max:50',
            'brand_id' => 'required|exists:brands,id'
        ]);

        $department = Department::findOrFail($id);

        $department->update([
            'name' => [
                'name_en' => $request->name['name_en'],
                'name_ar' => $request->name['name_ar'],
            ],
            'code' => $request->code,
            'brand_id' => $request->brand_id,
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();
        return response()->json(['success' => true]);
    }
}
