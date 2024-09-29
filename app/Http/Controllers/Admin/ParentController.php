<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParentModel;
use App\Models\Teacher;
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
            'subject_specialization' => 'required|string|max:255', // Validate subject specialization
            'class_id' => 'nullable|exists:school_classes,id', // Validate class ID
            'section_id' => 'nullable|exists:sections,id', // Validate section ID
        ]);

        // Create the user with a default password
        $role = Role::firstOrCreate(['name' => 'Teacher']);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make('teacherpassword'), // Default password
        ]);
        $user->assignRole($role);

        // Create the teacher model associated with the user
        Teacher::create([
            'user_id' => $user->id,
            'subject_specialization' => $request->subject_specialization,
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
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
            'naem' => $request->name,
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
