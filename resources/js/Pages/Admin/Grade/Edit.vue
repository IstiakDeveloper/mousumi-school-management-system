<template>
    <Head title="Edit Grade" />

    <AdminLayout>
      <div class="container mx-auto py-8">
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8">
          <h1 class="text-3xl font-bold mb-6 text-gray-800 dark:text-gray-300">Edit Grade</h1>
          <form @submit.prevent="updateGrade">
            <!-- Grade Name -->
            <div class="mb-4">
              <label for="grade" class="block text-sm font-medium text-gray-700 dark:text-gray-400">Grade</label>
              <input
                v-model="form.grade"
                type="text"
                id="grade"
                required
                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-600 dark:focus:border-blue-600 dark:bg-gray-700 dark:text-white"
              />
            </div>

            <!-- Grade Point -->
            <div class="mb-4">
              <label for="grade_point" class="block text-sm font-medium text-gray-700 dark:text-gray-400">Grade Point</label>
              <input
                v-model="form.grade_point"
                type="number"
                step="0.01"
                min="0"
                max="10"
                id="grade_point"
                required
                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-600 dark:focus:border-blue-600 dark:bg-gray-700 dark:text-white"
              />
            </div>

            <!-- Mark From -->
            <div class="mb-4">
              <label for="mark_from" class="block text-sm font-medium text-gray-700 dark:text-gray-400">Mark From</label>
              <input
                v-model="form.mark_from"
                type="number"
                min="0"
                max="100"
                id="mark_from"
                required
                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-600 dark:focus:border-blue-600 dark:bg-gray-700 dark:text-white"
              />
            </div>

            <!-- Mark Upto -->
            <div class="mb-4">
              <label for="mark_upto" class="block text-sm font-medium text-gray-700 dark:text-gray-400">Mark Upto</label>
              <input
                v-model="form.mark_upto"
                type="number"
                min="0"
                max="100"
                id="mark_upto"
                required
                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-600 dark:focus:border-blue-600 dark:bg-gray-700 dark:text-white"
              />
            </div>

            <div class="flex justify-end">
              <button type="submit" class="btn-primary inline-flex items-center">
                Update Grade
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M10 2a1 1 0 00-1 1v6H3a1 1 0 100 2h6v6a1 1 0 002 0v-6h6a1 1 0 100-2h-6V3a1 1 0 00-1-1z" />
                </svg>
              </button>
              <Link href="/admin/grades" class="btn-secondary inline-flex items-center ml-4">
                Back to Grades
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
import { ref, onMounted } from 'vue';
import { useForm, Link, Head } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
  gradeData: Object,
});

const form = useForm({
  grade: '',
  grade_point: '',
  mark_from: '',
  mark_upto: '',
});

const successMessage = ref('');
const errorMessage = ref('');

// Set form fields to the values from the `gradeData` prop when component is mounted
onMounted(() => {
  form.grade = props.gradeData.grade;
  form.grade_point = props.gradeData.grade_point;
  form.mark_from = props.gradeData.mark_from;
  form.mark_upto = props.gradeData.mark_upto;
});

function updateGrade() {
  form.put(route('admin.grades.update', props.gradeData.id), {
    onSuccess: () => {
      successMessage.value = 'Grade updated successfully!';
      errorMessage.value = ''; // Clear previous error messages
    },
    onError: () => {
      errorMessage.value = 'Error updating Grade. Please try again.';
      successMessage.value = ''; // Clear previous success messages
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
