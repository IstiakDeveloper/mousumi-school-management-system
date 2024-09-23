<template>
<Head title="Admin Role" />
  <AdminLayout>
    <div class="container mx-auto py-8">
    <div class="bg-white shadow rounded-lg p-6">
      <h1 class="text-2xl font-semibold text-gray-700 mb-6">Edit Role</h1>
      <form @submit.prevent="updateRole">
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-2">Role Name</label>
          <input
            v-model="form.name"
            type="text"
            class="form-input"
            placeholder="Enter role name"
          />
          <span v-if="errors.name" class="text-red-600 text-sm">{{ errors.name }}</span>
        </div>
        <div class="mt-6">
          <button type="submit" class="btn-primary">Update Role</button>
          <Link href="/roles" class="btn-secondary ml-2">Cancel</Link>
        </div>
      </form>
    </div>
  </div>
  </AdminLayout>
</template>

<script>
import { ref } from 'vue';
import { Link, Head } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

export default {
  components: {
    Link, // Register the Link component
    AdminLayout,
  },
  props: {
    role: Object,
  },
  data() {
    return {
      form: {
        name: this.role.name,
      },
      errors: {},
    };
  },
  methods: {
    updateRole() {
      this.$inertia.put(`/roles/${this.role.id}`, this.form, {
        onError: (errors) => {
          this.errors = errors;
        },
      });
    },
  },
};
</script>

<style scoped>
.form-input {
  @apply w-full px-3 py-2 border border-gray-300 rounded shadow-sm focus:outline-none focus:border-blue-500;
}

.btn-primary {
  @apply bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-500;
}

.btn-secondary {
  @apply bg-gray-600 text-white px-4 py-2 rounded shadow hover:bg-gray-500;
}
</style>
