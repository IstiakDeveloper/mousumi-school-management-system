<template>
    <AdminLayout>
      <div class="min-h-screen bg-gray-50 dark:bg-gray-900 p-6">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mb-6">
          <i class="fas fa-book-open mr-2"></i>
          Student Results
        </h1>

        <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-2 gap-6 mb-6">
          <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-5 border border-gray-200 dark:border-gray-700">
            <label for="exam_category" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Exam Name</label>
            <select id="exam_category" v-model="filters.exam_category_id" @change="filterResults" class="mt-1 p-2 border border-gray-300 rounded-md w-full dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200">
              <option value="">Select Exam Category</option>
              <option v-for="examCategory in examCategories" :key="examCategory.id" :value="examCategory.id">{{ examCategory.title }}</option>
            </select>
          </div>

          <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-5 border border-gray-200 dark:border-gray-700">
            <label for="school_class" class="block text-sm font-medium text-gray-700 dark:text-gray-300">School Class</label>
            <select id="school_class" v-model="filters.school_class_id" @change="filterResults" class="mt-1 p-2 border border-gray-300 rounded-md w-full dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200">
              <option value="">Select School Class</option>
              <option v-for="schoolClass in schoolClasses" :key="schoolClass.id" :value="schoolClass.id">{{ schoolClass.name }}</option>
            </select>
          </div>

          <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-5 border border-gray-200 dark:border-gray-700">
            <label for="section" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Section</label>
            <select id="section" v-model="filters.section_id" @change="filterResults" class="mt-1 p-2 border border-gray-300 rounded-md w-full dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200">
              <option value="">Select Section</option>
              <option v-for="section in sections" :key="section.id" :value="section.id">{{ section.name }}</option>
            </select>
          </div>

          <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-5 border border-gray-200 dark:border-gray-700">
            <label for="subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Subject</label>
            <select id="subject" v-model="filters.subject_id" @change="filterResults" class="mt-1 p-2 border border-gray-300 rounded-md w-full dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200">
              <option value="">Select Subject</option>
              <option v-for="subject in subjects" :key="subject.id" :value="subject.id">{{ subject.name }}</option>
            </select>
          </div>
        </div>


        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div v-for="gradeBook in filteredGradeBooks" :key="gradeBook.id" class="bg-white dark:bg-gray-800 p-6 shadow-lg rounded-lg border border-gray-200 dark:border-gray-700 transition hover:shadow-xl">
                <div class="flex items-center mb-4">
                    <i class="fas fa-user-graduate text-3xl text-blue-600"></i>
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-100 ml-3">
                        {{ gradeBook.student.name_en }}
                    </h2>
                </div>
                <div class="border-b border-gray-200 dark:border-gray-700 mb-4 pb-3">
                    <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Class:</strong> {{ getClassName(gradeBook.school_class_id) }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Section:</strong> {{ getSectionName(gradeBook.section_id) }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Subject:</strong> {{ getSubjectName(gradeBook.subject_id) }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Mark:</strong> <span class="font-bold">{{ gradeBook.mark }}</span></p>
                    <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Grade:</strong> <span class="font-bold">{{ gradeBook.grade ? gradeBook.grade.grade : 'N/A' }}</span></p>
                    <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Grade Point:</strong> <span class="font-bold">{{ gradeBook.grade ? gradeBook.grade.grade_point : 'N/A' }}</span></p>
                    <p class="text-sm text-gray-600 dark:text-gray-400"><strong>Exam Category:</strong> <span class="font-bold">{{ getExamCategoryName(gradeBook.exam_category_id) }}</span></p>
                </div>
            </div>
        </div>

      </div>
    </AdminLayout>
  </template>

  <script>
  import AdminLayout from '@/Layouts/AdminLayout.vue'; // Adjust the import path as necessary

  export default {
  components: {
    AdminLayout,
  },
  props: {
    gradeBooks: Array,
    examCategories: Array,
    schoolClasses: Array,
    sections: Array,
    subjects: Array,
  },
  data() {
    return {
      filters: {
        exam_category_id: '',
        school_class_id: '',
        section_id: '',
        subject_id: '',
      },
      filteredGradeBooks: this.gradeBooks,
    };
  },
  methods: {
    filterResults() {
      this.filteredGradeBooks = this.gradeBooks.filter((gradeBook) => {
        return (
          (!this.filters.exam_category_id || gradeBook.exam_category_id === this.filters.exam_category_id) &&
          (!this.filters.school_class_id || gradeBook.school_class_id === this.filters.school_class_id) &&
          (!this.filters.section_id || gradeBook.section_id === this.filters.section_id) &&
          (!this.filters.subject_id || gradeBook.subject_id === this.filters.subject_id)
        );
      });
    },
    getClassName(id) {
      const schoolClass = this.schoolClasses.find(cls => cls.id === id);
      return schoolClass ? schoolClass.name : 'N/A';
    },
    getSectionName(id) {
      const section = this.sections.find(sec => sec.id === id);
      return section ? section.name : 'N/A';
    },
    getSubjectName(id) {
      const subject = this.subjects.find(sub => sub.id === id);
      return subject ? subject.name : 'N/A';
    },
    getExamCategoryName(id) {
      const examCategory = this.examCategories.find(cat => cat.id === id);
      return examCategory ? examCategory.title : 'N/A';
    },
  },
  mounted() {
    this.filteredGradeBooks = this.gradeBooks; // Initialize filtered results
  },
};
</script>

  <style scoped>
  /* Additional custom styles can go here */
  </style>
