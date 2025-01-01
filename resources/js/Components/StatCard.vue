<template>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
      <div class="p-6">
        <div class="flex items-center justify-between">
          <div class="flex-1">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ title }}</h3>
            <div class="mt-1 flex items-baseline">
              <p class="text-2xl font-semibold" :class="colorClasses.text">{{ formattedValue }}</p>
              <p v-if="percentage" class="ml-2 flex items-baseline text-sm font-semibold" :class="trendClasses">
                <svg v-if="trend === 'up'" class="self-center flex-shrink-0 h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M12 19V5l-8 8 1.41 1.41L12 7.83l6.59 6.58L20 13l-8-8z"/>
                </svg>
                <svg v-else class="self-center flex-shrink-0 h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                  <path d="M20 12l-1.41-1.41L12 17.17l-6.59-6.58L4 12l8 8 8-8z"/>
                </svg>
                {{ percentage.toFixed(1) }}%
              </p>
            </div>
          </div>
          <div :class="colorClasses.bg" class="p-3 rounded-full bg-opacity-10">
            <component :is="selectedIcon" class="h-6 w-6" :class="colorClasses.text" />
          </div>
        </div>
      </div>
    </div>
  </template>

  <script setup>
  import { computed } from 'vue';
  import {
    UserGroupIcon,
    CheckCircleIcon,
    CurrencyDollarIcon,
    ExclamationCircleIcon
  } from '@heroicons/vue/24/solid';

  const props = defineProps({
    title: { type: String, required: true },
    value: { type: [Number, String], required: true },
    icon: { type: String, default: 'UserGroupIcon' },
    color: { type: String, default: 'blue' },
    percentage: { type: Number, default: null },
    trend: { type: String, default: 'up' }
  });

  const selectedIcon = computed(() => {
    const icons = {
      UserGroupIcon,
      CheckCircleIcon,
      CurrencyDollarIcon,
      ExclamationCircleIcon
    };
    return icons[props.icon];
  });

  const colorClasses = computed(() => ({
    blue: { bg: 'bg-blue-500', text: 'text-blue-600 dark:text-blue-400' },
    green: { bg: 'bg-green-500', text: 'text-green-600 dark:text-green-400' },
    red: { bg: 'bg-red-500', text: 'text-red-600 dark:text-red-400' },
    yellow: { bg: 'bg-yellow-500', text: 'text-yellow-600 dark:text-yellow-400' },
    indigo: { bg: 'bg-indigo-500', text: 'text-indigo-600 dark:text-indigo-400' }
  }[props.color] || { bg: 'bg-blue-500', text: 'text-blue-600 dark:text-blue-400' }));

  const trendClasses = computed(() =>
    props.trend === 'up' ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'
  );

  const formattedValue = computed(() =>
    typeof props.value === 'number' ? props.value.toLocaleString() : props.value
  );
  </script>
