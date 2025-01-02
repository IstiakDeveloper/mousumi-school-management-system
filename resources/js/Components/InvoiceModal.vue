<!-- components/InvoiceModal.vue -->
<template>
    <TransitionRoot appear :show="modelValue" as="template">
        <Dialog as="div" class="relative z-50" @close="$emit('update:modelValue', false)">
            <TransitionChild
                enter="duration-300 ease-out"
                enter-from="opacity-0"
                enter-to="opacity-100"
                leave="duration-200 ease-in"
                leave-from="opacity-100"
                leave-to="opacity-0"
            >
                <div class="fixed inset-0 bg-black/25" />
            </TransitionChild>

            <div class="fixed inset-0 overflow-y-auto">
                <div class="flex min-h-full items-center justify-center p-4">
                    <TransitionChild
                        enter="duration-300 ease-out"
                        enter-from="opacity-0 scale-95"
                        enter-to="opacity-100 scale-100"
                        leave="duration-200 ease-in"
                        leave-from="opacity-100 scale-100"
                        leave-to="opacity-0 scale-95"
                    >
                        <DialogPanel class="w-full max-w-2xl transform bg-white dark:bg-gray-800 p-6 text-left shadow-xl transition-all rounded-xl">
                            <!-- Invoice Header -->
                            <div class="flex justify-between items-start mb-8 border-b pb-4">
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Salary Invoice</h2>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        Invoice #: {{ generateInvoiceNumber() }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Date: {{ formatDate(new Date()) }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">
                                        Period: {{ monthName }} {{ year }}
                                    </p>
                                </div>
                            </div>

                            <!-- Teacher Details -->
                            <div class="mb-6">
                                <h3 class="text-lg font-semibold mb-2 text-gray-900 dark:text-white">Teacher Information</h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            <span class="font-medium">Name:</span> {{ teacher?.user?.name }}
                                        </p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            <span class="font-medium">ID:</span> {{ teacher?.id }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            <span class="font-medium">Status:</span>
                                            <span class="text-green-600 dark:text-green-400">Paid</span>
                                        </p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            <span class="font-medium">Payment Date:</span>
                                            {{ formatDate(teacher?.salary_details?.created_at) }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Details -->
                            <div class="mb-8">
                                <h3 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Payment Details</h3>
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Salary Amount</span>
                                        <span class="text-sm text-gray-900 dark:text-white">{{ formatCurrency(teacher?.salary_amount) }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex justify-end space-x-3">
                                <button
                                    type="button"
                                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600"
                                    @click="$emit('update:modelValue', false)"
                                >
                                    Close
                                </button>
                                <button
                                    type="button"
                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                    @click="printInvoice"
                                >
                                    Print Invoice
                                </button>
                            </div>
                        </DialogPanel>
                    </TransitionChild>
                </div>
            </div>
        </Dialog>
    </TransitionRoot>
</template>

<script setup>
import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from '@headlessui/vue';

const props = defineProps({
    modelValue: Boolean,
    teacher: Object,
    monthName: String,
    year: Number
});

defineEmits(['update:modelValue']);

const generateInvoiceNumber = () => {
    return `INV-${new Date().getFullYear()}${String(props.teacher?.id).padStart(4, '0')}`;
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    });
};

const formatCurrency = (amount) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(amount);
};

const printInvoice = () => {
    window.print();
};
</script>
