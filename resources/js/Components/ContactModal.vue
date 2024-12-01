<script setup>
import { ref } from 'vue';

const props = defineProps({
    isOpen: Boolean
});

const emit = defineEmits(['close']);

const formData = ref({
    name: '',
    email: '',
    phone: '',
    subject: '',
    message: ''
});

const isSubmitting = ref(false);
const showSuccess = ref(false);

const submitForm = async () => {
    isSubmitting.value = true;
    await new Promise(resolve => setTimeout(resolve, 1000));
    showSuccess.value = true;
    isSubmitting.value = false;
    setTimeout(() => {
        showSuccess.value = false;
        emit('close');
        formData.value = { name: '', email: '', phone: '', subject: '', message: '' };
    }, 2000);
};
</script>

<template>
    <div v-if="isOpen"
        class="fixed inset-0 bg-black/50 dark:bg-black/70 z-50 flex items-center justify-center p-4"
        @click="emit('close')"
    >
        <div
            class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-lg transform transition-all duration-300"
            :class="showSuccess ? 'scale-105' : 'scale-100'"
            @click.stop
        >
            <!-- Success Message -->
            <div v-if="showSuccess" class="p-8 text-center animate-fade-in-up">
                <div class="mb-4">
                    <i class="fas fa-check-circle text-green-500 text-5xl animate-bounce"></i>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Message Sent Successfully!</h3>
                <p class="text-gray-600 dark:text-gray-300">We'll get back to you soon.</p>
            </div>

            <!-- Contact Form -->
            <div v-else>
                <div class="p-6 border-b border-gray-100 dark:border-gray-700">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Contact Us</h2>
                    <p class="text-gray-600 dark:text-gray-300 mt-1">Fill out the form and we'll get back to you shortly.</p>
                </div>

                <form @submit.prevent="submitForm" class="p-6 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Name</label>
                            <input
                                type="text"
                                v-model="formData.name"
                                required
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                                    focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700
                                    dark:text-white transition"
                                :disabled="isSubmitting"
                            >
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Email</label>
                            <input
                                type="email"
                                v-model="formData.email"
                                required
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                                    focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700
                                    dark:text-white transition"
                                :disabled="isSubmitting"
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Phone</label>
                            <input
                                type="tel"
                                v-model="formData.phone"
                                required
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                                    focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700
                                    dark:text-white transition"
                                :disabled="isSubmitting"
                            >
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Subject</label>
                            <select
                                v-model="formData.subject"
                                required
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                                    focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700
                                    dark:text-white transition"
                                :disabled="isSubmitting"
                            >
                                <option value="">Select a subject</option>
                                <option value="admission">Admission Inquiry</option>
                                <option value="academic">Academic Information</option>
                                <option value="general">General Query</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-700 dark:text-gray-200">Message</label>
                        <textarea
                            v-model="formData.message"
                            required
                            rows="4"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg
                                focus:ring-2 focus:ring-green-500 focus:border-green-500 dark:bg-gray-700
                                dark:text-white transition"
                            :disabled="isSubmitting"
                        ></textarea>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <button
                            type="button"
                            @click="emit('close')"
                            class="px-4 py-2 text-gray-700 dark:text-gray-300 border border-gray-300
                                dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition"
                            :disabled="isSubmitting"
                        >
                            Cancel
                        </button>
                        <button
                            type="submit"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700
                                transition flex items-center space-x-2"
                            :disabled="isSubmitting"
                        >
                            <span v-if="isSubmitting">
                                <i class="fas fa-circle-notch fa-spin"></i> Sending...
                            </span>
                            <span v-else>Send Message</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

