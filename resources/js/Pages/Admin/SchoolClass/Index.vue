<template>
  <Head title="School Classes" />

  <AdminLayout>
    <div class="container mx-auto py-8">
      <div class="bg-white shadow-lg rounded-lg p-8">
        <div class="flex justify-between items-center mb-6">
          <h1 class="text-3xl font-bold text-gray-800">School Classes</h1>
          <Link href="/admin/school-classes/create" class="btn-primary flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
              <path d="M10 2a1 1 0 011 1v6h6a1 1 0 110 2h-6v6a1 1 0 11-2 0v-6H3a1 1 0 110-2h6V3a1 1 0 011-1z" />
            </svg>
            Add Class
          </Link>
        </div>

        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead>
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="classItem in classes" :key="classItem.id">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ classItem.name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ classItem.description }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <Link :href="route('admin.school-classes.show', classItem.id)" class="inline-flex items-center text-green-600 hover:text-green-800 bg-green-100 hover:bg-green-200 px-3 py-2 rounded transition duration-200">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                      <path d="M10 2a1 1 0 00-1 1v6H3a1 1 0 100 2h6v6a1 1 0 002 0v-6h6a1 1 0 100-2h-6V3a1 1 0 00-1-1z" />
                    </svg>
                    Show
                  </Link>
                  
                  <Link :href="route('admin.school-classes.edit', classItem.id)" class="inline-flex items-center text-blue-600 hover:text-blue-800 bg-blue-100 hover:bg-blue-200 px-3 py-2 rounded transition duration-200 ml-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                      <path d="M12.293 1.293a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-9 9a1 1 0 01-.353.213l-3 1a1 1 0 01-1.293-1.293l1-3a1 1 0 01.213-.353l9-9z" />
                    </svg>
                    Edit
                  </Link>

                  <button @click="confirmDelete(classItem.id)" class="inline-flex items-center text-red-600 hover:text-red-800 bg-red-100 hover:bg-red-200 px-3 py-2 rounded transition duration-200 ml-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                      <path d="M10 2a1 1 0 011 1v6h6a1 1 0 110 2h-6v6a1 1 0 11-2 0v-6H3a1 1 0 110-2h6V3a1 1 0 011-1z" />
                    </svg>
                    Delete
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Confirm Dialog -->
        <ConfirmDialog :show="showDialog" @update:show="showDialog = false" @confirm="deleteClass" />
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, useForm, Head } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ConfirmDialog from '@/Components/ConfirmationDialog.vue';

const props = defineProps({
  classes: Array,
});

const showDialog = ref(false);
const classToDelete = ref(null);

function confirmDelete(classId) {
  showDialog.value = true;
  classToDelete.value = classId;
}

function deleteClass() {
  if (classToDelete.value) {
    Inertia.delete(route('admin.school-classes.destroy', classToDelete.value), {
      onSuccess: () => {
        showDialog.value = false;
        // Optionally, refresh the classes list or show a success message
      },
      onError: () => {
        console.error("An error occurred while trying to delete the class.");
      },
    });
  }
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
