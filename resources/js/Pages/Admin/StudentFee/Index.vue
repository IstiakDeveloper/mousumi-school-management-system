<!-- Index.vue -->
<template>
    <Head title="Student Fees" />

    <AdminLayout>
      <div class="container mx-auto py-8 px-4">
        <!-- Header Section -->
        <div class="mb-8">
          <div class="flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Student Fees Management</h1>
            <Link
              href="/admin/student-fees/create"
              class="btn-primary flex items-center gap-2"
            >
              <PlusIcon class="w-5 h-5" />
              Add New Fee
            </Link>
          </div>
          <p class="mt-1 text-gray-600 dark:text-gray-400">Manage and monitor student fee records</p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <div class="flex justify-between">
              <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Total Due</p>
                <p class="text-2xl font-bold text-red-600 dark:text-red-400">
                  ৳{{ formatCurrency(stats.total_due) }}
                </p>
              </div>
              <CashIcon class="w-8 h-8 text-red-500 opacity-20" />
            </div>
          </div>

          <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <div class="flex justify-between">
              <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Total Collected</p>
                <p class="text-2xl font-bold text-green-600 dark:text-green-400">
                  ৳{{ formatCurrency(stats.total_collected) }}
                </p>
              </div>
              <CurrencyDollarIcon class="w-8 h-8 text-green-500 opacity-20" />
            </div>
          </div>

          <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
            <div class="flex justify-between">
              <div>
                <p class="text-sm text-gray-600 dark:text-gray-400">Overdue Payments</p>
                <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">
                  {{ stats.overdue_count }}
                </p>
              </div>
              <ExclamationIcon class="w-8 h-8 text-yellow-500 opacity-20" />
            </div>
          </div>
        </div>

        <!-- Filters Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 mb-8">
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Student Name
              </label>
              <input
                v-model="filters.search"
                type="text"
                class="form-input w-full rounded-lg"
                placeholder="Search student..."
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Payment Status
              </label>
              <select
                v-model="filters.status"
                class="form-select w-full rounded-lg"
              >
                <option value="">All Status</option>
                <option value="paid">Paid</option>
                <option value="pending">Pending</option>
                <option value="overdue">Overdue</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                Date Range
              </label>
              <select
                v-model="filters.dateRange"
                class="form-select w-full rounded-lg"
              >
                <option value="all">All Time</option>
                <option value="today">Today</option>
                <option value="week">This Week</option>
                <option value="month">This Month</option>
                <option value="custom">Custom Range</option>
              </select>
            </div>

            <div v-if="filters.dateRange === 'custom'" class="sm:col-span-2 lg:col-span-4 grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  Start Date
                </label>
                <input
                  v-model="filters.startDate"
                  type="date"
                  class="form-input w-full rounded-lg"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                  End Date
                </label>
                <input
                  v-model="filters.endDate"
                  type="date"
                  class="form-input w-full rounded-lg"
                />
              </div>
            </div>
          </div>
        </div>

        <!-- Fee Records Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
              <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                  <th scope="col" class="table-header">Student</th>
                  <th scope="col" class="table-header">Class/Section</th>
                  <th scope="col" class="table-header">Amount</th>
                  <th scope="col" class="table-header">Due Date</th>
                  <th scope="col" class="table-header">Status</th>
                  <th scope="col" class="table-header text-right">Actions</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                <tr
                  v-for="fee in fees.data"
                  :key="fee.id"
                  class="hover:bg-gray-50 dark:hover:bg-gray-700"
                >
                  <td class="table-cell">
                    <div class="flex items-center">
                      <div>
                        <div class="font-medium text-gray-900 dark:text-white">
                          {{ fee.student.name_en }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                          ID: {{ fee.student.student_id }}
                        </div>
                      </div>
                    </div>
                  </td>

                  <td class="table-cell">
                    <div class="text-sm text-gray-900 dark:text-white">
                      {{ fee.school_class.name }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                      Section: {{ fee.section.name }}
                    </div>
                  </td>

                  <td class="table-cell">
                    <div class="text-sm">
                      <div class="font-medium text-gray-900 dark:text-white">
                        ৳{{ formatCurrency(fee.total_fee) }}
                      </div>
                      <div class="text-gray-500 dark:text-gray-400">
                        Paid: ৳{{ formatCurrency(fee.paid_amount) }}
                      </div>
                    </div>
                  </td>

                  <td class="table-cell">
                    <div class="text-sm text-gray-900 dark:text-white">
                      {{ formatDate(fee.due_date) }}
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                      {{ getDaysUntilDue(fee.due_date) }}
                    </div>
                  </td>

                  <td class="table-cell">
                    <StatusBadge :fee="fee" />
                  </td>

                  <td class="table-cell text-right space-x-2">
                    <Link
                      :href="route('admin.student-fees.edit', fee.id)"
                      class="btn-icon"
                      title="Edit"
                    >
                      <PencilIcon class="w-4 h-4" />
                    </Link>

                    <button
                      @click="confirmDelete(fee)"
                      class="btn-icon-danger"
                      title="Delete"
                    >
                      <TrashIcon class="w-4 h-4" />
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div class="p-4 border-t border-gray-200 dark:border-gray-700">
            <Pagination
              :links="fees.links"
              :from="fees.from"
              :to="fees.to"
              :total="fees.total"
            />
          </div>
        </div>
      </div>

      <!-- Delete Confirmation Modal -->
      <ConfirmationDialog
        :show="showDeleteModal"
        :title="`Delete Fee Record`"
        :message="`Are you sure you want to delete the fee record for ${feeToDelete?.student?.name_en}? This action cannot be undone.`"
        @confirm="deleteFee"
        @close="showDeleteModal = false"
      />
    </AdminLayout>
   </template>

   <script setup>
   import { ref, watch } from 'vue';
   import { Head, Link, router } from '@inertiajs/vue3';
   import {
    PlusIcon, TrashIcon, PencilIcon,
    CashIcon, CurrencyDollarIcon, ExclamationIcon
   } from '@heroicons/vue/outline';
   import { format, formatDistance } from 'date-fns';
   import debounce from 'lodash/debounce';
   import AdminLayout from '@/Layouts/AdminLayout.vue';
   import StatusBadge from '@/Components/StatusBadge.vue';
   import Pagination from '@/Components/Pagination.vue';
   import ConfirmationDialog from '@/Components/ConfirmationDialog.vue';

   const props = defineProps({
    fees: Object,
    stats: Object
   });

   // Filters state
   const filters = ref({
    search: '',
    status: '',
    dateRange: 'all',
    startDate: '',
    endDate: ''
   });

   // Delete modal state
   const showDeleteModal = ref(false);
   const feeToDelete = ref(null);

   // Methods
   const formatCurrency = (value) => {
    return Number(value).toLocaleString('en-IN');
   };

   const formatDate = (date) => {
    return format(new Date(date), 'MMM dd, yyyy');
   };

   const getDaysUntilDue = (date) => {
    return formatDistance(new Date(date), new Date(), { addSuffix: true });
   };

   const confirmDelete = (fee) => {
    feeToDelete.value = fee;
    showDeleteModal.value = true;
   };

   const deleteFee = () => {
    router.delete(route('admin.student-fees.destroy', feeToDelete.value.id), {
      onSuccess: () => {
        showDeleteModal.value = false;
        feeToDelete.value = null;
      }
    });
   };

   // Watch filters for changes
   watch(filters, debounce(() => {
    router.get(
      route('admin.student-fees.index'),
      filters.value,
      {
        preserveState: true,
        preserveScroll: true,
        replace: true
      }
    );
   }, 300), { deep: true });
   </script>

   <style scoped>
   .table-header {
    @apply px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider;
   }

   .table-cell {
    @apply px-6 py-4 whitespace-nowrap;
   }

   .btn-icon {
    @apply inline-flex items-center p-2 text-blue-600 hover:text-blue-800 bg-blue-100 hover:bg-blue-200 rounded-lg transition-colors;
   }

   .btn-icon-danger {
    @apply inline-flex items-center p-2 text-red-600 hover:text-red-800 bg-red-100 hover:bg-red-200 rounded-lg transition-colors;
   }

   .form-input, .form-select {
    @apply block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-gray-200;
   }
   </style>
