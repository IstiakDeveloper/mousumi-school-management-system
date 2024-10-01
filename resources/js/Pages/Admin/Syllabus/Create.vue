<template>


    <AdminLayout>
        <div class="mx-auto py-8">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8">
                <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-300 mb-6">Create Syllabus</h1>

                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Class -->
                        <div>
                            <label for="class_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Class</label>
                            <select
                                v-model="form.class_id"
                                id="class_id"
                                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200"
                            >
                                <option value="">Select Class</option>
                                <option v-for="schoolClass in classes" :key="schoolClass.id" :value="schoolClass.id">{{ schoolClass.name }}</option>
                            </select>
                            <span v-if="form.errors.class_id" class="text-red-600 text-sm">{{ form.errors.class_id }}</span>
                        </div>

                        <!-- Subject -->
                        <div>
                            <label for="subject_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Subject</label>
                            <select
                                v-model="form.subject_id"
                                id="subject_id"
                                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200"
                            >
                                <option value="">Select Subject</option>
                                <option v-for="subject in subjects" :key="subject.id" :value="subject.id">{{ subject.name }}</option>
                            </select>
                            <span v-if="form.errors.subject_id" class="text-red-600 text-sm">{{ form.errors.subject_id }}</span>
                        </div>

                        <!-- Description -->
                        <div class="col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                            <textarea
                                v-model="form.description"
                                id="description"
                                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200"
                                rows="4"
                            ></textarea>
                            <span v-if="form.errors.description" class="text-red-600 text-sm">{{ form.errors.description }}</span>
                        </div>

                        <!-- PDF File -->
                        <div class="col-span-2">
                            <label for="pdf_file" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Upload PDF File</label>
                            <input
                                type="file"
                                id="pdf_file"
                                @input="form.pdf_file = $event.target.files[0]"
                                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200"
                            />
                            <span v-if="form.errors.pdf_file" class="text-red-600 text-sm">{{ form.errors.pdf_file }}</span>
                        </div>

                        <!-- Progress Bar -->
                        <div class="col-span-2">
                            <progress v-if="form.progress" :value="form.progress.percentage" max="100" class="w-full h-4 bg-blue-200">
                                {{ form.progress.percentage }}%
                            </progress>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6">
                        <button type="submit" class="btn-primary">
                            Create Syllabus
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

const props = defineProps({
    classes: Array,
    subjects: Array,
});

const form = useForm({
    class_id: null,
    subject_id: null,
    description: '',
    pdf_file: null,
});

// Handle form submission
function submit() {
    form.post(route('admin.syllabus.store'), {
        onSuccess: () => {
            // Redirect to syllabus index on success
            this.$inertia.visit(route('admin.syllabus.index'));
        },
        onError: (errors) => {
            // Errors will be auto-populated in form.errors
        },
        // Ensure file upload works by sending as FormData
        forceFormData: true,
    });
}
</script>

<style scoped>
.btn-primary {
    @apply bg-blue-600 text-white font-semibold py-2 px-4 rounded shadow hover:bg-blue-700 transition duration-200;
}
</style>
