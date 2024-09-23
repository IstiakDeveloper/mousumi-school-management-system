<template>
  <div class="flex h-screen overflow-hidden bg-gray-100">
    <!-- Sidebar -->
    <aside 
      :class="[
        'fixed inset-y-0 left-0 w-64 bg-white shadow-lg transition-transform transform lg:translate-x-0 z-10',
        showSidebar ? 'translate-x-0' : '-translate-x-64'
      ]"
    >
      <div class="p-4 flex items-center justify-between">
        <h1 class="text-xl font-bold text-gray-800">Admin Dashboard</h1>
        <button @click="toggleSidebar" class="lg:hidden text-gray-500 hover:text-gray-600">
          <!-- Close Icon -->
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
          </svg>
        </button>
      </div>
      <nav class="mt-5">
        <ul>
          <li v-for="item in navItems" :key="item.name" class="relative">
            <!-- Main Link -->
            <Link
              v-if="!item.children"
              :href="item.link"
              class="w-full flex items-center gap-2 px-4 py-2 text-sm text-gray-600 hover:bg-gray-200"
              :class="{ 'bg-gray-200': isActive(item.link) }"
            >
              <!-- Icon -->
              <svg v-if="item.icon" class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path :d="item.icon"></path>
              </svg>
              <span>{{ item.name }}</span>
            </Link>

            <!-- Dropdown Button -->
            <button
              v-if="item.children"
              @click="toggleDropdown(item)"
              class="w-full flex items-center gap-2 px-4 py-2 text-sm text-gray-600 hover:bg-gray-200"
              :class="{ 'bg-gray-200': isActive(item.link) || hasActiveChild(item) }"
            >
              <!-- Icon -->
              <svg v-if="item.icon" class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path :d="item.icon"></path>
              </svg>
              <span>{{ item.name }}</span>
              <!-- Dropdown Arrow -->
              <svg
                :class="{ 'transform rotate-180': item.isOpen || hasActiveChild(item) }"
                class="w-4 h-4 transition-transform"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
              </svg>
            </button>

            <!-- Dropdown Links -->
            <ul v-if="item.children && (item.isOpen || hasActiveChild(item))" class="pl-8">
              <li v-for="child in item.children" :key="child.name">
                <Link
                  :href="child.link"
                  class="flex items-center px-4 py-2 text-sm text-gray-600 hover:bg-gray-200 w-full rounded-md"
                  :class="{ 'bg-gray-200': isActive(child.link) }"
                >
                  <!-- Icon -->
                  <svg v-if="child.icon" class="w-5 h-5 text-gray-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"></path>
                  </svg>
                  <!-- Text -->
                  <span>{{ child.name }}</span>
                </Link>
              </li>
            </ul>
          </li>
        </ul>
      </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 flex flex-col lg:ml-64">
      <!-- Top Bar -->
      <header class="fixed top-0 left-0 right-0 lg:left-64 bg-white shadow-md z-20 flex justify-between items-center p-4">
        <button @click="toggleSidebar" class="lg:hidden text-gray-500 hover:text-gray-600">
          <!-- Menu Icon -->
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
          </svg>
        </button>
        <div class="relative">
          <button @click="toggleUserMenu" class="flex items-center space-x-2 focus:outline-none">
            <img src="https://via.placeholder.com/32" class="w-8 h-8 rounded-full" alt="User Avatar">
            <span class="text-gray-600">User Name</span>
            <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
            </svg>
          </button>
          <!-- User Dropdown -->
          <div v-if="showUserMenu" class="absolute right-0 mt-2 w-48 bg-white border rounded-lg shadow-lg">
            <Link href="/profile" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profile</Link>
            <button @click="logout" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">Logout</button>
          </div>
        </div>
      </header>

      <!-- Page Content -->
      <main class="flex-1 p-6 mt-16 overflow-auto bg-gray-100">
        <!-- Center Content -->
        <div class="max-w-4xl mx-auto">
          <slot></slot>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';

const showSidebar = ref(false);
const showUserMenu = ref(false);

const toggleSidebar = () => {
  showSidebar.value = !showSidebar.value;
};

const toggleUserMenu = () => {
  showUserMenu.value = !showUserMenu.value;
};

// Navigation items with expanded states
const navItems = ref([
  {
    name: 'Dashboard',
    link: '/dashboard',
    icon: 'M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z', // SVG path data for a square icon
    isActive: false,
  },
  {
    name: 'Role & Permission',
    link: '/',
    icon: 'M11.35 3.836c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m8.9-4.414c.376.023.75.05 1.124.08 1.131.094 1.976 1.057 1.976 2.192V16.5A2.25 2.25 0 0 1 18 18.75h-2.25m-7.5-10.5H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V18.75m-7.5-10.5h6.375c.621 0 1.125.504 1.125 1.125v9.375m-8.25-3 1.5 1.5 3-3.75', // SVG path data for a lock icon
    isActive: false,
    children: [
      { name: 'roles', link: '/roles', icon: 'M5 5h14v14H5V5z', isActive: false },
      { name: 'Permissions', link: '/permissions', icon: 'M2 2h20v20H2V2z', isActive: false },
      { name: 'Assign Permissions', link: '/role-permissions', icon: 'M2 2h20v20H2V2z', isActive: false },
    ],
  },
  {
    name: 'Users',
    link: '/users',
    icon: 'M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z', 
    isActive: false,
  },
]);

const toggleDropdown = (item) => {
  item.isOpen = !item.isOpen;
};

// Utility methods
const isActive = (link) => {
  return usePage().url === link;
};

const hasActiveChild = (parent) => {
  return parent.children.some(child => isActive(child.link) || (child.children && hasActiveChild(child)));
};

const logout = () => {
  // Handle logout logic here
};
</script>
