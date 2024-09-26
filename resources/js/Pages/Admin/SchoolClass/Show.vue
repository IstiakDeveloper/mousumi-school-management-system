<template>
    <Head :title="schoolClass ? schoolClass.name : 'Loading...'" />

    <AdminLayout>
      <div class="container mx-auto py-8">
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8">
          <h1 v-if="SchoolClass" class="text-3xl font-bold text-gray-800 dark:text-gray-300 mb-4">{{ SchoolClass.name }}</h1>
          <p v-if="SchoolClass" class="text-gray-600 dark:text-gray-400 mb-4">{{ SchoolClass.description }}</p>

          <div class="flex space-x-4" v-if="SchoolClass">
            <Link :href="route('admin.school-classes.edit', SchoolClass.id)" class="btn-primary">
              Edit
            </Link>
            <button @click="confirmDelete(SchoolClass.id)" class="btn-secondary">
              Delete
            </button>
          </div>
        </div>
      </div>

      <ConfirmationDialog
        :show="isDialogVisible"
        @update:show="isDialogVisible = $event"
        @confirm="deleteClass"
      />
    </AdminLayout>
  </template>

  <script setup>
  import { ref } from 'vue';
  import { Head, Link, useForm } from '@inertiajs/vue3';
  import AdminLayout from '@/Layouts/AdminLayout.vue';
  import ConfirmationDialog from '@/Components/ConfirmationDialog.vue';

  const props = defineProps({
    SchoolClass: Object, // Ensure this matches the key from your controller
  });

  const isDialogVisible = ref(false);
  const form = useForm({});

  function confirmDelete(classId) {
    isDialogVisible.value = true;
  }

  function deleteClass() {
    form.delete(route('admin.school-classes.destroy', props.SchoolClass.id), {
      onSuccess: () => {
        isDialogVisible.value = false; // Close dialog after successful deletion
        window.location.href = route('admin.school-classes.index'); // Redirect to index after deletion
      },
    });
  }
  </script>

  <style scoped>
  .btn-primary {
    @apply bg-blue-600 text-white font-semibold py-2 px-4 rounded shadow hover:bg-blue-700 transition duration-200;
  }

  .btn-secondary {
    @apply bg-red-600 text-white font-semibold py-2 px-4 rounded shadow hover:bg-red-700 transition duration-200;
  }
  </style>
