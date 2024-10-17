<template>
    <AdminLayout>
        <div class="container mx-auto mt-6">
            <h1 class="text-2xl font-bold mb-4">
                Payments for {{ selectedYear }} - {{ monthName }}
            </h1>


            <!-- Loading Spinner -->
            <div v-if="loading" class="mt-4 flex justify-center">
                <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 0116 0A8 8 0 014 12z"></path>
                </svg>
                <span class="ml-2 text-gray-700">Processing...</span>
            </div>

            <!-- Success Message -->
            <div v-if="successMessage" class="mt-4 p-4 bg-green-100 text-green-800 border border-green-300 rounded">
                {{ successMessage }}
            </div>

            <!-- Year and Month Selectors -->
            <div class="mb-4 flex items-center">
                <label for="year" class="mr-2">Select Year:</label>
                <select v-model="selectedYear" @change="fetchStudents" id="year" :disabled="loading"
                    class="border rounded p-2 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200">
                    <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                </select>

                <label for="month" class="ml-4 mr-2">Select Month:</label>
                <select v-model="selectedMonth" @change="fetchStudents" id="month" :disabled="loading"
                    class="border rounded p-2 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200">
                    <option v-for="(month, index) in months" :key="index" :value="index + 1">{{ month }}</option>
                </select>
            </div>

            <!-- Students Table -->
            <table class="min-w-full bg-white border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b">Student Name</th>
                        <th class="py-2 px-4 border-b">Class Name</th>
                        <th class="py-2 px-4 border-b">Roll</th>
                        <th class="py-2 px-4 border-b">Payment Status</th>
                        <th class="py-2 px-4 border-b">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="student in students" :key="student.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="py-2 px-4 border-b dark:text-gray-200">{{ student.name_en }}</td>
                        <td class="py-2 px-4 border-b dark:text-gray-200 text-center">{{ student.school_class.name }}</td>
                        <td class="py-2 px-4 border-b dark:text-gray-200 text-center">{{ student.class_role }}</td>
                        <td class="py-2 px-4 border-b text-center">
                            <span v-if="student.payment_status === 'paid'" class="text-green-600">Paid</span>
                            <span v-else class="text-red-600">Not Paid</span>
                        </td>
                        <td class="py-2 px-4 border-b text-right">
                            <button v-if="student.payment_status !== 'paid'" @click="openPaymentModal(student)"
                                :disabled="loading" aria-label="Pay Now"
                                class="bg-blue-500 text-white rounded px-4 py-2 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-500">
                                Pay Now
                            </button>
                            <button v-if="student.payment_status === 'paid'" @click="openInvoiceModal(student)"
                                class="bg-green-500 text-white rounded px-4 py-2 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-500 ml-2">
                                View Invoice
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>


            <!-- Payment Modal -->
            <div v-if="showModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                    <h2 class="text-xl font-bold mb-4">Make Payment for {{ monthName }} - {{ selectedYear }}</h2>

                    <!-- Payment Method Selection -->
                    <div class="mb-4">
                        <label for="paymentMethod" class="block mb-2">Select Payment Method:</label>
                        <select v-model="form.payment_method" id="paymentMethod"
                            class="border rounded p-2 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 w-full">
                            <option value="bank">Bank Transfer</option>
                            <option value="cash">Cash</option>
                        </select>
                    </div>

                    <!-- File Upload -->
                    <div class="mb-4">
                        <label for="paymentFile" class="block mb-2">Upload Payment Proof (Image/PDF):</label>
                        <input type="file" id="paymentFile" @change="handleFileUpload"
                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer dark:text-gray-400 dark:bg-gray-700 dark:border-gray-600" />
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-end">
                        <button @click="submitPayment"
                            class="bg-blue-500 text-white rounded px-4 py-2 mr-2 hover:bg-blue-600 transition duration-200 ease-in-out">
                            Confirm Payment
                        </button>
                        <button @click="closePaymentModal"
                            class="bg-red-500 text-white rounded px-4 py-2 hover:bg-red-600 transition duration-200 ease-in-out">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>

            <!-- Invoice Modal -->
            <div v-if="showInvoiceModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
                <div class="bg-white rounded-lg w-2/3 p-8 dark:bg-gray-800 dark:text-gray-200 shadow-lg">

                    <!-- Invoice Header with Logo and School Info -->
                    <div class="flex justify-between items-center mb-8">
                        <!-- Logo -->
                        <div class="flex items-center">
                            <img src="https://mbnbd.com/storage/logos/J5PIRszbKiktiSRnfF0InHcD6VWv1Z2gv4EnBySZ.png" alt="School Logo" class="h-16 w-16 object-contain">
                            <div class="ml-4">
                                <h1 class="text-2xl font-bold">School Name</h1>
                                <p class="text-sm text-gray-500 dark:text-gray-300">123 School Street, City, Country</p>
                                <p class="text-sm text-gray-500 dark:text-gray-300">Phone: +123-456-7890</p>
                            </div>
                        </div>

                        <!-- Invoice Details -->
                        <div class="text-right">
                            <h2 class="text-xl font-bold">Invoice</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-300">Date: {{ new Date().toLocaleDateString() }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-300">Invoice #: 001234</p>
                        </div>
                    </div>

                    <!-- Student Details and Payment Info -->
                    <div class="mb-6 border-b pb-4">
                        <h3 class="text-lg font-bold mb-2">Student Information</h3>
                        <p><strong>Name:</strong> {{ selectedStudent?.name_en }}</p>
                        <p><strong>Class:</strong> {{ selectedStudent?.school_class.name }}</p>
                        <p><strong>Roll Number:</strong> {{ selectedStudent?.class_role }}</p>
                    </div>

                    <!-- Payment Summary -->
                    <div class="mb-6">
                        <h3 class="text-lg font-bold mb-2">Payment Details</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p><strong>Payment Status:</strong> {{ selectedStudent?.payment_status }}</p>
                            </div>
                            <div class="text-right">
                                <p><strong>Amount Due:</strong> $100.00</p> <!-- Example amount -->
                            </div>
                        </div>
                    </div>

                    <!-- Footer Notes -->
                    <div class="mb-6">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            Please make your payment by bank transfer or cash at the school office. Contact us for any questions.
                        </p>
                    </div>

                    <!-- Action Buttons: Print and Close -->
                    <div class="mt-6 flex justify-end">
                        <button @click="printInvoice"
                            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition duration-200 ease-in-out">
                            Print Invoice
                        </button>
                        <button @click="closeInvoiceModal"
                            class="ml-4 bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition duration-200 ease-in-out">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script>
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

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
                'July', 'August', 'September', 'October', 'November', 'December',
            ],
            showModal: false,
            showInvoiceModal: false,
            selectedStudent: null,
            loading: false,
            successMessage: '',
            form: useForm({
                student_id: null,
                year: this.selectedYear,
                month: this.selectedMonth,
                payment_method: '',
                payment_proof: null,
            }),
        };
    },
    computed: {
        monthName() {
            return this.months[this.selectedMonth - 1];
        }
    },
    methods: {
        generateYears() {
            const currentYear = new Date().getFullYear();
            const years = [];
            for (let year = currentYear - 100; year <= currentYear; year++) {
                years.push(year);
            }
            return years;
        },
        fetchStudents() {
            this.loading = true;
            this.successMessage = ''; // Reset success message
            this.$inertia.get(this.route('payments.index', { year: this.selectedYear, month: this.selectedMonth }), {
                preserveState: true,
                onFinish: () => {
                    this.loading = false;
                }
            });
        },
        openPaymentModal(student) {
            this.selectedStudent = student;
            this.form.student_id = student.id;
            this.showModal = true;
        },
        closePaymentModal() {
            this.showModal = false;
            this.form.reset();
        },
        openInvoiceModal(student) {
            this.selectedStudent = student;
            this.showInvoiceModal = true;
        },
        closeInvoiceModal() {
            this.showInvoiceModal = false;
        },
        handleFileUpload(event) {
            this.form.payment_proof = event.target.files[0];
        },
        submitPayment() {
            this.form.year = this.selectedYear;
            this.form.month = this.selectedMonth;

            this.loading = true;
            this.successMessage = '';

            this.form.post(this.route('payments.store'), {
                onSuccess: () => {
                    this.successMessage = 'Payment successful! Thank you.';
                    setTimeout(() => {
                        this.successMessage = '';
                    }, 1000);
                },
                onFinish: () => {
                    this.loading = false;
                    this.showModal = false;
                },
            });
        },
        printInvoice() {
            window.print();
        }
    }
};
</script>

<style scoped>
.fixed {
    position: fixed;
}
</style>
