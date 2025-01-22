<template>
    <AdminLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Teacher Salary Management
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <!-- Flash Messages -->
                <div v-if="flashMessage" class="mb-4">
                    <div :class="[
                        'rounded-md p-4',
                        flashMessage.type === 'success' ? 'bg-green-50' : 'bg-red-50'
                    ]">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <CheckCircleIcon v-if="flashMessage.type === 'success'"
                                        class="h-5 w-5 text-green-400" />
                                    <XCircleIcon v-else
                                        class="h-5 w-5 text-red-400" />
                                </div>
                                <div class="ml-3">
                                    <p :class="[
                                        'text-sm font-medium',
                                        flashMessage.type === 'success' ? 'text-green-800' : 'text-red-800'
                                    ]">
                                        {{ flashMessage.message }}
                                    </p>
                                </div>
                            </div>
                            <button @click="clearFlashMessage"
                                class="inline-flex rounded-md p-1.5 focus:outline-none"
                                :class="flashMessage.type === 'success' ? 'hover:bg-green-100' : 'hover:bg-red-100'">
                                <XMarkIcon class="h-5 w-5" :class="flashMessage.type === 'success' ? 'text-green-500' : 'text-red-500'" />
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mb-6 flex flex-wrap items-center justify-between gap-4">
                    <div class="flex flex-wrap gap-4">
                        <PrimaryButton
                            @click="generateSalaries"
                            :disabled="processing"
                            class="bg-green-600 hover:bg-green-700 focus:ring-green-500"
                        >
                            <PlusIcon class="mr-2 h-5 w-5" />
                            {{ processing ? 'Generating...' : 'Generate Monthly Salaries' }}
                        </PrimaryButton>
                        <PrimaryButton
                            @click="showBulkPaymentModal"
                            :disabled="processing"
                            class="bg-blue-600 hover:bg-blue-700 focus:ring-blue-500"
                        >
                            <CreditCardIcon class="mr-2 h-5 w-5" />
                            Process Bulk Payment
                        </PrimaryButton>
                    </div>
                    <div class="flex flex-wrap gap-4">
                        <SecondaryButton @click="resetFilters">
                            <ArrowPathIcon class="mr-2 h-5 w-5" />
                            Reset Filters
                        </SecondaryButton>
                        <SecondaryButton @click="navigateToReport">
                            <ChartBarIcon class="mr-2 h-5 w-5" />
                            View Reports
                        </SecondaryButton>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <StatisticsCard :statistics="filteredData.statistics" class="mb-6" />

                <!-- Filters -->
                <div class="mb-6 rounded-lg bg-white p-4 shadow">
                    <h3 class="mb-4 text-lg font-medium text-gray-900">Filters</h3>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <!-- Year Filter -->
                        <div>
                            <InputLabel for="year" value="Year" />
                            <select
                                v-model="state.filters.year"
                                id="year"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option v-for="option in yearOptions" :key="option.value" :value="option.value">
                                    {{ option.label }}
                                </option>
                            </select>
                        </div>

                        <!-- Month Filter -->
                        <div>
                            <InputLabel for="month" value="Month" />
                            <select
                                v-model="state.filters.month"
                                id="month"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option v-for="option in monthOptions" :key="option.value" :value="option.value">
                                    {{ option.label }}
                                </option>
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <InputLabel for="status" value="Status" />
                            <select
                                v-model="state.filters.status"
                                id="status"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            >
                                <option v-for="option in statusOptions" :key="option.value" :value="option.value">
                                    {{ option.label }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="rounded-lg bg-white shadow">
                    <!-- No Data State -->
                    <div v-if="!filteredData.salaries.data?.length" class="p-8 text-center">
                        <InboxIcon class="mx-auto h-12 w-12 text-gray-400" />
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No salaries found</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            {{ state.filters.status === 'pending' ?
                                'No pending salaries found for the selected period.' :
                                'No salaries found matching your filters.' }}
                        </p>
                    </div>

                    <!-- Salary Table -->
                    <template v-else>
                        <SalaryTable
                            :salaries="filteredData.salaries.data"
                            :processing="processing"
                            @process-payment="processSinglePayment"
                        />

                        <!-- Pagination -->
                        <div class="border-t border-gray-200 px-4 py-3 sm:px-6">
                            <Pagination
                                v-if="filteredData.salaries.total > 0"
                                v-model="currentPage"
                                :links="filteredData.salaries.links"
                                :total="filteredData.salaries.total"
                                @update:modelValue="handlePageChange"
                            />
                        </div>
                    </template>
                </div>

                <!-- Payment Modal -->
                <PaymentModal
                    v-if="showingPaymentModal"
                    :month="state.filters.month"
                    :year="state.filters.year"
                    :total-teachers="filteredData.pendingSalaries.count"
                    :total-amount="filteredData.pendingSalaries.amount"
                    :processing="processing"
                    @close="showingPaymentModal = false"
                    @confirm="processBulkPayment"
                />
            </div>
        </div>

        <!-- Loading Overlay -->
        <div v-if="processing"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
            <div class="rounded-lg bg-white p-4">
                <div class="flex items-center space-x-3">
                    <div class="h-8 w-8 animate-spin rounded-full border-4 border-blue-500 border-t-transparent"></div>
                    <span class="text-lg font-medium">Processing...</span>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
<script setup>
import { ref, computed, reactive, watch, onMounted } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import axios from 'axios'
import debounce from 'lodash/debounce'

// Component Imports
import AdminLayout from '@/Layouts/AdminLayout.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import FlashMessage from '@/Components/FlashMessage.vue'
import StatisticsCard from '@/Components/StatisticsCard.vue'
import SalaryTable from '@/Components/SalaryTable.vue'
import Pagination from '@/Components/Pagination.vue'
import PaymentModal from '@/Components/PaymentModal.vue'

// Icon Imports
import {
    PlusIcon,
    XMarkIcon,
    CheckCircleIcon,
    XCircleIcon,
    CreditCardIcon,
    ArrowPathIcon,
    ChartBarIcon,
    InboxIcon,
    UserGroupIcon,
    BanknotesIcon,
    ClockIcon
} from '@heroicons/vue/24/outline'

// Props Definition
const props = defineProps({
    salaries: {
        type: Object,
        required: true
    },
    filters: {
        type: Object,
        default: () => ({
            year: new Date().getFullYear(),
            month: '',
            status: 'pending'
        })
    },
    statistics: {
        type: Object,
        required: true
    }
})

// Reactive State
const processing = ref(false)
const flashMessage = ref(null)
const currentPage = ref(1)
const showingPaymentModal = ref(false)

// State Management
const state = reactive({
    salaries: props.salaries || { data: [] },
    statistics: props.statistics || {},
    filters: {
        year: props.filters?.year || new Date().getFullYear(),
        month: props.filters?.month || '',
        status: 'pending'
    }
})

// Filter Options
const monthOptions = [
    { label: 'All Months', value: '' },
    { label: 'January', value: 1 },
    { label: 'February', value: 2 },
    { label: 'March', value: 3 },
    { label: 'April', value: 4 },
    { label: 'May', value: 5 },
    { label: 'June', value: 6 },
    { label: 'July', value: 7 },
    { label: 'August', value: 8 },
    { label: 'September', value: 9 },
    { label: 'October', value: 10 },
    { label: 'November', value: 11 },
    { label: 'December', value: 12 }
]

const yearOptions = computed(() => {
    const currentYear = new Date().getFullYear()
    return Array.from({ length: 5 }, (_, i) => ({
        label: String(currentYear - i),
        value: currentYear - i
    }))
})

const statusOptions = [
    { label: 'All Statuses', value: '' },
    { label: 'Pending', value: 'pending' },
    { label: 'Paid', value: 'paid' }
]

// Computed Properties
const filteredData = computed(() => {
    return {
        salaries: state.salaries,
        statistics: state.statistics,
        pendingSalaries: {
            count: state.salaries.data?.filter(s => s.status === 'pending').length || 0,
            amount: state.salaries.data?.filter(s => s.status === 'pending')
                .reduce((sum, salary) => sum + Number(salary.amount), 0) || 0
        }
    }
})

// Watchers
watch(() => props.salaries, (newValue) => {
    state.salaries = newValue
    currentPage.value = newValue?.current_page || 1
}, { deep: true })

watch(() => props.statistics, (newValue) => {
    state.statistics = newValue
}, { deep: true })

watch(() => state.filters, () => {
    currentPage.value = 1
    applyFilters()
}, { deep: true })

// Flash Message Handling
const clearFlashMessage = () => {
    flashMessage.value = null
}

const showFlashMessage = (type, message) => {
    flashMessage.value = { type, message }
    setTimeout(clearFlashMessage, 5000)
}

// Data Fetching Methods
const refreshData = async () => {
    try {
        const response = await axios.get(route('admin.teacher-salaries.index'), {
            params: {
                ...state.filters,
                page: currentPage.value
            }
        })
        state.salaries = response.data.salaries
        state.statistics = response.data.statistics
    } catch (error) {
        showFlashMessage('error', 'Failed to refresh data')
    }
}

const applyFilters = debounce(async () => {
    processing.value = true
    try {
        await refreshData()
    } finally {
        processing.value = false
    }
}, 300)

// Salary Generation
const generateSalaries = async () => {
    if (processing.value) return

    try {
        processing.value = true
        const response = await axios.post(route('admin.teacher-salaries.generate'))
        showFlashMessage('success', response.data.message)
        await refreshData()
    } catch (error) {
        const errorMessage = error.response?.data?.message || 'Failed to generate salaries'
        showFlashMessage('error', errorMessage)
    } finally {
        processing.value = false
    }
}

// Payment Processing
const processSinglePayment = async (salaryId) => {
    if (processing.value) return

    try {
        processing.value = true
        const response = await axios.post(route('admin.teacher-salaries.process', salaryId))
        showFlashMessage('success', 'Payment processed successfully')
        await refreshData()
    } catch (error) {
        const errorMessage = error.response?.data?.message || 'Failed to process payment'
        showFlashMessage('error', errorMessage)
    } finally {
        processing.value = false
    }
}

const processBulkPayment = async () => {
    if (processing.value) return

    try {
        processing.value = true
        const response = await axios.post(route('admin.teacher-salaries.bulk-process'), {
            year: state.filters.year,
            month: state.filters.month
        })
        showFlashMessage('success', 'Bulk payments processed successfully')
        showingPaymentModal.value = false
        await refreshData()
    } catch (error) {
        const errorMessage = error.response?.data?.message || 'Failed to process bulk payments'
        showFlashMessage('error', errorMessage)
    } finally {
        processing.value = false
    }
}

// UI Handlers
const handlePageChange = async (page) => {
    currentPage.value = page
    await refreshData()
}

const showBulkPaymentModal = () => {
    if (!state.filters.month) {
        showFlashMessage('error', 'Please select a month before processing bulk payments')
        return
    }
    showingPaymentModal.value = true
}

const resetFilters = () => {
    state.filters = {
        year: new Date().getFullYear(),
        month: '',
        status: 'pending'
    }
}

const navigateToReport = () => {
    router.get(route('admin.teacher-salaries.report'))
}

// Initialize
onMounted(() => {
    if (props.filters) {
        state.filters = {
            year: props.filters.year || new Date().getFullYear(),
            month: props.filters.month || '',
            status: props.filters.status || 'pending'
        }
    }
})
</script>
