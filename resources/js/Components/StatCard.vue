<template>
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
      <div class="p-6">
        <div class="flex items-center justify-between">
          <div class="flex-1">
            <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ title }}</h3>
            <div class="mt-1 flex items-baseline">
              <p class="text-2xl font-semibold" :class="colorClasses.text">{{ formattedValue }}</p>
              <p v-if="percentage" class="ml-2 flex items-baseline text-sm font-semibold" :class="trendClasses">
                <component
                  :is="trend === 'up' ? ArrowUpIcon : ArrowDownIcon"
                  class="self-center flex-shrink-0 h-4 w-4 mr-1"
                />
                {{ percentage.toFixed(1) }}%
              </p>
            </div>
          </div>
          <div :class="[colorClasses.bg, 'p-3 rounded-full bg-opacity-10']">
            <component
              :is="selectedIcon"
              class="h-6 w-6"
              :class="colorClasses.text"
            />
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
    ExclamationCircleIcon,
    ArrowUpIcon,
    ArrowDownIcon
  } from '@heroicons/vue/24/solid';

  const props = defineProps({
    title: {
      type: String,
      required: true
    },
    value: {
      type: [Number, String],
      required: true
    },
    icon: {
      type: String,
      default: 'UserGroupIcon',
      validator: (value) => ['UserGroupIcon', 'CheckCircleIcon', 'CurrencyDollarIcon', 'ExclamationCircleIcon'].includes(value)
    },
    color: {
      type: String,
      default: 'blue',
      validator: (value) => ['blue', 'green', 'red', 'yellow', 'indigo'].includes(value)
    },
    percentage: {
      type: Number,
      default: null
    },
    trend: {
      type: String,
      default: 'up',
      validator: (value) => ['up', 'down'].includes(value)
    }
  });

  const ICONS = {
    UserGroupIcon,
    CheckCircleIcon,
    CurrencyDollarIcon,
    ExclamationCircleIcon
  };

  const COLORS = {
    blue: {
      bg: 'bg-blue-500',
      text: 'text-blue-600 dark:text-blue-400'
    },
    green: {
      bg: 'bg-green-500',
      text: 'text-green-600 dark:text-green-400'
    },
    red: {
      bg: 'bg-red-500',
      text: 'text-red-600 dark:text-red-400'
    },
    yellow: {
      bg: 'bg-yellow-500',
      text: 'text-yellow-600 dark:text-yellow-400'
    },
    indigo: {
      bg: 'bg-indigo-500',
      text: 'text-indigo-600 dark:text-indigo-400'
    }
  };

  const selectedIcon = computed(() => ICONS[props.icon]);

  const colorClasses = computed(() => COLORS[props.color] || COLORS.blue);

  const trendClasses = computed(() =>
    props.trend === 'up'
      ? 'text-green-600 dark:text-green-400'
      : 'text-red-600 dark:text-red-400'
  );

  const formattedValue = computed(() => {
    if (typeof props.value === 'number') {
      return new Intl.NumberFormat('en-IN').format(props.value);
    }
    return props.value;
  });
  </script>
