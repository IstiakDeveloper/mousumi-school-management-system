<template>

    <Head title="Edit Teacher" />

    <AdminLayout>
        <div class="mx-auto py-8">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8">
                <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-300 mb-6">Edit Teacher</h1>

                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Profile Image -->
                        <!-- <div>
                            <label for="profile_image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Profile Image</label>
                            <input
                                type="file"
                                id="profile_image"
                                @change="handleFileChange"
                                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200"
                            />
                            <span v-if="form.errors.profile_image" class="text-red-600 text-sm">{{ form.errors.profile_image }}</span>
                        </div> -->

                        <!-- Phone Number -->
                        <div>
                            <label for="phone_number"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone Number</label>
                            <input v-model="form.phone_number" type="text" id="phone_number" required
                                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200" />
                            <span v-if="form.errors.phone_number" class="text-red-600 text-sm">{{
                                form.errors.phone_number }}</span>
                        </div>

                        <!-- Name -->
                        <div>
                            <label for="name"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                            <input v-model="form.name" type="text" id="name" required
                                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200" />
                            <span v-if="form.errors.name" class="text-red-600 text-sm">{{ form.errors.name }}</span>
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email</label>
                            <input v-model="form.email" type="email" id="email" required
                                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200" />
                            <span v-if="form.errors.email" class="text-red-600 text-sm">{{ form.errors.email }}</span>
                        </div>

                        <div>
                            <label for="designation"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Designation</label>
                            <input v-model="form.designation" type="text" id="designation" required
                                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200" />
                            <span v-if="form.errors.designation" class="text-red-600 text-sm">{{
                                form.errors.subject_specialization }}</span>
                        </div>

                        <div>
                            <label for="joining_date"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Joining Date</label>
                            <input v-model="form.joining_date" type="text" id="joining_date"
                                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200" />
                            <span v-if="form.errors.joining_date" class="text-red-600 text-sm">{{
                                form.errors.joining_date }}</span>
                        </div>



                        <!-- Job Status -->
                        <div>
                            <label for="job_status"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Job Status</label>
                            <select v-model="form.job_status" id="job_status"
                                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="on_leave">On Leave</option>
                                <option value="terminated">Terminated</option>
                            </select>
                            <span v-if="form.errors.job_status" class="text-red-600 text-sm">{{ form.errors.job_status
                                }}</span>
                        </div>


                        <!-- Subject Specialization -->
                        <div>
                            <label for="subject_specialization"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Subject
                                Specialization</label>
                            <input v-model="form.subject_specialization" type="text" id="subject_specialization"
                                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200" />
                            <span v-if="form.errors.subject_specialization" class="text-red-600 text-sm">{{
                                form.errors.subject_specialization }}</span>
                        </div>

                        <!-- Salary Amount -->
                        <div>
                            <label for="salary_amount"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Salary Amount</label>
                            <input v-model="form.salary_amount" type="number" id="salary_amount" required step="0.01"
                                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200" />
                            <span v-if="form.errors.salary_amount" class="text-red-600 text-sm">{{
                                form.errors.salary_amount }}</span>
                        </div>



                        <!-- Address -->
                        <div>
                            <label for="address"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address</label>
                            <textarea v-model="form.address" id="address" rows="3"
                                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200"></textarea>
                            <span v-if="form.errors.address" class="text-red-600 text-sm">{{ form.errors.address
                                }}</span>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="btn-primary">
                            Update Teacher
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { defineProps } from 'vue';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    teacher: Object,
    classes: Array,
    sections: Array,
});

const form = useForm({
    name: props.teacher.user.name,
    email: props.teacher.user.email,
    subject_specialization: props.teacher.subject_specialization,
    class_id: props.teacher.class_id,
    section_id: props.teacher.section_id,
    salary_amount: props.teacher.salary_amount,
    designation: props.teacher.designation,
    dob: props.teacher.dob,
    joining_date: props.teacher.joining_date,
    job_status: props.teacher.job_status,
    phone_number: props.teacher.phone_number,
    address: props.teacher.address,
    profile_image: null, // Track profile image file
});

function handleFileChange(event) {
    form.profile_image = event.target.files[0]; // Save the selected file to the form
}

function submit() {
    form.put(route('admin.teachers.update', props.teacher.id), {
        onSuccess: () => {
            // Redirect or show success message
        },
        onError: () => {
            // Handle errors
        },
    });
}
</script>

<style scoped>
.btn-primary {
    @apply bg-blue-600 text-white font-semibold py-2 px-4 rounded shadow hover:bg-blue-700 transition duration-200;
}
</style>
