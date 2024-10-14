<template>
    <AdminLayout>
      <div class="container mx-auto mt-6">
        <h1 class="text-2xl font-bold mb-4">
          Payments for {{ selectedYear }} - {{ monthName }}
        </h1>

        <div class="mb-4 flex items-center">
          <label for="year" class="mr-2">Select Year:</label>
          <select
            v-model="selectedYear"
            @change="fetchStudents"
            id="year"
            class="border rounded p-2 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200"
          >
            <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
          </select>

          <label for="month" class="ml-4 mr-2">Select Month:</label>
          <select
            v-model="selectedMonth"
            @change="fetchStudents"
            id="month"
            class="border rounded p-2 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200"
          >
            <option v-for="(month, index) in months" :key="index" :value="index + 1">{{ month }}</option>
          </select>
        </div>

        <table class="min-w-full bg-white border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
          <thead>
            <tr>
              <th class="py-2 px-4 border-b text-left">Name</th>
              <th class="py-2 px-4 border-b">Payment Status</th>
              <th class="py-2 px-4 border-b">Action</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="student in students" :key="student.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
              <td class="py-2 px-4 border-b dark:text-gray-200">{{ student.name_en }}</td>
              <td class="py-2 px-4 border-b text-center">
                <span v-if="student.payment_status === 'paid'" class="text-green-600">Paid</span>
                <span v-else class="text-red-600">Not Paid</span>
              </td>
              <td class="py-2 px-4 border-b text-right">
                <button
                  v-if="student.payment_status !== 'paid'"
                  @click="makePayment(student.id)"
                  class="bg-blue-500 text-white rounded px-4 py-2 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-500"
                >
                  Pay Now
                </button>
              </td>
            </tr>
          </tbody>
        </table>

        <div v-if="loading" class="mt-4 text-center">Loading...</div>
      </div>
    </AdminLayout>
  </template>

  <script>
  import AdminLayout from '@/Layouts/AdminLayout.vue'; // Ensure the correct path to your AdminLayout component

  export default {
    components: {
      AdminLayout,
    },
    props: {
      students: Array,
      year: Number,
      month: Number,
    },
    data() {
      return {
        selectedYear: this.year,
        selectedMonth: this.month,
        years: this.generateYears(),
        months: [
          'January', 'February', 'March', 'April', 'May', 'June',
          'July', 'August', 'September', 'October', 'November', 'December'
        ],
        loading: false,
      };
    },
    computed: {
      monthName() {
        return this.months[this.selectedMonth - 1]; // Get month name based on selected month
      },
    },
    methods: {
      generateYears() {
        const currentYear = new Date().getFullYear();
        const years = [];
        for (let year = 1900; year <= currentYear; year++) {
          years.push(year);
        }
        return years;
      },
      async fetchStudents() {
        this.loading = true;
        try {
          await this.$inertia.get(this.route('payments.index'), {
            year: this.selectedYear,
            month: this.selectedMonth,
          });
        } finally {
          this.loading = false;
        }
      },
      async makePayment(studentId) {
        this.loading = true;
        try {
          await this.$inertia.post(this.route('payments.store'), {
            student_id: studentId,
            year: this.selectedYear,
            month: this.selectedMonth,
          });
        } finally {
          this.loading = false;
        }
      },
    },
  };
  </script>

  <style scoped>
  /* Add any component-specific styles here */
  </style>
