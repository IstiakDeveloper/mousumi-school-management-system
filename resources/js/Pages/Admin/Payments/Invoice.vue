<template>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-8">
      <div class="max-w-4xl mx-auto">
        <!-- Invoice Card -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
          <!-- Invoice Header -->
          <div class="p-8 border-b border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-start">
              <!-- School Info -->
              <div class="flex items-center">
                <img
                  :src="school.logo"
                  alt="School Logo"
                  class="h-16 w-16 object-contain"
                />
                <div class="ml-4">
                  <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                    {{ school.name }}
                  </h1>
                  <p class="text-gray-600 dark:text-gray-400">{{ school.address }}</p>
                  <p class="text-gray-600 dark:text-gray-400">Tel: {{ school.phone }}</p>
                </div>
              </div>

              <!-- Invoice Details -->
              <div class="text-right">
                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">INVOICE</h2>
                <p class="text-gray-600 dark:text-gray-400">#{{ payment.invoice_no }}</p>
                <p class="text-gray-600 dark:text-gray-400">Date: {{ formatDate(payment.date) }}</p>
              </div>
            </div>
          </div>

          <!-- Student Info -->
          <div class="p-8 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Student Information</h3>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <p class="text-gray-600 dark:text-gray-400">Name:</p>
                <p class="font-medium text-gray-900 dark:text-gray-100">{{ payment.student.name }}</p>
              </div>
              <div>
                <p class="text-gray-600 dark:text-gray-400">Student ID:</p>
                <p class="font-medium text-gray-900 dark:text-gray-100">{{ payment.student.id }}</p>
              </div>
              <div>
                <p class="text-gray-600 dark:text-gray-400">Class:</p>
                <p class="font-medium text-gray-900 dark:text-gray-100">{{ payment.student.class }}</p>
              </div>
              <div>
                <p class="text-gray-600 dark:text-gray-400">Section:</p>
                <p class="font-medium text-gray-900 dark:text-gray-100">{{ payment.student.section }}</p>
              </div>
            </div>
          </div>

          <!-- Payment Details -->
          <div class="p-8 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-gray-100">Payment Details</h3>
            <table class="w-full">
              <thead>
                <tr class="border-b border-gray-200 dark:border-gray-700">
                  <th class="text-left py-3 text-gray-600 dark:text-gray-400">Description</th>
                  <th class="text-right py-3 text-gray-600 dark:text-gray-400">Amount</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="py-4 text-gray-900 dark:text-gray-100">
                    Monthly Fee for {{ getMonthName(payment.month) }} {{ payment.year }}
                  </td>
                  <td class="py-4 text-right text-gray-900 dark:text-gray-100">
                    ৳{{ formatAmount(payment.amount) }}
                  </td>
                </tr>
                <tr class="border-t border-gray-200 dark:border-gray-700">
                  <td class="py-4 text-right font-semibold text-gray-900 dark:text-gray-100">Total</td>
                  <td class="py-4 text-right font-semibold text-gray-900 dark:text-gray-100">
                    ৳{{ formatAmount(payment.amount) }}
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Payment Method -->
          <div class="p-8 border-b border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-2 gap-4">
              <div>
                <p class="text-gray-600 dark:text-gray-400">Payment Method:</p>
                <p class="font-medium text-gray-900 dark:text-gray-100">
                  {{ formatPaymentMethod(payment.payment_method) }}
                </p>
              </div>
              <div>
                <p class="text-gray-600 dark:text-gray-400">Status:</p>
                <span
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                  :class="getStatusClasses(payment.status)"
                >
                  {{ payment.status.toUpperCase() }}
                </span>
              </div>
            </div>
          </div>

          <!-- Footer -->
          <div class="p-8 text-center text-gray-600 dark:text-gray-400">
            <p>This is a computer generated invoice. No signature is required.</p>
          </div>
        </div>

        <!-- Print Button -->
        <div class="mt-6 flex justify-end print:hidden">
          <button
            @click="printInvoice"
            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
          >
            <PrinterIcon class="h-5 w-5 mr-2" />
            Print Invoice
          </button>
        </div>
      </div>
    </div>
  </template>

  <script setup>
  import { PrinterIcon } from '@heroicons/vue/24/outline';
  import { format } from 'date-fns';

  const props = defineProps({
    payment: {
      type: Object,
      required: true
    },
    school: {
      type: Object,
      required: true
    }
  });

  const formatDate = (date) => format(new Date(date), 'MMMM dd, yyyy');

  const getMonthName = (month) => {
    const months = [
      'January', 'February', 'March', 'April', 'May', 'June',
      'July', 'August', 'September', 'October', 'November', 'December'
    ];
    return months[month - 1];
  };

  const formatAmount = (amount) => {
    return Number(amount).toLocaleString('en-IN', {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    });
  };

  const formatPaymentMethod = (method) => {
    const methods = {
      cash: 'Cash',
      bank: 'Bank Transfer',
      mobile_banking: 'Mobile Banking'
    };
    return methods[method] || method;
  };

  const getStatusClasses = (status) => {
    const classes = {
      paid: 'bg-green-100 text-green-800',
      pending: 'bg-yellow-100 text-yellow-800',
      failed: 'bg-red-100 text-red-800'
    };
    return classes[status.toLowerCase()] || '';
  };

  const printInvoice = () => {
    window.print();
  };
  </script>

  <style scoped>
  @media print {
    body * {
      visibility: hidden;
    }
    .invoice, .invoice * {
      visibility: visible;
    }
    .invoice {
      position: absolute;
      left: 0;
      top: 0;
      width: 100%;
    }
    @page {
      size: A4;
      margin: 2cm;
    }
  }
  </style>
