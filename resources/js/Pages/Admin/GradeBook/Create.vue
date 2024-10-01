<template>
    <Head title="Create Grade Book" />

    <AdminLayout>
      <div class="container mx-auto py-8">
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8">
          <h1 class="text-3xl font-bold mb-6 text-gray-800 dark:text-gray-300">Create Grade Book</h1>
          <form @submit.prevent="fetchStudents">
            <div class="mb-4">
              <label for="exam_category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-400">Exam Category</label>
              <select
                v-model="form.exam_category_id"
                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-600 dark:focus:border-blue-600 dark:bg-gray-700 dark:text-white"
                required
              >
                <option value="" disabled>Select Exam Category</option>
                <option v-for="category in examCategories" :key="category.id" :value="category.id">{{ category.title }}</option>
              </select>
            </div>

            <div class="mb-4">
              <label for="school_class_id" class="block text-sm font-medium text-gray-700 dark:text-gray-400">Class</label>
              <select
                v-model="form.school_class_id"
                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-600 dark:focus:border-blue-600 dark:bg-gray-700 dark:text-white"
                required
              >
                <option value="" disabled>Select Class</option>
                <option v-for="classItem in schoolClasses" :key="classItem.id" :value="classItem.id">{{ classItem.name }}</option>
              </select>
            </div>

            <div class="mb-4">
              <label for="section_id" class="block text-sm font-medium text-gray-700 dark:text-gray-400">Section</label>
              <select
                v-model="form.section_id"
                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-600 dark:focus:border-blue-600 dark:bg-gray-700 dark:text-white"
                required
              >
                <option value="" disabled>Select Section</option>
                <option v-for="section in sections" :key="section.id" :value="section.id">{{ section.name }}</option>
              </select>
            </div>

            <div class="mb-4">
              <label for="subject_id" class="block text-sm font-medium text-gray-700 dark:text-gray-400">Subject</label>
              <select
                v-model="form.subject_id"
                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-600 dark:focus:border-blue-600 dark:bg-gray-700 dark:text-white"
                required
              >
                <option value="" disabled>Select Subject</option>
                <option v-for="subject in subjects" :key="subject.id" :value="subject.id">{{ subject.name }}</option>
              </select>
            </div>

            <div class="flex justify-end">
              <button type="submit" class="btn-primary inline-flex items-center">
                Show Students
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M10 2a1 1 0 00-1 1v6H3a1 1 0 100 2h6v6a1 1 0 002 0v-6h6a1 1 0 100-2h-6V3a1 1 0 00-1-1z" />
                </svg>
              </button>
              <Link href="/admin/gradebooks" class="btn-secondary inline-flex items-center ml-4">
                Back to Grade Books
              </Link>
            </div>
          </form>

          <div v-if="students.length > 0" class="mt-6">
            <h2 class="text-2xl font-bold mb-4">Students</h2>
            <table class="min-w-full border border-gray-300 dark:border-gray-600">
              <thead>
                <tr class="bg-gray-200 dark:bg-gray-700">
                  <th class="border border-gray-300 dark:border-gray-600 p-2">Student Name</th>
                  <th class="border border-gray-300 dark:border-gray-600 p-2">Marks</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="student in students" :key="student.id">
                  <td class="border border-gray-300 dark:border-gray-600 p-2">{{ student.name }}</td>
                  <td class="border border-gray-300 dark:border-gray-600 p-2">
                    <input type="number" v-model="marks[student.id]" class="border border-gray-300 dark:border-gray-600 p-1" min="0" max="100" />
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div v-if="successMessage" class="mt-4 text-green-600">{{ successMessage }}</div>
          <div v-if="errorMessage" class="mt-4 text-red-600">{{ errorMessage }}</div>
        </div>
      </div>
    </AdminLayout>
  </template>

  <script setup>
  import { ref } from 'vue';
  import { useForm, Link, Head } from '@inertiajs/vue3';
  import AdminLayout from '@/Layouts/AdminLayout.vue';

  const props = defineProps({
    examCategories: Array,
    schoolClasses: Array,
    sections: Array,
    subjects: Array,
  });

  // Create a form instance
  const form = useForm({
    exam_category_id: '',
    school_class_id: '',
    section_id: '',
    subject_id: '',
  });

  const students = ref([]);
  const marks = ref({});
  const successMessage = ref('');
  const errorMessage = ref('');

  function fetchStudents() {
    // Use the form instance to submit data
    form.post(route('admin.gradebooks.showStudents'), {
      onSuccess: (page) => {
        students.value = page.props.students || [];
        errorMessage.value = ''; // Clear any previous error messages
      },
      onError: () => {
        errorMessage.value = 'Error fetching students. Please try again.';
        students.value = []; // Clear students list on error
      },
    });
  }
  </script>

  <style scoped>
  .btn-primary {
    @apply bg-blue-600 text-white font-semibold py-2 px-4 rounded shadow hover:bg-blue-700 transition duration-200;
  }

  .btn-secondary {
    @apply bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded shadow hover:bg-gray-400 transition duration-200;
  }
  </style>
