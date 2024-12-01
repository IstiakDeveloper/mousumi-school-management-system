<script setup>
import { ref, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const props = defineProps({
    monthlyReports: {
        type: Array,
        required: true
    },
    selectedMonthReport: {
        type: Object,
        required: true
    },
    availableYears: {
        type: Array,
        required: true
    },
    selectedYear: {
        type: Number,
        required: true
    },
    selectedMonth: {
        type: Number,
        required: true
    },
    statistics: {
        type: Object,
        required: true
    }
})

const selectedYear = ref(props.selectedYear)
const selectedMonth = ref(props.selectedMonth)

watch([selectedYear, selectedMonth], ([newYear, newMonth]) => {
    router.get(route('admin.reports.funds'), {
        year: newYear,
        month: newMonth
    }, {
        preserveState: true,
        preserveScroll: true,
    })
}, { immediate: true })

const formatAmount = (amount) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'BDT',
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    }).format(amount || 0)
}

const formatDate = (dateString) => {
    if (!dateString) return '-'
    const date = new Date(dateString)
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    })
}
</script>

<template>
    <AdminLayout>
        <div class="p-6 dark:bg-gray-800">
            <!-- Header Section -->
            <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <h1 class="text-2xl font-bold dark:text-white">Fund Management Report</h1>
                <div class="flex gap-4">
                    <select
                        v-model="selectedYear"
                        class="rounded-lg border border-gray-300 px-3 py-2 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                    >
                        <option v-for="year in availableYears" :key="year" :value="year">
                            {{ year }}
                        </option>
                    </select>
                    <select
                        v-model="selectedMonth"
                        class="rounded-lg border border-gray-300 px-3 py-2 bg-white dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200"
                    >
                        <option v-for="month in 12" :key="month" :value="month">
                            {{ new Date(2000, month - 1).toLocaleString('default', { month: 'long' }) }}
                        </option>
                    </select>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <!-- Opening Balance -->
                <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-4">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="text-sm font-medium text-gray-600 dark:text-gray-300">Opening Balance</h3>
                        <i class="fas fa-wallet text-blue-500"></i>
                    </div>
                    <div class="text-2xl font-bold dark:text-white">
                        {{ formatAmount(selectedMonthReport?.opening_balance) }}
                    </div>
                </div>

                <!-- Income -->
                <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-4">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="text-sm font-medium text-gray-600 dark:text-gray-300">Total Income</h3>
                        <i class="fas fa-arrow-trend-up text-green-500"></i>
                    </div>
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                        {{ formatAmount(selectedMonthReport?.total_income) }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        <span class="text-green-500">↑</span> {{ formatAmount(statistics?.yearlyIncome) }} this year
                    </div>
                </div>

                <!-- Expense -->
                <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-4">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="text-sm font-medium text-gray-600 dark:text-gray-300">Total Expense</h3>
                        <i class="fas fa-arrow-trend-down text-red-500"></i>
                    </div>
                    <div class="text-2xl font-bold text-red-600 dark:text-red-400">
                        {{ formatAmount(selectedMonthReport?.total_expense) }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        <span class="text-red-500">↓</span> {{ formatAmount(statistics?.yearlyExpense) }} this year
                    </div>
                </div>

                <!-- Net Balance -->
                <div class="bg-white dark:bg-gray-700 rounded-lg shadow p-4">
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="text-sm font-medium text-gray-600 dark:text-gray-300">Closing Balance</h3>
                        <i class="fas fa-scale-balanced text-purple-500"></i>
                    </div>
                    <div class="text-2xl font-bold dark:text-white">
                        {{ formatAmount(selectedMonthReport?.closing_balance) }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        {{ statistics?.totalTransactions }} transactions this year
                    </div>
                </div>
            </div>

            <!-- Yearly Overview -->
            <div class="bg-white dark:bg-gray-700 rounded-lg shadow mb-6">
                <div class="p-4 border-b border-gray-200 dark:border-gray-600">
                    <h2 class="text-lg font-semibold dark:text-white">Yearly Overview {{ selectedYear }}</h2>
                </div>
                <div class="p-4">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-600">
                                    <th class="text-left py-3 px-4 dark:text-gray-200">Month</th>
                                    <th class="text-right py-3 px-4 dark:text-gray-200">Opening Balance</th>
                                    <th class="text-right py-3 px-4 dark:text-gray-200">Income</th>
                                    <th class="text-right py-3 px-4 dark:text-gray-200">Expense</th>
                                    <th class="text-right py-3 px-4 dark:text-gray-200">Net Flow</th>
                                    <th class="text-right py-3 px-4 dark:text-gray-200">Closing Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="report in monthlyReports"
                                    :key="report.month"
                                    :class="[
                                        'border-b border-gray-200 dark:border-gray-600',
                                        'hover:bg-gray-50 dark:hover:bg-gray-600',
                                        report.month === selectedMonth ? 'bg-blue-50 dark:bg-blue-900/30' : ''
                                    ]"
                                >
                                    <td class="py-3 px-4 dark:text-gray-200">{{ report.month_name }}</td>
                                    <td class="text-right py-3 px-4 dark:text-gray-200">
                                        {{ formatAmount(report.opening_balance) }}
                                    </td>
                                    <td class="text-right py-3 px-4 text-green-600 dark:text-green-400">
                                        {{ formatAmount(report.total_income) }}
                                    </td>
                                    <td class="text-right py-3 px-4 text-red-600 dark:text-red-400">
                                        {{ formatAmount(report.total_expense) }}
                                    </td>
                                    <td class="text-right py-3 px-4"
                                        :class="report.net_flow >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">
                                        {{ formatAmount(report.net_flow) }}
                                    </td>
                                    <td class="text-right py-3 px-4 font-medium dark:text-gray-200">
                                        {{ formatAmount(report.closing_balance) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Monthly Transactions -->
            <div class="bg-white dark:bg-gray-700 rounded-lg shadow">
                <div class="p-4 border-b border-gray-200 dark:border-gray-600 flex justify-between items-center">
                    <h2 class="text-lg font-semibold dark:text-white">Monthly Transactions</h2>
                    <button
                        @click="$inertia.get(route('admin.reports.funds.export'), { year: selectedYear, month: selectedMonth })"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-600 border border-gray-300 dark:border-gray-500 rounded-md hover:bg-gray-50 dark:hover:bg-gray-500 transition-colors duration-150"
                    >
                        <i class="fas fa-download"></i>
                        Export
                    </button>
                </div>
                <div class="p-4">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-gray-200 dark:border-gray-600">
                                    <th class="text-left py-3 px-4 dark:text-gray-200">Date</th>
                                    <th class="text-left py-3 px-4 dark:text-gray-200">Description</th>
                                    <th class="text-right py-3 px-4 dark:text-gray-200">Income</th>
                                    <th class="text-right py-3 px-4 dark:text-gray-200">Expense</th>
                                    <th class="text-right py-3 px-4 dark:text-gray-200">Balance</th>
                                    <th class="text-right py-3 px-4 dark:text-gray-200">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-if="selectedMonthReport.transactions?.length === 0">
                                    <td colspan="6" class="py-8 text-center text-gray-500 dark:text-gray-400">
                                        <i class="fas fa-inbox text-4xl mb-3 block"></i>
                                        <p>No transactions found for this month</p>
                                    </td>
                                </tr>
                                <tr v-else v-for="transaction in selectedMonthReport.transactions"
                                    :key="transaction.id"
                                    class="border-b border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600"
                                >
                                    <td class="py-3 px-4 dark:text-gray-200">{{ formatDate(transaction.date) }}</td>
                                    <td class="py-3 px-4 dark:text-gray-200">{{ transaction.description }}</td>
                                    <td class="text-right py-3 px-4 text-green-600 dark:text-green-400">
                                        {{ transaction.type === 'in' ? formatAmount(transaction.amount) : '-' }}
                                    </td>
                                    <td class="text-right py-3 px-4 text-red-600 dark:text-red-400">
                                        {{ transaction.type === 'out' ? formatAmount(transaction.amount) : '-' }}
                                    </td>
                                    <td class="text-right py-3 px-4 font-medium dark:text-gray-200">
                                        {{ formatAmount(transaction.running_balance) }}
                                    </td>
                                    <td class="text-right py-3 px-4 dark:text-gray-200">
                                        <button
                                            class="text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 mr-2"
                                            title="Edit"
                                        >
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button
                                            class="text-gray-600 dark:text-gray-300 hover:text-red-600 dark:hover:text-red-400"
                                            title="Delete"
                                        >
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <!-- Table Footer with Totals -->
                            <tfoot v-if="selectedMonthReport.transactions?.length" class="bg-gray-50 dark:bg-gray-600">
                                <tr>
                                    <td colspan="2" class="py-3 px-4 font-medium dark:text-gray-200">Totals</td>
                                    <td class="text-right py-3 px-4 font-medium text-green-600 dark:text-green-400">
                                        {{ formatAmount(selectedMonthReport.total_income) }}
                                    </td>
                                    <td class="text-right py-3 px-4 font-medium text-red-600 dark:text-red-400">
                                        {{ formatAmount(selectedMonthReport.total_expense) }}
                                    </td>
                                    <td class="text-right py-3 px-4 font-medium dark:text-gray-200">
                                        {{ formatAmount(selectedMonthReport.closing_balance) }}
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>
