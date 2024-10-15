<template>
    <AdminLayout>
      <div class="container mx-auto mt-6">
        <h1 class="text-2xl font-bold mb-4">
          Payments for {{ selectedYear }} - {{ monthName }}
        </h1>

        <!-- Year and Month Selectors -->
        <div class="mb-4 flex items-center">
          <label for="year" class="mr-2">Select Year:</label>
          <select
            v-model="selectedYear"
            @change="fetchStudents"
            id="year"
            :disabled="loading"
            class="border rounded p-2 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200"
          >
            <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
          </select>

          <label for="month" class="ml-4 mr-2">Select Month:</label>
          <select
            v-model="selectedMonth"
            @change="fetchStudents"
            id="month"
            :disabled="loading"
            class="border rounded p-2 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200"
          >
            <option v-for="(month, index) in months" :key="index" :value="index + 1">{{ index + 1 }}</option>
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
                    <!-- Pay Now button, visible only if payment status is not 'paid' -->
                    <button
                    v-if="student.payment_status !== 'paid'"
                    @click="openPaymentModal(student)"
                    :disabled="loading"
                    aria-label="Pay Now"
                    class="bg-blue-500 text-white rounded px-4 py-2 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-500"
                    >
                    Pay Now
                    </button>

                    <!-- View Details button, visible only if payment status is 'paid' -->
                    <button
                    v-if="student.payment_status === 'paid'"
                    @click="openPaymentDetailsModal(student.payment_details)"
                    class="bg-green-500 text-white rounded px-4 py-2 hover:bg-green-600 dark:bg-green-600 dark:hover:bg-green-500 ml-2"
                    >
                    View Details
                    </button>
                </td>
                </tr>
            </tbody>
            </table>

        <!-- Loading Spinner -->
        <div v-if="loading" class="mt-4 text-center">Loading...</div>

        <!-- Payment Modal -->
        <div v-if="showModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center">
          <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-bold mb-4">Make Payment</h2>
            <h2 class="text-xl font-bold mb-4">Make Payment for {{ monthName }} - {{ selectedYear }}</h2>

            <!-- Hidden Fields for Year and Month -->
            <input type="hidden" v-model="form.year" />
            <input type="hidden" v-model="form.month" />

            <!-- Payment Method Selection -->
            <div class="mb-4">
              <label for="paymentMethod" class="block mb-2">Select Payment Method:</label>
              <select
                v-model="form.payment_method"
                id="paymentMethod"
                class="border rounded p-2 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 w-full"
              >
                <option value="bank">Bank Transfer</option>
                <option value="cash">Cash</option>
              </select>
            </div>

            <!-- File Upload -->
            <div class="mb-4">
              <label for="paymentFile" class="block mb-2">Upload Payment Proof (Image/PDF):</label>
              <input
                type="file"
                id="paymentFile"
                @change="handleFileUpload"
                class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer dark:text-gray-400 dark:bg-gray-700 dark:border-gray-600"
              />
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end">
              <button
                @click="submitPayment"
                class="bg-blue-500 text-white rounded px-4 py-2 mr-2 hover:bg-blue-600"
              >
                Confirm Payment
              </button>
              <button
                @click="closePaymentModal"
                class="bg-red-500 text-white rounded px-4 py-2 hover:bg-red-600"
              >
                Cancel
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
        showModal: false, // For modal visibility
        selectedStudent: null, // Currently selected student for payment
        loading: false,
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
      },
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
        this.$inertia.get(this.route('payments.index'), {
          year: this.selectedYear,
          month: this.selectedMonth,
        }, {
          onFinish: () => {
            this.loading = false;
          },
        });
      },
      openPaymentModal(student) {
        this.selectedStudent = student;
        this.form.student_id = student.id;
        this.showModal = true;
      },
      closePaymentModal() {
        this.showModal = false;
        this.form.reset(); // Reset form on modal close
        this.form.payment_proof = null; // Reset payment proof
      },
      handleFileUpload(event) {
        this.form.payment_proof = event.target.files[0]; // Assign the uploaded file to form
      },
      submitPayment() {
        this.loading = true;

        this.form.year = this.selectedYear; // Ensure the selected year is set
        this.form.month = this.selectedMonth; // Ensure the selected month is set

        console.log('Submitting Payment:', this.form); // Log the form data
        this.form.post(this.route('payments.store'), {
          onSuccess: () => {
            this.closePaymentModal();
            this.loading = false;
            this.$inertia.visit(this.route('payments.index'), {
              preserveState: true,
            });
          },
          onError: () => {
            this.loading = false;
          },
          onFinish: () => {
            this.loading = false;
          },
        });
      },
    },
  };
  </script>

  <style scoped>
  /* Modal Styles */
  .fixed {
    position: fixed;
  }
  </style>
