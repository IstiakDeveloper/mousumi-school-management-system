<template>
    <AdminLayout>
        <div class="container mx-auto mt-6">
            <h1 class="text-2xl font-bold mb-4">
                Salaries for {{ selectedYear }} - {{ monthName }}
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

            <div class="flex justify-between items-center">
                            <!-- Year and Month Selectors -->
                <div class="mb-4 flex items-center">
                    <label for="year" class="mr-2">Select Year:</label>
                    <select v-model="selectedYear" @change="fetchTeachers" id="year" :disabled="loading"
                        class="border rounded p-2 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200">
                        <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                    </select>

                    <label for="month" class="ml-4 mr-2">Select Month:</label>
                    <select v-model="selectedMonth" @change="fetchTeachers" id="month" :disabled="loading"
                        class="border rounded p-2 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200">
                        <option v-for="(month, index) in months" :key="index" :value="index + 1">{{ month }}</option>
                    </select>
                </div>

                <div class="mb-4">
                    <button v-if="teachers.length > 0 && !allPaid" @click="payAllTeachers" :disabled="loading"
                            class="bg-blue-600 text-white rounded px-4 py-2 hover:bg-blue-700 transition duration-200 ease-in-out">
                        Pay All Teachers for {{ monthName }} - {{ selectedYear }}
                    </button>
                </div>

            </div>
                        <!-- Teachers Table -->
            <table class="min-w-full bg-white border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                <thead>
                    <tr>
                        <th class="py-2 px-4 border-b text-left">Teacher Name</th>
                        <th class="py-2 px-4 border-b">Salary Mount (Per month)</th>
                        <th class="py-2 px-4 border-b">Salary Status</th>
                        <th class="py-2 px-4 border-b">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="teacher in teachers" :key="teacher.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                        <td class="py-2 px-4 border-b dark:text-gray-200">{{ teacher.user?.name }}</td>
                        <td class="py-2 px-4 border-b dark:text-gray-200 text-center">{{ teacher.salary_amount }}</td>
                        <td class="py-2 px-4 border-b text-center">
                            <span v-if="teacher.salary_status === 'paid'" class="text-green-600">Paid</span>
                            <span v-else class="text-red-600">Not Paid</span>
                        </td>
                        <td class="py-2 px-4 border-b text-right">
                            <button v-if="teacher.salary_status !== 'paid'" @click="openSalaryModal(teacher)"
                                :disabled="loading" aria-label="Pay Now"
                                class="bg-blue-500 text-white rounded px-4 py-2 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-500">
                                Pay Now
                            </button>
                            <button v-if="teacher.salary_status === 'paid'" @click="openInvoiceModal(teacher)"
                                class="bg-green-500 text-white rounded px-4 py-2 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-500 ml-2">
                                View Invoice
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Salary Payment Modal -->
            <div v-if="showModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
                    <h2 class="text-xl font-bold mb-4">Make Salary Payment for {{ monthName }} - {{ selectedYear }}</h2>

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
                        <button @click="submitSalaryPayment"
                            class="bg-blue-500 text-white rounded px-4 py-2 mr-2 hover:bg-blue-600 transition duration-200 ease-in-out">
                            Confirm Payment
                        </button>
                        <button @click="closeSalaryModal"
                            class="bg-red-500 text-white rounded px-4 py-2 hover:bg-red-600 transition duration-200 ease-in-out">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>

            <!-- Invoice Modal -->
            <div v-if="showInvoiceModal" class="fixed inset-0 bg-black bg-opacity-50 flex justify-center items-center z-50">
                <div class="bg-white rounded-lg w-2/3 p-8 dark:bg-gray-800 dark:text-gray-200 shadow-lg">
                    <!-- Invoice Header with School Info -->
                    <div class="flex justify-between items-center mb-8">
                        <div class="text-left">
                            <h1 class="text-2xl font-bold">Mousumi Biddyaniketon</h1>
                            <p class="text-sm text-gray-500 dark:text-gray-300">Ukilpara, Naogaon Sadar, Naogaon - 6500</p>
                            <p class="text-sm text-gray-500 dark:text-gray-300">Phone: +123-456-7890</p>
                        </div>

                        <div class="text-right">
                            <h2 class="text-xl font-bold">Salary Invoice</h2>
                            <p class="text-sm text-gray-500 dark:text-gray-300">Date: {{ new Date().toLocaleDateString() }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-300">Invoice #: 001234</p>
                        </div>
                    </div>

                    <!-- Teacher Details -->
                    <div class="mb-6 border-b pb-4">
                        <h3 class="text-lg font-bold mb-2">Teacher Information</h3>
                        <p><strong>Name:</strong> {{ selectedTeacher?.name }}</p>
                        <p><strong>Subject:</strong> {{ selectedTeacher?.subject }}</p>
                    </div>

                    <!-- Salary Details -->
                    <div class="mb-6">
                        <h3 class="text-lg font-bold mb-2">Payment Details</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p><strong>Payment Status:</strong> {{ selectedTeacher?.salary_status }}</p>
                            </div>
                            <div class="text-right">
                                <p><strong>Amount Due:</strong> ${{ selectedTeacher?.salary_amount }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
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
        teachers: Array,
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
            selectedTeacher: null,
            loading: false,
            successMessage: '',
            form: useForm({
                teacher_id: null,
                year: this.selectedYear,
                month: this.selectedMonth,
                payment_method: 'bank',
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
        fetchTeachers() {
            this.loading = true;
            this.successMessage = ''; // Reset success message
            this.$inertia.get(this.route('salaries.index', { year: this.selectedYear, month: this.selectedMonth }), {
                preserveState: true,
                onFinish: () => {
                    this.loading = false;
                }
            });
        },
        openSalaryModal(teacher) {
            this.selectedTeacher = teacher;
            this.form.teacher_id = teacher.id;
            this.showModal = true;
        },
        closeSalaryModal() {
            this.showModal = false;
            this.form.reset();
        },
        openInvoiceModal(teacher) {
            this.selectedTeacher = teacher;
            this.showInvoiceModal = true;
        },
        closeInvoiceModal() {
            this.showInvoiceModal = false;
        },
        handleFileUpload(event) {
            this.form.payment_proof = event.target.files[0];
        },
        submitSalaryPayment() {
            this.form.year = this.selectedYear;
            this.form.month = this.selectedMonth;

            this.loading = true;
            this.successMessage = '';

            this.form.post(this.route('salaries.store'), {
                onSuccess: () => {
                    this.successMessage = 'Salary payment successful! Thank you.';
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
        payAllTeachers() {
    this.loading = true;
    this.successMessage = ''; // Reset success message
    this.errorMessage = '';   // Reset error message

    // Track the status for whether all payments are successful
    let allPaid = true;

    // Loop through all teachers and submit the payment
    const paymentPromises = this.teachers.map(teacher => {
        const formData = new FormData();
        formData.append('teacher_id', teacher.id);
        formData.append('year', this.selectedYear);
        formData.append('month', this.selectedMonth);
        formData.append('payment_method', 'bank'); // Assuming 'bank' is the default
        formData.append('payment_proof', this.form.payment_proof); // Optional, add proof if required

        return this.$inertia.post(this.route('teacher_salaries.pay_all'), formData, {
            onSuccess: () => {
                // Mark teacher as paid in the local data
                teacher.salary_status = 'paid';
            },
            onError: () => {
                // If payment fails for any teacher, set `allPaid` to false
                allPaid = false;
            }
        });
    });

    // Wait for all payments to finish (using Promise.all)
    Promise.all(paymentPromises)
        .then(() => {
            // Update allPaid flag based on whether all payments succeeded
            this.allPaid = allPaid;

            // If all payments were successful, disable the button
            if (allPaid) {
                this.loading = false;
                this.successMessage = 'All teachers have been paid!';
                setTimeout(() => {
                    this.successMessage = '';
                }, 1000);
            } else {
                this.loading = false;
                this.errorMessage = 'Payment failed for some teachers.';
                setTimeout(() => {
                    this.errorMessage = '';
                }, 2000);
            }
        })
        .catch(() => {
            this.loading = false;
            this.errorMessage = 'An error occurred while processing payments.';
            setTimeout(() => {
                this.errorMessage = '';
            }, 2000);
        });
},


        printInvoice() {
            window.print();
        }

    },
    watch: {
        selectedYear(newYear) {
            this.fetchTeachers();
        },
        selectedMonth(newMonth) {
            this.fetchTeachers();
        }
    }
};


</script>

<style scoped>
.fixed {
    position: fixed;
}
</style>
