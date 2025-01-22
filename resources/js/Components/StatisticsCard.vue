<template>
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
      <!-- Total Teachers -->
      <div class="rounded-lg bg-white p-6 shadow">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <UserGroupIcon class="h-8 w-8 text-gray-400" />
          </div>
          <div class="ml-5 w-0 flex-1">
            <dl>
              <dt class="truncate text-sm font-medium text-gray-500">Total Teachers</dt>
              <dd class="mt-1">
                <div class="text-lg font-medium text-gray-900">
                  {{ statistics.totalTeachers }}
                </div>
              </dd>
            </dl>
          </div>
        </div>
      </div>

      <!-- Total Salary Amount -->
      <div class="rounded-lg bg-white p-6 shadow">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <BanknotesIcon class="h-8 w-8 text-gray-400" />
          </div>
          <div class="ml-5 w-0 flex-1">
            <dl>
              <dt class="truncate text-sm font-medium text-gray-500">Total Salary Amount</dt>
              <dd class="mt-1">
                <div class="text-lg font-medium text-gray-900">
                  {{ formatCurrency(statistics.totalAmount) }}
                </div>
              </dd>
            </dl>
          </div>
        </div>
      </div>

      <!-- Paid Salaries -->
      <div class="rounded-lg bg-white p-6 shadow">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <CheckCircleIcon class="h-8 w-8 text-green-400" />
          </div>
          <div class="ml-5 w-0 flex-1">
            <dl>
              <dt class="truncate text-sm font-medium text-gray-500">Paid Salaries</dt>
              <dd class="mt-1">
                <div class="text-lg font-medium text-green-600">
                  {{ statistics.paidCount }} / {{ formatCurrency(statistics.paidAmount) }}
                </div>
              </dd>
            </dl>
          </div>
        </div>
      </div>

      <!-- Pending Salaries -->
      <div class="rounded-lg bg-white p-6 shadow">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <ClockIcon class="h-8 w-8 text-orange-400" />
          </div>
          <div class="ml-5 w-0 flex-1">
            <dl>
              <dt class="truncate text-sm font-medium text-gray-500">Pending Salaries</dt>
              <dd class="mt-1">
                <div class="text-lg font-medium text-orange-600">
                  {{ statistics.pendingCount }} / {{ formatCurrency(statistics.pendingAmount) }}
                </div>
              </dd>
            </dl>
          </div>
        </div>
      </div>
    </div>
  </template>

  <script setup>
  import { UserGroupIcon, BanknotesIcon, CheckCircleIcon, ClockIcon } from '@heroicons/vue/24/outline'
  import { formatCurrency } from '@/utils/formatters'

  defineProps({
    statistics: {
      type: Object,
      required: true,
      default: () => ({
        totalTeachers: 0,
        totalAmount: 0,
        paidCount: 0,
        paidAmount: 0,
        pendingCount: 0,
        pendingAmount: 0
      })
    }
  })
  </script>
