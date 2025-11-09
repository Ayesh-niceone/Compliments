<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $permissions = Permission::select(['id', 'name', 'guard_name']);
            return DataTables::of($permissions)
                ->addColumn('action', function ($permission) {
                    return '
                        <button class="btn btn-sm btn-info editPermissionBtn" data-id="'.$permission->id.'">Edit</button>
                        <button class="btn btn-sm btn-danger deletePermissionBtn" data-id="'.$permission->id.'">Delete</button>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('permissions.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|unique:permissions,name',
        ]);

        Permission::create(['name' => $validated['name']]);
        return response()->json(['message' => 'Permission created successfully']);
    }

    public function edit(Permission $permission)
    {
        return response()->json($permission);
    }

    public function update(Request $request, Permission $permission)
    {
        $validated = $request->validate([
            'name' => 'required|unique:permissions,name,'.$permission->id,
        ]);

        $permission->update(['name' => $validated['name']]);
        return response()->json(['message' => 'Permission updated successfully']);
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();
        return response()->json(['message' => 'Permission deleted successfully']);
    }
}
