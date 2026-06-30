<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(): View
    {
        // Fetch all users (or use paginate(15) if you have a lot)
        $users = User::all(); 

        return view('users.index', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role'     => 'required|string|exists:roles,name', // Ensures the role exists in Spatie's table
        ]);

        // 1. Create the user in your standard users table
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'role'     => $request->role, // Syncs your local text fallback column
            'password' => Hash::make($request->password),
        ]);

        // 2. CRITICAL: Spatie hooks in here and creates the matching row inside `model_has_roles`
        $user->assignRole($request->role); 

        return redirect()->back()->with('success', 'User created and role linked successfully!');
    }

    /**
     * Handle updating an existing user and altering their role status.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8',
            'role'     => 'required|string|exists:roles,name',
        ]);

        // 1. Update basic information profile details
        $user->update([
            'name'  => $request->name,
            'email' => $request->email,
            'role'  => $request->role,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        // 2. CRITICAL: This wipes out their old role mapping and applies the new one inside `model_has_roles`
        $user->syncRoles([$request->role]); 

        return redirect()->back()->with('success', 'User updated and role synchronized successfully!');
    }

    // Handle deleting a user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully!');
    }


}
