<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Role::select(['id', 'name']);
            return DataTables::of($data)
                ->addColumn('action', function ($row) {
                    return '
                        <button class="btn btn-sm btn-warning" onclick="editRole(' . $row->id . ')">Edit</button>
                        <button class="btn btn-sm btn-danger" onclick="deleteRole(' . $row->id . ')">Delete</button>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('roles.index');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:roles,name']);
        Role::create(['name' => $request->name]);
        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $role->update(['name' => $request->name]);
        $permissions = Permission::whereIn('id', $request->permissions)->get();

        $role->syncPermissions($permissions ?? []);
        return response()->json(['success' => true]);
    }

    public function destroy(Role $role)
    {
        $role->delete();
        return response()->json(['success' => true]);
    }
}
