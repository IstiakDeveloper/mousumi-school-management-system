<!-- resources/js/Pages/Admin/TeacherAttendance/History.vue -->
<script setup>
import { ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import { Head } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { format } from 'date-fns'

const props = defineProps({
  teacher: {
    type: Object,
    required: true
  },
  attendances: {
    type: Object,
    required: true
  }
})

const expandedRows = ref(new Set())

const toggleDetails = (id) => {
  if (expandedRows.value.has(id)) {
    expandedRows.value.delete(id)
  } else {
    expandedRows.value.add(id)
  }
}

const getStatusColor = (status) => {
  const colors = {
    present: 'bg-green-100 text-green-800',
    late: 'bg-yellow-100 text-yellow-800',
    absent: 'bg-red-100 text-red-800'
  }
  return colors[status] || 'bg-gray-100 text-gray-800'
}

const formatTime = (timeString) => {
  if (!timeString) return '-'
  return format(new Date(timeString), 'hh:mm:ss a')
}
</script>

<template>
  <Head :title="`${teacher.name}'s Attendance History`" />

  <AdminLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          {{ teacher.name }}'s Attendance History
        </h2>
        <Link
          :href="route('admin.teacher-attendance.index')"
          class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition-colors"
        >
          Back to Attendance
        </Link>
      </div>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6">
            <!-- Attendance History Table -->
            <div class="overflow-x-auto bg-white rounded-lg shadow">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Date
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      First Punch
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Last Punch
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Duration
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Total Punches
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Status
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                      Details
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <template v-for="attendance in attendances.data" :key="attendance.date">
                    <!-- Main Row -->
                    <tr class="hover:bg-gray-50">
                      <td class="px-6 py-4 whitespace-nowrap">
                        {{ attendance.date }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        {{ formatTime(attendance.first_attendance) }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        {{ formatTime(attendance.last_attendance) }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        {{ attendance.duration || '-' }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        {{ attendance.total_punches }}
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <span :class="[
                          'px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full',
                          getStatusColor(attendance.status)
                        ]">
                          {{ attendance.status }}
                        </span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <button
                          @click="toggleDetails(attendance.date)"
                          class="text-indigo-600 hover:text-indigo-900"
                        >
                          {{ expandedRows.has(attendance.date) ? 'Hide' : 'Show' }} Details
                        </button>
                      </td>
                    </tr>

                    <!-- Expanded Details Row -->
                    <tr v-if="expandedRows.has(attendance.date)">
                      <td colspan="7" class="px-6 py-4 bg-gray-50">
                        <div class="space-y-4">
                          <h4 class="font-medium text-gray-900">Attendance Logs</h4>
                          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            <div
                              v-for="(log, index) in attendance.logs"
                              :key="index"
                              class="bg-white p-4 rounded-lg shadow-sm border border-gray-200"
                            >
                              <div class="text-sm">
                                <div class="flex justify-between items-center mb-2">
                                  <span class="font-medium text-gray-900">
                                    Punch #{{ index + 1 }}
                                  </span>
                                  <span :class="[
                                    'px-2 py-1 text-xs rounded-full',
                                    log.state === 1 ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'
                                  ]">
                                    {{ log.state === 1 ? 'In' : 'Out' }}
                                  </span>
                                </div>
                                <div class="text-gray-600">
                                  {{ formatTime(log.timestamp) }}
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </td>
                    </tr>
                  </template>

                  <!-- Empty State -->
                  <tr v-if="attendances.data.length === 0">
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
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
                  <Link
                    v-if="attendances.links[0].url"
                    :href="attendances.links[0].url"
                    class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                  >
                    Previous
                  </Link>
                  <Link
                    v-if="attendances.links[attendances.links.length - 1].url"
                    :href="attendances.links[attendances.links.length - 1].url"
                    class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50"
                  >
                    Next
                  </Link>
                </div>

                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                  <div>
                    <p class="text-sm text-gray-700">
                      Showing
                      <span class="font-medium">{{ attendances.from }}</span>
                      to
                      <span class="font-medium">{{ attendances.to }}</span>
                      of
                      <span class="font-medium">{{ attendances.total }}</span>
                      results
                    </p>
                  </div>

                  <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                    <Link
                      v-for="(link, i) in attendances.links"
                      :key="i"
                      :href="link.url"
                      v-html="link.label"
                      class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                      :class="[
                        link.url ? 'hover:bg-gray-50' : 'cursor-default',
                        link.active
                          ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                          : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                      ]"
                    />
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
