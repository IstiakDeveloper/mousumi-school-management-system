<template>

    <Head title="Teachers" />

    <AdminLayout>
        <div class="container mx-auto py-8">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-300">Teachers</h1>
                    <Link href="/admin/teachers/create" class="btn-primary flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path
                            d="M10 2a1 1 0 011 1v6h6a1 1 0 110 2h-6v6a1 1 0 11-2 0v-6H3a1 1 0 110-2h6V3a1 1 0 011-1z" />
                    </svg>
                    Add Teacher
                    </Link>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Profile Image</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Name</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Subject Specialization</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    PIN</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    UID</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Salary Amount</th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-700 divide-y divide-gray-200">
                            <tr v-for="teacher in teachers" :key="teacher.id">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">
                                    <img v-if="teacher.profile_image_url" :src="teacher.profile_image_url"
                                        alt="Profile Image" class="h-10 w-10 rounded-full object-cover" />
                                    <span v-else class="h-10 w-10 bg-gray-300 rounded-full inline-block"></span>
                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                    {{ teacher.user.name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">{{
                                    teacher.user.email }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">{{
                                    teacher.subject_specialization }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">{{
                                    teacher.pin || 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">{{
                                    teacher.uid || 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-300">{{
                                    teacher.salary_amount || 'N/A' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <Link :href="route('admin.teachers.show', teacher.id)"
                                        class="inline-flex items-center text-green-600 hover:text-green-800 bg-green-100 hover:bg-green-200 px-3 py-2 rounded transition duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path fill-rule="evenodd"
                                            d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    View
                                    </Link>
                                    <Link :href="route('admin.teachers.edit', teacher.id)"
                                        class="inline-flex items-center text-blue-600 hover:text-blue-800 bg-blue-100 hover:bg-blue-200 px-3 py-2 rounded transition duration-200 ml-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path
                                            d="M12.293 1.293a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-9 9a1 1 0 01-.353.213l-3 1a1 1 0 01-1.293-1.293l1-3a1 1 0 01.213-.353l9-9z" />
                                    </svg>
                                    Edit
                                    </Link>

                                    <button @click="confirmDelete(teacher.id)"
                                        class="inline-flex items-center text-red-600 hover:text-red-800 bg-red-100 hover:bg-red-200 px-3 py-2 rounded transition duration-200 ml-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20"
                                            fill="currentColor">
                                            <path d="M6 2a1 1 0 00-1 1v1h12V3a1 1 0 00-1-1H6z" />
                                            <path fill-rule="evenodd"
                                                d="M5 4h10a1 1 0 011 1v13a1 1 0 01-1 1H5a1 1 0 01-1-1V5a1 1 0 011-1zm1 1v13h8V5H6z"
                                                clip-rule="evenodd" />
                                        </svg>
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <ConfirmationDialog :show="isDialogVisible" @update:show="isDialogVisible = $event" @confirm="deleteTeacher" />
    </AdminLayout>
</template>

<script setup>
import { Link, Head, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ConfirmationDialog from '@/Components/ConfirmationDialog.vue';
import { ref } from 'vue';

const props = defineProps({
    teachers: Array,
});


const isDialogVisible = ref(false);
const teacherToDelete = ref(null);
const form = useForm({});

function confirmDelete(teacherId) {
    teacherToDelete.value = teacherId;
    isDialogVisible.value = true;
}

function deleteTeacher() {
    if (teacherToDelete.value) {
        form.delete(route('admin.teachers.destroy', teacherToDelete.value), {
            onSuccess: () => {
                isDialogVisible.value = false; // Close dialog after successful deletion
            },
        });
    }
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
