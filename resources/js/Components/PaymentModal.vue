<template>
    <Modal :show="true" @close="$emit('close')">
      <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900">
          Confirm Bulk Salary Payment
        </h2>

        <div class="mt-4">
          <!-- Warning Notice -->
          <div class="rounded-md bg-yellow-50 p-4">
            <div class="flex">
              <ExclamationTriangleIcon class="h-5 w-5 text-yellow-400" />
              <div class="ml-3">
                <h3 class="text-sm font-medium text-yellow-800">Important Notice</h3>
                <div class="mt-2 text-sm text-yellow-700">
                  This action will process salary payments for all pending salaries in the selected month. This action cannot be undone.
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="mt-6 space-y-4">
          <div class="rounded-md bg-gray-50 p-4">
            <div class="flex justify-between">
              <span class="text-sm text-gray-500">Selected Month/Year:</span>
              <span class="text-sm font-medium text-gray-900">
                {{ formatMonth(month) }}/{{ year }}
              </span>
            </div>
            <div class="mt-2 flex justify-between">
              <span class="text-sm text-gray-500">Total Teachers:</span>
              <span class="text-sm font-medium text-gray-900">
                {{ totalTeachers }}
              </span>
            </div>
            <div class="mt-2 flex justify-between">
              <span class="text-sm text-gray-500">Total Amount:</span>
              <span class="text-sm font-medium text-gray-900">
                {{ formatCurrency(totalAmount) }}
              </span>
            </div>
          </div>

          <div class="rounded-md bg-blue-50 p-4">
            <div class="flex">
              <InformationCircleIcon class="h-5 w-5 text-blue-400" />
              <div class="ml-3">
                <h3 class="text-sm font-medium text-blue-800">Payment Method</h3>
                <div class="mt-2 text-sm text-blue-700">
                  All payments will be processed via bank transfer. Please ensure sufficient bank balance is available.
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="mt-6 flex justify-end space-x-3">
          <SecondaryButton @click="$emit('close')" :disabled="processing">
            Cancel
          </SecondaryButton>
          <PrimaryButton @click="$emit('confirm')" :disabled="processing">
            {{ processing ? 'Processing...' : 'Confirm Payment' }}
          </PrimaryButton>
        </div>
      </div>
    </Modal>
  </template>

  <script setup>
  import { ExclamationTriangleIcon, InformationCircleIcon } from '@heroicons/vue/24/outline'
  import Modal from '@/Components/Modal.vue'
  import PrimaryButton from '@/Components/PrimaryButton.vue'
  import SecondaryButton from '@/Components/SecondaryButton.vue'
  import { formatCurrency, formatMonth } from '@/utils/formatters'

  const props = defineProps({
    month: {
      type: Number,
      required: true
    },
    year: {
      type: Number,
      required: true
    },
    totalTeachers: {
      type: Number,
      required: true
    },
    totalAmount: {
      type: Number,
      required: true
    },
    processing: {
      type: Boolean,
      default: false
    }
  })

  defineEmits(['close', 'confirm'])
  </script>
