<template>
    <Head title="Students Management" />

    <AdminLayout>
      <div class="container mx-auto py-8 px-4">
        <!-- Header Section -->
        <div class="mb-8 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
          <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Students Management</h1>
            <p class="mt-1 text-gray-600 dark:text-gray-400">Manage and monitor all students</p>
          </div>
          <Link
            href="/admin/students/create"
            class="btn-primary flex items-center gap-2 self-start"
          >
            <PlusIcon class="w-5 h-5" />
            Add New Student
          </Link>
        </div>

        <!-- Filters Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 mb-6">
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Search Input -->
            <div class="relative">
              <input
                v-model="search"
                type="text"
                placeholder="Search students..."
                class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500"
              />
              <SearchIcon class="absolute right-3 top-2.5 h-5 w-5 text-gray-400" />
            </div>

            <!-- Class Filter -->
            <select
              v-model="filters.class_id"
              class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
            >
              <option value="">All Classes</option>
              <option v-for="class_ in classes" :key="class_.id" :value="class_.id">
                {{ class_.name }}
              </option>
            </select>

            <!-- Section Filter -->
            <select
              v-model="filters.section_id"
              class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
            >
              <option value="">All Sections</option>
              <option v-for="section in sections" :key="section.id" :value="section.id">
                {{ section.name }}
              </option>
            </select>
          </div>
        </div>

        <!-- Students Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
              <thead class="bg-gray-50 dark:bg-gray-900">
                <tr>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                    <div class="flex items-center gap-2">
                      Student
                      <ArrowUpIcon class="w-4 h-4 cursor-pointer" />
                    </div>
                  </th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Contact</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Class Info</th>
                  <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                  <th scope="col" class="relative px-6 py-3">
                    <span class="sr-only">Actions</span>
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-for="student in students.data" :key="student.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                  <!-- Student Info -->
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-10 w-10">
                        <img
                          :src="getStudentPhotoUrl(student.photo || 'placeholder.png')"
                          :alt="student.name_en"
                          class="h-10 w-10 rounded-full object-cover"
                        />
                      </div>
                      <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                          {{ student.name_en }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                          ID: {{ student.student_id }}
                        </div>
                      </div>
                    </div>
                  </td>

                  <!-- Contact Info -->
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900 dark:text-gray-100">
                      {{ student.parent?.name }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                      {{ student.parent?.phone }}
                    </div>
                  </td>

                  <!-- Class Info -->
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900 dark:text-gray-100">
                      {{ student.class?.name }} - {{ student.section?.name }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                      Roll: {{ student.class_role }}
                    </div>
                  </td>

                  <!-- Status -->
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span
                      class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                      :class="student.information_correct
                        ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-100'
                        : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-100'"
                    >
                      {{ student.information_correct ? 'Verified' : 'Pending' }}
                    </span>
                  </td>

                  <!-- Actions -->
                  <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex justify-end gap-2">
                      <Link
                        :href="route('admin.students.show', student.id)"
                        class="text-blue-600 hover:text-blue-900 dark:hover:text-blue-400"
                        title="View Details"
                      >
                        <EyeIcon class="w-5 h-5" />
                      </Link>
                      <Link
                        :href="route('admin.students.edit', student.id)"
                        class="text-indigo-600 hover:text-indigo-900 dark:hover:text-indigo-400"
                        title="Edit Student"
                      >
                        <PencilIcon class="w-5 h-5" />
                      </Link>
                      <button
                        @click="confirmDelete(student.id)"
                        class="text-red-600 hover:text-red-900 dark:hover:text-red-400"
                        title="Delete Student"
                      >
                        <TrashIcon class="w-5 h-5" />
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div class="bg-white dark:bg-gray-800 px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6">
            <Pagination :links="students.links" class="mt-6" />
          </div>
        </div>

        <!-- Confirmation Dialog -->
        <ConfirmationDialog
          :show="isDialogVisible"
          title="Delete Student"
          message="Are you sure you want to delete this student? This action cannot be undone."
          @update:show="isDialogVisible = $event"
          @confirm="deleteStudent"
        />
      </div>
    </AdminLayout>
  </template>

  <script setup>
  import { ref, watch } from 'vue';
  import { Link, Head, router } from '@inertiajs/vue3';
  import {
    PlusIcon, EyeIcon, PencilIcon,
    TrashIcon, ArrowUpIcon
  } from '@heroicons/vue/24/solid';
  import AdminLayout from '@/Layouts/AdminLayout.vue';
  import ConfirmationDialog from '@/Components/ConfirmationDialog.vue';
  import Pagination from '@/Components/Pagination.vue';
  import debounce from 'lodash/debounce';

  const props = defineProps({
    students: Object,
    filters: Object,
    classes: Array,
    sections: Array
  });

  const search = ref(props.filters.search);
  const isDialogVisible = ref(false);
  const studentToDelete = ref(null);

  // Debounced search
  watch(search, debounce((value) => {
    router.get(route('admin.students.index'),
      { search: value, class_id: filters.class_id, section_id: filters.section_id },
      { preserveState: true, preserveScroll: true }
    );
  }, 300));

  // Filter changes
  watch(() => props.filters, (value) => {
    router.get(route('admin.students.index'), value,
      { preserveState: true, preserveScroll: true }
    );
  }, { deep: true });

  function confirmDelete(studentId) {
    studentToDelete.value = studentId;
    isDialogVisible.value = true;
  }

  function deleteStudent() {
    if (studentToDelete.value) {
      router.delete(route('admin.students.destroy', studentToDelete.value), {
        onSuccess: () => {
          isDialogVisible.value = false;
        },
      });
    }
  }

  const appUrl = import.meta.env.VITE_APP_URL;
  const getStudentPhotoUrl = (photo) => `${appUrl}/students/${photo}`;
  </script>

  <style scoped>
  .btn-primary {
    @apply inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200;
  }
  </style>
