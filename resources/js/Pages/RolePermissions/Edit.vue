<template>
<Head title="Admin Role & Permission" />
  <AdminLayout>
    <div class="container mx-auto py-8">
      <h1 class="text-2xl font-semibold text-gray-800 mb-6">Edit Role Permissions</h1>
      <form @submit.prevent="submit" class="space-y-6">
        <!-- Role Field -->
        <div class="bg-white p-4 rounded-lg shadow-md">
          <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
          <input
            type="text"
            id="role"
            v-model="form.role"
            readonly
            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
          />
        </div>

        <!-- Permissions Field -->
        <div class="bg-white p-4 rounded-lg shadow-md">
          <label for="permissions" class="block text-sm font-medium text-gray-700">Permissions</label>
          <div class="mt-2 space-y-4">
            <label v-for="permission in permissions" :key="permission.id" class="flex items-center">
              <input
                type="checkbox"
                :value="permission.id"
                v-model="form.permissions"
                class="form-checkbox h-5 w-5 text-blue-600 transition duration-150 ease-in-out"
              />
              <span class="ml-3 text-sm text-gray-700">{{ permission.name }}</span>
            </label>
          </div>
          <!-- Display Selected Permissions -->
          <div v-if="selectedPermissions.length > 0" class="mt-4">
            <p class="text-sm font-medium text-gray-800">Selected Permissions:</p>
            <ul class="list-disc list-inside mt-1 text-sm text-gray-700">
              <li v-for="permission in selectedPermissions" :key="permission.id">
                {{ permission.name }}
              </li>
            </ul>
          </div>
          <!-- Display Unchecked Permissions -->
          <div v-if="uncheckedPermissions.length > 0" class="mt-4">
            <p class="text-sm font-medium text-gray-800">Unchecked Permissions:</p>
            <ul class="list-disc list-inside mt-1 text-red-600 text-sm">
              <li v-for="permission in uncheckedPermissions" :key="permission.id">
                {{ permission.name }}
              </li>
            </ul>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
          <button type="submit" class="btn-primary">Update Permissions</button>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
  role: {
    type: Object,
    default: () => ({ permissions: [] })
  },
  permissions: {
    type: Array,
    default: () => []
  }
});

// Initialize form state
const form = useForm({
  role: props.role.name || '',
  permissions: props.role.permissions ? props.role.permissions.map(permission => permission.id) : []
});

// Compute selected and unchecked permissions
const selectedPermissions = computed(() => {
  const selectedIds = new Set(form.permissions);
  return props.permissions.filter(permission => selectedIds.has(permission.id));
});

const uncheckedPermissions = computed(() => {
  const selectedIds = new Set(form.permissions);
  return props.permissions
    .filter(permission => !selectedIds.has(permission.id))
    .map(permission => ({
      id: permission.id,
      name: permission.name
    }));
});

function submit() {
  form.put(route('role-permissions.update', props.role.id), {
    onSuccess: () => {
      // Handle success response
    },
  });
}
</script>

<style scoped>
/* Add additional styles for the form */
.btn-primary {
  @apply bg-blue-600 text-white px-6 py-2 rounded shadow hover:bg-blue-500;
}
</style>
