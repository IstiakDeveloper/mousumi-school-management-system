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
                        <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 p-6 text-left align-middle shadow-xl transition-all">
                            <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
                                Confirm Salary Payment
                            </DialogTitle>

                            <div class="mt-4">
                                <div class="space-y-3">
                                    <p class="text-sm text-gray-600 dark:text-gray-300">
                                        <span class="font-semibold">Teacher:</span> {{ teacher?.user?.name }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-300">
                                        <span class="font-semibold">Amount:</span> {{ teacher?.salary_amount }}
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-300">
                                        <span class="font-semibold">Period:</span> {{ monthName }} {{ selectedYear }}
                                    </p>
                                </div>

                                <!-- Payment Method Selection -->
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Payment Method
                                    </label>
                                    <select
                                        v-model="form.payment_method"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600"
                                    >
                                        <option value="bank">Bank Transfer</option>
                                        <option value="cash">Cash</option>
                                    </select>
                                </div>

                                <!-- Receipt Upload -->
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Payment Receipt (Optional)
                                    </label>
                                    <input
                                        type="file"
                                        @change="handleFileUpload"
                                        class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer dark:text-gray-300 dark:bg-gray-700 dark:border-gray-600"
                                        accept=".jpg,.jpeg,.png,.pdf"
                                    />
                                </div>
                            </div>

                            <div class="mt-6 flex justify-end space-x-3">
                                <button
                                    type="button"
                                    class="inline-flex justify-center rounded-md border border-transparent bg-gray-100 px-4 py-2 text-sm font-medium text-gray-900 hover:bg-gray-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-500 focus-visible:ring-offset-2 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600"
                                    @click="$emit('update:modelValue', false)"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="button"
                                    class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                    :disabled="loading"
                                    @click="confirmPayment"
                                >
                                    {{ loading ? 'Processing...' : 'Confirm Payment' }}
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
import { ref } from 'vue';
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    modelValue: Boolean,
    teacher: Object,
    selectedYear: Number,
    selectedMonth: Number,
    monthName: String,
});

const emit = defineEmits(['update:modelValue', 'payment-success']);

const loading = ref(false);

const form = useForm({
    teacher_id: props.teacher?.id,
    year: props.selectedYear,
    month: props.selectedMonth,
    payment_method: 'bank',
    payment_proof: null,
});

const handleFileUpload = (event) => {
    form.payment_proof = event.target.files[0];
};

const confirmPayment = () => {
    loading.value = true;
    form.post(route('salaries.store'), {
        onSuccess: () => {
            emit('payment-success');
            emit('update:modelValue', false);
        },
        onFinish: () => {
            loading.value = false;
        },
    });
};
</script>
