<?php

namespace App\Http\Controllers\RolePermission;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionController extends Controller
{
    public function index()
    {
        $roles = Role::with('permissions')->get();

        return Inertia::render('RolePermissions/Index', [
            'roles' => $roles,
        ]);
    }

    public function create()
    {
        $permissions = Permission::all();
        $roles = Role::all();

        return Inertia::render('RolePermissions/Create', [
            'permissions' => $permissions,
            'roles' => $roles,
        ]);
    }

    public function store(Request $request)
    {
        $role = Role::findOrFail($request->input('role_id'));
        $role->syncPermissions($request->input('permissions'));

        return redirect()->route('role-permissions.index')->with('success', 'Permissions assigned successfully.');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
    
        return Inertia::render('RolePermissions/Edit', [
            'role' => $role,
            'permissions' => $permissions,
        ]);
    }
    

    public function update(Request $request, Role $role)
    {
        $role->syncPermissions($request->input('permissions'));

        return redirect()->route('role-permissions.index')->with('success', 'Permissions updated successfully.');
    }


    public function destroy(Role $role)
    {
        $role->permissions()->detach();
        return redirect()->route('role-permissions.index')->with('success', 'Permissions removed successfully.');
    }
}
