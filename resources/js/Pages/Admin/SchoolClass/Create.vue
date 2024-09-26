<template>
    <Head title="Create School Class" />

    <AdminLayout>
      <div class="container mx-auto py-8">
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8">
          <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-300 mb-6">Create School Class</h1>
          <form @submit.prevent="createClass">
            <div class="mb-4">
              <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Class Name</label>
              <input
                type="text"
                v-model="form.name"
                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200"
                required
              />
            </div>
            <div class="mb-4">
              <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
              <textarea
                v-model="form.description"
                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200"
              ></textarea>
            </div>
            <div class="flex justify-end">
              <button type="submit" class="inline-flex items-center text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 rounded-md px-4 py-2">
                Create Class
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M10 2a1 1 0 00-1 1v6H3a1 1 0 100 2h6v6a1 1 0 002 0v-6h6a1 1 0 100-2h-6V3a1 1 0 00-1-1z" />
                </svg>
              </button>
              <Link href="/admin/school-classes" class="inline-flex items-center text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md px-4 py-2 ml-4">
                Back to Classes
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

  const form = useForm({
    name: '',
    description: '',
  });

  const successMessage = ref('');
  const errorMessage = ref('');

  function createClass() {
    form.post(route('admin.school-classes.store'), {
      onSuccess: () => {
        successMessage.value = 'Class created successfully!';
        errorMessage.value = ''; // Clear any previous error messages
        form.reset(); // Reset form fields
      },
      onError: () => {
        errorMessage.value = 'Error creating class. Please try again.';
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
