<template>
    <div class="flex items-center justify-center gap-1 md:gap-2">
      <!-- Previous Button -->
      <button
        :disabled="currentPage === 1"
        @click="$emit('update:modelValue', currentPage - 1)"
        class="pagination-button"
        :class="{ 'opacity-50 cursor-not-allowed': currentPage === 1 }"
      >
        <ChevronLeftIcon class="h-5 w-5" />
        <span class="sr-only">Previous</span>
      </button>

      <!-- Page Numbers -->
      <div class="flex items-center gap-1 md:gap-2">
        <template v-for="page in displayedPages" :key="page">
          <span v-if="page === '...'" class="px-3 py-2 text-gray-500 dark:text-gray-400">
            ...
          </span>
          <button
            v-else
            @click="$emit('update:modelValue', page)"
            class="pagination-button"
            :class="{
              'bg-blue-600 text-white hover:bg-blue-700': page === currentPage,
              'hover:bg-gray-100 dark:hover:bg-gray-700': page !== currentPage
            }"
            :aria-current="page === currentPage ? 'page' : undefined"
          >
            {{ page }}
          </button>
        </template>
      </div>

      <!-- Next Button -->
      <button
        :disabled="currentPage === total"
        @click="$emit('update:modelValue', currentPage + 1)"
        class="pagination-button"
        :class="{ 'opacity-50 cursor-not-allowed': currentPage === total }"
      >
        <ChevronRightIcon class="h-5 w-5" />
        <span class="sr-only">Next</span>
      </button>
    </div>
  </template>

  <script setup>
  import { computed } from 'vue';
  import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/24/solid';

  const props = defineProps({
    modelValue: {
      type: Number,
      required: true
    },
    total: {
      type: Number,
      required: true
    }
  });

  defineEmits(['update:modelValue']);

  const currentPage = computed(() => props.modelValue);

  const displayedPages = computed(() => {
    const pages = [];
    const total = props.total;
    const current = props.modelValue;

    if (total <= 7) {
      return Array.from({ length: total }, (_, i) => i + 1);
    }

    pages.push(1);

    if (current > 3) {
      pages.push('...');
    }

    const start = Math.max(2, current - 1);
    const end = Math.min(total - 1, current + 1);

    for (let i = start; i <= end; i++) {
      pages.push(i);
    }

    if (current < total - 2) {
      pages.push('...');
    }

    pages.push(total);

    return pages;
  });
  </script>

  <style scoped>
  .pagination-button {
    @apply inline-flex items-center px-3 py-2 text-sm font-medium rounded-md
      border border-gray-300 dark:border-gray-600
      bg-white dark:bg-gray-800
      text-gray-700 dark:text-gray-200
      transition-colors duration-200
      focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500;
  }

  .pagination-button:disabled {
    @apply cursor-not-allowed opacity-50;
  }
  </style>
