<template>
    <div class="flex items-center space-x-2">
      <ClockIcon
        v-if="time"
        class="w-4 h-4 text-gray-400"
      />
      <span
        class="text-sm"
        :class="[
          time ? 'text-gray-900 dark:text-gray-100' : 'text-gray-500 dark:text-gray-400'
        ]"
      >
        {{ formattedTime }}
      </span>
    </div>
  </template>

  <script setup>
  import { computed } from 'vue';
  import { ClockIcon } from '@heroicons/vue/outline';
  import { format, parseISO } from 'date-fns';

  const props = defineProps({
    time: {
      type: String,
      default: null
    }
  });

  const formattedTime = computed(() => {
    if (!props.time) return '-';
    return format(parseISO(`2000-01-01T${props.time}`), 'hh:mm a');
  });
  </script>
