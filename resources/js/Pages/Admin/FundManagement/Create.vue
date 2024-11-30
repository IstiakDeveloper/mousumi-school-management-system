<template>
    <Head title="Create Fund" />

    <AdminLayout>
        <div class="container mx-auto py-8">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8">
                <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-300 mb-6">Fund Management</h1>

                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Date Field -->
                    <div>
                        <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date</label>
                        <input
                            type="date"
                            v-model="form.date"
                            id="date"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:bg-gray-800 dark:text-gray-300"
                        />
                        <span v-if="form.errors.date" class="text-red-600 text-xs mt-1">{{ form.errors.date }}</span>
                    </div>

                    <!-- Type Field -->
                    <div>
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Type</label>
                        <select
                            v-model="form.type"
                            id="type"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:bg-gray-800 dark:text-gray-300"
                        >
                            <option disabled value="">Select Type</option>
                            <option value="in">Fund In</option>
                            <option value="out">Fund Out</option>
                        </select>
                        <span v-if="form.errors.type" class="text-red-600 text-xs mt-1">{{ form.errors.type }}</span>
                    </div>

                    <!-- Amount Field -->
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Amount</label>
                        <input
                            type="number"
                            v-model="form.amount"
                            id="amount"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:bg-gray-800 dark:text-gray-300"
                        />
                        <span v-if="form.errors.amount" class="text-red-600 text-xs mt-1">{{ form.errors.amount }}</span>
                    </div>

                    <!-- Description Field -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                        <textarea
                            v-model="form.description"
                            id="description"
                            rows="3"
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-500 dark:focus:border-blue-500 dark:bg-gray-800 dark:text-gray-300"
                        ></textarea>
                        <span v-if="form.errors.description" class="text-red-600 text-xs mt-1">{{ form.errors.description }}</span>
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center justify-between">
                        <button type="submit" class="btn-primary">Save</button>
                        <Link href="/admin/funds" class="btn-secondary">Cancel</Link>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>


<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const form = useForm({
    date: '',
    type: '',
    amount: '',
    description: '',
});

function submit() {
    form.post(route('admin.funds.store'), {
        onSuccess: () => {
            form.reset();
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
