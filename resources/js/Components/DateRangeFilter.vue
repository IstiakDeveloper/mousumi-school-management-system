<!-- DateRangeFilter.vue -->
<script setup>
import { ref, watch } from 'vue'
import { startOfToday, subDays, startOfMonth, endOfMonth, format } from 'date-fns'

const props = defineProps({
  onFilterChange: {
    type: Function,
    required: true
  }
})

const dateRange = ref('today')
const customStartDate = ref(format(new Date(), 'yyyy-MM-dd'))
const customEndDate = ref(format(new Date(), 'yyyy-MM-dd'))

const updateFilter = () => {
  let startDate, endDate

  switch (dateRange.value) {
    case 'today':
      startDate = format(startOfToday(), 'yyyy-MM-dd')
      endDate = format(startOfToday(), 'yyyy-MM-dd')
      break
    case 'last7':
      startDate = format(subDays(new Date(), 6), 'yyyy-MM-dd')
      endDate = format(new Date(), 'yyyy-MM-dd')
      break
    case 'lastMonth':
      const lastMonth = new Date()
      lastMonth.setMonth(lastMonth.getMonth() - 1)
      startDate = format(startOfMonth(lastMonth), 'yyyy-MM-dd')
      endDate = format(endOfMonth(lastMonth), 'yyyy-MM-dd')
      break
    case 'custom':
      startDate = customStartDate.value
      endDate = customEndDate.value
      break
    default:
      startDate = format(new Date(), 'yyyy-MM-dd')
      endDate = format(new Date(), 'yyyy-MM-dd')
  }

  props.onFilterChange({ startDate, endDate })
}

watch([dateRange, customStartDate, customEndDate], () => {
  updateFilter()
})
</script>

<template>
  <div class="flex flex-col sm:flex-row gap-4 items-end">
    <div class="w-full sm:w-48">
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
        Date Range
      </label>
      <select
        v-model="dateRange"
        class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md dark:bg-gray-700 dark:text-white"
      >
        <option value="today">Today</option>
        <option value="last7">Last 7 Days</option>
        <option value="lastMonth">Last Month</option>
        <option value="custom">Custom Range</option>
      </select>
    </div>

    <div v-if="dateRange === 'custom'" class="flex gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
          Start Date
        </label>
        <input
          type="date"
          v-model="customStartDate"
          class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white"
        >
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
          End Date
        </label>
        <input
          type="date"
          v-model="customEndDate"
          class="mt-1 block w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-700 dark:text-white"
        >
      </div>
    </div>
  </div>
</template>
