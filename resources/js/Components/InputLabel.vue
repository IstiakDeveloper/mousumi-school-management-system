<template>
    <label class="block font-medium text-sm
                  text-gray-700 dark:text-gray-300">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>

<script setup>
defineProps({
    value: {
        type: String,
        required: false,
    },
});
</script>

<style scoped>
/* Additional styles can be added here if necessary */
</style>
