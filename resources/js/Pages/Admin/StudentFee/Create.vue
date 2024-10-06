<template>
    <Head title="Create Student Fee" />

    <AdminLayout>
        <div class="mx-auto py-8">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8">
                <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-300 mb-6">Create Student Fee</h1>

                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Student Select -->
                        <div>
                            <label for="student" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Student</label>
                            <select
                                v-model="form.student_id"
                                id="student"
                                required
                                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200"
                            >
                                <option v-for="student in students" :key="student.id" :value="student.id">{{ student.name_en }}</option>
                            </select>
                            <span v-if="form.errors.student_id" class="text-red-600 text-sm">{{ form.errors.student_id }}</span>
                        </div>

                        <!-- School Class Select -->
                        <div>
                            <label for="class" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Class</label>
                            <select
                                v-model="form.school_class_id"
                                id="class"
                                required
                                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200"
                            >
                                <option v-for="schoolClass in schoolClasses" :key="schoolClass.id" :value="schoolClass.id">{{ schoolClass.name }}</option>
                            </select>
                            <span v-if="form.errors.school_class_id" class="text-red-600 text-sm">{{ form.errors.school_class_id }}</span>
                        </div>

                        <!-- Section Select -->
                        <div>
                            <label for="section" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Section</label>
                            <select
                                v-model="form.section_id"
                                id="section"
                                required
                                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200"
                            >
                                <option v-for="section in sections" :key="section.id" :value="section.id">{{ section.name }}</option>
                            </select>
                            <span v-if="form.errors.section_id" class="text-red-600 text-sm">{{ form.errors.section_id }}</span>
                        </div>

                        <!-- Total Fee -->
                        <div>
                            <label for="total_fee" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total Fee</label>
                            <input
                                v-model="form.total_fee"
                                type="number"
                                id="total_fee"
                                required
                                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200"
                            />
                            <span v-if="form.errors.total_fee" class="text-red-600 text-sm">{{ form.errors.total_fee }}</span>
                        </div>

                        <!-- Paid Amount -->
                        <div>
                            <label for="paid_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Paid Amount</label>
                            <input
                                v-model="form.paid_amount"
                                type="number"
                                id="paid_amount"
                                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200"
                            />
                            <span v-if="form.errors.paid_amount" class="text-red-600 text-sm">{{ form.errors.paid_amount }}</span>
                        </div>

                        <!-- Due Date -->
                        <div>
                            <label for="due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Due Date</label>
                            <input
                                v-model="form.due_date"
                                type="date"
                                id="due_date"
                                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200"
                            />
                            <span v-if="form.errors.due_date" class="text-red-600 text-sm">{{ form.errors.due_date }}</span>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="btn-primary">
                            Create Student Fee
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

// Props passed from the controller
const props = defineProps({
    students: Array,
    schoolClasses: Array,
    sections: Array
});

// Define the form
const form = useForm({
    student_id: '',
    school_class_id: '',
    section_id: '',
    total_fee: '',
    paid_amount: '',
    due_date: ''
});

// Submit handler
function submit() {
    form.post(route('admin.student-fees.store'), {
        onSuccess: () => {
            // Handle success, e.g., redirect
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
