<template>
    <AdminLayout>
        <div class="container mx-auto py-6">
            <!-- Heading -->
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-3xl font-semibold text-gray-800 dark:text-white">Bank Balance Report</h1>
                <!-- Print Button -->
                <button @click="printReport" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Print Report
                </button>
            </div>

            <!-- Filter Section -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Filter Bank Balance</h2>
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

            <!-- Bank Balance Report Summary -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Summary for {{ filters.monthName }} {{ filters.year }}</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4">
                        <h3 class="text-lg font-semibold">Initial Balance</h3>
                        <p class="text-xl text-green-600 dark:text-green-400">{{ initialBalance | currency }}</p>
                    </div>
                    <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4">
                        <h3 class="text-lg font-semibold">Total Cash In</h3>
                        <p class="text-xl text-green-600 dark:text-green-400">{{ totalCashIn | currency }}</p>
                    </div>
                    <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4">
                        <h3 class="text-lg font-semibold">Total Cash Out</h3>
                        <p class="text-xl text-red-600 dark:text-red-400">{{ totalCashOut | currency }}</p>
                    </div>
                </div>
                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4 mt-4">
                    <h3 class="text-lg font-semibold">Bank Balance</h3>
                    <p class="text-xl text-green-600 dark:text-green-400">{{ bankBalance | currency }}</p>
                </div>
            </div>

            <!-- Bank Balance Report Table -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6" id="print-section">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Monthly Transactions</h2>
                <table class="min-w-full table-auto border-collapse bg-white dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-600 text-gray-700 dark:text-gray-200">
                            <th class="px-6 py-4 text-left border border-gray-200 dark:border-gray-600">Date</th>
                            <th class="px-6 py-4 text-left border border-gray-200 dark:border-gray-600">(+) Balance In </th>
                            <th class="px-6 py-4 text-left border border-gray-200 dark:border-gray-600">(-) Balance Out</th>
                            <th class="px-6 py-4 text-left border border-gray-200 dark:border-gray-600">Bank Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Current Month's Bank Transactions -->
                        <tr v-for="report in monthlyReport" :key="report.date" class="border-b border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <td class="px-6 py-4 text-sm text-gray-800 dark:text-white border border-gray-200 dark:border-gray-600">{{ formatDate(report.date) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800 dark:text-white border border-gray-200 dark:border-gray-600">{{ report.cash_in | currency }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800 dark:text-white border border-gray-200 dark:border-gray-600">{{ report.cash_out | currency }}</td>
                            <td class="px-6 py-4 text-sm text-gray-800 dark:text-white border border-gray-200 dark:border-gray-600">{{ report.bank_balance | currency }}</td>
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
        initialBalance: Number,
        totalCashIn: Number,
        totalCashOut: Number,
        bankBalance: Number,
        monthlyReport: Array,
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
            this.$inertia.get('/admin/reports/bank-balance', {
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
