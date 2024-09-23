<template>
  <Head title="School Class Details" />

  <AdminLayout>
    <div class="container mx-auto py-8">
      <div class="bg-white shadow-lg rounded-lg p-8">
        <h1 class="text-3xl font-bold mb-4">{{ class.name }}</h1>
        <p class="mb-4">{{ class.description }}</p>
        <Link :href="`/admin/school-classes/${class.id}/edit`" class="btn-primary">Edit Class</Link>
        <button @click="confirmDelete(class.id)" class="btn-danger ml-4">Delete Class</button>
      </div>
    </div>

    <!-- Confirm Dialog -->
    <ConfirmDialog :show="showDialog" @update:show="showDialog = false" @confirm="deleteClass" />
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ConfirmDialog from '@/Components/ConfirmationDialog.vue';

const props = defineProps({
  class: Object,
});

const showDialog = ref(false);
const currentClassId = ref(null);

function confirmDelete(classId) {
  showDialog.value = true;
  currentClassId.value = classId;
}

function deleteClass() {
  if (currentClassId.value) {
    Inertia.delete(route('school-classes.destroy', currentClassId.value), {
      onSuccess: () => {
        // Redirect to the index page after deletion
        Inertia.visit('/admin/school-classes');
      },
    });
  }
}
</script>

<style scoped>
/* Add your styles here */
</style>
