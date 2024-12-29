<template>

    <Head :title="`Student Profile - ${student.name_en}`" />
    <AdminLayout>
        <div class="max-w-7xl mx-auto p-4">
            <!-- Header Section -->
            <div class="mb-8 flex flex-col md:flex-row items-start md:items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                        Student Profile
                    </h1>
                    <p class="text-gray-500 dark:text-gray-400 mt-1">
                        Manage and view student information
                    </p>
                </div>
                <div class="mt-4 md:mt-0 flex space-x-3">
                    <button @click="$router.go(-1)"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Back
                    </button>
                    <button @click="openGenerateCardDialog"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 flex items-center gap-2">
                        <i class="fas fa-id-card"></i>
                        Generate ID Card
                    </button>
                </div>
            </div>

            <!-- Main Content -->
            <div class="grid grid-cols-12 gap-6">
                <!-- Left Column - Photo and Basic Info -->
                <div class="col-span-12 md:col-span-4">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                        <div class="flex flex-col items-center">
                            <div class="relative mb-4">
                                <img :src="getStudentPhotoUrl(student.photo || 'placeholder.png')"
                                    :alt="student.name_en"
                                    class="w-32 h-32 rounded-full object-cover border-4 border-blue-100 dark:border-blue-900" />
                                <span class="absolute bottom-0 right-0 w-6 h-6 rounded-full"
                                    :class="student.information_correct ? 'bg-green-500' : 'bg-yellow-500'"></span>
                            </div>
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ student.name_en }}
                            </h2>
                            <p class="text-gray-500 dark:text-gray-400">ID: {{ student.student_id }}</p>
                            <div class="mt-4 flex items-center space-x-2">
                                <span class="px-3 py-1 text-sm rounded-full"
                                    :class="student.information_correct ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-100'">
                                    {{ student.information_correct ? 'Verified' : 'Pending Verification' }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-6 space-y-4">
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-graduation-cap text-gray-400 w-5"></i>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Class</p>
                                    <p class="text-gray-900 dark:text-gray-100">{{ student.class?.name || 'N/A' }} - {{
                                        student.section?.name || 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-calendar text-gray-400 w-5"></i>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Date of Birth</p>
                                    <p class="text-gray-900 dark:text-gray-100">{{ student.date_of_birth || 'N/A' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <i class="fas fa-money-bill text-gray-400 w-5"></i>
                                <div>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Monthly Fee</p>
                                    <p class="text-gray-900 dark:text-gray-100">{{ student.monthly_fee || 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Detailed Information -->
                <div class="col-span-12 md:col-span-8 space-y-6">
                    <!-- Tabs Navigation -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg">
                        <div class="border-b border-gray-200 dark:border-gray-700">
                            <nav class="flex -mb-px">
                                <button v-for="tab in ['Details', 'Parents', 'Address']" :key="tab"
                                    @click="activeTab = tab.toLowerCase()" :class="[
                                        activeTab === tab.toLowerCase()
                                            ? 'border-blue-500 text-blue-600 dark:text-blue-400'
                                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:hover:text-gray-300',
                                        'w-1/3 py-4 px-1 text-center border-b-2 font-medium text-sm'
                                    ]">
                                    {{ tab }}
                                </button>
                            </nav>
                        </div>

                        <!-- Tab Contents -->
                        <div class="p-6">
                            <!-- Details Tab -->
                            <div v-if="activeTab === 'details'" class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div v-for="(value, key) in detailsInfo" :key="key"
                                        class="flex items-start space-x-3">
                                        <i :class="['fas', value.icon, 'text-gray-400 mt-1']"></i>
                                        <div>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ value.label }}</p>
                                            <p class="text-gray-900 dark:text-gray-100">{{ student[key] || 'N/A' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Parents Tab -->
                            <div v-if="activeTab === 'parents'" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Father's Information -->
                                <div class="space-y-4">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Father's
                                        Information</h3>
                                    <div class="space-y-3">
                                        <div v-for="(value, key) in fatherInfo" :key="key"
                                            class="flex items-start space-x-3">
                                            <i :class="['fas', value.icon, 'text-gray-400 mt-1']"></i>
                                            <div>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ value.label }}
                                                </p>
                                                <p class="text-gray-900 dark:text-gray-100">{{ student[key] || 'N/A' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Mother's Information -->
                                <div class="space-y-4">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Mother's
                                        Information</h3>
                                    <div class="space-y-3">
                                        <div v-for="(value, key) in motherInfo" :key="key"
                                            class="flex items-start space-x-3">
                                            <i :class="['fas', value.icon, 'text-gray-400 mt-1']"></i>
                                            <div>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ value.label }}
                                                </p>
                                                <p class="text-gray-900 dark:text-gray-100">{{ student[key] || 'N/A' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Address Tab -->
                            <div v-if="activeTab === 'address'" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Present Address -->
                                <div class="space-y-4">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Present Address
                                    </h3>
                                    <div class="space-y-3">
                                        <div v-for="(value, key) in presentAddressInfo" :key="key"
                                            class="flex items-start space-x-3">
                                            <i :class="['fas', value.icon, 'text-gray-400 mt-1']"></i>
                                            <div>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ value.label }}
                                                </p>
                                                <p class="text-gray-900 dark:text-gray-100">{{ student[key] || 'N/A' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Permanent Address -->
                                <div class="space-y-4">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Permanent Address
                                    </h3>
                                    <div class="space-y-3">
                                        <div v-for="(value, key) in permanentAddressInfo" :key="key"
                                            class="flex items-start space-x-3">
                                            <i :class="['fas', value.icon, 'text-gray-400 mt-1']"></i>
                                            <div>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ value.label }}
                                                </p>
                                                <p class="text-gray-900 dark:text-gray-100">{{ student[key] || 'N/A' }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ID Card Modal -->
            <TransitionRoot appear :show="isCardDialogVisible" as="template">
                <Dialog as="div" @close="closeGenerateCardDialog" class="relative z-50">
                    <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0"
                        enter-to="opacity-100" leave="duration-200 ease-in" leave-from="opacity-100"
                        leave-to="opacity-0">
                        <div class="fixed inset-0 bg-black bg-opacity-25" />
                    </TransitionChild>

                    <div class="fixed inset-0 overflow-y-auto">
                        <div class="flex min-h-full items-center justify-center p-4 text-center">
                            <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95"
                                enter-to="opacity-100 scale-100" leave="duration-200 ease-in"
                                leave-from="opacity-100 scale-100" leave-to="opacity-0 scale-95">
                                <DialogPanel
                                    class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 p-6 text-left align-middle shadow-xl transition-all">
                                    <DialogTitle as="h3"
                                        class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">
                                        Student ID Card
                                    </DialogTitle>

                                    <div class="mt-4">
                                        <div class="flex justify-center">
                                            <!-- Add id-card-container class -->
                                            <div
                                                class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg w-96 id-card-container">
                                                <!-- Add id-card-print class -->
                                                <div class="id-card-print">
                                                    <div class="school-header text-center mb-4">
                                                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                                                            School Name</h3>
                                                        <p class="text-sm text-gray-600 dark:text-gray-400">Address Line
                                                        </p>
                                                    </div>
                                                    <div class="flex justify-center mb-4">
                                                        <img :src="getStudentPhotoUrl(student.photo || 'placeholder.png')"
                                                            :alt="student.name_en"
                                                            class="w-24 h-24 rounded-full object-cover" />
                                                    </div>
                                                    <div class="text-center space-y-2">
                                                        <h4
                                                            class="text-xl font-semibold text-gray-900 dark:text-gray-100">
                                                            {{ student.name_en }}</h4>
                                                        <p class="text-gray-600 dark:text-gray-400">ID: {{
                                                            student.student_id }}</p>
                                                        <p class="text-gray-600 dark:text-gray-400">Class: {{
                                                            student.class?.name }} - {{ student.section?.name }}</p>
                                                        <p class="text-gray-600 dark:text-gray-400">Date of Birth: {{
                                                            student.date_of_birth }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mt-6 flex justify-end space-x-3">
                                        <button type="button"
                                            class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                            @click="closeGenerateCardDialog">
                                            Close
                                        </button>
                                        <button type="button"
                                            class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                            @click="printIdCard">
                                            Print
                                        </button>
                                    </div>
                                </DialogPanel>
                            </TransitionChild>
                        </div>
                    </div>
                </Dialog>
            </TransitionRoot>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue';

const props = defineProps({
    student: {
        type: Object,
        required: true
    }
});

// State management
const activeTab = ref('details');
const isCardDialogVisible = ref(false);
const appUrl = import.meta.env.VITE_APP_URL;

// Helper function for student photo URL
const getStudentPhotoUrl = (photo) => `${appUrl}/students/${photo}`;

// Modal control functions
const openGenerateCardDialog = () => {
    isCardDialogVisible.value = true;
};

const closeGenerateCardDialog = () => {
    isCardDialogVisible.value = false;
};

const printIdCard = () => {
  const printWindow = window.open('', '_blank');
  const cardContent = document.querySelector('.id-card-print');

  printWindow.document.write(`
    <!DOCTYPE html>
    <html>
    <head>
      <title>Student ID Card</title>
      <style>
        @page {
          size: 3.375in 2.125in;
          margin: 0;
        }
        body {
          margin: 0;
          padding: 12px;
          font-family: Arial, sans-serif;
          -webkit-print-color-adjust: exact;
          print-color-adjust: exact;
        }
        .id-card-container {
          width: 3.375in;
          height: 4in;
          background: white;
          border: 1px solid #ddd;
          border-radius: 8px;
          overflow: hidden;
          page-break-after: always;
        }
        .school-header {
          background: #1e40af;
          color: white;
          padding: 8px 0;
          margin-bottom: 8px;
        }
        img {
          width: 80px;
          height: 80px;
          border-radius: 50%;
          object-fit: cover;
          border: 2px solid #1e40af;
          margin: 8px auto;
        }
        .text-center {
          text-align: center;
        }
        h4 {
          margin: 8px 0;
          font-size: 16px;
          color: #111827;
        }
        p {
          margin: 4px 0;
          font-size: 12px;
          color: #4b5563;
        }
      </style>
    </head>
    <body>
      <div class="id-card-container">
        ${cardContent.innerHTML}
      </div>
    </body>
    </html>
  `);

  printWindow.document.close();

  // Wait for images to load before printing
  const img = printWindow.document.querySelector('img');
  if (img) {
    img.onload = () => {
      printWindow.focus();
      printWindow.print();
      setTimeout(() => {
        printWindow.close();
      }, 250);
    };
  } else {
    printWindow.focus();
    printWindow.print();
    setTimeout(() => {
      printWindow.close();
    }, 250);
  }
};

// Information structures for different tabs
const detailsInfo = {
    birth_certificate_number: { label: 'Birth Certificate', icon: 'fa-certificate' },
    birth_place_district: { label: 'Birth Place', icon: 'fa-map-marker' },
    gender: { label: 'Gender', icon: 'fa-venus-mars' },
    nationality: { label: 'Nationality', icon: 'fa-flag' },
    religion: { label: 'Religion', icon: 'fa-pray' },
    blood_group: { label: 'Blood Group', icon: 'fa-tint' },
    class_role: { label: 'Class Role', icon: 'fa-user-tag' }
};

const fatherInfo = {
    father_name_bn: { label: 'Name (Bengali)', icon: 'fa-user' },
    father_name_en: { label: 'Name (English)', icon: 'fa-user' },
    father_mobile: { label: 'Mobile Number', icon: 'fa-phone' },
    father_occupation: { label: 'Occupation', icon: 'fa-briefcase' },
    father_dead: { label: 'Status', icon: 'fa-info-circle' }
};

const motherInfo = {
    mother_name_bn: { label: 'Name (Bengali)', icon: 'fa-user' },
    mother_name_en: { label: 'Name (English)', icon: 'fa-user' },
    mother_mobile: { label: 'Mobile Number', icon: 'fa-phone' },
    mother_occupation: { label: 'Occupation', icon: 'fa-briefcase' },
    mother_dead: { label: 'Status', icon: 'fa-info-circle' }
};

const presentAddressInfo = {
    present_address_division: { label: 'Division', icon: 'fa-map' },
    present_address_district: { label: 'District', icon: 'fa-map-marker' },
    present_address_upazila: { label: 'Upazila', icon: 'fa-map-pin' },
    present_address_village: { label: 'Village', icon: 'fa-home' },
    present_address_house_number: { label: 'House No', icon: 'fa-building' },
    present_address_post_code: { label: 'Post Code', icon: 'fa-mail-bulk' }
};

const permanentAddressInfo = {
    permanent_address_division: { label: 'Division', icon: 'fa-map' },
    permanent_address_district: { label: 'District', icon: 'fa-map-marker' },
    permanent_address_upazila: { label: 'Upazila', icon: 'fa-map-pin' },
    permanent_address_village: { label: 'Village', icon: 'fa-home' },
    permanent_address_house_number: { label: 'House No', icon: 'fa-building' },
    permanent_address_post_code: { label: 'Post Code', icon: 'fa-mail-bulk' }
};

// Print styles for ID card
const style = document.createElement('style');
style.textContent = `
  @media print {
    body * {
      visibility: hidden;
    }
    .id-card, .id-card * {
      visibility: visible;
    }
    .id-card {
      position: absolute;
      left: 0;
      top: 0;
      width: 100%;
    }
  }
`;
document.head.appendChild(style);
</script>

<style>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

.id-card-container {
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.school-header {
  background: linear-gradient(to right, #1e40af, #3b82f6);
  color: white;
  padding: 8px 0;
  margin: -24px -24px 16px -24px;
}

@media print {
  .id-card-print {
    width: 3.375in;
    height: 4in;
    padding: 12px;
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
  }
}
</style>
