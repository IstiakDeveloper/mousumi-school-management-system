<!-- components/Pagination.vue -->
<template>
    <div v-if="links.length > 3" class="flex items-center justify-center gap-1 md:gap-2">
      <!-- Previous Button -->
      <button
        :disabled="!hasPreviousPage"
        @click="handleClick(previousPageUrl)"
        class="pagination-button"
        :class="{ 'opacity-50 cursor-not-allowed': !hasPreviousPage }"
      >
        <ChevronLeftIcon class="h-5 w-5" />
        <span class="sr-only">Previous</span>
      </button>

      <!-- Page Links -->
      <div class="flex items-center gap-1 md:gap-2">
        <template v-for="(link, index) in normalizedLinks" :key="index">
          <!-- Current Page -->
          <button
            v-if="link.active"
            class="pagination-button bg-blue-600 text-white hover:bg-blue-700"
            aria-current="page"
          >
            {{ link.label }}
          </button>

          <!-- Other Pages -->
          <button
            v-else-if="link.url && !link.label.includes('Previous') && !link.label.includes('Next')"
            @click="handleClick(link.url)"
            class="pagination-button hover:bg-gray-100 dark:hover:bg-gray-700"
          >
            {{ link.label }}
          </button>

          <!-- Ellipsis -->
          <span
            v-else-if="link.label === '...'"
            class="px-3 py-2 text-gray-500 dark:text-gray-400"
          >
            ...
          </span>
        </template>
      </div>

      <!-- Next Button -->
      <button
        :disabled="!hasNextPage"
        @click="handleClick(nextPageUrl)"
        class="pagination-button"
        :class="{ 'opacity-50 cursor-not-allowed': !hasNextPage }"
      >
        <ChevronRightIcon class="h-5 w-5" />
        <span class="sr-only">Next</span>
      </button>
    </div>
  </template>

  <script setup>
  import { computed } from 'vue';
  import { router } from '@inertiajs/vue3';
  import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/24/solid';

  const props = defineProps({
    links: {
      type: Array,
      required: true
    }
  });

  // Computed Properties
  const normalizedLinks = computed(() => {
    return props.links.filter(link => {
      return link.label !== 'Previous' && link.label !== 'Next';
    });
  });

  const hasPreviousPage = computed(() => {
    return props.links.find(link => link.label === 'Previous')?.url !== null;
  });

  const hasNextPage = computed(() => {
    return props.links.find(link => link.label === 'Next')?.url !== null;
  });

  const previousPageUrl = computed(() => {
    return props.links.find(link => link.label === 'Previous')?.url;
  });

  const nextPageUrl = computed(() => {
    return props.links.find(link => link.label === 'Next')?.url;
  });

  // Methods
  const handleClick = (url) => {
    if (url) {
      router.get(url, {}, {
        preserveScroll: true,
        preserveState: true,
      });
    }
  };
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
