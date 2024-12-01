<template>
    <Head title="Create Teacher" />

    <AdminLayout>
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
                <!-- Header Section -->
                <div class="border-b border-gray-200 dark:border-gray-700 p-6">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Create Teacher</h1>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Add a new teacher to your institution</p>
                </div>

                <form @submit.prevent="submit" class="p-6">
                    <!-- Profile Image Section -->
                    <div class="mb-8 p-6 bg-gray-50 dark:bg-gray-900 rounded-lg">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Profile Image</h2>
                        <div class="flex items-center space-x-6">
                            <div class="flex-shrink-0">
                                <div v-if="imagePreview" class="relative h-40 w-40">
                                    <img :src="imagePreview" class="h-40 w-40 rounded-full object-cover border-4 border-white dark:border-gray-800 shadow-lg" />
                                    <button @click="removeImage" class="absolute top-0 right-0 p-1 bg-red-500 text-white rounded-full hover:bg-red-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div v-else class="h-40 w-40 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                                    <svg class="h-20 w-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="flex flex-col">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Upload Photo
                                </label>
                                <div class="flex items-center space-x-4">
                                    <label class="cursor-pointer bg-white dark:bg-gray-800 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none">
                                        <span>Change Photo</span>
                                        <input type="file" class="hidden" @change="handleFileChange" accept="image/*">
                                    </label>
                                </div>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    JPG, PNG or GIF (MAX. 2MB)
                                </p>
                                <span v-if="form.errors.profile_image" class="mt-1 text-sm text-red-600">
                                    {{ form.errors.profile_image }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Form Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Personal Information Section -->
                        <div class="space-y-6">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 border-b pb-2">Personal Information</h2>

                            <div class="space-y-4">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Full Name</label>
                                    <input
                                        v-model="form.name"
                                        type="text"
                                        id="name"
                                        required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700"
                                    />
                                    <span v-if="form.errors.name" class="text-sm text-red-600">{{ form.errors.name }}</span>
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Address</label>
                                    <input
                                        v-model="form.email"
                                        type="email"
                                        id="email"
                                        required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700"
                                    />
                                    <span v-if="form.errors.email" class="text-sm text-red-600">{{ form.errors.email }}</span>
                                </div>

                                <div>
                                    <label for="phone_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone Number</label>
                                    <input
                                        v-model="form.phone_number"
                                        type="tel"
                                        id="phone_number"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700"
                                    />
                                    <span v-if="form.errors.phone_number" class="text-sm text-red-600">{{ form.errors.phone_number }}</span>
                                </div>

                                <div>
                                    <label for="dob" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date of Birth</label>
                                    <input
                                        v-model="form.dob"
                                        type="date"
                                        id="dob"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700"
                                    />
                                    <span v-if="form.errors.dob" class="text-sm text-red-600">{{ form.errors.dob }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Employment Information Section -->
                        <div class="space-y-6">
                            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 border-b pb-2">Employment Information</h2>

                            <div class="space-y-4">
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label for="pin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Teacher PIN</label>
                                        <input
                                            v-model="form.pin"
                                            type="text"
                                            id="pin"
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700"
                                        />
                                        <span v-if="form.errors.pin" class="text-sm text-red-600">{{ form.errors.pin }}</span>
                                    </div>

                                    <div>
                                        <label for="uid" class="block text-sm font-medium text-gray-700 dark:text-gray-300">UID</label>
                                        <input
                                            v-model="form.uid"
                                            type="text"
                                            id="uid"
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700"
                                        />
                                        <span v-if="form.errors.uid" class="text-sm text-red-600">{{ form.errors.uid }}</span>
                                    </div>
                                </div>

                                <div>
                                    <label for="designation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Designation</label>
                                    <input
                                        v-model="form.designation"
                                        type="text"
                                        id="designation"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700"
                                    />
                                    <span v-if="form.errors.designation" class="text-sm text-red-600">{{ form.errors.designation }}</span>
                                </div>

                                <div>
                                    <label for="subject_specialization" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Subject Specialization</label>
                                    <input
                                        v-model="form.subject_specialization"
                                        type="text"
                                        id="subject_specialization"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700"
                                    />
                                    <span v-if="form.errors.subject_specialization" class="text-sm text-red-600">{{ form.errors.subject_specialization }}</span>
                                </div>

                                <div>
                                    <label for="salary_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Salary Amount</label>
                                    <div class="mt-1 relative rounded-md shadow-sm">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 sm:text-sm">৳</span>
                                        </div>
                                        <input
                                            v-model="form.salary_amount"
                                            type="number"
                                            id="salary_amount"
                                            required
                                            step="0.01"
                                            class="pl-7 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700"
                                        />
                                    </div>
                                    <span v-if="form.errors.salary_amount" class="text-sm text-red-600">{{ form.errors.salary_amount }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information Section -->
                    <div class="mt-8 space-y-6">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 border-b pb-2">Additional Information</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="joining_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Joining Date</label>
                                <input
                                    v-model="form.joining_date"
                                    type="date"
                                    id="joining_date"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700"
                                />
                                <span v-if="form.errors.joining_date" class="text-sm text-red-600">{{ form.errors.joining_date }}</span>
                            </div>

                            <div>
                                <label for="job_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Job Status</label>
                                <select
                                    v-model="form.job_status"
                                    id="job_status"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700"
                                >
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="on_leave">On Leave</option>
                                    <option value="terminated">Terminated</option>
                                </select>
                                <span v-if="form.errors.job_status" class="text-sm text-red-600">{{ form.errors.job_status }}</span>
                            </div>

                            <div class="md:col-span-2">
                                <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address</label>
                                <textarea
                                    v-model="form.address"
                                    id="address"
                                    rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700"
                                ></textarea>
                                <span v-if="form.errors.address" class="text-sm text-red-600">{{ form.errors.address }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-8 flex justify-end space-x-4">
                        <button
                            type="button"
                            @click="$inertia.visit(route('admin.teachers.index'))"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
                        >
                            <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ form.processing ? 'Creating...' : 'Create Teacher' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, watch } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    classes: Array,
    sections: Array,
});

const imagePreview = ref(null);

const form = useForm({
    name: '',
    email: '',
    subject_specialization: '',
    pin: '',
    uid: '',
    salary_amount: '',
    profile_image: null,
    phone_number: '',
    address: '',
    dob: null,
    joining_date: null,
    designation: '',
    job_status: 'active',
});

function handleFileChange(event) {
    const file = event.target.files[0];
    if (file) {
        form.profile_image = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
}

function removeImage() {
    form.profile_image = null;
    imagePreview.value = null;
}

function submit() {
    form.post(route('admin.teachers.store'), {
        onSuccess: () => {
            // Redirect to index page on success
            window.location = route('admin.teachers.index');
        },
        preserveScroll: true,
    });
}

// Watch for form processing state to handle loading states
watch(() => form.processing, (value) => {
    if (value) {
        // Disable form inputs while processing
        document.querySelectorAll('input, select, textarea, button').forEach(el => {
            el.disabled = true;
        });
    }
});
</script>

<style scoped>
/* Optional: Add any additional custom styles here */
.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}
</style>
