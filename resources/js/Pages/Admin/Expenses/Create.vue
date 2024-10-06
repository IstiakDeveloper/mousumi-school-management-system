
<template>
    <Head title="Create Expense" />

    <AdminLayout>
      <div class="container mx-auto py-8">
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-8">
          <h1 class="text-3xl font-bold mb-6 text-gray-800 dark:text-gray-300">Create Expense</h1>
          <form @submit.prevent="createExpense">
            <div class="mb-4">
              <label for="expense_category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-400">Expense Category</label>
              <select
                v-model="form.expense_category_id"
                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-600 dark:focus:border-blue-600 dark:bg-gray-700 dark:text-white"
                required
              >
                <option value="" disabled>Select Expense Category</option>
                <option v-for="category in expenseCategories" :key="category.id" :value="category.id">{{ category.name }}</option>
              </select>
            </div>

            <div class="mb-4">
              <label for="amount" class="block text-sm font-medium text-gray-700 dark:text-gray-400">Amount</label>
              <input
                type="number"
                v-model="form.amount"
                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-600 dark:focus:border-blue-600 dark:bg-gray-700 dark:text-white"
                required
              />
            </div>

            <div class="mb-4">
              <label for="date" class="block text-sm font-medium text-gray-700 dark:text-gray-400">Date</label>
              <input
                type="date"
                v-model="form.date"
                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-600 dark:focus:border-blue-600 dark:bg-gray-700 dark:text-white"
                required
              />
            </div>

            <div class="mb-4">
              <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-400">Description</label>
              <textarea
                v-model="form.description"
                class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 dark:focus:ring-blue-600 dark:focus:border-blue-600 dark:bg-gray-700 dark:text-white"
                rows="4"
                placeholder="Describe the expense"
              ></textarea>
            </div>

            <div class="flex justify-end">
              <button type="submit" class="btn-primary inline-flex items-center">
                Create Expense
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M10 2a1 1 0 00-1 1v6H3a1 1 0 100 2h6v6a1 1 0 002 0v-6h6a1 1 0 100-2h-6V3a1 1 0 00-1-1z" />
                </svg>
              </button>
              <Link href="/admin/expenses" class="btn-secondary inline-flex items-center ml-4">
                Back to Expenses
              </Link>
            </div>
          </form>
          <div v-if="successMessage" class="mt-4 text-green-600">{{ successMessage }}</div>
          <div v-if="errorMessage" class="mt-4 text-red-600">{{ errorMessage }}</div>
        </div>
      </div>
    </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, Link, Head } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
    expenseCategories: Array,
});

const form = useForm({
    expense_category_id: '',
    amount: '',
    date: '',
    description: '',
});

const successMessage = ref('');
const errorMessage = ref('');

function createExpense() {
    form.post(route('admin.expenses.store'), {
        onSuccess: () => {
            successMessage.value = 'Expense created successfully!';
            errorMessage.value = ''; // Clear any previous error messages
            // Reset the form fields after successful creation
            form.reset();
        },
        onError: () => {
            errorMessage.value = 'Error creating expense. Please try again.';
            successMessage.value = ''; // Clear any previous success messages
        },
    });
}
</script>

<style scoped>
.btn-primary {
    @apply bg-blue-600 text-white font-semibold py-2 px-4 rounded shadow hover:bg-blue-700 transition duration-200;
}

.btn-secondary {
    @apply bg-gray-300 text-gray-800 font-semibold py-2 px-4 rounded shadow hover:bg-gray-400 transition duration-200;
}
</style>
