<template>
    <Head title="Update Student" />

    <AdminLayout>
      <div class="container mx-auto py-8">
        <div class="bg-white dark:bg-gray-900 shadow-lg rounded-lg p-2">
          <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-6 text-center py-2">Update Student</h1>

          <form @submit.prevent="updateStudent">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                <div class="border border-gray-400 dark:border-gray-600 p-6 rounded bg-white dark:bg-gray-800">
                    <h3 class="text-center font-bold mb-2 text-lg text-gray-800 dark:text-gray-200">Student Details (Part 1)</h3>

                    <div class="mb-4">
                        <label for="photo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Photo</label>
                        <input
                            type="file"
                            @change="handleFileUpload"
                            id="photo"
                            class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                            accept="image/*"
                        />
                        <span v-if="form.errors.photo" class="text-red-600 text-sm">{{ form.errors.photo }}</span>

                        <!-- Image Preview -->
                        <div v-if="imagePreview" class="mt-2">
                            <img :src="imagePreview" alt="Preview" class="w-20 h-auto rounded-md shadow-md" />
                        </div>

                        <!-- Loading Animation -->
                        <div v-if="isLoading" class="flex items-center mt-2">
                            <div class="loader"></div>
                            <span class="ml-2 text-gray-700 dark:text-gray-300">Uploading...</span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Email <span class="text-red-600">*</span>
                        </label>
                        <input
                            v-model="form.email"
                            type="email"
                            id="email"
                            required
                            class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                        />
                        <span v-if="form.errors.email" class="text-red-600 text-sm">{{ form.errors.email }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="student_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Student ID <span class="text-red-600">*</span>
                        </label>
                        <input
                            v-model="form.student_id"
                            type="text"
                            id="student_id"
                            required
                            class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                        />
                        <span v-if="form.errors.student_id" class="text-red-600 text-sm">{{ form.errors.student_id }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="form_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Form Number</label>
                        <input
                            v-model="form.form_number"
                            type="text"
                            id="form_number"
                            class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                        />
                        <span v-if="form.errors.form_number" class="text-red-600 text-sm">{{ form.errors.form_number }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="name_bn" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name (Bangla)</label>
                        <input
                            v-model="form.name_bn"
                            type="text"
                            id="name_bn"
                            class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                        />
                        <span v-if="form.errors.name_bn" class="text-red-600 text-sm">{{ form.errors.name_bn }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="name_en" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Name (English) <span class="text-red-600">*</span>
                        </label>
                        <input
                            v-model="form.name_en"
                            type="text"
                            id="name_en"
                            required
                            class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                        />
                        <span v-if="form.errors.name_en" class="text-red-600 text-sm">{{ form.errors.name_en }}</span>
                    </div>
                </div>

                <div class="border border-gray-400 dark:border-gray-600 p-6 rounded bg-white dark:bg-gray-800">
                    <h3 class="text-center font-bold mb-2 text-lg text-gray-800 dark:text-gray-200">Student Details (Part 2)</h3>

                    <div class="mb-4">
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Date of Birth <span class="text-red-600">*</span>
                        </label>
                        <input
                            v-model="form.date_of_birth"
                            type="date"
                            id="date_of_birth"
                            required
                            class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                        />
                        <span v-if="form.errors.date_of_birth" class="text-red-600 text-sm">{{ form.errors.date_of_birth }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="birth_certificate_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Birth Certificate Number <span class="text-red-600">*</span>
                        </label>
                        <input
                            v-model="form.birth_certificate_number"
                            type="text"
                            id="birth_certificate_number"
                            required
                            class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                        />
                        <span v-if="form.errors.birth_certificate_number" class="text-red-600 text-sm">{{ form.errors.birth_certificate_number }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="birth_place_district" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Birth Place District <span class="text-red-600">*</span>
                        </label>
                        <input
                            v-model="form.birth_place_district"
                            type="text"
                            id="birth_place_district"
                            required
                            class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                        />
                        <span v-if="form.errors.birth_place_district" class="text-red-600 text-sm">{{ form.errors.birth_place_district }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Gender <span class="text-red-600">*</span>
                        </label>
                        <select v-model="form.gender" id="gender" required class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            <option value="">Select Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Other">Other</option>
                        </select>
                        <span v-if="form.errors.gender" class="text-red-600 text-sm">{{ form.errors.gender }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="nationality" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Nationality <span class="text-red-600">*</span>
                        </label>
                        <input
                            v-model="form.nationality"
                            type="text"
                            id="nationality"
                            required
                            class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                        />
                        <span v-if="form.errors.nationality" class="text-red-600 text-sm">{{ form.errors.nationality }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="religion" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Religion <span class="text-red-600">*</span>
                        </label>
                        <input
                            v-model="form.religion"
                            type="text"
                            id="religion"
                            required
                            class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                        />
                        <span v-if="form.errors.religion" class="text-red-600 text-sm">{{ form.errors.religion }}</span>
                    </div>
                </div>



                <div class="border border-gray-400 dark:border-gray-600 p-6 rounded bg-white dark:bg-gray-800">
                    <h3 class="text-center font-bold mb-2 text-lg text-gray-800 dark:text-gray-200">Student Details (Part 3)</h3>

                    <div class="mb-4">
                        <label for="blood_group" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Blood Group</label>
                        <input
                            v-model="form.blood_group"
                            type="text"
                            id="blood_group"
                            class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                        />
                        <span v-if="form.errors.blood_group" class="text-red-600 text-sm">{{ form.errors.blood_group }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="class_role" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Class Role <span class="text-red-600">*</span></label>
                        <input
                            v-model="form.class_role"
                            type="text"
                            id="class_role"
                            required
                            class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                        />
                        <span v-if="form.errors.class_role" class="text-red-600 text-sm">{{ form.errors.class_role }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="minorities" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Minorities (If have)</label>
                        <input type="checkbox" v-model="form.minorities" id="minorities" />
                    </div>

                    <div class="mb-4" v-if="form.minorities">
                        <label for="minority_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Minority Name</label>
                        <input
                            v-model="form.minority_name"
                            type="text"
                            id="minority_name"
                            class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                        />
                        <span v-if="form.errors.minority_name" class="text-red-600 text-sm">{{ form.errors.minority_name }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="handicap" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Handicap</label>
                        <select
                            v-model="form.handicap"
                            id="handicap"
                            class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                        >
                            <option value="" disabled>Select an option</option>
                            <option value="yes">Yes</option>
                            <option value="no">No</option>
                        </select>
                        <span v-if="form.errors.handicap" class="text-red-600 text-sm">{{ form.errors.handicap }}</span>
                    </div>
                </div>

                <div class="border border-gray-400 dark:border-gray-600 p-6 rounded bg-white dark:bg-gray-800">
                    <h3 class="text-center font-bold mb-2 text-lg text-gray-800 dark:text-gray-200">Assign</h3>

                    <div class="mb-4">
                        <label for="class_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Class</label>
                        <select v-model="form.class_id" id="class_id" required class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            <option disabled selected value="">Select Class</option>
                            <option v-for="classItem in classes" :key="classItem.id" :value="classItem.id">{{ classItem.name }}</option>
                        </select>
                        <span v-if="form.errors.class_id" class="text-red-600 text-sm">{{ form.errors.class_id }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="section_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Section</label>
                        <select v-model="form.section_id" id="section_id" required class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            <option disabled selected value="">Select Section</option>
                            <option v-for="section in sections" :key="section.id" :value="section.id">{{ section.name }}</option>
                        </select>
                        <span v-if="form.errors.section_id" class="text-red-600 text-sm">{{ form.errors.section_id }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="parent_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Parent</label>
                        <select v-model="form.parent_id" id="parent_id" class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                            <option disabled selected value="">Select Parent</option>
                            <option v-for="parent in parents" :key="parent.id" :value="parent.id">{{ parent.user.name }}</option>
                        </select>
                        <span v-if="form.errors.parent_id" class="text-red-600 text-sm">{{ form.errors.parent_id }}</span>
                    </div>
                </div>


                <div class="border border-gray-400 dark:border-gray-600 p-6 rounded bg-white dark:bg-gray-800">
                    <h3 class="text-center font-bold mb-2 text-lg text-gray-800 dark:text-gray-200">Mother Details</h3>
                    <div class="mb-4">
                        <label for="mother_name_en" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mother's Name</label>
                        <input
                            v-model="form.mother_name_en"
                            type="text"
                            id="mother_name_en"
                            class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                        />
                        <span v-if="form.errors.mother_name_en" class="text-red-600 text-sm">{{ form.errors.mother_name_en }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="mother_nid" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mother's NID</label>
                        <input
                            v-model="form.mother_nid"
                            type="text"
                            id="mother_nid"
                            class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                        />
                        <span v-if="form.errors.mother_nid" class="text-red-600 text-sm">{{ form.errors.mother_nid }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="mother_mobile" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mother's Mobile</label>
                        <input
                            v-model="form.mother_mobile"
                            type="text"
                            id="mother_mobile"
                            class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                        />
                        <span v-if="form.errors.mother_mobile" class="text-red-600 text-sm">{{ form.errors.mother_mobile }}</span>
                    </div>
                </div>

                <div class="border border-gray-400 dark:border-gray-600 p-6 rounded bg-white dark:bg-gray-800">
                    <h3 class="text-center font-bold mb-2 text-lg text-gray-800 dark:text-gray-200">Father Details</h3>
                    <div class="mb-4">
                        <label for="father_name_en" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Father's Name</label>
                        <input
                            v-model="form.father_name_en"
                            type="text"
                            id="father_name_en"
                            class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                        />
                        <span v-if="form.errors.father_name_en" class="text-red-600 text-sm">{{ form.errors.father_name_en }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="father_nid" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Father's NID</label>
                        <input
                            v-model="form.father_nid"
                            type="text"
                            id="father_nid"
                            class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                        />
                        <span v-if="form.errors.father_nid" class="text-red-600 text-sm">{{ form.errors.father_nid }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="father_mobile" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Father's Mobile</label>
                        <input
                            v-model="form.father_mobile"
                            type="text"
                            id="father_mobile"
                            class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                        />
                        <span v-if="form.errors.father_mobile" class="text-red-600 text-sm">{{ form.errors.father_mobile }}</span>
                    </div>
                </div>


                <div class="border border-gray-400 dark:border-gray-600 p-6 rounded bg-white dark:bg-gray-800">
                    <h3 class="text-center font-bold mb-2 text-lg text-gray-800 dark:text-gray-200">Present Address</h3>

                    <div class="mb-4">
                        <label for="present_address_division" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Division</label>
                        <select
                            v-model="form.present_address_division"
                            @change="updateDistricts('present')"
                            id="present_address_division"
                            class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                        >
                            <option value="">Select Division</option>
                            <option v-for="division in divisions" :key="division" :value="division">{{ division }}</option>
                        </select>
                        <span v-if="form.errors.present_address_division" class="text-red-600 text-sm">{{ form.errors.present_address_division }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="present_address_district" class="block text-sm font-medium text-gray-700 dark:text-gray-300">District</label>
                        <select
                            v-model="form.present_address_district"
                            @change="updateUpazilas('present')"
                            id="present_address_district"
                            class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                        >
                            <option value="">Select District</option>
                            <option v-for="district in filteredPresentDistricts" :key="district" :value="district">{{ district }}</option>
                        </select>
                        <span v-if="form.errors.present_address_district" class="text-red-600 text-sm">{{ form.errors.present_address_district }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="present_address_upazila" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Upazila</label>
                        <select
                            v-model="form.present_address_upazila"
                            id="present_address_upazila"
                            class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                        >
                            <option value="">Select Upazila</option>
                            <option v-for="upazila in filteredPresentUpazilas" :key="upazila" :value="upazila">{{ upazila }}</option>
                        </select>
                        <span v-if="form.errors.present_address_upazila" class="text-red-600 text-sm">{{ form.errors.present_address_upazila }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="present_address_city" class="block text-sm font-medium text-gray-700 dark:text-gray-300">City</label>
                        <input
                            v-model="form.present_address_city"
                            type="text"
                            id="present_address_city"
                            class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                        />
                        <span v-if="form.errors.present_address_city" class="text-red-600 text-sm">{{ form.errors.present_address_city }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="present_address_ward" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ward</label>
                        <input
                            v-model="form.present_address_ward"
                            type="text"
                            id="present_address_ward"
                            class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                        />
                        <span v-if="form.errors.present_address_ward" class="text-red-600 text-sm">{{ form.errors.present_address_ward }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="present_address_village" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Village</label>
                        <input
                            v-model="form.present_address_village"
                            type="text"
                            id="present_address_village"
                            class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                        />
                        <span v-if="form.errors.present_address_village" class="text-red-600 text-sm">{{ form.errors.present_address_village }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="present_address_house_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">House Number</label>
                        <input
                            v-model="form.present_address_house_number"
                            type="text"
                            id="present_address_house_number"
                            class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                        />
                        <span v-if="form.errors.present_address_house_number" class="text-red-600 text-sm">{{ form.errors.present_address_house_number }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="present_address_post" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Post</label>
                        <input
                            v-model="form.present_address_post"
                            type="text"
                            id="present_address_post"
                            class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                        />
                        <span v-if="form.errors.present_address_post" class="text-red-600 text-sm">{{ form.errors.present_address_post }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="present_address_post_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Post Code</label>
                        <input
                            v-model="form.present_address_post_code"
                            type="text"
                            id="present_address_post_code"
                            class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                        />
                        <span v-if="form.errors.present_address_post_code" class="text-red-600 text-sm">{{ form.errors.present_address_post_code }}</span>
                    </div>
                </div>

                <div class="border border-gray-400 dark:border-gray-600 p-6 rounded bg-white dark:bg-gray-800">
                    <h3 class="text-center font-bold mb-2 text-lg text-gray-800 dark:text-gray-200">Permanent Address</h3>

                    <div class="mb-4">
                        <label for="permanent_address_division" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Division</label>
                        <select
                            v-model="form.permanent_address_division"
                            @change="updateDistricts('permanent')"
                            id="permanent_address_division"
                            class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                        >
                            <option value="">Select Division</option>
                            <option v-for="division in divisions" :key="division" :value="division">{{ division }}</option>
                        </select>
                        <span v-if="form.errors.permanent_address_division" class="text-red-600 text-sm">{{ form.errors.permanent_address_division }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="permanent_address_district" class="block text-sm font-medium text-gray-700 dark:text-gray-300">District</label>
                        <select
                            v-model="form.permanent_address_district"
                            @change="updateUpazilas('permanent')"
                            id="permanent_address_district"
                            class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                        >
                            <option value="">Select District</option>
                            <option v-for="district in filteredPermanentDistricts" :key="district" :value="district">{{ district }}</option>
                        </select>
                        <span v-if="form.errors.permanent_address_district" class="text-red-600 text-sm">{{ form.errors.permanent_address_district }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="permanent_address_upazila" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Upazila</label>
                        <select
                            v-model="form.permanent_address_upazila"
                            id="permanent_address_upazila"
                            class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                        >
                            <option value="">Select Upazila</option>
                            <option v-for="upazila in filteredPermanentUpazilas" :key="upazila" :value="upazila">{{ upazila }}</option>
                        </select>
                        <span v-if="form.errors.permanent_address_upazila" class="text-red-600 text-sm">{{ form.errors.permanent_address_upazila }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="permanent_address_city" class="block text-sm font-medium text-gray-700 dark:text-gray-300">City</label>
                        <input
                            v-model="form.permanent_address_city"
                            type="text"
                            id="permanent_address_city"
                            class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                        />
                        <span v-if="form.errors.permanent_address_city" class="text-red-600 text-sm">{{ form.errors.permanent_address_city }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="permanent_address_ward" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Ward</label>
                        <input
                            v-model="form.permanent_address_ward"
                            type="text"
                            id="permanent_address_ward"
                            class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                        />
                        <span v-if="form.errors.permanent_address_ward" class="text-red-600 text-sm">{{ form.errors.permanent_address_ward }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="permanent_address_village" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Village</label>
                        <input
                            v-model="form.permanent_address_village"
                            type="text"
                            id="permanent_address_village"
                            class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                        />
                        <span v-if="form.errors.permanent_address_village" class="text-red-600 text-sm">{{ form.errors.permanent_address_village }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="permanent_address_house_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">House Number</label>
                        <input
                            v-model="form.permanent_address_house_number"
                            type="text"
                            id="permanent_address_house_number"
                            class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                        />
                        <span v-if="form.errors.permanent_address_house_number" class="text-red-600 text-sm">{{ form.errors.permanent_address_house_number }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="permanent_address_post" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Post</label>
                        <input
                            v-model="form.permanent_address_post"
                            type="text"
                            id="permanent_address_post"
                            class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                        />
                        <span v-if="form.errors.permanent_address_post" class="text-red-600 text-sm">{{ form.errors.permanent_address_post }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="permanent_address_post_code" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Post Code</label>
                        <input
                            v-model="form.permanent_address_post_code"
                            type="text"
                            id="permanent_address_post_code"
                            class="mt-1 block w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50"
                        />
                        <span v-if="form.errors.permanent_address_post_code" class="text-red-600 text-sm">{{ form.errors.permanent_address_post_code }}</span>
                    </div>
                </div>



                <div class="px-6">
                    <label for="information_correct" class="inline-flex items-center">
                        <input
                            v-model="form.information_correct"
                            type="checkbox"
                            id="information_correct"
                            class="form-checkbox h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 dark:border-gray-600 dark:bg-gray-700"
                        />
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">I confirm that the information provided is correct</span>
                    </label>
                </div>

                </div>

                <div class="my-6 px-6">
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
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import locationData from '@/location.json'; // Include location data for divisions, districts, etc.

const props = defineProps({
  student: Object,
  classes: Array,
  sections: Array,
  parents: Array,
});

const imagePreview = ref(null);
const isLoading = ref(false);
const successMessage = ref('');
const errorMessage = ref('');

// Handle file upload and set the image preview
function handleFileUpload(event) {
  const file = event.target.files[0];
  if (file) {
    // Check for valid image types if needed
    const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
    if (!validTypes.includes(file.type)) {
      errorMessage.value = 'Please upload a valid image file (JPEG, PNG, GIF).';
      return;
    }

    form.photo = file; // Assign the uploaded file to form.photo
    const reader = new FileReader();
    reader.onload = (e) => {
      imagePreview.value = e.target.result; // Display preview of the new image
      errorMessage.value = ''; // Clear any previous error messages
    };
    reader.readAsDataURL(file);
  } else {
    // Reset preview and error if no file selected
    imagePreview.value = null;
    errorMessage.value = 'No file selected.';
  }
}

// Initialize the form with student data
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
  information_correct: props.student.information_correct ?? false,
  photo: null, // Prepare for photo upload, initial is null
});

// Load location data
const divisions = locationData.divisions;
const districts = locationData.districts;
const upazilas = locationData.upazilas || {};

// Filtered districts and upazilas based on selected divisions
const filteredPresentDistricts = computed(() => {
  return form.present_address_division ? districts[form.present_address_division] : [];
});

const filteredPermanentDistricts = computed(() => {
  return form.permanent_address_division ? districts[form.permanent_address_division] : [];
});

const filteredPresentUpazilas = computed(() => {
  return form.present_address_district ? upazilas[form.present_address_district] : [];
});

const filteredPermanentUpazilas = computed(() => {
  return form.permanent_address_district ? upazilas[form.permanent_address_district] : [];
});

// Address copying functionality
const copyAddress = ref(false);

function updateDistricts(type) {
  if (type === 'present') {
    form.present_address_district = '';
    form.present_address_upazila = '';
  } else {
    form.permanent_address_district = '';
    form.permanent_address_upazila = '';
  }
}

function updateUpazilas(type) {
  if (type === 'present') {
    form.present_address_upazila = '';
  } else {
    form.permanent_address_upazila = '';
  }
}

function copyAddressToPermanent() {
  if (copyAddress.value) {
    form.permanent_address_division = form.present_address_division;
    form.permanent_address_district = form.present_address_district;
    form.permanent_address_upazila = form.present_address_upazila;
    form.permanent_address_city = form.present_address_city;
    form.permanent_address_ward = form.present_address_ward;
    form.permanent_address_village = form.present_address_village;
    form.permanent_address_house_number = form.present_address_house_number;
    form.permanent_address_post = form.present_address_post;
    form.permanent_address_post_code = form.present_address_post_code;
  } else {
    form.permanent_address_division = '';
    form.permanent_address_district = '';
    form.permanent_address_upazila = '';
    form.permanent_address_city = '';
    form.permanent_address_ward = '';
    form.permanent_address_village = '';
    form.permanent_address_house_number = '';
    form.permanent_address_post = '';
    form.permanent_address_post_code = '';
  }
}

// Update student information
function updateStudent() {
  // Create a FormData object
  const formData = new FormData();

  // Append form fields to FormData object
  Object.keys(form).forEach(key => {
    formData.append(key, form[key]);
  });

  // Submit the form with the PUT method to update the student
  form.put(route('admin.students.update', props.student.id), {
    data: formData, // Pass formData here
    onSuccess: () => {
      successMessage.value = 'Student updated successfully!';
      errorMessage.value = ''; // Clear any previous error messages
    },
    onError: (errors) => {
      errorMessage.value = 'Error updating student. Please try again.';
      successMessage.value = ''; // Clear any previous success messages

      // Optionally, handle field-specific validation errors here
      if (errors.name) {
        errorMessage.value = errors.name[0]; // Show first error for the 'name' field
      }
      // Add similar checks for other fields if needed
    },
  });
}
</script>


<style scoped>
.btn-primary {
  @apply bg-blue-600 text-white font-semibold py-2 px-4 rounded shadow hover:bg-blue-700 transition duration-200;
}
</style>
