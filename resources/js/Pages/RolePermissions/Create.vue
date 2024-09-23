<template>
<Head title="Admin Role & Permission" />
  <AdminLayout>
    <div class="container mx-auto py-8">
      <h1 class="text-2xl font-semibold text-gray-700 mb-6">Assign Permissions to Role</h1>
      <form @submit.prevent="assignPermissions">
        <div class="mb-4">
          <label class="block text-gray-700 font-semibold mb-2">Select Role</label>
          <select v-model="selectedRole" class="form-select mt-1 block w-full" :class="{'border-red-500': form.errors.role_id}">
            <option value="" disabled>Select a role</option>
            <option v-for="role in roles" :key="role.id" :value="role.id">{{ role.name }}</option>
          </select>
          <p v-if="form.errors.role_id" class="text-red-500 text-sm mt-1">{{ form.errors.role_id }}</p>
        </div>

        <div class="mb-4">
          <div class="flex justify-between items-center">
            <label class="block text-gray-700 font-semibold mb-2">Permissions</label>
            <button type="button" @click="toggleSelectAll" class="btn-secondary">
              {{ isAllSelected ? 'Deselect All' : 'Select All' }}
            </button>
          </div>
          <div class="flex flex-wrap">
            <div v-for="permission in permissions" :key="permission.id" class="mr-4 mb-2">
              <label class="inline-flex items-center">
                <input
                  type="checkbox"
                  :value="permission.id"
                  v-model="selectedPermissions"
                  class="form-checkbox"
                />
                <span class="ml-2">{{ permission.name }}</span>
              </label>
            </div>
          </div>
          <p v-if="form.errors.permissions" class="text-red-500 text-sm mt-1">{{ form.errors.permissions }}</p>
        </div>

        <div class="flex justify-end">
          <button type="submit" class="btn-primary" :disabled="form.processing">Assign Permissions</button>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, watch, computed } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
  permissions: Array,
  roles: Array,
});

const selectedRole = ref(null);
const selectedPermissions = ref([]);
const form = useForm({ role_id: selectedRole.value, permissions: selectedPermissions.value });

const isAllSelected = computed(() => selectedPermissions.value.length === props.permissions.length);

function toggleSelectAll() {
  if (isAllSelected.value) {
    selectedPermissions.value = [];
  } else {
    selectedPermissions.value = props.permissions.map(permission => permission.id);
  }
}

watch([selectedRole, selectedPermissions], () => {
  form.role_id = selectedRole.value;
  form.permissions = selectedPermissions.value;
});

function assignPermissions() {
  form.post(route('role-permissions.store'), {
    onSuccess: () => {
      selectedRole.value = null;
      selectedPermissions.value = [];
    },
  });
}
</script>


<style scoped>
.btn-primary {
  @apply bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-500;
  @apply disabled:bg-gray-400 disabled:cursor-not-allowed;
}

.btn-secondary {
  @apply bg-gray-600 text-white px-4 py-2 rounded shadow hover:bg-gray-500;
}

.form-select {
  @apply block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm;
}

.form-checkbox {
  @apply h-4 w-4 text-indigo-600 transition duration-150 ease-in-out;
}
</style>
