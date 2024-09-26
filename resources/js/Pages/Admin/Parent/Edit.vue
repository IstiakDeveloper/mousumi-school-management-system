<template>
    <Head title="Edit Parent" />

    <AdminLayout>
        <div class="container mx-auto py-8">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8">
                <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-300 mb-6">Edit Parent</h1>

                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                        <input
                            v-model="form.name"
                            type="text"
                            id="name"
                            class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200"
                            required
                        />
                        <span v-if="form.errors.name" class="text-red-600 text-sm">{{ form.errors.name }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                        <input
                            v-model="form.email"
                            type="email"
                            id="email"
                            class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200"
                            required
                        />
                        <span v-if="form.errors.email" class="text-red-600 text-sm">{{ form.errors.email }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                        <input
                            v-model="form.phone"
                            type="text"
                            id="phone"
                            class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200"
                        />
                        <span v-if="form.errors.phone" class="text-red-600 text-sm">{{ form.errors.phone }}</span>
                    </div>

                    <div class="mb-4">
                        <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address</label>
                        <input
                            v-model="form.address"
                            type="text"
                            id="address"
                            class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200"
                        />
                        <span v-if="form.errors.address" class="text-red-600 text-sm">{{ form.errors.address }}</span>
                    </div>

                    <div>
                        <button type="submit" class="btn-primary">
                            Update Parent
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, Head } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    parent: Object,
});

const form = useForm({
    name: props.parent.user.name,
    email: props.parent.user.email,
    phone: props.parent.phone,
    address: props.parent.address,
});

// Submit the form
function submit() {
    form.put(route('admin.parents.update', props.parent.id), {
        onSuccess: () => {
            // Handle successful update, e.g., show a notification
        },
    });
}
</script>

<style scoped>
.btn-primary {
    @apply bg-blue-600 text-white font-semibold py-2 px-4 rounded shadow hover:bg-blue-700 transition duration-200;
}
</style>
