<template>
    <Head title="Edit Exam" />

    <AdminLayout>
      <div class="container mx-auto py-8">
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8">
          <h1 class="text-3xl font-bold mb-6 text-gray-800 dark:text-gray-300">Edit Exam</h1>
          <form @submit.prevent="updateExam">
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
              <label for="class_id" class="block text-sm font-medium text-gray-700 dark:text-gray-400">Class</label>
              <select
                v-model="form.class_id"
                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-600 dark:focus:border-blue-600 dark:bg-gray-700 dark:text-white"
                required
              >
                <option value="" disabled>Select Class</option>
                <option v-for="classItem in schoolClasses" :key="classItem.id" :value="classItem.id">{{ classItem.name }}</option>
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

            <div class="mb-4">
              <label for="starting_date" class="block text-sm font-medium text-gray-700 dark:text-gray-400">Starting Date</label>
              <input
                type="date"
                id="starting_date"
                v-model="form.starting_date"
                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-600 dark:focus:border-blue-600 dark:bg-gray-700 dark:text-white"
                required
              />
            </div>

            <div class="mb-4">
              <label for="starting_time" class="block text-sm font-medium text-gray-700 dark:text-gray-400">Starting Time</label>
              <input
                type="time"
                id="starting_time"
                v-model="form.starting_time"
                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-600 dark:focus:border-blue-600 dark:bg-gray-700 dark:text-white"
                required
              />
            </div>

            <div class="mb-4">
              <label for="ending_date" class="block text-sm font-medium text-gray-700 dark:text-gray-400">Ending Date</label>
              <input
                type="date"
                id="ending_date"
                v-model="form.ending_date"
                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-600 dark:focus:border-blue-600 dark:bg-gray-700 dark:text-white"
              />
            </div>

            <div class="mb-4">
              <label for="ending_time" class="block text-sm font-medium text-gray-700 dark:text-gray-400">Ending Time</label>
              <input
                type="time"
                id="ending_time"
                v-model="form.ending_time"
                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-600 dark:focus:border-blue-600 dark:bg-gray-700 dark:text-white"
              />
            </div>

            <div class="mb-4">
              <label for="total_marks" class="block text-sm font-medium text-gray-700 dark:text-gray-400">Total Marks</label>
              <input
                type="number"
                id="total_marks"
                v-model="form.total_marks"
                min="0"
                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-600 dark:focus:border-blue-600 dark:bg-gray-700 dark:text-white"
                required
              />
            </div>

            <div class="flex justify-end">
              <button type="submit" class="btn-primary inline-flex items-center">
                Update Exam
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M10 2a1 1 0 00-1 1v6H3a1 1 0 100 2h6v6a1 1 0 002 0v-6h6a1 1 0 100-2h-6V3a1 1 0 00-1-1z" />
                </svg>
              </button>
              <Link href="/admin/exams" class="btn-secondary inline-flex items-center ml-4">
                Back to Exams
              </Link>
            </div>
          </form>
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
    subjects: Array,
    exam: Object, // Existing exam data passed from the server
  });

  // Initialize the form with existing exam data
  const form = useForm({
    exam_category_id: props.exam.exam_category_id || '',
    class_id: props.exam.class_id || '',
    subject_id: props.exam.subject_id || '',
    starting_date: props.exam.starting_date || '',
    starting_time: props.exam.starting_time || '',
    ending_date: props.exam.ending_date || '',
    ending_time: props.exam.ending_time || '',
    total_marks: props.exam.total_marks || '',
  });

  const successMessage = ref('');
  const errorMessage = ref('');

  function updateExam() {
    form.put(route('admin.exams.update', props.exam.id), {
      onSuccess: () => {
        successMessage.value = 'Exam updated successfully!';
        errorMessage.value = ''; // Clear any previous error messages
      },
      onError: () => {
        errorMessage.value = 'Error updating exam. Please try again.';
        successMessage.value = ''; // Clear any previous success messages
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
