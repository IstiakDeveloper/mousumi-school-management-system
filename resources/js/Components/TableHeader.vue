<!-- Components/TableHeader.vue -->
<template>
    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider"
        :class="[
          sortable ? 'cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-600' : '',
          'text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-700'
        ]"
        @click="sortable && $emit('sort')"
    >
      <div class="flex items-center space-x-1">
        <slot></slot>
        <div v-if="sortable" class="flex flex-col ml-1">
          <svg class="w-3 h-3" :class="sortDirection === 'asc' ? 'text-gray-900 dark:text-white' : 'text-gray-400'"
               viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 4l-8 8h16l-8-8z"/>
          </svg>
          <svg class="w-3 h-3" :class="sortDirection === 'desc' ? 'text-gray-900 dark:text-white' : 'text-gray-400'"
               viewBox="0 0 24 24" fill="currentColor">
            <path d="M12 20l-8-8h16l-8 8z"/>
          </svg>
        </div>
      </div>
    </th>
   </template>

   <script setup>
   defineProps({
    sortable: {
      type: Boolean,
      default: false
    },
    sortDirection: {
      type: String,
      default: null,
      validator: value => ['asc', 'desc', null].includes(value)
    }
   });

   defineEmits(['sort']);
   </script>
