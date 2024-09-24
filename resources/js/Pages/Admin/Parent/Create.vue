<template>
    <Head title="Create Parent" />
  
    <AdminLayout>
      <div class="container mx-auto py-8">
        <div class="bg-white shadow-lg rounded-lg p-8">
          <h1 class="text-3xl font-bold text-gray-800 mb-6">Create Parent</h1>
          
          <form @submit.prevent="submit">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input 
                  v-model="form.name" 
                  type="text" 
                  id="name" 
                  required 
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" 
                />
                <span v-if="form.errors.name" class="text-red-600 text-sm">{{ form.errors.name }}</span>
              </div>
  
              <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input 
                  v-model="form.email" 
                  type="email" 
                  id="email" 
                  required 
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" 
                />
                <span v-if="form.errors.email" class="text-red-600 text-sm">{{ form.errors.email }}</span>
              </div>
  
              <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                <input 
                  v-model="form.phone" 
                  type="text" 
                  id="phone" 
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" 
                />
                <span v-if="form.errors.phone" class="text-red-600 text-sm">{{ form.errors.phone }}</span>
              </div>
  
              <div>
                <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                <input 
                  v-model="form.address" 
                  type="text" 
                  id="address" 
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50" 
                />
                <span v-if="form.errors.address" class="text-red-600 text-sm">{{ form.errors.address }}</span>
              </div>
            </div>
  
            <div class="mt-6">
              <button type="submit" class="btn-primary">
                Create Parent
              </button>
            </div>
          </form>
        </div>
      </div>
    </AdminLayout>
  </template>
  
  <script setup>
  import { ref } from 'vue';
  import { Head, useForm } from '@inertiajs/vue3';
  import AdminLayout from '@/Layouts/AdminLayout.vue';
  
  const form = useForm({
    name: '',
    email: '',
    phone: '',
    address: '',
  });
  
  function submit() {
    form.post(route('admin.parents.store'), {
      onSuccess: () => {
        // Optionally, you could redirect to the parents index after successful creation
        // e.g., this.$inertia.visit(route('admin.parents.index'));
      },
      onError: () => {
        // Handle errors if needed
      },
    });
  }
  </script>
  
  <style scoped>
  .btn-primary {
    @apply bg-blue-600 text-white font-semibold py-2 px-4 rounded shadow hover:bg-blue-700 transition duration-200;
  }
  </style>
  