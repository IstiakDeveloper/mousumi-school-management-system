<!-- Components/PaymentStatus.vue -->
<template>
    <div class="flex items-center">
      <span
        class="px-2 py-1 text-xs font-semibold rounded-full flex items-center"
        :class="statusClasses"
      >
        <component :is="statusIcon" class="w-3 h-3 mr-1" />
        {{ statusText }}
      </span>
      <span
        v-if="method"
        class="ml-2 text-xs text-gray-500 dark:text-gray-400"
      >
        via {{ formatPaymentMethod }}
      </span>
    </div>
   </template>

   <script setup>
   import { computed } from 'vue';
   import {
    CheckIcon,
    ClockIcon,
    XMarkIcon,
    BanknotesIcon,
    CreditCardIcon
   } from '@heroicons/vue/24/solid';

   const props = defineProps({
    status: {
      type: String,
      required: true
    },
    method: {
      type: String,
      default: null
    }
   });

   const statusClasses = computed(() => ({
    paid: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
    pending: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
    not_paid: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
    overdue: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
   }[props.status] || 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'));

   const statusIcon = computed(() => ({
    paid: CheckIcon,
    pending: ClockIcon,
    not_paid: XMarkIcon,
    overdue: XMarkIcon
   }[props.status] || XMarkIcon));

   const statusText = computed(() => ({
    paid: 'Paid',
    pending: 'Pending',
    not_paid: 'Not Paid',
    overdue: 'Overdue'
   }[props.status] || 'Not Paid'));

   const formatPaymentMethod = computed(() => ({
    cash: 'Cash',
    bank: 'Bank Transfer',
    mobile_banking: 'Mobile Banking'
   }[props.method] || props.method));
   </script>
