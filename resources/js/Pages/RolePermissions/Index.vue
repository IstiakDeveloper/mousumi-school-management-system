<template>
<Head title="Admin Role & Permission" />
  <AdminLayout>
    <div class="container mx-auto py-8">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-700">Roles & Permissions</h1>
        <Link href="/role-permissions/create" class="btn-primary">Assign Role Permissions</Link>
      </div>

      <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full bg-white">
          <thead class="bg-gray-100 border-b">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Role Name
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Assigned Permissions
              </th>
              <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                Actions
              </th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200">
            <tr v-for="role in roles" :key="role.id">
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                {{ role.name }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                {{ role.permissions.map(permission => permission.name).join(', ') }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-right space-x-2">
                <Link :href="`/role-permissions/${role.id}/edit`" class="btn-secondary">Edit</Link>
                <button @click="confirmDelete(role.id)" class="btn-danger">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Confirm Dialog Component -->
      <ConfirmDialog :show="showDialog" @update:show="showDialog = false" @confirm="deleteRole" />
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Link, useForm, Head } from '@inertiajs/vue3';
import ConfirmDialog from '@/Components/ConfirmationDialog.vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
  roles: Array,
});

const showDialog = ref(false);
const roleToDelete = ref(null);
const form = useForm({});

function confirmDelete(roleId) {
  showDialog.value = true;
  roleToDelete.value = roleId;
}

function deleteRole() {
  form.delete(route('role-permissions.destroy', roleToDelete.value), {
    onSuccess: () => {
      showDialog.value = false;
    },
  });
}
</script>

<style scoped>
.btn-primary {
  @apply bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-500;
}

.btn-secondary {
  @apply bg-gray-600 text-white px-4 py-2 rounded shadow hover:bg-gray-500;
}

.btn-danger {
  @apply bg-red-600 text-white px-4 py-2 rounded shadow hover:bg-red-500;
}
</style>
