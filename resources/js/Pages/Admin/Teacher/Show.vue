<script setup>
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { router } from '@inertiajs/vue3'
import { Head } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { format } from 'date-fns'

const props = defineProps({
    teacher: Object,
    attendances: Object,
    statistics: Object,
    filters: Object
})


// State management
const dateRange = ref(props.filters.dateRange || 'currentMonth')
const customStartDate = ref(props.filters.startDate || format(new Date(), 'yyyy-MM-dd'))
const customEndDate = ref(props.filters.endDate || format(new Date(), 'yyyy-MM-dd'))

// Methods
const handleDateRangeChange = () => {
    if (dateRange.value !== 'custom') {
        applyFilters()
    }
}

const applyFilters = () => {
    router.get(
        route('admin.teachers.show', props.teacher.id),
        {
            dateRange: dateRange.value,
            startDate: dateRange.value === 'custom' ? customStartDate.value : null,
            endDate: dateRange.value === 'custom' ? customEndDate.value : null
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true
        }
    )
}

const getStatusColor = (status) => {
    const colors = {
        present: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        late: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
        absent: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
    }
    return colors[status] || 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'
}

const getProgressColor = (percentage) => {
    if (percentage >= 90) return 'bg-green-500'
    if (percentage >= 75) return 'bg-blue-500'
    if (percentage >= 60) return 'bg-yellow-500'
    return 'bg-red-500'
}
</script>


<template>

    <Head :title="`${teacher.name} - Teacher Details`" />

    <AdminLayout>
        <!-- Header Section -->
        <div class="bg-white dark:bg-gray-800 shadow lg:px-8">
            <div class="mx-auto py-4 px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center">
                    <h1 class="text-xl font-bold text-gray-800 dark:text-white">
                        Teacher Details
                    </h1>

                    <div class="flex items-center space-x-4">
                        <Link :href="route('admin.teachers.index')"
                            class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors duration-150">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                        </svg>
                        Back to Teachers
                        </Link>

                        <Link
        :href="route('admin.teachers.download-pdf', {
            teacher: teacher.id,
            dateRange: dateRange,
            startDate: dateRange === 'custom' ? customStartDate : null,
            endDate: dateRange === 'custom' ? customEndDate : null
        })"
        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-150"
    >
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        Download PDF
    </Link>

                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content Section -->
        <div class="py-6">
            <div class="mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Teacher Information Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Personal Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Teacher Info Grid -->
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Profile
                                    Image</label>
                                <div class="mt-1">
                                    <img v-if="teacher.profile_image_url" :src="teacher.profile_image_url" alt="Profile Image"
                                        class="w-32 h-32 rounded-full object-cover" />
                                    <span v-else class="text-gray-500 dark:text-gray-400">No Image</span>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Name</label>
                                <p class="mt-1 text-base text-gray-900 dark:text-white">{{ teacher.name }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Email</label>
                                <p class="mt-1 text-base text-gray-900 dark:text-white">{{ teacher.email }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Phone
                                    Number</label>
                                <p class="mt-1 text-base text-gray-900 dark:text-white">{{ teacher.phone_number || 'Not Provided' }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">UID</label>
                                <p class="mt-1 text-base text-gray-900 dark:text-white">{{ teacher.uid || 'Not Assigned'
                                    }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">PIN</label>
                                <p class="mt-1 text-base text-gray-900 dark:text-white">{{ teacher.pin || 'Not Assigned'
                                    }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Class</label>
                                <p class="mt-1 text-base text-gray-900 dark:text-white">{{ teacher.class ||  'Not Assigned' }}</p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-500 dark:text-gray-400">Section</label>
                                <p class="mt-1 text-base text-gray-900 dark:text-white">{{ teacher.section || 'Not Assigned' }}</p>
                            </div>
                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-500 dark:text-gray-400">Specialization</label>
                                <p class="mt-1 text-base text-gray-900 dark:text-white">{{
                                    teacher.subject_specialization }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-500 dark:text-gray-400">Salary</label>
                                <p class="mt-1 text-base text-gray-900 dark:text-white">৳{{ teacher.salary_amount ||
                                    'Not Set' }}</p>
                            </div>

                            <div>
                                <label
                                    class="block text-sm font-medium text-gray-500 dark:text-gray-400">Address</label>
                                <p class="mt-1 text-base text-gray-900 dark:text-white">{{ teacher.address || 'Not Provided' }}</p>
                            </div>


                        </div>
                    </div>
                </div>

                <!-- Attendance Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <!-- Total Days Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Days</h4>
                        <p class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ statistics.total_days }}</p>
                    </div>

                    <!-- Present Days Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Present Days</h4>
                        <p class="mt-2 text-3xl font-bold text-green-600 dark:text-green-400">{{ statistics.present_days
                            }}</p>
                    </div>

                    <!-- Late Days Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Late Days</h4>
                        <p class="mt-2 text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ statistics.late_days
                            }}</p>
                    </div>

                    <!-- Absent Days Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Absent Days</h4>
                        <p class="mt-2 text-3xl font-bold text-red-600 dark:text-red-400">{{ statistics.absent_days }}
                        </p>
                    </div>
                </div>

                <!-- Attendance Progress Bar -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-6">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Attendance Rate</h4>
                        <span class="text-sm font-semibold text-gray-900 dark:text-white">
                            {{ statistics.attendance_percentage }}%
                        </span>
                    </div>
                    <div class="w-full h-4 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                        <div class="h-full transition-all duration-300 rounded-full"
                            :class="getProgressColor(statistics.attendance_percentage)"
                            :style="{ width: `${statistics.attendance_percentage}%` }"></div>
                    </div>
                </div>

                <!-- Attendance History Section -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Attendance History</h3>

                            <!-- Date Range Filter -->
                            <div class="flex gap-4">
                                <div class="w-48">
                                    <select v-model="dateRange" @change="handleDateRangeChange"
                                        class="w-full px-3 py-2 text-base border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                                        <option value="currentMonth">Current Month</option>
                                        <option value="lastMonth">Last Month</option>
                                        <option value="last3Months">Last 3 Months</option>
                                        <option value="custom">Custom Range</option>
                                    </select>
                                </div>

                                <!-- Custom Date Range -->
                                <div v-if="dateRange === 'custom'" class="flex gap-4">
                                    <div>
                                        <input type="date" v-model="customStartDate" @change="applyFilters"
                                            class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                                    </div>
                                    <div>
                                        <input type="date" v-model="customEndDate" @change="applyFilters"
                                            class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:text-white">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Attendance Table -->
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Date</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Check In</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Check Out</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Duration</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Punches</th>
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                    <tr v-for="attendance in attendances.data" :key="attendance.id"
                                        class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                            {{ attendance.date }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ attendance.check_in || '-' }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ attendance.check_out || '-' }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ attendance.duration || '-' }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                            {{ attendance.total_punches }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                :class="['px-3 py-1 text-xs font-semibold rounded-full', getStatusColor(attendance.status)]">
                                                {{ attendance.status }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr v-if="attendances.data.length === 0">
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                            No attendance records found for the selected period
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div v-if="attendances.links && attendances.links.length > 3" class="mt-6">
                            <div class="flex items-center justify-between">
                                <!-- Mobile Pagination -->
                                <div class="flex justify-between flex-1 sm:hidden">
                                    <Link v-if="attendances.links[0].url" :href="attendances.links[0].url"
                                        class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-600">
                                    Previous
                                    </Link>
                                    <Link v-if="attendances.links[attendances.links.length - 1].url"
                                        :href="attendances.links[attendances.links.length - 1].url"
                                        class="relative inline-flex items-center px-4 py-2 ml-3 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-600">
                                    Next
                                    </Link>
                                </div>

                                <!-- Desktop Pagination -->
                                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                    <div>
                                        <p class="text-sm text-gray-700 dark:text-gray-300">
                                            Showing <span class="font-medium">{{ attendances.from }}</span> to
                                            <span class="font-medium">{{ attendances.to }}</span> of
                                            <span class="font-medium">{{ attendances.total }}</span> results
                                        </p>
                                    </div>

                                    <nav class="relative z-0 inline-flex -space-x-px rounded-md shadow-sm">
                                        <Link v-for="(link, i) in attendances.links" :key="i" :href="link.url"
                                            v-html="link.label"
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
