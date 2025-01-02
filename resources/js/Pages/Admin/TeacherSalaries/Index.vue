<template>
    <AdminLayout>
        <div class="container mx-auto mt-6 px-4">
            <!-- Header Section -->
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
                    Salaries for {{ monthName }} - {{ selectedYear }}
                </h1>
            </div>

            <!-- Controls Section -->
            <div
                class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
                <div class="flex flex-wrap items-center gap-4">
                    <div class="flex items-center">
                        <label for="year"
                            class="mr-2 text-sm font-medium text-gray-700 dark:text-gray-300">Year:</label>
                        <select v-model="form.year" @change="handleFilter" id="year" :disabled="form.processing"
                            class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                            <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                        </select>
                    </div>

                    <div class="flex items-center">
                        <label for="month"
                            class="mr-2 text-sm font-medium text-gray-700 dark:text-gray-300">Month:</label>
                        <select v-model="form.month" @change="handleFilter" id="month" :disabled="form.processing"
                            class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-200">
                            <option v-for="(month, index) in months" :key="index" :value="index + 1">
                                {{ month }}
                            </option>
                        </select>
                    </div>

                    <!-- Loading indicator -->
                    <div v-if="form.processing" class="text-sm text-gray-500">
                        <span class="inline-flex items-center">
                            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-gray-500"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor"
                                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                                </path>
                            </svg>
                            Loading...
                        </span>
                    </div>
                </div>

                <!-- Pay All Button -->
                <button v-if="unpaidTeachersExist" @click="confirmPayAll" :disabled="loading"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    <span v-if="!loading">Pay All Teachers</span>
                    <span v-else class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4">
                            </circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                        Processing...
                    </span>
                </button>
            </div>

            <!-- Alert Messages -->
            <TransitionRoot v-if="successMessage" enter="transform ease-out duration-300 transition"
                enterFrom="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                enterTo="translate-y-0 opacity-100 sm:translate-x-0" leave="transition ease-in duration-100"
                leaveFrom="opacity-100" leaveTo="opacity-0" as="template">
                <div class="rounded-md bg-green-50 p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <CheckCircleIcon class="h-5 w-5 text-green-400" aria-hidden="true" />
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ successMessage }}</p>
                        </div>
                    </div>
                </div>
            </TransitionRoot>

            <!-- Teachers Table -->
            <div class="overflow-x-auto bg-white dark:bg-gray-800 shadow rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Teacher Name
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Monthly Salary
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-for="teacher in teachers" :key="teacher.id"
                            class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td
                                class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-200">
                                {{ teacher.user?.name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                {{ formatCurrency(teacher.salary_amount) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="[
                                    'px-2 inline-flex text-xs leading-5 font-semibold rounded-full',
                                    teacher.salary_status === 'paid'
                                        ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100'
                                        : 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100'
                                ]">
                                    {{ teacher.salary_status === 'paid' ? 'Paid' : 'Unpaid' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button v-if="teacher.salary_status !== 'paid'" @click="openPaymentModal(teacher)"
                                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                    Pay Now
                                </button>
                                <button v-else @click="openInvoiceModal(teacher)"
                                    class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 ml-4">
                                    View Invoice
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Payment Confirmation Modal -->
            <PaymentConfirmationModal v-if="showPaymentModal" v-model="showPaymentModal" :teacher="selectedTeacher"
                :selected-year="selectedYear" :selected-month="selectedMonth" :month-name="monthName"
                @payment-success="handlePaymentSuccess" />

            <InvoiceModal v-if="showInvoiceModal" v-model="showInvoiceModal" :teacher="selectedTeacher"
                :month-name="monthName" :year="selectedYear" />

        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { CheckCircleIcon } from '@heroicons/vue/24/outline';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import PaymentConfirmationModal from '@/Components/PaymentConfirmationModal.vue';
import InvoiceModal from '@/Components/InvoiceModal.vue';
import { TransitionRoot } from '@headlessui/vue';

const props = defineProps({
    teachers: {
        type: Array,
        default: () => [] // Provide default empty array
    },
    year: {
        type: Number,
        required: true
    },
    month: {
        type: Number,
        required: true
    }
});

// States
const showPaymentModal = ref(false);
const showInvoiceModal = ref(false);
const selectedTeacher = ref(null);
const successMessage = ref('');
const loading = ref(false);

// Form
const form = useForm({
    year: props.year,
    month: props.month
});

// Constants
const months = [
    'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December'
];

// Computed Properties
const years = computed(() => {
    const currentYear = new Date().getFullYear();
    return Array.from({ length: 5 }, (_, i) => currentYear - i);
});

const monthName = computed(() => months[form.month - 1]);
const selectedYear = computed(() => form.year);
const selectedMonth = computed(() => form.month);

const unpaidTeachersExist = computed(() => {
    return props.teachers?.some(teacher => teacher.salary_status !== 'paid') ?? false;
});

// Methods
const handleFilter = () => {
    form.get(route('salaries.index'), {
        preserveState: true,
        preserveScroll: true,
        onStart: () => loading.value = true,
        onFinish: () => loading.value = false
    });
};

const openPaymentModal = (teacher) => {
    selectedTeacher.value = teacher;
    showPaymentModal.value = true;
};

const openInvoiceModal = (teacher) => {
    selectedTeacher.value = teacher;
    showInvoiceModal.value = true;
};

const handlePaymentSuccess = () => {
    successMessage.value = 'Payment processed successfully!';
    setTimeout(() => {
        successMessage.value = '';
    }, 3000);
    handleFilter();
};

const confirmPayAll = () => {
    if (confirm('Are you sure you want to process payment for all unpaid teachers?')) {
        loading.value = true;
        form.post(route('teacher_salaries.pay_all'), {
            onSuccess: () => {
                successMessage.value = 'All payments processed successfully!';
                setTimeout(() => {
                    successMessage.value = '';
                }, 3000);
            },
            onFinish: () => {
                loading.value = false;
            }
        });
    }
};

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(amount);
};
</script>
