<template>
    <TransitionRoot appear :show="true" as="template">
      <Dialog as="div" @close="$emit('close')" class="relative z-50">
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
              <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 p-6 shadow-xl transition-all">
                <div class="flex justify-between items-start mb-4">
                  <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 dark:text-white">
                    Record Payment
                  </DialogTitle>
                  <button
                    @click="$emit('close')"
                    class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300"
                  >
                    <XMarkIcon class="h-5 w-5" />
                  </button>
                </div>

                <!-- Rest of the template remains the same -->
                <!-- Student Info -->
                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 mb-6">
                  <div class="flex items-center">
                    <div class="flex-shrink-0">
                      <img
                        :src="student.photo || '/placeholder.png'"
                        class="h-12 w-12 rounded-full"
                      />
                    </div>
                    <div class="ml-4">
                      <h4 class="text-sm font-medium text-gray-900 dark:text-white">
                        {{ student.name_en }}
                      </h4>
                      <p class="text-sm text-gray-500 dark:text-gray-400">
                        Class: {{ student.school_class?.name }} |
                        Monthly Fee: ৳{{ formatCurrency(student.monthly_fee) }}
                      </p>
                    </div>
                  </div>
                </div>

                <!-- Payment Form -->
                <form @submit.prevent="submit" class="space-y-4">
                  <div>
                    <label class="form-label">Payment Method</label>
                    <select
                      v-model="form.payment_method"
                      class="form-select"
                      required
                    >
                      <option value="">Select Method</option>
                      <option value="cash">Cash</option>
                      <option value="bank">Bank Transfer</option>
                      <option value="mobile_banking">Mobile Banking</option>
                    </select>
                    <p v-if="form.errors.payment_method" class="mt-1 text-sm text-red-600">
                      {{ form.errors.payment_method }}
                    </p>
                  </div>

                  <div>
                    <label class="form-label">Payment Proof</label>
                    <div class="mt-1 flex items-center">
                      <span class="inline-block h-12 w-12 rounded-md overflow-hidden bg-gray-100">
                        <img
                          v-if="previewUrl"
                          :src="previewUrl"
                          class="h-full w-full object-cover"
                        />
                        <PhotoIcon
                          v-else
                          class="h-full w-full text-gray-300"
                        />
                      </span>
                      <label class="ml-5">
                        <input
                          type="file"
                          class="sr-only"
                          accept="image/*,.pdf"
                          @change="handleFileUpload"
                        >
                        <span class="btn-secondary text-sm">
                          Change
                        </span>
                      </label>
                    </div>
                    <p v-if="form.errors.payment_proof" class="mt-1 text-sm text-red-600">
                      {{ form.errors.payment_proof }}
                    </p>
                  </div>

                  <div>
                    <label class="form-label">Notes (Optional)</label>
                    <textarea
                      v-model="form.notes"
                      rows="3"
                      class="form-input"
                    ></textarea>
                  </div>

                  <div class="mt-6 flex justify-end space-x-3">
                    <button
                      type="button"
                      class="btn-secondary"
                      @click="$emit('close')"
                    >
                      Cancel
                    </button>
                    <button
                      type="submit"
                      class="btn-primary"
                      :disabled="form.processing"
                    >
                      <span v-if="form.processing">
                        <SpinnerIcon class="animate-spin h-4 w-4 mr-2" />
                        Processing...
                      </span>
                      <span v-else>Record Payment</span>
                    </button>
                  </div>
                </form>
              </DialogPanel>
            </TransitionChild>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
  </template>

  <script setup>
  import { ref } from 'vue';
  import { useForm } from '@inertiajs/vue3';
  import {
    Dialog,
    DialogPanel,
    DialogTitle,
    TransitionChild,
    TransitionRoot,
  } from '@headlessui/vue';
  import {
    XMarkIcon,
    PhotoIcon,
  } from '@heroicons/vue/24/outline';
  import SpinnerIcon from '@/Components/Icons/SpinnerIcon.vue';

  const props = defineProps({
    student: {
      type: Object,
      required: true
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

  defineEmits(['close', 'payment-recorded']);

  const previewUrl = ref(null);

  const form = useForm({
    student_id: props.student.id,
    year: props.year,
    month: props.month,
    payment_method: '',
    payment_proof: null,
    notes: ''
  });

  const formatCurrency = (value) => {
    return Number(value).toLocaleString('en-IN');
  };

  const handleFileUpload = (e) => {
    const file = e.target.files[0];
    if (!file) return;

    form.payment_proof = file;

    if (file.type.startsWith('image/')) {
      const reader = new FileReader();
      reader.onload = e => previewUrl.value = e.target.result;
      reader.readAsDataURL(file);
    } else {
      previewUrl.value = null;
    }
  };

  const submit = () => {
    form.post(route('payments.store'), {
      onSuccess: () => {
        form.reset();
        previewUrl.value = null;
        emit('payment-recorded');
      }
    });
  };
  </script>

  <style scoped>
  .form-label {
    @apply block text-sm font-medium text-gray-700 dark:text-gray-300;
  }

  .form-input {
    @apply mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm;
  }

  .form-select {
    @apply mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm;
  }

  .btn-primary {
    @apply inline-flex justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed;
  }

  .btn-secondary {
    @apply inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2;
  }
  </style>
