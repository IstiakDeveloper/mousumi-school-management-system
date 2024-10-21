<template>
    <AdminLayout>
      <div class="container mx-auto py-6">
        <!-- Heading -->
        <div class="mb-6 flex items-center justify-between">
          <h1 class="text-3xl font-semibold text-gray-800 dark:text-white">Expenses Report</h1>
          <!-- Print Button -->
          <button @click="printReport" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Print Report
          </button>
        </div>

        <!-- Filter Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
          <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Filter Expenses</h2>
          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
            <!-- Year Filter -->
            <div>
              <label class="block text-sm font-medium text-gray-600 dark:text-gray-200">Year</label>
              <select v-model="filters.year" @change="fetchReport" class="mt-2 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:ring-indigo-500 focus:border-indigo-500">
                <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
              </select>
            </div>

            <!-- Month Filter -->
            <div>
              <label class="block text-sm font-medium text-gray-600 dark:text-gray-200">Month</label>
              <select v-model="filters.month" @change="fetchReport" class="mt-2 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:ring-indigo-500 focus:border-indigo-500">
                <option v-for="month in months" :key="month.value" :value="month.value">{{ month.name }}</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Expenses Report Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6" id="print-section">
          <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Expenses Report for {{ filters.monthName }} {{ filters.year }}</h2>
          <table class="min-w-full table-auto border-collapse bg-white dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
            <thead>
              <tr class="bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-200">
                <th class="px-6 py-4 text-left border border-gray-200 dark:border-gray-600">Date</th>
                <th class="px-6 py-4 text-left border border-gray-200 dark:border-gray-600">Category</th>
                <th class="px-6 py-4 text-left border border-gray-200 dark:border-gray-600">Amount</th>
                <th class="px-6 py-4 text-left border border-gray-200 dark:border-gray-600">Description</th>
                <th class="px-6 py-4 text-left border border-gray-200 dark:border-gray-600">Total</th> <!-- New Total column -->
              </tr>
            </thead>
            <tbody>
              <!-- Previous Month's Total Expenses -->
              <tr class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-semibold">
                <td colspan="4" class="px-6 py-4 border border-gray-200 dark:border-gray-600">Total Expenses before {{ filters.monthName }} {{ filters.year }}:</td>
                <td class="px-6 py-4 text-green-600 dark:text-green-400 border border-gray-200 dark:border-gray-600">{{ previousMonthExpenses | currency }}</td>
              </tr>

              <!-- Current Month's Expenses -->
              <tr v-for="expense in currentMonthExpenses" :key="expense.id" class="border-b border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600">
                <td class="px-6 py-4 text-sm text-gray-800 dark:text-white border border-gray-200 dark:border-gray-600">{{ formatDate(expense.date) }}</td>
                <td class="px-6 py-4 text-sm text-gray-800 dark:text-white border border-gray-200 dark:border-gray-600">{{ expense.category.name }}</td>
                <td class="px-6 py-4 text-sm text-gray-800 dark:text-white border border-gray-200 dark:border-gray-600">{{ expense.amount | currency }}</td>
                <td class="px-6 py-4 text-sm text-gray-800 dark:text-white border border-gray-200 dark:border-gray-600">{{ expense.description }}</td>
                <td class="px-6 py-4 text-sm text-gray-800 dark:text-white border border-gray-200 dark:border-gray-600">{{ expense.cumulative_total | currency }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </AdminLayout>
  </template>

  <script>
  import AdminLayout from '@/Layouts/AdminLayout.vue';

  export default {
    components: {
      AdminLayout,
    },
    props: {
      previousMonthExpenses: Number,
      currentMonthExpenses: Array,
      years: Array,
      months: Array,
      selectedYear: Number,
      selectedMonth: Number,
    },
    data() {
      return {
        filters: {
          year: this.selectedYear,
          month: this.selectedMonth,
          monthName: this.getMonthName(this.selectedMonth),
        },
      };
    },
    methods: {
      getMonthName(month) {
        const months = [
          'January', 'February', 'March', 'April', 'May', 'June',
          'July', 'August', 'September', 'October', 'November', 'December',
        ];
        return months[month - 1];
      },
      fetchReport() {
        this.$inertia.get('/admin/reports/expenses', {
          year: this.filters.year,
          month: this.filters.month,
        });
      },
      formatDate(date) {
        return new Date(date).toLocaleDateString();
      },
      printReport() {
        const printContent = document.getElementById('print-section').innerHTML;
        const originalContent = document.body.innerHTML;

        document.body.innerHTML = printContent;
        window.print();
        document.body.innerHTML = originalContent;
        window.location.reload(); // Reload to restore the original view after printing
      },
    },
    filters: {
      currency(value) {
        return `$${parseFloat(value).toFixed(2)}`;
      },
    },
  };
  </script>

  <style scoped>
  /* Hide print button when printing */
  @media print {
    button {
      display: none;
    }
    body {
      background-color: white;
    }
    table {
      width: 100%;
      border-collapse: collapse;
    }
    th, td {
      border: 1px solid black;
      padding: 8px;
      text-align: left;
    }
  }
  </style>
