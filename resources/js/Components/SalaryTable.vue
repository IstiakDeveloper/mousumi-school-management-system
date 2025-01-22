<template>
    <div class="overflow-x-auto rounded-lg bg-white shadow">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Teacher</th>
            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Designation</th>
            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Month/Year</th>
            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Amount</th>
            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Actions</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white">
          <tr v-for="salary in salaries" :key="salary.id">
            <td class="whitespace-nowrap px-6 py-4">
              <div class="flex items-center">
                <div class="h-10 w-10 flex-shrink-0">
                  <img
                    v-if="salary.teacher.profile_image"
                    :src="salary.teacher.profile_image"
                    class="h-10 w-10 rounded-full"
                    alt=""
                  />
                  <div v-else class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                    <UserIcon class="h-6 w-6 text-gray-400" />
                  </div>
                </div>
                <div class="ml-4">
                  <div class="text-sm font-medium text-gray-900">
                    {{ salary.teacher.user.name }}
                  </div>
                  <div class="text-sm text-gray-500">
                    {{ salary.teacher.pin }}
                  </div>
                </div>
              </div>
            </td>
            <td class="whitespace-nowrap px-6 py-4">
              <div class="text-sm text-gray-900">{{ salary.teacher.designation }}</div>
            </td>
            <td class="whitespace-nowrap px-6 py-4">
              <div class="text-sm text-gray-900">
                {{ formatMonth(salary.month) }}/{{ salary.year }}
              </div>
            </td>
            <td class="whitespace-nowrap px-6 py-4">
              <div class="text-sm text-gray-900">{{ formatCurrency(salary.amount) }}</div>
            </td>
            <td class="whitespace-nowrap px-6 py-4">
              <span
                :class="{
                  'px-2 py-1 text-xs font-medium rounded-full': true,
                  'bg-green-100 text-green-800': salary.status === 'paid',
                  'bg-orange-100 text-orange-800': salary.status === 'pending'
                }"
              >
                {{ salary.status }}
              </span>
            </td>
            <td class="whitespace-nowrap px-6 py-4 text-sm">
              <button
                v-if="salary.status === 'pending'"
                @click="$emit('process-payment', salary.id)"
                :disabled="processing"
                class="text-indigo-600 hover:text-indigo-900 disabled:opacity-50"
              >
                Process Payment
              </button>
              <span v-else class="text-gray-500">
                Paid on {{ formatDate(salary.updated_at) }}
              </span>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>

  <script setup>
  import { UserIcon } from '@heroicons/vue/24/outline'
  import { formatCurrency, formatMonth, formatDate } from '@/utils/formatters'

  defineProps({
    salaries: {
      type: Array,
      required: true
    },
    processing: {
      type: Boolean,
      default: false
    }
  })

  defineEmits(['process-payment'])
  </script>
