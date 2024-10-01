<template>
    <Head title="Enter Marks" />

    <AdminLayout>
      <div class="container mx-auto py-8">
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8">
          <h1 class="text-3xl font-bold mb-6 text-gray-800 dark:text-gray-300">Enter Marks</h1>

          <div class="mb-6">
          <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
            Exam Category: <span class="font-normal">{{ examCategoryName }}</span>
          </p>
          <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
            School Class: <span class="font-normal">{{ schoolClassName }}</span>
          </p>
          <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
            Section: <span class="font-normal">{{ sectionName }}</span>
          </p>
          <p class="text-lg font-semibold text-gray-700 dark:text-gray-200">
            Subject: <span class="font-normal">{{ subjectName }}</span>
          </p>
        </div>
          <!-- Show a table with students -->
          <div v-if="students.length > 0" class="mt-6">
            <h2 class="text-2xl font-bold mb-4">Students</h2>
            <form @submit.prevent="submitMarks">
              <table class="min-w-full border border-gray-300 dark:border-gray-600">
                <thead>
                  <tr class="bg-gray-200 dark:bg-gray-700">
                    <th class="border border-gray-300 dark:border-gray-600 p-2">Student Name</th>
                    <th class="border border-gray-300 dark:border-gray-600 p-2">Marks</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="student in students" :key="student.id">
                    <td class="border border-gray-300 dark:border-gray-600 p-2">{{ student.name_en }}</td>
                    <td class="border border-gray-300 dark:border-gray-600 p-2">
                        <input
                            type="number"
                            v-model.number="marks[student.id]"
                            class="border border-gray-300 dark:border-gray-600 p-2 w-full bg-gray-50 dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-500"
                            min="0"
                            max="100"
                            required
                        />
                    </td>
                  </tr>
                </tbody>
              </table>

              <div class="flex justify-end mt-4">
                <button type="submit" class="btn-primary inline-flex items-center">
                  Submit Marks
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10 2a1 1 0 00-1 1v6H3a1 1 0 100 2h6v6a1 1 0 002 0v-6h6a1 1 0 100-2h-6V3a1 1 0 00-1-1z" />
                  </svg>
                </button>
              </div>
            </form>
          </div>

          <div v-else class="mt-4 text-red-600">No students found for the selected criteria.</div>
        </div>
      </div>
    </AdminLayout>
  </template>

  <script setup>
  import { ref } from 'vue';
  import { Head, useForm } from '@inertiajs/vue3';
  import AdminLayout from '@/Layouts/AdminLayout.vue';

  const props = defineProps({
    students: Array,
    examCategoryId: Number,
    schoolClassId: Number,
    sectionId: Number,
    subjectId: Number,
    examCategoryName: String,
    schoolClassName: String,
    sectionName: String,
    subjectName: String,
  });

  // Marks will be stored as key-value pairs (student_id: marks)
  const marks = ref({});

  // Create a form object to submit marks
  const form = useForm({
    exam_category_id: props.examCategoryId,
    school_class_id: props.schoolClassId,
    section_id: props.sectionId,
    subject_id: props.subjectId,
    students: [],
  });

  // Handle form submission
  const submitMarks = () => {
    // Prepare the students array with their marks
    form.students = Object.entries(marks.value).map(([studentId, mark]) => ({
      student_id: studentId,
      mark: mark,
    }));

    // Post the form data to the server
    form.post(route('admin.gradebooks.storeMarks'), {
      onSuccess: () => {
        alert('Marks and grades have been saved successfully!');
      },
      onError: () => {
        alert('There was an error submitting the marks. Please try again.');
      },
    });
  };
  </script>

  <style scoped>
  .btn-primary {
    @apply bg-blue-600 text-white font-semibold py-2 px-4 rounded shadow hover:bg-blue-700 transition duration-200;
  }
  </style>
