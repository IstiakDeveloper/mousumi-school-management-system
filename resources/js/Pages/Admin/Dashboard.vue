<template>
    <AdminLayout>
        <div class="p-6 bg-white dark:bg-gray-800 rounded-lg shadow-md transition-all duration-300 ease-in-out">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-300 mb-4">Admin Dashboard</h1>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Bank Balance Card -->
                <div class="bg-blue-500 p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-200 ease-in-out flex items-center text-white">
                    <div class="flex-shrink-0">
                        <i class="fas fa-money-bill-wave text-4xl mr-4"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-950">Bank Balance</h2>
                        <p class="text-3xl font-bold">{{ formatCurrency(bankBalance) }}</p>
                    </div>
                </div>

                <!-- Total Expenses Card -->
                <div class="bg-gradient-to-r from-red-500 to-red-700 p-6 rounded-lg shadow-lg hover:shadow-xl transition-shadow duration-200 ease-in-out flex items-center text-white">
                    <div class="flex-shrink-0">
                        <i class="fas fa-file-invoice-dollar text-4xl mr-4"></i>
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold">Total Expenses</h2>
                        <p class="text-3xl font-bold">{{ formatCurrency(totalExpenses) }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-8">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-300 mb-2">Recent Expenses</h2>
                <table class="min-w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg overflow-hidden">
                    <thead>
                        <tr class="bg-gray-100 dark:bg-gray-600">
                            <th class="py-3 px-4 border-b text-gray-600 dark:text-gray-200 text-left">Date</th>
                            <th class="py-3 px-4 border-b text-gray-600 dark:text-gray-200 text-left">Category</th>
                            <th class="py-3 px-4 border-b text-gray-600 dark:text-gray-200 text-left">Amount</th>
                            <th class="py-3 px-4 border-b text-gray-600 dark:text-gray-200 text-left">Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="expense in recentExpenses" :key="expense.id" class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-200">
                            <td class="py-4 px-4 border-b text-gray-800 dark:text-gray-300">{{ formatDate(expense.date) }}</td>
                            <td class="py-4 px-4 border-b text-gray-800 dark:text-gray-300">{{ expense.expense_category.name }}</td>
                            <td class="py-4 px-4 border-b text-gray-800 dark:text-gray-300">{{ formatCurrency(expense.amount) }}</td>
                            <td class="py-4 px-4 border-b text-gray-800 dark:text-gray-300">{{ expense.description || 'N/A' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AdminLayout>
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue'; // Import your Admin Layout component

export default {
    components: {
        AdminLayout,
    },
    props: {
        bankBalance: Number,
        totalExpenses: Number,
        recentExpenses: Array,
    },
    methods: {
        formatCurrency(amount) {
            return new Intl.NumberFormat('en-BD', { // Format for Bangladeshi Taka
                style: 'currency',
                currency: 'BDT',
            }).format(amount);
        },
        formatDate(date) {
            return new Date(date).toLocaleDateString('en-US');
        },
    },
};
</script>

<style scoped>
/* Add any additional styles here if needed */
</style>
