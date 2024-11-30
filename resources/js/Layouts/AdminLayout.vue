<template>
    <div class="flex h-screen overflow-hidden bg-gray-50 dark:bg-gray-900">
      <!-- Sidebar -->
      <aside
        :class="[
          'bg-white dark:bg-gray-800 fixed inset-y-0 left-0 w-64 transition-transform lg:translate-x-0 z-20 border-r border-gray-200/80 dark:border-gray-700',
          showSidebar ? 'translate-x-0 shadow-2xl lg:shadow-none' : '-translate-x-64'
        ]"
      >
        <!-- Logo Section -->
        <div class="h-16 flex items-center gap-2 px-6 border-b border-gray-200/80 dark:border-gray-700">
          <Link href="/dashboard" class="flex items-center gap-2">
            <i class="fa-solid fa-graduation-cap text-2xl text-blue-600 dark:text-blue-400"></i>
            <span class="text-xl font-bold bg-gradient-to-r from-blue-600 to-blue-400 bg-clip-text text-transparent">EduAdmin</span>
          </Link>
          <button @click="toggleSidebar" class="lg:hidden ml-auto text-gray-400 hover:text-gray-600 dark:text-gray-300 dark:hover:text-white">
            <i class="fa-solid fa-xmark text-xl"></i>
          </button>
        </div>

        <!-- Navigation Section -->
        <div class="h-[calc(100vh-4rem)] overflow-y-auto">
          <nav class="p-4 space-y-1">
            <template v-for="item in filteredNavItems" :key="item.name">
              <!-- Single Item -->
              <Link
                v-if="!item.children"
                :href="item.link"
                class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200"
                :class="[
                  isActive(item.link)
                    ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-500/10'
                    : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700'
                ]"
              >
                <i :class="['fa-solid', item.icon, 'w-5 text-lg']"></i>
                {{ item.name }}
              </Link>

              <!-- Dropdown Item -->
              <div v-else class="space-y-1">
                <button
                  @click="toggleDropdown(item)"
                  class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-medium transition-all duration-200"
                  :class="[
                    hasActiveChild(item)
                      ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-500/10'
                      : 'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700'
                  ]"
                >
                  <div class="flex items-center gap-3">
                    <i :class="['fa-solid', item.icon, 'w-5 text-lg']"></i>
                    {{ item.name }}
                  </div>
                  <i
                    :class="['fa-solid', item.isOpen || hasActiveChild(item) ? 'fa-chevron-down' : 'fa-chevron-right', 'text-sm transition-transform duration-200']"
                  ></i>
                </button>

                <!-- Dropdown Content -->
                <div v-show="item.isOpen || hasActiveChild(item)" class="mt-1 ml-4 pl-4 border-l border-gray-200 dark:border-gray-700">
                  <Link
                    v-for="child in item.children"
                    :key="child.name"
                    :href="child.link"
                    class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm transition-all duration-200"
                    :class="[
                      isActive(child.link)
                        ? 'text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-500/10 font-medium'
                        : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'
                    ]"
                  >
                    <i :class="['fa-solid', child.icon, 'w-5']"></i>
                    {{ child.name }}
                  </Link>
                </div>
              </div>
            </template>
          </nav>
        </div>
      </aside>

      <!-- Main Content Area -->
      <div class="flex-1 flex flex-col lg:ml-64">
        <!-- Header -->
        <header class="h-16 bg-white dark:bg-gray-800 border-b border-gray-200/80 dark:border-gray-700 flex items-center gap-4 px-4 fixed top-0 right-0 left-0 lg:left-64 z-10">
          <button
            @click="toggleSidebar"
            class="lg:hidden text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white"
          >
            <i class="fa-solid fa-bars text-xl"></i>
          </button>

          <div class="ml-auto flex items-center gap-2">
            <div class="relative">
              <button
                @click="toggleUserMenu"
                class="flex items-center gap-2 px-2 py-1.5 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700"
              >
                <img
                  :src="user.avatar || 'https://via.placeholder.com/32'"
                  class="w-8 h-8 rounded-full object-cover ring-2 ring-gray-200 dark:ring-gray-700"
                  :alt="user.name"
                >
                <span class="text-sm font-medium text-gray-700 dark:text-gray-200">{{ user.name }}</span>
                <i class="fa-solid fa-chevron-down text-xs text-gray-400"></i>
              </button>

              <!-- User Menu Dropdown -->
              <div
                v-if="showUserMenu"
                class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1"
              >
                <Link
                  href="/profile"
                  class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700"
                >
                  <i class="fa-solid fa-user w-4"></i>
                  <span>Profile</span>
                </Link>
                <hr class="my-1 border-gray-200 dark:border-gray-700">
                <Link
                  :href="route('logout')"
                  method="post"
                  as="button"
                  class="w-full flex items-center gap-2 px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700"
                >
                  <i class="fa-solid fa-right-from-bracket w-4"></i>
                  <span>Logout</span>
                </Link>
              </div>
            </div>

            <div class="w-px h-8 bg-gray-200 dark:bg-gray-700"></div>

            <button
              @click="switchTheme"
              class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-300 dark:hover:text-white rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700"
            >
              <i class="fa-solid fa-circle-half-stroke text-xl"></i>
            </button>
          </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 p-6 mt-16 overflow-y-auto">
          <!-- Flash Messages -->
          <TransitionGroup name="fade" tag="div" class="space-y-2">
            <div
              v-if="showFlashSuccess"
              key="success"
              class="flex items-center gap-2 p-4 bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-lg border border-green-200 dark:border-green-800"
            >
              <i class="fa-solid fa-check-circle"></i>
              {{ flash.success }}
            </div>
            <div
              v-if="showFlashError"
              key="error"
              class="flex items-center gap-2 p-4 bg-red-50 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-lg border border-red-200 dark:border-red-800"
            >
              <i class="fa-solid fa-exclamation-circle"></i>
              {{ flash.error }}
            </div>
          </TransitionGroup>

          <!-- Page Content -->
          <div class="mt-4">
            <slot></slot>
          </div>
        </main>
      </div>
    </div>
  </template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';
import { switchTheme } from '@/theme';

const page = usePage();
const { flash, auth } = page.props;
const user = auth.user;

const showSidebar = ref(false);
const showUserMenu = ref(false);
const showFlashSuccess = ref(!!flash.success);
const showFlashError = ref(!!flash.error);

// Navigation items with FontAwesome icons
const navItems = ref([
  {
    name: 'Dashboard',
    link: '/dashboard',
    icon: 'fa-gauge-high',
  },
  {
    name: 'Role & Permission',
    icon: 'fa-shield-halved',
    roles: ['admin'],
    children: [
      { name: 'Roles', link: '/roles', icon: 'fa-user-shield' },
      { name: 'Permissions', link: '/permissions', icon: 'fa-key' },
      { name: 'Assign Permissions', link: '/role-permissions', icon: 'fa-user-lock' }
    ]
  },
  {
    name: 'Academics',
    icon: 'fa-graduation-cap',
    roles: ['admin', 'teacher'],
    children: [
      { name: 'Students', link: '/admin/students', icon: 'fa-users' },
      { name: 'Teachers', link: '/admin/teachers', icon: 'fa-chalkboard-user' },
      { name: 'Parents', link: '/admin/parents', icon: 'fa-people-roof' },
      { name: 'Classes', link: '/admin/school-classes', icon: 'fa-school' },
      { name: 'Sections', link: '/admin/sections', icon: 'fa-layer-group' },
      { name: 'Subjects', link: '/admin/subjects', icon: 'fa-book' },
      { name: 'Syllabus', link: '/admin/syllabus', icon: 'fa-book-open' }
    ]
  },
  {
    name: 'Examination',
    icon: 'fa-file-lines',
    roles: ['admin', 'teacher'],
    children: [
      { name: 'Exam Categories', link: '/admin/exam-categories', icon: 'fa-folder' },
      { name: 'Exams', link: '/admin/exams', icon: 'fa-file-circle-check' },
      { name: 'Grades', link: '/admin/grades', icon: 'fa-star' },
      { name: 'Marks', link: '/admin/gradebooks', icon: 'fa-chart-simple' }
    ]
  },
  {
    name: 'Accounts',
    icon: 'fa-wallet',
    roles: ['admin'],
    children: [
      { name: 'Student Fees', link: '/admin/payments', icon: 'fa-money-bill' },
      { name: 'Teacher Salaries', link: '/admin/teachers/salaries', icon: 'fa-money-bill-transfer' },
      { name: 'Expenses', link: '/admin/expenses', icon: 'fa-receipt' },
      { name: 'Expense Categories', link: '/admin/expense-categories', icon: 'fa-folder-open' }
    ]
  },
  {
    name: 'Reports',
    icon: 'fa-chart-pie',
    roles: ['admin'],
    children: [
      { name: 'Expenses', link: '/admin/reports/expenses', icon: 'fa-chart-column' },
      { name: 'Student Fees', link: '/admin/reports/student-fees', icon: 'fa-chart-line' },
      { name: 'Teacher Salaries', link: '/admin/reports/teacher-salaries', icon: 'fa-chart-area' },
      { name: 'Bank Balance', link: '/admin/reports/bank-balance', icon: 'fa-building-columns' }
    ]
  },
  {
    name: 'Attendance',
    icon: 'fa-calendar-check',
    roles: ['admin', 'teacher'],
    children: [
      { name: 'Teacher Attendance', link: '/admin/teacher-attendance', icon: 'fa-clipboard-user' }
    ]
  },
  {
    name: 'Users',
    link: '/users',
    icon: 'fa-users-gear',
    roles: ['admin']
  }
]);

const filteredNavItems = computed(() => navItems.value);

// Utility functions
const toggleSidebar = () => showSidebar.value = !showSidebar.value;
const toggleUserMenu = () => showUserMenu.value = !showUserMenu.value;
const toggleDropdown = (item) => item.isOpen = !item.isOpen;


const isActive = (link) => page.url === link;
const hasActiveChild = (parent) => {
  if (!Array.isArray(parent.children)) return false;
  return parent.children.some(child => isActive(child.link));
};



// Flash message handling
onMounted(() => {
  if (flash.success || flash.error) {
    setTimeout(() => {
      showFlashSuccess.value = false;
      showFlashError.value = false;
    }, 3000);
  }
});

watch(() => flash.success, (newVal) => {
  if (newVal) {
    showFlashSuccess.value = true;
    setTimeout(() => showFlashSuccess.value = false, 3000);
  }
});

watch(() => flash.error, (newVal) => {
  if (newVal) {
    showFlashError.value = true;
    setTimeout(() => showFlashError.value = false, 3000);
  }
});
</script>

<style>
.fade-enter-active,
.fade-leave-active {
  transition: all 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}
</style>
