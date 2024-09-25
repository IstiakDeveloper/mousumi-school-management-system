<template>
    <Head title="Edit Student" />
  
    <AdminLayout>
      <div class="container mx-auto py-8">
        <div class="bg-white shadow-lg rounded-lg p-8">
          <h1 class="text-3xl font-bold text-gray-800 mb-6">Edit Student</h1>
  
          <form @submit.prevent="submit">
            <div class="mb-4">
              <label for="student_id" class="block text-sm font-medium text-gray-700">Student ID</label>
              <input
                v-model="form.student_id"
                type="text"
                id="student_id"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                required
              />
              <span v-if="form.errors.student_id" class="text-red-600 text-sm">{{ form.errors.student_id }}</span>
            </div>
  
            <div class="mb-4">
              <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
              <input
                v-model="form.email"
                type="email"
                id="email"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                required
              />
              <span v-if="form.errors.email" class="text-red-600 text-sm">{{ form.errors.email }}</span>
            </div>
  
            <div class="mb-4">
              <label for="form_number" class="block text-sm font-medium text-gray-700">Form Number</label>
              <input
                v-model="form.form_number"
                type="text"
                id="form_number"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                required
              />
              <span v-if="form.errors.form_number" class="text-red-600 text-sm">{{ form.errors.form_number }}</span>
            </div>
  
            <div class="mb-4">
              <label for="name_bn" class="block text-sm font-medium text-gray-700">Name (Bangla)</label>
              <input
                v-model="form.name_bn"
                type="text"
                id="name_bn"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                required
              />
              <span v-if="form.errors.name_bn" class="text-red-600 text-sm">{{ form.errors.name_bn }}</span>
            </div>
  
            <div class="mb-4">
              <label for="name_en" class="block text-sm font-medium text-gray-700">Name (English)</label>
              <input
                v-model="form.name_en"
                type="text"
                id="name_en"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                required
              />
              <span v-if="form.errors.name_en" class="text-red-600 text-sm">{{ form.errors.name_en }}</span>
            </div>
  
            <div class="mb-4">
              <label for="date_of_birth" class="block text-sm font-medium text-gray-700">Date of Birth</label>
              <input
                v-model="form.date_of_birth"
                type="date"
                id="date_of_birth"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                required
              />
              <span v-if="form.errors.date_of_birth" class="text-red-600 text-sm">{{ form.errors.date_of_birth }}</span>
            </div>
  
            <div class="mb-4">
              <label for="birth_certificate_number" class="block text-sm font-medium text-gray-700">Birth Certificate Number</label>
              <input
                v-model="form.birth_certificate_number"
                type="text"
                id="birth_certificate_number"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
              />
              <span v-if="form.errors.birth_certificate_number" class="text-red-600 text-sm">{{ form.errors.birth_certificate_number }}</span>
            </div>
  
            <div class="mb-4">
              <label for="birth_place_district" class="block text-sm font-medium text-gray-700">Birth Place District</label>
              <input
                v-model="form.birth_place_district"
                type="text"
                id="birth_place_district"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
              />
              <span v-if="form.errors.birth_place_district" class="text-red-600 text-sm">{{ form.errors.birth_place_district }}</span>
            </div>
  
            <div class="mb-4">
              <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
              <select
                v-model="form.gender"
                id="gender"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
              >
                <option value="">Select</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
              </select>
              <span v-if="form.errors.gender" class="text-red-600 text-sm">{{ form.errors.gender }}</span>
            </div>
  
            <div class="mb-4">
              <label for="nationality" class="block text-sm font-medium text-gray-700">Nationality</label>
              <input
                v-model="form.nationality"
                type="text"
                id="nationality"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
              />
              <span v-if="form.errors.nationality" class="text-red-600 text-sm">{{ form.errors.nationality }}</span>
            </div>
  
            <div class="mb-4">
              <label for="religion" class="block text-sm font-medium text-gray-700">Religion</label>
              <input
                v-model="form.religion"
                type="text"
                id="religion"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
              />
              <span v-if="form.errors.religion" class="text-red-600 text-sm">{{ form.errors.religion }}</span>
            </div>
  
            <div class="mb-4">
              <label for="blood_group" class="block text-sm font-medium text-gray-700">Blood Group</label>
              <input
                v-model="form.blood_group"
                type="text"
                id="blood_group"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
              />
              <span v-if="form.errors.blood_group" class="text-red-600 text-sm">{{ form.errors.blood_group }}</span>
            </div>
  
            <div class="mb-4">
              <label for="class_role" class="block text-sm font-medium text-gray-700">Class Role</label>
              <input
                v-model="form.class_role"
                type="text"
                id="class_role"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
              />
              <span v-if="form.errors.class_role" class="text-red-600 text-sm">{{ form.errors.class_role }}</span>
            </div>
  
            <div class="mb-4">
              <label for="minorities" class="block text-sm font-medium text-gray-700">Minorities</label>
              <input
                type="checkbox"
                v-model="form.minorities"
                id="minorities"
                class="form-checkbox"
              />
            </div>
  
            <div class="mb-4" v-if="form.minorities">
              <label for="minority_name" class="block text-sm font-medium text-gray-700">Minority Name</label>
              <input
                v-model="form.minority_name"
                type="text"
                id="minority_name"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
              />
              <span v-if="form.errors.minority_name" class="text-red-600 text-sm">{{ form.errors.minority_name }}</span>
            </div>
  
            <div class="mb-4">
              <label for="handicap" class="block text-sm font-medium text-gray-700">Handicap</label>
              <input
                v-model="form.handicap"
                type="text"
                id="handicap"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
              />
              <span v-if="form.errors.handicap" class="text-red-600 text-sm">{{ form.errors.handicap }}</span>
            </div>
  
            <div class="mb-4">
              <label for="mother_name_en" class="block text-sm font-medium text-gray-700">Mother's Name (English)</label>
              <input
                v-model="form.mother_name_en"
                type="text"
                id="mother_name_en"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
              />
              <span v-if="form.errors.mother_name_en" class="text-red-600 text-sm">{{ form.errors.mother_name_en }}</span>
            </div>
  
            <div class="mb-4">
              <label for="mother_nid" class="block text-sm font-medium text-gray-700">Mother's NID</label>
              <input
                v-model="form.mother_nid"
                type="text"
                id="mother_nid"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
              />
              <span v-if="form.errors.mother_nid" class="text-red-600 text-sm">{{ form.errors.mother_nid }}</span>
            </div>
  
            <div class="mb-4">
              <label for="mother_mobile" class="block text-sm font-medium text-gray-700">Mother's Mobile</label>
              <input
                v-model="form.mother_mobile"
                type="text"
                id="mother_mobile"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
              />
              <span v-if="form.errors.mother_mobile" class="text-red-600 text-sm">{{ form.errors.mother_mobile }}</span>
            </div>
  
            <div class="mb-4">
              <label for="father_name_en" class="block text-sm font-medium text-gray-700">Father's Name (English)</label>
              <input
                v-model="form.father_name_en"
                type="text"
                id="father_name_en"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
              />
              <span v-if="form.errors.father_name_en" class="text-red-600 text-sm">{{ form.errors.father_name_en }}</span>
            </div>
  
            <div class="mb-4">
              <label for="father_nid" class="block text-sm font-medium text-gray-700">Father's NID</label>
              <input
                v-model="form.father_nid"
                type="text"
                id="father_nid"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
              />
              <span v-if="form.errors.father_nid" class="text-red-600 text-sm">{{ form.errors.father_nid }}</span>
            </div>
  
            <div class="mb-4">
              <label for="father_mobile" class="block text-sm font-medium text-gray-700">Father's Mobile</label>
              <input
                v-model="form.father_mobile"
                type="text"
                id="father_mobile"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
              />
              <span v-if="form.errors.father_mobile" class="text-red-600 text-sm">{{ form.errors.father_mobile }}</span>
            </div>
  
            <div class="mb-4">
              <label for="class_id" class="block text-sm font-medium text-gray-700">Class ID</label>
              <input
                v-model="form.class_id"
                type="text"
                id="class_id"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                required
              />
              <span v-if="form.errors.class_id" class="text-red-600 text-sm">{{ form.errors.class_id }}</span>
            </div>
  
            <div class="mb-4">
              <label for="section_id" class="block text-sm font-medium text-gray-700">Section ID</label>
              <input
                v-model="form.section_id"
                type="text"
                id="section_id"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                required
              />
              <span v-if="form.errors.section_id" class="text-red-600 text-sm">{{ form.errors.section_id }}</span>
            </div>
  
            <div class="mb-4">
              <label for="parent_id" class="block text-sm font-medium text-gray-700">Parent ID</label>
              <input
                v-model="form.parent_id"
                type="text"
                id="parent_id"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                required
              />
              <span v-if="form.errors.parent_id" class="text-red-600 text-sm">{{ form.errors.parent_id }}</span>
            </div>
  
            <div class="mb-4">
              <label for="present_address_division" class="block text-sm font-medium text-gray-700">Present Address Division</label>
              <input
                v-model="form.present_address_division"
                type="text"
                id="present_address_division"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                required
              />
              <span v-if="form.errors.present_address_division" class="text-red-600 text-sm">{{ form.errors.present_address_division }}</span>
            </div>
  
            <div class="mb-4">
              <label for="present_address_district" class="block text-sm font-medium text-gray-700">Present Address District</label>
              <input
                v-model="form.present_address_district"
                type="text"
                id="present_address_district"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                required
              />
              <span v-if="form.errors.present_address_district" class="text-red-600 text-sm">{{ form.errors.present_address_district }}</span>
            </div>
  
            <div class="mb-4">
              <label for="present_address_upazila" class="block text-sm font-medium text-gray-700">Present Address Upazila</label>
              <input
                v-model="form.present_address_upazila"
                type="text"
                id="present_address_upazila"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                required
              />
              <span v-if="form.errors.present_address_upazila" class="text-red-600 text-sm">{{ form.errors.present_address_upazila }}</span>
            </div>
  
            <div class="mb-4">
              <label for="present_address_city" class="block text-sm font-medium text-gray-700">Present Address City</label>
              <input
                v-model="form.present_address_city"
                type="text"
                id="present_address_city"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
              />
              <span v-if="form.errors.present_address_city" class="text-red-600 text-sm">{{ form.errors.present_address_city }}</span>
            </div>
  
            <div class="mb-4">
              <label for="present_address_ward" class="block text-sm font-medium text-gray-700">Present Address Ward</label>
              <input
                v-model="form.present_address_ward"
                type="text"
                id="present_address_ward"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
              />
              <span v-if="form.errors.present_address_ward" class="text-red-600 text-sm">{{ form.errors.present_address_ward }}</span>
            </div>
  
            <div class="mb-4">
              <label for="present_address_village" class="block text-sm font-medium text-gray-700">Present Address Village</label>
              <input
                v-model="form.present_address_village"
                type="text"
                id="present_address_village"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
              />
              <span v-if="form.errors.present_address_village" class="text-red-600 text-sm">{{ form.errors.present_address_village }}</span>
            </div>
  
            <div class="mb-4">
              <label for="present_address_house_number" class="block text-sm font-medium text-gray-700">Present Address House Number</label>
              <input
                v-model="form.present_address_house_number"
                type="text"
                id="present_address_house_number"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
              />
              <span v-if="form.errors.present_address_house_number" class="text-red-600 text-sm">{{ form.errors.present_address_house_number }}</span>
            </div>
  
            <div class="mb-4">
              <label for="present_address_post" class="block text-sm font-medium text-gray-700">Present Address Post</label>
              <input
                v-model="form.present_address_post"
                type="text"
                id="present_address_post"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
              />
              <span v-if="form.errors.present_address_post" class="text-red-600 text-sm">{{ form.errors.present_address_post }}</span>
            </div>
  
            <div class="mb-4">
              <label for="present_address_post_code" class="block text-sm font-medium text-gray-700">Present Address Post Code</label>
              <input
                v-model="form.present_address_post_code"
                type="text"
                id="present_address_post_code"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
              />
              <span v-if="form.errors.present_address_post_code" class="text-red-600 text-sm">{{ form.errors.present_address_post_code }}</span>
            </div>
  
            <div class="mb-4">
              <label for="permanent_address_division" class="block text-sm font-medium text-gray-700">Permanent Address Division</label>
              <input
                v-model="form.permanent_address_division"
                type="text"
                id="permanent_address_division"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                required
              />
              <span v-if="form.errors.permanent_address_division" class="text-red-600 text-sm">{{ form.errors.permanent_address_division }}</span>
            </div>
  
            <div class="mb-4">
              <label for="permanent_address_district" class="block text-sm font-medium text-gray-700">Permanent Address District</label>
              <input
                v-model="form.permanent_address_district"
                type="text"
                id="permanent_address_district"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                required
              />
              <span v-if="form.errors.permanent_address_district" class="text-red-600 text-sm">{{ form.errors.permanent_address_district }}</span>
            </div>
  
            <div class="mb-4">
              <label for="permanent_address_upazila" class="block text-sm font-medium text-gray-700">Permanent Address Upazila</label>
              <input
                v-model="form.permanent_address_upazila"
                type="text"
                id="permanent_address_upazila"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                required
              />
              <span v-if="form.errors.permanent_address_upazila" class="text-red-600 text-sm">{{ form.errors.permanent_address_upazila }}</span>
            </div>
  
            <div class="mb-4">
              <label for="permanent_address_city" class="block text-sm font-medium text-gray-700">Permanent Address City</label>
              <input
                v-model="form.permanent_address_city"
                type="text"
                id="permanent_address_city"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
              />
              <span v-if="form.errors.permanent_address_city" class="text-red-600 text-sm">{{ form.errors.permanent_address_city }}</span>
            </div>
  
            <div class="mb-4">
              <label for="permanent_address_ward" class="block text-sm font-medium text-gray-700">Permanent Address Ward</label>
              <input
                v-model="form.permanent_address_ward"
                type="text"
                id="permanent_address_ward"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
              />
              <span v-if="form.errors.permanent_address_ward" class="text-red-600 text-sm">{{ form.errors.permanent_address_ward }}</span>
            </div>
  
            <div class="mb-4">
              <label for="permanent_address_village" class="block text-sm font-medium text-gray-700">Permanent Address Village</label>
              <input
                v-model="form.permanent_address_village"
                type="text"
                id="permanent_address_village"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
              />
              <span v-if="form.errors.permanent_address_village" class="text-red-600 text-sm">{{ form.errors.permanent_address_village }}</span>
            </div>
  
            <div class="mb-4">
              <label for="permanent_address_house_number" class="block text-sm font-medium text-gray-700">Permanent Address House Number</label>
              <input
                v-model="form.permanent_address_house_number"
                type="text"
                id="permanent_address_house_number"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
              />
              <span v-if="form.errors.permanent_address_house_number" class="text-red-600 text-sm">{{ form.errors.permanent_address_house_number }}</span>
            </div>
  
            <div class="mb-4">
              <label for="permanent_address_post" class="block text-sm font-medium text-gray-700">Permanent Address Post</label>
              <input
                v-model="form.permanent_address_post"
                type="text"
                id="permanent_address_post"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
              />
              <span v-if="form.errors.permanent_address_post" class="text-red-600 text-sm">{{ form.errors.permanent_address_post }}</span>
            </div>
  
            <div class="mb-4">
              <label for="permanent_address_post_code" class="block text-sm font-medium text-gray-700">Permanent Address Post Code</label>
              <input
                v-model="form.permanent_address_post_code"
                type="text"
                id="permanent_address_post_code"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
              />
              <span v-if="form.errors.permanent_address_post_code" class="text-red-600 text-sm">{{ form.errors.permanent_address_post_code }}</span>
            </div>
  
            <div class="mb-4">
              <label for="mother_dead" class="block text-sm font-medium text-gray-700">Mother Dead</label>
              <input
                type="checkbox"
                v-model="form.mother_dead"
                id="mother_dead"
                class="form-checkbox"
              />
            </div>
  
            <div class="mb-4">
              <label for="father_dead" class="block text-sm font-medium text-gray-700">Father Dead</label>
              <input
                type="checkbox"
                v-model="form.father_dead"
                id="father_dead"
                class="form-checkbox"
              />
            </div>
  
            <div class="mb-4">
              <label for="information_correct" class="block text-sm font-medium text-gray-700">Information Correct</label>
              <input
                type="checkbox"
                v-model="form.information_correct"
                id="information_correct"
                class="form-checkbox"
                required
              />
            </div>
  
            <div>
              <button type="submit" class="btn-primary">
                Update Student
              </button>
            </div>
          </form>
        </div>
      </div>
    </AdminLayout>
  </template>
  
  <script setup>
  import { useForm, Head } from '@inertiajs/vue3';
  import AdminLayout from '@/Layouts/AdminLayout.vue';
  
  const props = defineProps({
    student: Object,
  });
  
  const form = useForm({
    student_id: props.student.student_id,
    email: props.student.user.email,
    form_number: props.student.form_number,
    name_bn: props.student.name_bn,
    name_en: props.student.name_en,
    date_of_birth: props.student.date_of_birth,
    birth_certificate_number: props.student.birth_certificate_number,
    birth_place_district: props.student.birth_place_district,
    gender: props.student.gender,
    nationality: props.student.nationality,
    religion: props.student.religion,
    blood_group: props.student.blood_group,
    class_role: props.student.class_role,
    minorities: props.student.minorities,
    minority_name: props.student.minority_name,
    handicap: props.student.handicap,
    mother_name_en: props.student.mother_name_en,
    mother_nid: props.student.mother_nid,
    mother_mobile: props.student.mother_mobile,
    father_name_en: props.student.father_name_en,
    father_nid: props.student.father_nid,
    father_mobile: props.student.father_mobile,
    class_id: props.student.class_id,
    section_id: props.student.section_id,
    parent_id: props.student.parent_id,
    present_address_division: props.student.present_address_division,
    present_address_district: props.student.present_address_district,
    present_address_upazila: props.student.present_address_upazila,
    present_address_city: props.student.present_address_city,
    present_address_ward: props.student.present_address_ward,
    present_address_village: props.student.present_address_village,
    present_address_house_number: props.student.present_address_house_number,
    present_address_post: props.student.present_address_post,
    present_address_post_code: props.student.present_address_post_code,
    permanent_address_division: props.student.permanent_address_division,
    permanent_address_district: props.student.permanent_address_district,
    permanent_address_upazila: props.student.permanent_address_upazila,
    permanent_address_city: props.student.permanent_address_city,
    permanent_address_ward: props.student.permanent_address_ward,
    permanent_address_village: props.student.permanent_address_village,
    permanent_address_house_number: props.student.permanent_address_house_number,
    permanent_address_post: props.student.permanent_address_post,
    permanent_address_post_code: props.student.permanent_address_post_code,
    mother_dead: props.student.mother_dead,
    father_dead: props.student.father_dead,
    information_correct: props.student.information_correct,
    errors: {},
  });
  
  function submit() {
    form.post(route('student.update', props.student.student_id), {
      onSuccess: () => {
        // handle success response
      },
      onError: () => {
        // handle error response
      },
    });
  }
  </script>
  
  <style scoped>
  /* Add any specific styles here */
  </style>
  