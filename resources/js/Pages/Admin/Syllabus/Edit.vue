<template>
    <Head title="Edit Syllabus" />

    <AdminLayout>
        <div class="mx-auto py-8">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8">
                <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-300 mb-6">Edit Syllabus</h1>

                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Class Selection -->
                        <div>
                            <label for="class_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Class</label>
                            <select
                                v-model="form.class_id"
                                id="class_id"
                                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200"
                            >
                                <option value="">Select Class</option>
                                <option v-for="SchoolClass in classes" :key="SchoolClass.id" :value="SchoolClass.id">{{ SchoolClass.name }}</option>
                            </select>
                            <span v-if="form.errors.class_id" class="text-red-600 text-sm">{{ form.errors.class_id }}</span>
                        </div>

                        <!-- Subject Selection -->
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
                        <div class="md:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                            <textarea
                                v-model="form.description"
                                id="description"
                                rows="5"
                                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200"
                            ></textarea>
                            <span v-if="form.errors.description" class="text-red-600 text-sm">{{ form.errors.description }}</span>
                        </div>

                        <!-- PDF File -->
                        <!-- <div class="md:col-span-2">
                            <label for="pdf_file" class="block text-sm font-medium text-gray-700 dark:text-gray-300">PDF File</label>
                            <input
                                type="file"
                                id="pdf_file"
                                @change="handleFileChange"
                                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-200"
                            />
                            <span v-if="form.errors.pdf_file" class="text-red-600 text-sm">{{ form.errors.pdf_file }}</span>
                        </div> -->
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="btn-primary">
                            Update Syllabus
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

const props = defineProps({
    syllabus: Object,
    classes: Array,
    subjects: Array,
});

const form = useForm({
    class_id: props.syllabus.class_id,
    subject_id: props.syllabus.subject_id,
    description: props.syllabus.description || '',
    pdf_file: null,
});

function submit() {
    // Create FormData object to handle file upload
    const formData = new FormData();
    formData.append('class_id', form.class_id);
    formData.append('subject_id', form.subject_id);
    formData.append('description', form.description);

    if (form.pdf_file) {
        formData.append('pdf_file', form.pdf_file);
    }

    // Send formData to the server
    form.put(route('admin.syllabus.update', props.syllabus.id), {
        data: formData,
        onSuccess: () => {
            // Handle success, like redirecting or showing a success message
        },
        onError: () => {
            // Handle errors, showing them if necessary
        },
    });
}

function handleFileChange(event) {
    const file = event.target.files[0];
    form.pdf_file = file;
}
</script>

<style scoped>
.btn-primary {
    @apply bg-blue-600 text-white font-semibold py-2 px-4 rounded shadow hover:bg-blue-700 transition duration-200;
}
</style>
