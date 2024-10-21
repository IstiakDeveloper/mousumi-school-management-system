<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teachers = Teacher::with(['user', 'schoolClass', 'section'])->get();
        return Inertia::render('Admin/Teacher/Index', compact('teachers'));
    }

    public function create()
    {
        $classes = SchoolClass::all();
        $sections = Section::all();

        return Inertia::render('Admin/Teacher/Create', [
            'classes' => $classes,
            'sections' => $sections,
        ]);
    }

    public function store(Request $request)
    {
        // Validate the request and return errors if validation fails
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'subject_specialization' => 'required|string|max:255',
            'class_id' => 'nullable|exists:school_classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'salary_amount' => 'required|numeric|min:0', // New validation for salary
        ]);

        // Create a new role for the teacher and assign the role
        $role = Role::firstOrCreate(['name' => 'Teacher']);

        // Create the user with a default password
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
            'salary_amount' => $request->salary_amount, // Store the salary amount
        ]);

        // Redirect back with success message
        return redirect()->route('admin.teachers.index')->with('success', 'Teacher created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $teacher = Teacher::with('user')->findOrFail($id);
        $classes = SchoolClass::all();
        $sections = Section::all();

        return Inertia::render('Admin/Teacher/Edit', [
            'teacher' => $teacher,
            'classes' => $classes,
            'sections' => $sections,
        ]);
    }

    public function update(Request $request, string $id)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255,' . $id, // Ensure unique email for the same user
            'subject_specialization' => 'required|string|max:255',
            'class_id' => 'nullable|exists:school_classes,id',
            'section_id' => 'nullable|exists:sections,id',
            'salary_amount' => 'required|numeric|min:0', // New validation for salary
        ]);

        $teacher = Teacher::with('user')->findOrFail($id);

        // Update the user's details
        $teacher->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update the teacher's details
        $teacher->update([
            'subject_specialization' => $request->subject_specialization,
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
            'salary_amount' => $request->salary_amount, // Update the salary amount
        ]);

        return redirect()->route('admin.teachers.index')->with('success', 'Teacher updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
        // Delete the user and parent model
        $teacher->user->delete();
        $teacher->delete();

        return redirect()->route('admin.teachers.index')->with('success', 'Teacher deleted successfully!');
    }
}
