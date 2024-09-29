<template>
    <Head title="Student Profile" />
    <AdminLayout>
      <div class="max-w-7xl mx-auto p-6 bg-white dark:bg-gray-800 shadow-lg rounded-lg">
        <h1 class="text-3xl font-semibold mb-8 text-gray-800 dark:text-gray-200">Student Profile</h1>
        
  
        <!-- Basic Information -->
        <section class="mb-8">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-700 dark:text-gray-200">Basic Information</h2>
        <div class="flex flex-col items-center">
        <div class="mb-4">
            <img 
              :src="getStudentPhotoUrl(student.photo || 'placeholder.png')" 
              alt="Student Photo" 
              class="w-32 h-32 rounded-full object-cover" 
            />
        </div>
        <div class="space-y-2 text-center">
            <p class="text-gray-700 dark:text-gray-300"><strong>Student ID:</strong> {{ student.student_id }}</p>
            <p class="text-gray-700 dark:text-gray-300"><strong>Form Number:</strong> {{ student.form_number }}</p>
            <p class="text-gray-700 dark:text-gray-300"><strong>Name:</strong> {{ student.name_en }}</p>
            <p class="text-gray-700 dark:text-gray-300"><strong>Birth Certificate Number:</strong> {{ student.birth_certificate_number }}</p>
            <p class="text-gray-700 dark:text-gray-300"><strong>Birth Place:</strong> {{ student.birth_place_district || 'N/A' }}</p>
            <p class="text-gray-700 dark:text-gray-300"><strong>Date of Birth:</strong> {{ student.date_of_birth || 'N/A' }}</p>
            <p class="text-gray-700 dark:text-gray-300"><strong>Gender:</strong> {{ student.gender || 'N/A' }}</p>
            <p class="text-gray-700 dark:text-gray-300"><strong>Nationality:</strong> {{ student.nationality || 'N/A' }}</p>
            <p class="text-gray-700 dark:text-gray-300"><strong>Religion:</strong> {{ student.religion || 'N/A' }}</p>
            <p class="text-gray-700 dark:text-gray-300"><strong>Blood Group:</strong> {{ student.blood_group || 'N/A' }}</p>
            <p class="text-gray-700 dark:text-gray-300"><strong>Class:</strong> {{ student.class?.name || 'N/A' }}</p>
            <p class="text-gray-700 dark:text-gray-300"><strong>Section:</strong> {{ student.section?.name || 'N/A' }}</p>
            <p class="text-gray-700 dark:text-gray-300"><strong>Class Role:</strong> {{ student.class_role || 'N/A' }}</p>
        </div>
        </div>
        </section>
  

        <!-- Parent Information -->
        <section class="mb-8">
          <h2 class="text-2xl font-bold mb-6 text-gray-700 dark:text-gray-200">Parent Information</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <h3 class="text-xl font-semibold text-gray-600 dark:text-gray-300">Mother's Details</h3>
              <div class="space-y-2">
                <p class="text-gray-700 dark:text-gray-300"><strong>Name (BN):</strong> {{ student.mother_name_bn || 'N/A' }}</p>
                <p class="text-gray-700 dark:text-gray-300"><strong>Name (EN):</strong> {{ student.mother_name_en || 'N/A' }}</p>
                <p class="text-gray-700 dark:text-gray-300"><strong>Mobile:</strong> {{ student.mother_mobile || 'N/A' }}</p>
                <p class="text-gray-700 dark:text-gray-300"><strong>Occupation:</strong> {{ student.mother_occupation || 'N/A' }}</p>
                <p class="text-gray-700 dark:text-gray-300"><strong>Deceased:</strong> {{ student.mother_dead ? 'Yes' : 'No' }}</p>
              </div>
            </div>
            <div>
              <h3 class="text-xl font-semibold text-gray-600 dark:text-gray-300">Father's Details</h3>
              <div class="space-y-2">
                <p class="text-gray-700 dark:text-gray-300"><strong>Name (BN):</strong> {{ student.father_name_bn || 'N/A' }}</p>
                <p class="text-gray-700 dark:text-gray-300"><strong>Name (EN):</strong> {{ student.father_name_en || 'N/A' }}</p>
                <p class="text-gray-700 dark:text-gray-300"><strong>Mobile:</strong> {{ student.father_mobile || 'N/A' }}</p>
                <p class="text-gray-700 dark:text-gray-300"><strong>Occupation:</strong> {{ student.father_occupation || 'N/A' }}</p>
                <p class="text-gray-700 dark:text-gray-300"><strong>Deceased:</strong> {{ student.father_dead ? 'Yes' : 'No' }}</p>
              </div>
            </div>
          </div>
        </section>

        <!-- Address Information -->
        <section class="mb-8">
          <h2 class="text-2xl font-bold mb-6 text-gray-700 dark:text-gray-200">Address Information</h2>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <h3 class="text-xl font-semibold text-gray-600 dark:text-gray-300">Present Address</h3>
              <div class="space-y-2">
                <p class="text-gray-700 dark:text-gray-300"><strong>Division:</strong> {{ student.present_address_division || 'N/A' }}</p>
                <p class="text-gray-700 dark:text-gray-300"><strong>District:</strong> {{ student.present_address_district || 'N/A' }}</p>
                <p class="text-gray-700 dark:text-gray-300"><strong>Upazila:</strong> {{ student.present_address_upazila || 'N/A' }}</p>
                <p class="text-gray-700 dark:text-gray-300"><strong>Village:</strong> {{ student.present_address_village || 'N/A' }}</p>
                <p class="text-gray-700 dark:text-gray-300"><strong>House No:</strong> {{ student.present_address_house_number || 'N/A' }}</p>
                <p class="text-gray-700 dark:text-gray-300"><strong>Post Code:</strong> {{ student.present_address_post_code || 'N/A' }}</p>
              </div>
            </div>
            <div>
              <h3 class="text-xl font-semibold text-gray-600 dark:text-gray-300">Permanent Address</h3>
              <div class="space-y-2">
                <p class="text-gray-700 dark:text-gray-300"><strong>Division:</strong> {{ student.permanent_address_division || 'N/A' }}</p>
                <p class="text-gray-700 dark:text-gray-300"><strong>District:</strong> {{ student.permanent_address_district || 'N/A' }}</p>
                <p class="text-gray-700 dark:text-gray-300"><strong>Upazila:</strong> {{ student.permanent_address_upazila || 'N/A' }}</p>
                <p class="text-gray-700 dark:text-gray-300"><strong>Village:</strong> {{ student.permanent_address_village || 'N/A' }}</p>
                <p class="text-gray-700 dark:text-gray-300"><strong>House No:</strong> {{ student.permanent_address_house_number || 'N/A' }}</p>
                <p class="text-gray-700 dark:text-gray-300"><strong>Post Code:</strong> {{ student.permanent_address_post_code || 'N/A' }}</p>
              </div>
            </div>
          </div>
        </section>

        <!-- Information Verified -->
        <section class="mb-8">
          <p class="text-gray-700 dark:text-gray-300"><strong>Information Correct:</strong> {{ student.information_correct ? 'Yes' : 'No' }}</p>
        </section>

        <!-- Buttons -->
        <div class="flex justify-end space-x-4 mt-6">
            <!-- <button @click="$router.go(-1)" class="px-5 py-3 bg-gray-300 hover:bg-gray-400 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-100 rounded-lg shadow">Back</button>
            <button @click="editProfile" class="px-5 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow">Edit Profile</button> -->
            <button @click="openGenerateCardDialog" class="px-5 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow">Generate ID Card</button>
        </div>

        <!-- Generate ID Card Modal -->
        <div v-if="isCardDialogVisible" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8 w-96">
              <h2 class="text-2xl font-semibold mb-6 text-gray-800 dark:text-gray-200 text-center">Student ID Card</h2>
              <div class="flex justify-center mb-6">
                <img 
                  :src="getStudentPhotoUrl(student.photo || 'placeholder.png')" 
                  alt="Student Photo" 
                  class="w-24 h-24 rounded-full object-cover"
                />
              </div>
              <div class="space-y-4 text-center">
                <p class="text-gray-700 dark:text-gray-300"><strong>ID:</strong> {{ student.student_id }}</p>
                <p class="text-gray-700 dark:text-gray-300"><strong>Name:</strong> {{ student.name_en }}</p>
                <p class="text-gray-700 dark:text-gray-300"><strong>Class:</strong> {{ student.class?.name || 'N/A' }}</p>
                <p class="text-gray-700 dark:text-gray-300"><strong>Section:</strong> {{ student.section?.name || 'N/A' }}</p>
                <p class="text-gray-700 dark:text-gray-300"><strong>Date of Birth:</strong> {{ student.date_of_birth || 'N/A' }}</p>
              </div>
  
              <div class="flex justify-center mt-6 space-x-4">
                <button @click="printIdCard" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow">Print</button>
                <button @click="closeGenerateCardDialog" class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg shadow">Close</button>
              </div>
            </div>
        </div>
        </div>

    </AdminLayout>
</template>
  
<script setup>
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const appUrl = import.meta.env.VITE_APP_URL;

const getStudentPhotoUrl = (photo) => `${appUrl}/students/${photo}`;

const props = defineProps({
  student: Object,
});

const isCardDialogVisible = ref(false);

// Function to open the Generate Card dialog
const openGenerateCardDialog = () => {
  isCardDialogVisible.value = true;
};

// Function to close the Generate Card dialog
const closeGenerateCardDialog = () => {
  isCardDialogVisible.value = false;
};

// Function to print the ID card
const printIdCard = () => {
  window.print();
};
</script>

<!-- <style>
@media print {
  /* Hide everything except the ID card during print */
  body * {
    visibility: hidden;
  }
  .print-area, .print-area * {
    visibility: visible;
  }
  .print-area {
    position: absolute;
    top: 0;
    left: 0;
  }
}
</style> -->
  
  