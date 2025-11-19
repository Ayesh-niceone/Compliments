<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        if ($request->ajax()) {
            $data = User::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('role', fn($row) => $row->roles->pluck('name')->join(', '))
                ->addColumn('action', fn($row) => view('users.actions', compact('row'))->render())
                ->rawColumns(['action'])
                ->make(true);
        }

        $roles = Role::pluck('name', 'id'); // key & value as role name
        return view('users.index', compact('roles'));
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'role'     => 'required|string|max:50', // role name
            'password' => 'required|string|min:6',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => $validated['password'],
            'role'     => $validated['role'], // optional convenience column
        ]);

        // Assign Spatie role
        $user->syncRoles([$validated['role']]);

        return response()->json(['message' => 'User created successfully']);
    }


    /**
     * Get data for editing.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $user->syncRoles(['Admin']);

        // Update convenience column in users table
        $user->role = 'Admin';
        $user->save();
        return response()->json($user);
    }

    /**
     * Update an existing user.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role'  => 'required|string|max:50', // role name
            'password' => 'nullable|string|min:6', // optional
        ]);

        // Update password only if provided
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        // Save user first
        $user->save();

        // Update Spatie role
        $user->syncRoles([$validated['role']]);

        // Update convenience column in users table
        $user->role = $validated['role'];
        $user->save();

        return response()->json(['message' => 'User updated successfully']);
    }


    /**
     * Remove a user.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
