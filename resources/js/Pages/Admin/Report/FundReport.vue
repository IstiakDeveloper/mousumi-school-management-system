<template>
    <AdminLayout>
      <div class="container mx-auto py-6">
        <!-- Heading -->
        <div class="mb-6 flex items-center justify-between">
          <h1 class="text-3xl font-semibold text-gray-800 dark:text-white">Funds Report</h1>
          <!-- Print Button -->
          <button @click="printReport" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            Print Report
          </button>
        </div>

        <!-- Filter Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
          <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Filter Funds</h2>
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

        <!-- Funds Report Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6" id="print-section">
          <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Funds Report for {{ filters.monthName }} {{ filters.year }}</h2>
          <table class="min-w-full table-auto border-collapse bg-white dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
            <thead>
              <tr class="bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-200">
                <th class="px-6 py-4 text-left border border-gray-200 dark:border-gray-600">Date</th>
                <th class="px-6 py-4 text-left border border-gray-200 dark:border-gray-600">Type</th>
                <th class="px-6 py-4 text-left border border-gray-200 dark:border-gray-600">Amount</th>
                <th class="px-6 py-4 text-left border border-gray-200 dark:border-gray-600">Description</th>
                <th class="px-6 py-4 text-left border border-gray-200 dark:border-gray-600">Total</th> <!-- New Total column -->
              </tr>
            </thead>
            <tbody>
              <!-- Previous Month's Total Funds -->
              <tr class="bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-200 font-semibold">
                <td colspan="4" class="px-6 py-4 border border-gray-200 dark:border-gray-600">Total Funds before {{ filters.monthName }} {{ filters.year }}:</td>
                <td class="px-6 py-4 text-green-600 dark:text-green-400 border border-gray-200 dark:border-gray-600">{{ previousMonthFunds | currency }}</td>
              </tr>

              <!-- Current Month's Funds -->
              <tr v-for="fund in currentMonthFunds" :key="fund.id" class="border-b border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600">
                <td class="px-6 py-4 text-sm text-gray-800 dark:text-white border border-gray-200 dark:border-gray-600">{{ formatDate(fund.date) }}</td>
                <td class="px-6 py-4 text-sm text-gray-800 dark:text-white border border-gray-200 dark:border-gray-600">{{ fund.type }}</td>
                <td class="px-6 py-4 text-sm text-gray-800 dark:text-white border border-gray-200 dark:border-gray-600">{{ fund.amount | currency }}</td>
                <td class="px-6 py-4 text-sm text-gray-800 dark:text-white border border-gray-200 dark:border-gray-600">{{ fund.description }}</td>
                <td class="px-6 py-4 text-sm text-gray-800 dark:text-white border border-gray-200 dark:border-gray-600">{{ fund.cumulative_total | currency }}</td>
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
      previousMonthFunds: Number,
      currentMonthFunds: Array,
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
        const monthNames = [
          "January", "February", "March", "April", "May", "June",
          "July", "August", "September", "October", "November", "December"
        ];
        return monthNames[month - 1];
      },
      fetchReport() {
        // Trigger data fetching based on selected filters
        this.filters.monthName = this.getMonthName(this.filters.month);
        this.$inertia.get(route('funds.report'), {
          year: this.filters.year,
          month: this.filters.month,
        }, { preserveState: true });
      },
      printReport() {
        // Print the report section
        const printContent = document.getElementById('print-section');
        const printWindow = window.open('', '_blank');
        printWindow.document.write('<html><head><title>Funds Report</title></head><body>');
        printWindow.document.write(printContent.innerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
      },
      formatDate(date) {
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        return new Date(date).toLocaleDateString(undefined, options);
      },
    },
    filters: {
      currency(value) {
        return new Intl.NumberFormat('en-US', {
          style: 'currency',
          currency: 'USD',
        }).format(value);
      },
    },
  };
  </script>

