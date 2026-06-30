<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionController extends Controller
{
    public function index()
    {
        // Load roles along with their existing permissions
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();

        return view('roles.index', compact('roles', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        
        // Spatie replaces all old permissions for this role with the ones sent in the array
        $role->syncPermissions($request->permissions ?? []);

        return redirect()->back()->with('success', 'Permissions updated for ' . ucfirst($role->name));
    }
}
