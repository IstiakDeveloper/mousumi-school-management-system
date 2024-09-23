<template>
  <Head title="Admin User" />

  <AdminLayout>
    <div class="container mx-auto py-8">
        <div class="bg-white shadow-lg rounded-lg p-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Users</h1>
            <button @click="openCreateModal" class="btn-primary">Add User</button>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Roles</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="user in users" :key="user.id">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ user.name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ user.email }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ user.roles.map(role => role.name).join(', ') }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <button @click="openEditModal(user)" class="text-blue-600 hover:text-blue-900">Edit</button>
                    <button @click="confirmDelete(user.id)" class="text-red-600 hover:text-red-900 ml-4">Delete</button>
                </td>
                </tr>
            </tbody>
            </table>
        </div>
        </div>


      <!-- Create/Edit User Modal -->
      <transition name="fade">
        <div v-if="showModal" class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50">
          <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full">
            <h2 class="text-2xl font-bold mb-4">{{ isEditing ? 'Edit' : 'Create' }} User</h2>
            <form @submit.prevent="submit">
              <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input
                  type="text"
                  id="name"
                  v-model="form.name"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                  required
                />
              </div>
              <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input
                  type="email"
                  id="email"
                  v-model="form.email"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                  required
                />
              </div>
              <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input
                  type="password"
                  id="password"
                  v-model="form.password"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                  :required="!isEditing"
                />
              </div>
              <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <input
                  type="password"
                  id="password_confirmation"
                  v-model="form.password_confirmation"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                  :required="!isEditing"
                />
              </div>
              <div class="mb-4">
                <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                <select
                id="role"
                v-model="form.role"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                required
                >
                <option value="" disabled>Select a role</option>
                <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.name }}</option>
                </select>

              </div>
              <div class="flex justify-end space-x-4">
                <button type="button" @click="closeModal" class="btn-secondary">Cancel</button>
                <button type="submit" class="btn-primary">{{ isEditing ? 'Update' : 'Create' }}</button>
              </div>
            </form>
          </div>
        </div>
      </transition>

      <!-- Confirm Dialog Component -->
      <ConfirmDialog :show="showDialog" @update:show="showDialog = false" @confirm="deleteUser" />
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ConfirmDialog from '@/Components/ConfirmationDialog.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
  users: Array,
  roles: Array
});

const showModal = ref(false);
const showDialog = ref(false);
const isEditing = ref(false);
const currentUser = ref(null);

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  role: '',
});

function openCreateModal() {
  form.reset(); // This resets form to default values
  isEditing.value = false;
  showModal.value = true;
}

function openEditModal(user) {
  if (!user) {
    console.error('User is undefined');
    return;
  }
  form.reset(); // Reset the form to default values

  // Set form data manually
  form.name = user.name || '';
  form.email = user.email || '';
  form.password = ''; // Clear password fields for editing
  form.password_confirmation = '';
  form.role = user.roles && user.roles[0] ? user.roles[0].id : '';

  isEditing.value = true;
  currentUser.value = user;
  showModal.value = true;
}

function closeModal() {
  showModal.value = false;
  currentUser.value = null;
}

function submit() {
  if (isEditing.value) {
    form.put(route('users.update', currentUser.value.id), {
      onSuccess: () => {
        closeModal();
      },
    });
  } else {
    form.post(route('users.store'), {
      onSuccess: () => {
        closeModal();
      },
    });
  }
}

function confirmDelete(userId) {
  showDialog.value = true;
  currentUser.value = { id: userId };
}

function deleteUser() {
  if (currentUser.value) {
    form.delete(route('users.destroy', currentUser.value.id), {
      onSuccess: () => {
        showDialog.value = false;
      },
    });
  }
}
</script>

<style scoped>
@media (max-width: 640px) {
  table {
    font-size: 0.875rem; /* Smaller text size for small screens */
  }
  th, td {
    padding: 0.5rem; /* Reduced padding for smaller screens */
  }
}
/* Fade transition */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active in <2.1.8 */ {
  opacity: 0;
}

/* Modal width and overlay */
.bg-black.bg-opacity-50 {
  background-color: rgba(0, 0, 0, 0.5);
}

.max-w-md {
  max-width: 28rem; /* Adjust the width here for a smaller desktop size */
}

@media (min-width: 768px) {
  .max-w-md {
    max-width: 32rem; /* Adjust this value as needed */
  }
}

@media (min-width: 1024px) {
  .max-w-md {
    max-width: 36rem; /* Adjust this value as needed for larger screens */
  }
}

.btn-primary {
  @apply bg-blue-600 text-white font-bold py-2 px-6 rounded-lg shadow hover:bg-blue-500 transition duration-150 ease-in-out;
}

.btn-secondary {
  @apply bg-gray-500 text-white font-bold py-2 px-6 rounded-lg shadow hover:bg-gray-400 transition duration-150 ease-in-out;
}

.btn-danger {
  @apply bg-red-600 text-white font-bold py-2 px-6 rounded-lg shadow hover:bg-red-500 transition duration-150 ease-in-out;
}
</style>
