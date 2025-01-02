<template>

    <Head title="Student Payments" />

    <AdminLayout>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Header Section -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        Student Payments
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Manage and track student fee payments
                    </p>
                </div>
                <div class="print:hidden">
                    <button @click="printReport" class="btn-secondary mr-2">
                        <PrinterIcon class="w-4 h-4 mr-2" />
                        Print Report
                    </button>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <StatCard title="Total Students" :value="stats.total_students" icon="UserGroupIcon" color="blue" />
                <StatCard title="Paid Students" :value="stats.paid_count"
                    :percentage="(stats.paid_count / stats.total_students) * 100" icon="CheckCircleIcon"
                    color="green" />
                <StatCard title="Total Collected" :value="formatCurrency(stats.total_amount)" icon="CurrencyDollarIcon"
                    color="indigo" />
                <StatCard title="Pending Amount" :value="formatCurrency(stats.pending_amount)"
                    icon="ExclamationCircleIcon" color="red" />
            </div>


            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-4">
                        <div>
                            <label class="form-label">Select Year</label>
                            <select v-model="filters.year" class="form-select">
                                <option v-for="y in yearRange" :key="y" :value="y">
                                    {{ y }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="form-label">Select Month</label>
                            <select v-model="filters.month" class="form-select">
                                <option v-for="(name, index) in months" :key="index" :value="index + 1">
                                    {{ name }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="form-label">Payment Status</label>
                            <select v-model="filters.status" class="form-select">
                                <option value="">All Status</option>
                                <option value="paid">Paid</option>
                                <option value="not_paid">Not Paid</option>
                            </select>
                        </div>

                        <div>
                            <label class="form-label">Class</label>
                            <select v-model="filters.class_id" class="form-select">
                                <option value="">All Classes</option>
                                <option v-for="c in classes" :key="c.id" :value="c.id">
                                    {{ c.name }}
                                </option>
                            </select>
                        </div>

                        <div>
                            <label class="form-label">Search</label>
                            <div class="relative">
                                <input v-model="filters.search" type="text" placeholder="Search student..."
                                    class="form-input pl-10" />
                                <SearchIcon class="w-5 h-5 absolute left-3 top-3 text-gray-400" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Students Table -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <TableHeader>Student</TableHeader>
                                <TableHeader>Class/Section</TableHeader>
                                <TableHeader>Monthly Fee</TableHeader>
                                <TableHeader>Payment Status</TableHeader>
                                <TableHeader>Payment Date</TableHeader>
                                <TableHeader class="text-right">Actions</TableHeader>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <tr v-for="student in filteredStudents" :key="student.id"
                                class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img :src="student.photo || '/placeholder.png'"
                                                class="h-10 w-10 rounded-full" />
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ student.name_en }}
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                ID: {{ student.student_id }}
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 dark:text-gray-100">
                                        {{ student.school_class?.name }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        Section: {{ student.section?.name }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        ৳{{ formatCurrency(student.monthly_fee) }}
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <PaymentStatus :status="student.payment_details.status"
                                        :method="student.payment_details.method" />
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ formatDate(student.payment_details.date) }}
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <div class="flex justify-end space-x-2">
                                        <template v-if="student.payment_details.status === 'paid'">
                                            <button @click="viewInvoice(student)" class="btn-icon" title="View Invoice">
                                                <DocumentTextIcon class="w-4 h-4" />
                                            </button>
                                            <button @click="downloadReceipt(student)" class="btn-icon"
                                                title="Download Receipt">
                                                <DownloadIcon class="w-4 h-4" />
                                            </button>
                                        </template>
                                        <button v-else @click="openPaymentModal(student)" class="btn-primary-sm">
                                            Record Payment
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    <Pagination :total="totalPages" v-model="currentPage" />
                </div>
            </div>

            <!-- Payment Modal -->
            <PaymentModal v-if="showPaymentModal" :student="selectedStudent" :year="filters.year" :month="filters.month"
                @close="closePaymentModal" @payment-recorded="handlePaymentRecorded" />
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, computed, watch } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import debounce from 'lodash/debounce';
import { format } from 'date-fns';
import {
    MagnifyingGlassIcon as SearchIcon,
    PrinterIcon,
    DocumentTextIcon,
    ArrowDownTrayIcon as DownloadIcon,
    UsersIcon,
    CheckCircleIcon,
    BanknotesIcon as CurrencyDollarIcon,
    ExclamationCircleIcon
} from '@heroicons/vue/24/outline';

import AdminLayout from '@/Layouts/AdminLayout.vue';
import StatCard from '@/Components/StatCard.vue';
import TableHeader from '@/Components/TableHeader.vue';
import PaymentStatus from '@/Components/PaymentStatus.vue';
import PaymentModal from '@/Components/Payments/PaymentModal.vue';
import Pagination from '@/Components/Pagination.vue';
import { useToast } from "vue-toastification";


const props = defineProps({
    students: Array,
    stats: Object,
    year: Number,
    month: Number,
    filters: Object
});

// State
const showPaymentModal = ref(false);
const selectedStudent = ref(null);
const currentPage = ref(1);
const itemsPerPage = 10;
const toast = useToast();

const filters = ref({
    year: props.year,
    month: props.month,
    status: '',
    class_id: '',
    search: '',
});

// Constants
const months = [
    'January', 'February', 'March', 'April', 'May', 'June',
    'July', 'August', 'September', 'October', 'November', 'December'
];

const yearRange = computed(() => {
    const currentYear = new Date().getFullYear();
    return Array.from({ length: 5 }, (_, i) => currentYear - 2 + i);
});

// Computed
const filteredStudents = computed(() => {
    let filtered = props.students;

    if (filters.value.status) {
        filtered = filtered.filter(s => s.payment_details.status === filters.value.status);
    }

    if (filters.value.class_id) {
        filtered = filtered.filter(s => s.school_class?.id === filters.value.class_id);
    }

    if (filters.value.search) {
        const search = filters.value.search.toLowerCase();
        filtered = filtered.filter(s =>
            s.name_en.toLowerCase().includes(search) ||
            s.student_id.toLowerCase().includes(search)
        );
    }

    return filtered;
});

const totalPages = computed(() =>
    Math.ceil(filteredStudents.value.length / itemsPerPage)
);

// Methods
const formatCurrency = (value) => {
    return Number(value).toLocaleString('en-IN');
};

const formatDate = (date) => {
    return date ? format(new Date(date), 'MMM dd, yyyy HH:mm') : '-';
};

const handleSuccess = () => {
    toast.success("Operation completed successfully!");
};
const handleError = () => {
    toast.error("Something went wrong!");
};
const handleInfo = () => {
    toast.info("Here's some information.");
};

const handleWarning = () => {
    toast.warning("Warning message!");
};


const openPaymentModal = (student) => {
    selectedStudent.value = student;
    showPaymentModal.value = true;
};

const handlePaymentRecorded = () => {
    showPaymentModal.value = false;
    router.reload();
};

const viewInvoice = (student) => {
    if (student.payment_details.status === 'paid') {
        window.open(route('admin.payments.invoice', { payment: student.payment_id }));
    }
};

const downloadReceipt = (student) => {
    if (student.payment_details.status === 'paid') {
        window.location.href = route('admin.payments.download-receipt', { payment: student.payment_id });
    }
};

const printReport = () => {
    window.print();
};

// Watch filters for changes
watch(filters, debounce((value) => {
    router.get(
        route('payments.index'),
        value,
        {
            preserveState: true,
            preserveScroll: true,
            replace: true
        }
    );
}, 300), { deep: true });
</script>

<style scoped>
.form-label {
    @apply block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1;
}

.form-input {
    @apply block w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-200;
}

.form-select {
    @apply block w-full border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-200;
}

.btn-primary-sm {
    @apply inline-flex items-center px-3 py-1.5 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500;
}

.btn-secondary {
    @apply inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:bg-gray-800 dark:text-gray-200 dark:border-gray-600 dark:hover:bg-gray-700;
}

.btn-icon {
    @apply p-1.5 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500;
}

@media print {
    .print\:hidden {
        display: none;
    }
}
</style>
