<template>
    <Head title="Create Subject" />

    <AdminLayout>
        <div class="mx-auto py-8">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8">
                <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-300 mb-6">Create Subject</h1>

                <form @submit.prevent="submit">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Subject Name</label>
                        <input
                            v-model="form.name"
                            type="text"
                            id="name"
                            required
                            class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200"
                        />
                        <span v-if="form.errors.name" class="text-red-600 text-sm">{{ form.errors.name }}</span>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="btn-primary">
                            Create Subject
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const form = useForm({
    name: '',
});

function submit() {
    form.post(route('admin.subjects.store'), {
        onSuccess: () => {
            form.reset();
        },
        onError: () => {
            // Handle errors if needed
        },
    });
}
</script>

<style scoped>
.btn-primary {
    @apply bg-blue-600 text-white font-semibold py-2 px-4 rounded shadow hover:bg-blue-700 transition duration-200;
}
</style>
