<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParentModel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class ParentController extends Controller
{
    public function index()
    {
        $parents = ParentModel::with('user')->get(); // Eager load the user relationship
        return Inertia::render('Admin/Parent/Index', [
            'parents' => $parents,
        ]);
    }

    // Display the form for creating a new parent
    public function create()
    {
        return Inertia::render('Admin/Parent/Create');
    }

    // Store a newly created user and parent in storage
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255', // Validate user name
            'email' => 'required|email|unique:users,email', // Validate email
            'phone' => 'nullable|string|max:15', // Validate phone number
            'address' => 'nullable|string|max:255', // Validate address
        ]);

        // Create the user with a default password
        $role = Role::firstOrCreate(['name' => 'Parent']);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('parentpassword'),
        ]);
        $user->assignRole($role);

        // Create the parent model associated with the user
        ParentModel::create([
            'user_id' => $user->id,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('admin.parents.index')->with('success', 'Parent created successfully!');
    }

    // Display the specified parent
    public function show(ParentModel $parent)
    {
        return Inertia::render('Admin/Parent/Show', [
            'parent' => $parent->load('user'), // Load the user relationship
        ]);
    }

    // Display the form for editing the specified parent
    public function edit(ParentModel $parent)
    {
        return Inertia::render('Admin/Parent/Edit', [
            'parent' => $parent->load('user'), // Load the user relationship
        ]);
    }

    // Update the specified parent in storage
    public function update(Request $request, ParentModel $parent)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $parent->user_id,
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
        ]);

        // Update the user associated with the parent
        $parent->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update the parent model
        $parent->update([
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('admin.parents.index')->with('success', 'Parent updated successfully!');
    }

    // Remove the specified parent from storage
    public function destroy(ParentModel $parent)
    {
        // Delete the user and parent model
        $parent->user->delete();
        $parent->delete();

        return redirect()->route('admin.parents.index')->with('success', 'Parent deleted successfully!');
    }
}
