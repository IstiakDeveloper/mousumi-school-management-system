<template>
    <Head title="Create Exam Category" />

    <AdminLayout>
        <div class="container mx-auto py-8">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8">
                <h1 class="text-3xl font-bold mb-6 text-gray-800 dark:text-gray-300">Create Exam Category</h1>

                <div v-if="successMessage" class="mt-4 p-4 bg-green-100 text-green-800 border border-green-300 rounded">
                    {{ successMessage }}
                </div>

                <form @submit.prevent="createExamCategory">
                    <div class="mb-4">
                        <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-400">Category Title</label>
                        <input
                            v-model="form.title"
                            type="text"
                            id="title"
                            required
                            class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-600 dark:focus:border-blue-600 dark:bg-gray-700 dark:text-white"
                        />
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="btn-primary inline-flex items-center">
                            Create Exam Category
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 2a1 1 0 00-1 1v6H3a1 1 0 100 2h6v6a1 1 0 002 0v-6h6a1 1 0 100-2h-6V3a1 1 0 00-1-1z" />
                            </svg>
                        </button>
                        <Link href="/admin/exam-categories" class="btn-secondary inline-flex items-center ml-4">
                            Back to Exam Categories
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
import { ref } from 'vue';
import { useForm, Link, Head } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const form = useForm({
    title: '',
});

const successMessage = ref('');
const errorMessage = ref('');

function createExamCategory() {
    form.post(route('admin.exam-categories.store'), {

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
