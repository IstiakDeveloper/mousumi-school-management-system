<template>
  <Head title="Edit School Class" />

  <AdminLayout>
    <div class="container mx-auto py-8">
      <div class="bg-white shadow-lg rounded-lg p-8">
        <h1 class="text-3xl font-bold mb-6">Edit School Class</h1>
        <form @submit.prevent="updateClass">
          <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Class Name</label>
            <input
              type="text"
              v-model="form.name"
              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500"
              required
            />
          </div>
          <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea
              v-model="form.description"
              class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500"
            ></textarea>
          </div>
          <button type="submit" class="btn-primary">Update Class</button>
          <Link href="/admin/school-classes" class="btn-secondary ml-4">Back to Classes</Link>
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
  SchoolClass: {
    type: Object,
    required: true, // Make it required
  },
});

const form = useForm({
  name: props.SchoolClass?.name || '', // Use optional chaining
  description: props.SchoolClass?.description || '',
});

const successMessage = ref('');
const errorMessage = ref('');

function updateClass() {
  form.put(route('admin.school-classes.update', props.SchoolClass.id), {
    onSuccess: () => {
      successMessage.value = 'Class updated successfully!';
      errorMessage.value = ''; // Clear any previous error messages
    },
    onError: (errors) => {
      errorMessage.value = 'Error updating class. Please try again.';
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
