<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ParentModel;
use App\Models\SchoolClass; // Update this to use SchoolClass
use App\Models\Section;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with(['user', 'class', 'section', 'parent'])->get(); // Updated relationship reference
        // dd($students);
        return Inertia::render('Admin/Student/Index', [
            'students' => $students,
        ]);
    }

    // Display the form for creating a new student
    public function create()
    {
        $schoolClassesList = SchoolClass::all(); // Fetch all school classes
        $sections = Section::all(); // Fetch all sections
        $parents = ParentModel::with('user')->get(); // Eager load parents

        // Load location data from JSON file
        $locationData = json_decode(File::get(public_path('location.json')), true);

        return Inertia::render('Admin/Student/Create', [
            'classes' => $schoolClassesList, // School classes
            'sections' => $sections, // Sections
            'parents' => $parents, // Parents
            'divisions' => $locationData['divisions'], // Divisions from JSON
            'districts' => $locationData['districts'], // Districts from JSON
        ]);
    }

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'student_id' => 'required|string|unique:students,student_id', // Validate student ID
            'form_number' => 'nullable|string|unique:students,form_number', // Validate form number
            'name_bn' => 'nullable|string|max:255', // Validate Bangla name
            'name_en' => 'required|string|max:255', // Validate English name
            'birth_certificate_number' => 'required|string|max:255', // Validate birth certificate number
            'birth_place_district' => 'required|string|max:255', // Validate birth place district
            'date_of_birth' => 'required|date', // Validate date of birth
            'gender' => 'required|in:Male,Female,Other', // Validate gender
            'nationality' => 'required|string|max:255', // Validate nationality
            'religion' => 'required|string|max:255', // Validate religion
            'blood_group' => 'nullable|string|max:255', // Validate blood group
            'class_role' => 'required|string|max:255', // Validate class role
            'minorities' => 'boolean', // Validate minorities
            'minority_name' => 'nullable|string|max:255', // Validate minority name
            'handicap' => 'nullable|string|max:255', // Validate handicap

            // Validate mother details
            'mother_nid' => 'nullable|string|max:255',
            'mother_dob' => 'nullable|date',
            'mother_name_bn' => 'nullable|string|max:255',
            'mother_name_en' => 'nullable|string|max:255',
            'mother_mobile' => 'nullable|string|max:255',
            'mother_occupation' => 'nullable|string|max:255',
            'mother_dead' => 'boolean',

            // Validate father details
            'father_nid' => 'nullable|string|max:255',
            'father_dob' => 'nullable|date',
            'father_name_bn' => 'nullable|string|max:255',
            'father_name_en' => 'nullable|string|max:255',
            'father_mobile' => 'nullable|string|max:255',
            'father_occupation' => 'nullable|string|max:255',
            'father_dead' => 'boolean',

            // Present address validation
            'present_address_division' => 'nullable|string|max:255',
            'present_address_district' => 'nullable|string|max:255',
            'present_address_upazila' => 'nullable|string|max:255',
            'present_address_city' => 'nullable|string|max:255',
            'present_address_ward' => 'nullable|string|max:255',
            'present_address_village' => 'nullable|string|max:255',
            'present_address_house_number' => 'nullable|string|max:255',
            'present_address_post' => 'nullable|string|max:255',
            'present_address_post_code' => 'nullable|string|max:255',

            // Permanent address validation
            'permanent_address_division' => 'nullable|string|max:255',
            'permanent_address_district' => 'nullable|string|max:255',
            'permanent_address_upazila' => 'nullable|string|max:255',
            'permanent_address_city' => 'nullable|string|max:255',
            'permanent_address_ward' => 'nullable|string|max:255',
            'permanent_address_village' => 'nullable|string|max:255',
            'permanent_address_house_number' => 'nullable|string|max:255',
            'permanent_address_post' => 'nullable|string|max:255',
            'permanent_address_post_code' => 'nullable|string|max:255',

            'information_correct' => 'boolean',
            'class_id' => 'required|exists:school_classes,id', // Validate class ID
            'section_id' => 'required|exists:sections,id', // Validate section ID
            'parent_id' => 'nullable|exists:parent_models,id', // Validate parent ID
            'address' => 'nullable|string|max:255', // Validate address
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $filename = time() . '.' . $request->photo->getClientOriginalExtension();
            $request->photo->move(public_path('students'), $filename);
            $photoPath = $filename; // Store the filename for database insertion
        }

        // // Create the user for the student with a default password
        $role = Role::firstOrCreate(['name' => 'Student']);
        $user = User::create([
            'name' => $request->name_en,
            'email' => $request->email,
            'password' => Hash::make('studentpassword'), // Set a default password
            'photo' => $photoPath,
        ]);
        $user->assignRole($role);



        // Create the student model associated with the user
        Student::create([
            'photo' => $photoPath,
            'user_id' => $user->id,
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
            'parent_id' => $request->parent_id,
            'student_id' => $request->student_id,
            'form_number' => $request->form_number,
            'name_bn' => $request->name_bn,
            'name_en' => $request->name_en,
            'birth_certificate_number' => $request->birth_certificate_number,
            'birth_place_district' => $request->birth_place_district,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'nationality' => $request->nationality,
            'religion' => $request->religion,
            'blood_group' => $request->blood_group,
            'class_role' => $request->class_role,
            'minorities' => $request->minorities,
            'minority_name' => $request->minority_name,
            'handicap' => $request->handicap,
            'mother_nid' => $request->mother_nid,
            'mother_dob' => $request->mother_dob,
            'mother_name_bn' => $request->mother_name_bn,
            'mother_name_en' => $request->mother_name_en,
            'mother_mobile' => $request->mother_mobile,
            'mother_occupation' => $request->mother_occupation,
            'mother_dead' => $request->mother_dead,
            'father_nid' => $request->father_nid,
            'father_dob' => $request->father_dob,
            'father_name_bn' => $request->father_name_bn,
            'father_name_en' => $request->father_name_en,
            'father_mobile' => $request->father_mobile,
            'father_occupation' => $request->father_occupation,
            'father_dead' => $request->father_dead,
            'present_address_division' => $request->present_address_division,
            'present_address_district' => $request->present_address_district,
            'present_address_upazila' => $request->present_address_upazila,
            'present_address_city' => $request->present_address_city,
            'present_address_ward' => $request->present_address_ward,
            'present_address_village' => $request->present_address_village,
            'present_address_house_number' => $request->present_address_house_number,
            'present_address_post' => $request->present_address_post,
            'present_address_post_code' => $request->present_address_post_code,
            'permanent_address_division' => $request->permanent_address_division,
            'permanent_address_district' => $request->permanent_address_district,
            'permanent_address_upazila' => $request->permanent_address_upazila,
            'permanent_address_city' => $request->permanent_address_city,
            'permanent_address_ward' => $request->permanent_address_ward,
            'permanent_address_village' => $request->permanent_address_village,
            'permanent_address_house_number' => $request->permanent_address_house_number,
            'permanent_address_post' => $request->permanent_address_post,
            'permanent_address_post_code' => $request->permanent_address_post_code,
            'information_correct' => $request->information_correct,
        ]);

        return redirect()->route('admin.students.index')->with('success', 'Student created successfully!');
    }

    // Display the specified student
    public function show(Student $student)
    {
        return Inertia::render('Admin/Student/Show', [
            'student' => $student->load(['user', 'schoolClass', 'section', 'parent']),
        ]);
    }

    // Show the form for editing the specified student
    public function edit(Student $student)
    {
        $schoolClasses = SchoolClass::all();
        $sections = Section::all();
        $parents = ParentModel::with('user')->get(); // Eager load parents

        return Inertia::render('Admin/Student/Edit', [
            'student' => $student->load(['user']), // Include the user relation
            'classes' => $schoolClasses, // Updated variable name
            'sections' => $sections,
            'parents' => $parents,
        ]);
    }

    // Update the specified student in storage
    public function update(Request $request, Student $student)
    {
        // Validate the request data
        $request->validate([
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'student_id' => 'required|string',
            'form_number' => 'nullable|string',
            'name_bn' => 'nullable|string|max:255',
            'name_en' => 'required|string|max:255',
            'birth_certificate_number' => 'required|string|max:255',
            'birth_place_district' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:Male,Female,Other',
            'nationality' => 'required|string|max:255',
            'religion' => 'required|string|max:255',
            'blood_group' => 'nullable|string|max:255',
            'class_role' => 'required|string|max:255',
            'minorities' => 'boolean',
            'minority_name' => 'nullable|string|max:255',
            'handicap' => 'nullable|string|max:255',
            // Mother details
            'mother_nid' => 'nullable|string|max:255',
            'mother_dob' => 'nullable|date',
            'mother_name_bn' => 'nullable|string|max:255',
            'mother_name_en' => 'nullable|string|max:255',
            'mother_mobile' => 'nullable|string|max:255',
            'mother_occupation' => 'nullable|string|max:255',
            'mother_dead' => 'boolean',
            // Father details
            'father_nid' => 'nullable|string|max:255',
            'father_dob' => 'nullable|date',
            'father_name_bn' => 'nullable|string|max:255',
            'father_name_en' => 'nullable|string|max:255',
            'father_mobile' => 'nullable|string|max:255',
            'father_occupation' => 'nullable|string|max:255',
            'father_dead' => 'boolean',
            // Present address validation
            'present_address_division' => 'nullable|string|max:255',
            'present_address_district' => 'nullable|string|max:255',
            'present_address_upazila' => 'nullable|string|max:255',
            'present_address_city' => 'nullable|string|max:255',
            'present_address_ward' => 'nullable|string|max:255',
            'present_address_village' => 'nullable|string|max:255',
            'present_address_house_number' => 'nullable|string|max:255',
            'present_address_post' => 'nullable|string|max:255',
            'present_address_post_code' => 'nullable|string|max:255',
            // Permanent address validation
            'permanent_address_division' => 'nullable|string|max:255',
            'permanent_address_district' => 'nullable|string|max:255',
            'permanent_address_upazila' => 'nullable|string|max:255',
            'permanent_address_city' => 'nullable|string|max:255',
            'permanent_address_ward' => 'nullable|string|max:255',
            'permanent_address_village' => 'nullable|string|max:255',
            'permanent_address_house_number' => 'nullable|string|max:255',
            'permanent_address_post' => 'nullable|string|max:255',
            'permanent_address_post_code' => 'nullable|string|max:255',
            'information_correct' => 'boolean',
            'class_id' => 'required|exists:school_classes,id',
            'section_id' => 'required|exists:sections,id',
            'parent_id' => 'nullable|exists:parent_models,id',
            'address' => 'nullable|string|max:255',
        ]);

        $photoPath = $student->user->photo;
        if ($request->hasFile('photo')) {
            // Delete old photo if it exists
            if ($photoPath && file_exists(public_path('students/' . $photoPath))) {
                unlink(public_path('students/' . $photoPath));
            }

            // Upload new photo
            $filename = time() . '.' . $request->photo->getClientOriginalExtension();
            $request->photo->move(public_path('students'), $filename);
            $photoPath = $filename;

        }

        // Update the user for the student
        $user = $student->user;
        $user->update([
            'name' => $request->name_en,
            'email' => $request->email,
            'photo' => $photoPath,
        ]);

        // Update the student model
        $student->update([
            'class_id' => $request->class_id,
            'section_id' => $request->section_id,
            'parent_id' => $request->parent_id,
            'student_id' => $request->student_id,
            'form_number' => $request->form_number,
            'name_bn' => $request->name_bn,
            'name_en' => $request->name_en,
            'birth_certificate_number' => $request->birth_certificate_number,
            'birth_place_district' => $request->birth_place_district,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'nationality' => $request->nationality,
            'religion' => $request->religion,
            'blood_group' => $request->blood_group,
            'class_role' => $request->class_role,
            'minorities' => $request->minorities,
            'minority_name' => $request->minority_name,
            'handicap' => $request->handicap,
            'mother_nid' => $request->mother_nid,
            'mother_dob' => $request->mother_dob,
            'mother_name_bn' => $request->mother_name_bn,
            'mother_name_en' => $request->mother_name_en,
            'mother_mobile' => $request->mother_mobile,
            'mother_occupation' => $request->mother_occupation,
            'mother_dead' => $request->mother_dead,
            'father_nid' => $request->father_nid,
            'father_dob' => $request->father_dob,
            'father_name_bn' => $request->father_name_bn,
            'father_name_en' => $request->father_name_en,
            'father_mobile' => $request->father_mobile,
            'father_occupation' => $request->father_occupation,
            'father_dead' => $request->father_dead,
            'present_address_division' => $request->present_address_division,
            'present_address_district' => $request->present_address_district,
            'present_address_upazila' => $request->present_address_upazila,
            'present_address_city' => $request->present_address_city,
            'present_address_ward' => $request->present_address_ward,
            'present_address_village' => $request->present_address_village,
            'present_address_house_number' => $request->present_address_house_number,
            'present_address_post' => $request->present_address_post,
            'present_address_post_code' => $request->present_address_post_code,
            'permanent_address_division' => $request->permanent_address_division,
            'permanent_address_district' => $request->permanent_address_district,
            'permanent_address_upazila' => $request->permanent_address_upazila,
            'permanent_address_city' => $request->permanent_address_city,
            'permanent_address_ward' => $request->permanent_address_ward,
            'permanent_address_village' => $request->permanent_address_village,
            'permanent_address_house_number' => $request->permanent_address_house_number,
            'permanent_address_post' => $request->permanent_address_post,
            'permanent_address_post_code' => $request->permanent_address_post_code,
            'information_correct' => $request->information_correct,
            'photo' => $photoPath,
        ]);

        // Handle the photo upload
        if ($request->hasFile('photo')) {
            // Delete the old photo if it exists
            if ($student->photo) {
                $oldPhotoPath = public_path('students/' . $student->photo);
                if (file_exists($oldPhotoPath)) {
                    unlink($oldPhotoPath); // Delete the old photo
                }
            }

            // Store the new photo
            $filename = time() . '.' . $request->photo->getClientOriginalExtension();
            $request->photo->move(public_path('students'), $filename);
            $student->photo = $filename; // Save new filename
            $student->save(); // Save the new photo filename to the database
        }


        return redirect()->route('admin.students.index')->with('success', 'Student updated successfully!');
    }


    // Remove the specified student from storage
    public function destroy(Student $student)
    {
        $student->user->delete(); // Delete the user associated with the student
        $student->delete(); // Delete the student

        return redirect()->route('admin.students.index')->with('success', 'Student deleted successfully!');
    }
}
