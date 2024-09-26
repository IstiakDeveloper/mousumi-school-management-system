<template>
    <input
      class="mt-1 block w-full border rounded-md shadow-sm
             border-gray-300 dark:border-gray-600
             dark:bg-gray-700 dark:text-gray-200
             dark:placeholder-gray-400
             focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
      v-model="model"
      ref="input"
      type="text"
      v-bind="$attrs"
    />
  </template>

  <script setup>
  import { onMounted, ref } from 'vue';

  const model = defineModel({
    type: String,
    required: true,
  });

  const input = ref(null);

  onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
      input.value.focus();
    }
  });

  defineExpose({ focus: () => input.value.focus() });
  </script>

  <style scoped>
  /* Additional styles can go here if needed */
  </style>
