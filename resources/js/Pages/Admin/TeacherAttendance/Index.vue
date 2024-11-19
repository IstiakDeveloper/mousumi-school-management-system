<!-- resources/js/Pages/Admin/TeacherAttendance/Index.vue -->
<script setup>
// Keep the existing script part unchanged
import { ref } from 'vue'
import { router, Link } from '@inertiajs/vue3'
import debounce from 'lodash/debounce'
import { Head } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { format } from 'date-fns'

const props = defineProps({
  attendances: Object,
  filters: Object
})

const search = ref(props.filters.search || '')
const selectedDate = ref(props.filters.date || format(new Date(), 'yyyy-MM-dd'))
const loading = ref(false)

const refreshAttendance = async () => {
  loading.value = true
  try {
    await router.post(route('teacher-attendance.fetch'), {}, {
      preserveScroll: true,
      onFinish: () => loading.value = false
    })
  } catch (error) {
    console.error('Error refreshing attendance:', error)
    loading.value = false
  }
}

const filter = debounce(() => {
  router.get(
    route('teacher-attendance.index'),
    { search: search.value, date: selectedDate.value },
    {
      preserveState: true,
      preserveScroll: true,
      replace: true
    }
  )
}, 300)

const getStatusColor = (status) => {
  const colors = {
    present: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
    late: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
    absent: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
  }
  return colors[status] || 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
}
</script>

<template>
  <Head title="Teacher Attendance" />

  <AdminLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="text-xl font-bold text-gray-800 dark:text-white">
          Teacher Attendance Management
        </h2>
        <button
          @click="refreshAttendance"
          :disabled="loading"
          class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-500 text-white rounded-lg hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 transition-all duration-150 ease-in-out"
        >
          <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          {{ loading ? 'Refreshing...' : 'Refresh Data' }}
        </button>
      </div>
    </template>

    <div class="py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
          <div class="p-6">
            <!-- Control Panel -->
            <div class="space-y-4 sm:space-y-0 sm:flex sm:gap-4 mb-6">
              <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search Teacher</label>
                <input
                  v-model="search"
                  type="text"
                  placeholder="Search by name..."
                  class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-indigo-400"
                  @input="filter"
                >
              </div>
              <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Select Date</label>
                <input
                  v-model="selectedDate"
                  type="date"
                  class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300 shadow-sm focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-1 focus:ring-indigo-500 dark:focus:ring-indigo-400"
                  @change="filter"
                >
              </div>


              <div class="flex items-end">
                <button
                  @click="refreshAttendance"
                  :disabled="loading"
                  class="w-full sm:w-auto px-4 py-2 bg-indigo-600 dark:bg-indigo-500 text-white rounded-lg shadow-sm hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-150 ease-in-out"
                >
                  <span v-if="loading" class="inline-flex items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span>Refreshing...</span>
                  </span>
                  <span v-else class="inline-flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>Refresh Data</span>
                  </span>
                </button>
              </div>
            </div>

            <!-- Flash Messages -->
            <div v-if="$page.props.flash.success"
                 class="mb-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-200 px-4 py-3 rounded-lg">
              {{ $page.props.flash.success }}
            </div>
            <div v-if="$page.props.flash.error"
                 class="mb-4 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-700 text-red-700 dark:text-red-200 px-4 py-3 rounded-lg">
              {{ $page.props.flash.error }}
            </div>

            <!-- Attendance Table -->
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Teacher</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Check In</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Check Out</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Duration</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Punches</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                  </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                  <tr v-for="attendance in attendances.data" :key="attendance.id"
                      class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                      {{ attendance.teacher_name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ attendance.date }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ attendance.check_in || '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ attendance.check_out || '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ attendance.duration || '-' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ attendance.total_punches }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="['px-3 py-1 text-xs font-semibold rounded-full', getStatusColor(attendance.status)]">
                        {{ attendance.status }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                      <Link :href="route('admin.teacher-attendance.history', attendance.teacher_id)"
                            class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
                        View History
                      </Link>
                    </td>
                  </tr>
                  <tr v-if="attendances.data.length === 0">
                    <td colspan="8" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                      No attendance records found
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Pagination -->
            <div v-if="attendances.links.length > 3" class="mt-6">
              <div class="flex items-center justify-between">
                <div class="flex justify-between flex-1 sm:hidden">
                  <Link v-if="attendances.links[0].url" :href="attendances.links[0].url"
                        class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-600">
                    Previous
                  </Link>
                  <Link v-if="attendances.links[attendances.links.length - 1].url" :href="attendances.links[attendances.links.length - 1].url"
                        class="ml-3 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-600">
                    Next
                  </Link>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                  <div>
                    <p class="text-sm text-gray-700 dark:text-gray-300">
                      Showing <span class="font-medium">{{ attendances.from }}</span> to
                      <span class="font-medium">{{ attendances.to }}</span> of
                      <span class="font-medium">{{ attendances.total }}</span> results
                    </p>
                  </div>
                  <nav class="relative z-0 inline-flex -space-x-px rounded-md shadow-sm">
                    <Link v-for="(link, i) in attendances.links" :key="i" :href="link.url" v-html="link.label"
                          class="relative inline-flex items-center px-4 py-2 border text-sm font-medium first:rounded-l-md last:rounded-r-md"
                          :class="[
                            link.url ? 'hover:bg-gray-50 dark:hover:bg-gray-700' : 'cursor-default',
                            link.active
                              ? 'z-10 bg-indigo-50 dark:bg-indigo-900 border-indigo-500 dark:border-indigo-500 text-indigo-600 dark:text-indigo-200'
                              : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-gray-500 dark:text-gray-400'
                          ]" />
                  </nav>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
