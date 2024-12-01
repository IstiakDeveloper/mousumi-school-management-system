<script setup>
import { onMounted, ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import ContactModal from '@/Components/ContactModal.vue';

const isContactModalOpen = ref(false);
const isVisible = ref(false);
const isMobileMenuOpen = ref(false);
const statsRef = ref(null);
const isScrolled = ref(false);

const menuItems = ref([
    { name: 'About', section: 'about', showContact: true },
    { name: 'Features', section: 'features', showContact: true },
    { name: 'News', section: 'news', showContact: true },
    { name: 'Contact', section: '', showContact: true },
]);

const stats = ref([
    { label: 'Students', value: '1200+', icon: 'fas fa-user-graduate', startValue: 0, endValue: 1200 },
    { label: 'Teachers', value: '50+', icon: 'fas fa-chalkboard-teacher', startValue: 0, endValue: 50 },
    { label: 'Success Rate', value: '98%', icon: 'fas fa-chart-line', startValue: 0, endValue: 98 },
    { label: 'Years of Excellence', value: '25+', icon: 'fas fa-building-columns', startValue: 0, endValue: 25 }
]);

const features = ref([
    {
        title: 'Academic Excellence',
        description: 'Providing quality education with modern teaching methods and experienced faculty.',
        icon: 'fas fa-book-reader'
    },
    {
        title: 'Sports & Activities',
        description: 'Comprehensive sports facilities and extracurricular activities for holistic development.',
        icon: 'fas fa-running'
    },
    {
        title: 'Modern Facilities',
        description: 'Well-equipped labs, library, and smart classrooms for enhanced learning experience.',
        icon: 'fas fa-laptop'
    },
    {
        title: 'Skilled Teachers',
        description: 'Highly qualified and dedicated teaching staff committed to student success.',
        icon: 'fas fa-user-tie'
    }
]);

const news = ref([
    {
        title: 'Annual Sports Day 2024',
        date: '2024-03-15',
        image: '/news/1.jpg',
        excerpt: 'Join us for our annual sports day celebration with exciting events and activities.'
    },
    {
        title: 'Academic Achievement',
        date: '2024-03-10',
        image: '/news/2.jpg',
        excerpt: 'Our students secured top positions in national level competitions.'
    }
]);

const handleAction = (isContact = true, section = null) => {
    if (isContact) {
        isContactModalOpen.value = true;
    } else if (section) {
        scrollToSection(section);
    }
};

// Animation function for statistics
const animateNumber = (element, start, end) => {
    let current = start;
    const increment = end / 50;
    const timer = setInterval(() => {
        current += increment;
        if (current >= end) {
            current = end;
            clearInterval(timer);
        }
        element.textContent = Math.floor(current) + (end === 98 ? '%' : '+');
    }, 40);
};

// Initialize animations
onMounted(() => {
    window.addEventListener('scroll', () => {
        isScrolled.value = window.scrollY > 50;
    });

    isVisible.value = true;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('show');
                if (entry.target.classList.contains('stat-number')) {
                    const index = Array.from(document.querySelectorAll('.stat-number')).indexOf(entry.target);
                    if (index !== -1) {
                        animateNumber(entry.target, 0, stats.value[index].endValue);
                    }
                }
            }
        });
    }, { threshold: 0.1 });

    document.querySelectorAll('.scroll-animation, .stat-number').forEach((el) => {
        observer.observe(el);
    });
});

const scrollToSection = (id) => {
    const element = document.getElementById(id);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth' });
    }
};
</script>


<template>
    <div class="min-h-screen bg-white dark:bg-gray-900">
        <!-- Navigation -->
        <nav :class="['fixed w-full transition-all duration-300 z-50',
            { 'bg-white/90 dark:bg-gray-900/90 shadow-sm backdrop-blur-sm': isScrolled,
              'bg-transparent': !isScrolled }]">
            <div class="container mx-auto px-4 py-3">
                <div class="flex justify-between items-center">
                    <!-- Logo -->
                    <div class="flex items-center space-x-2">
                        <img src="/logo.png" alt="School Logo" class="h-12 w-auto">
                        <div class="text-lg font-bold text-green-800 dark:text-green-400">
                            Mousumi <br />
                            <span class="text-green-600 dark:text-green-500">Bidyaniketon</span>
                        </div>
                    </div>

                    <!-- Desktop Menu -->
                    <div class="hidden md:flex space-x-8">
                        <button
                            v-for="(item, index) in menuItems"
                            :key="index"
                            @click="handleAction(item.showContact, item.section)"
                            class="text-gray-600 dark:text-gray-300 hover:text-green-600
                                dark:hover:text-green-400 transition nav-link">
                            {{ item.name }}
                        </button>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center space-x-4">
                        <Link href="/login"
                            class="px-4 py-2 text-green-600 dark:text-green-400 border border-green-600
                                dark:border-green-400 rounded-lg hover:bg-green-50 dark:hover:bg-green-900/30
                                transition">
                            Login
                        </Link>
                        <button
                            @click="handleAction(true)"
                            class="hidden md:inline-flex px-4 py-2 bg-green-600 dark:bg-green-500
                                text-white rounded-lg hover:bg-green-700 dark:hover:bg-green-600
                                transition">
                            Enroll Now
                        </button>
                        <!-- Mobile Menu Toggle -->
                        <button
                            @click="isMobileMenuOpen = !isMobileMenuOpen"
                            class="md:hidden text-gray-600 dark:text-gray-300">
                            <i :class="isMobileMenuOpen ? 'fas fa-times' : 'fas fa-bars'"
                                class="text-xl"></i>
                        </button>
                    </div>
                </div>

                <!-- Mobile Menu -->
                <div v-show="isMobileMenuOpen"
                    class="md:hidden mt-4 py-2 space-y-2 border-t border-gray-100 dark:border-gray-800">
                    <button
                        v-for="(item, index) in menuItems"
                        :key="index"
                        @click="handleAction(item.showContact, item.section)"
                        class="block w-full text-left px-4 py-2 text-gray-600 dark:text-gray-300
                            hover:text-green-600 dark:hover:text-green-400">
                        {{ item.name }}
                    </button>
                    <button
                        @click="handleAction(true)"
                        class="block w-full text-left px-4 py-2 text-green-600 dark:text-green-400
                            hover:text-green-700 dark:hover:text-green-500">
                        Enroll Now
                    </button>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="min-h-screen flex items-center pt-20 bg-gradient-to-b from-green-50
            to-white dark:from-gray-900 dark:to-gray-800">
            <div class="container mx-auto px-4">
                <div class="grid lg:grid-cols-2 gap-12 items-center">
                    <!-- Hero Content -->
                    <div class="space-y-6 scroll-animation fade-in-left">
                        <h1 class="text-5xl font-bold text-green-800 dark:text-green-400 leading-tight">
                            Nurturing Minds, <br/>
                            <span class="text-green-600 dark:text-green-500">Shaping Futures</span>
                        </h1>
                        <p class="text-lg text-gray-600 dark:text-gray-300 max-w-lg">
                            Welcome to Mousumi Bidyaniketon, where we combine academic excellence
                            with character development to prepare students for tomorrow's challenges.
                        </p>
                        <div class="flex space-x-4 pt-4">
                            <button
                                @click="handleAction(true)"
                                class="px-8 py-3 bg-green-600 dark:bg-green-500 text-white rounded-lg
                                    hover:bg-green-700 dark:hover:bg-green-600 transform hover:-translate-y-1
                                    transition duration-300">
                                Start Your Journey
                            </button>
                            <button
                                @click="handleAction(true)"
                                class="px-8 py-3 border border-green-600 dark:border-green-400
                                    text-green-600 dark:text-green-400 rounded-lg hover:bg-green-50
                                    dark:hover:bg-green-900/30 transform hover:-translate-y-1
                                    transition duration-300">
                                Contact Us
                            </button>
                        </div>
                    </div>
                    <!-- Hero Image -->
                    <div class="relative scroll-animation fade-in-right">
                        <img src="/hero.jpg" alt="School"
                            class="rounded-lg shadow-2xl w-full object-cover h-[500px] hover:scale-[1.02]
                                transition duration-500">
                        <div class="absolute inset-0 bg-gradient-to-r from-green-600/20
                            dark:from-green-500/20 to-transparent rounded-lg"></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Statistics Section -->
        <section id="stats" ref="statsRef" class="py-20 bg-white dark:bg-gray-900">
            <div class="container mx-auto px-4">
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div v-for="(stat, index) in stats"
                        :key="index"
                        class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg hover:shadow-xl
                            transition duration-300 scroll-animation fade-in-up"
                        :style="{ animationDelay: `${index * 200}ms` }">
                        <i :class="[stat.icon, 'text-4xl text-green-600 dark:text-green-400 mb-4']"></i>
                        <div class="text-3xl font-bold text-gray-900 dark:text-white mb-2 stat-number">
                            {{ stat.value }}
                        </div>
                        <div class="text-gray-600 dark:text-gray-300">{{ stat.label }}</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Features Section -->
        <section id="features" class="py-20 bg-gradient-to-b from-white to-green-50
            dark:from-gray-900 dark:to-gray-800">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center text-green-800 dark:text-green-400 mb-12">
                    Why Choose Us?
                </h2>
                <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div v-for="(feature, index) in features"
                        :key="index"
                        class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg hover:shadow-xl
                            transition duration-300 scroll-animation fade-in-up"
                        :style="{ animationDelay: `${index * 200}ms` }">
                        <i :class="[feature.icon, 'text-3xl text-green-600 dark:text-green-400 mb-4']"></i>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                            {{ feature.title }}
                        </h3>
                        <p class="text-gray-600 dark:text-gray-300">{{ feature.description }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- News Section -->
        <section id="news" class="py-20 bg-white dark:bg-gray-900">
            <div class="container mx-auto px-4">
                <h2 class="text-3xl font-bold text-center text-green-800 dark:text-green-400 mb-12">
                    Latest News
                </h2>
                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div v-for="(item, index) in news"
                        :key="index"
                        class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden
                            hover:shadow-xl transition duration-300 scroll-animation fade-in-up"
                        :style="{ animationDelay: `${index * 200}ms` }">
                        <img :src="item.image" :alt="item.title" class="w-full h-48 object-cover">
                        <div class="p-6">
                            <div class="text-sm text-green-600 dark:text-green-400 mb-2">
                                {{ new Date(item.date).toLocaleDateString() }}
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">
                                {{ item.title }}
                            </h3>
                            <p class="text-gray-600 dark:text-gray-300 mb-4">{{ item.excerpt }}</p>
                            <button
                                @click="handleAction(true)"
                                class="text-green-600 dark:text-green-400 hover:text-green-700
                                    dark:hover:text-green-500 font-medium">
                                Read More →
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Call to Action -->
        <section class="py-20 bg-green-600 dark:bg-green-700 scroll-animation fade-in">
            <div class="container mx-auto px-4 text-center">
                <div class="max-w-2xl mx-auto">
                    <h2 class="text-3xl font-bold text-white mb-6">
                        Begin Your Educational Journey Today
                    </h2>
                    <p class="text-green-100 mb-8">
                        Join our community of learners and discover the path to academic excellence.
                    </p>
                    <div class="flex justify-center space-x-4">
                        <button
                            @click="handleAction(true)"
                            class="px-8 py-3 bg-white text-green-600 dark:text-green-700 rounded-lg
                                hover:bg-green-50 transform hover:-translate-y-1 transition duration-300">
                            Apply Now
                        </button>
                        <button
                            @click="handleAction(true)"
                            class="px-8 py-3 border border-white text-white rounded-lg hover:bg-green-500
                                dark:hover:bg-green-600 transform hover:-translate-y-1 transition duration-300">
                            Get in Touch
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900 dark:bg-gray-950 text-gray-300 py-12">
            <div class="container mx-auto px-4">
                <div class="grid md:grid-cols-3 gap-8">
                    <!-- Contact Info -->
                    <div>
                        <h3 class="text-xl font-semibold text-white mb-4">Contact Us</h3>
                        <div class="space-y-2">
                            <p><i class="fas fa-map-marker-alt mr-2"></i> School Address Here</p>
                            <p><i class="fas fa-phone mr-2"></i> +880 1234-567890</p>
                            <p><i class="fas fa-envelope mr-2"></i> info@mousumi.edu.bd</p>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h3 class="text-xl font-semibold text-white mb-4">Quick Links</h3>
                        <ul class="space-y-2">
                            <li>
                                <button
                                    @click="handleAction(true)"
                                    class="hover:text-green-400 transition">
                                    About Us
                                </button>
                            </li>
                            <li>
                                <button
                                    @click="handleAction(true)"
                                    class="hover:text-green-400 transition">
                                    Facilities
                                </button>
                            </li>
                            <li>
                                <button
                                    @click="handleAction(true)"
                                    class="hover:text-green-400 transition">
                                    Admission
                                </button>
                            </li>
                            <li>
                                <button
                                    @click="handleAction(true)"
                                    class="hover:text-green-400 transition">
                                    Academic Calendar
                                </button>
                            </li>
                        </ul>
                    </div>

                    <!-- Social Links -->
                    <div>
                        <h3 class="text-xl font-semibold text-white mb-4">Follow Us</h3>
                        <div class="flex space-x-4">
                            <a href="#" class="text-2xl hover:text-green-400 transition">
                                <i class="fab fa-facebook"></i>
                            </a>
                            <a href="#" class="text-2xl hover:text-green-400 transition">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="text-2xl hover:text-green-400 transition">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="text-2xl hover:text-green-400 transition">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="text-2xl hover:text-green-400 transition">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Copyright -->
                <div class="text-center mt-8 pt-8 border-t border-gray-800">
                    <p>&copy; {{ new Date().getFullYear() }} Mousumi Bidyaniketon. All rights reserved.</p>
                </div>
            </div>
        </footer>

        <!-- Contact Modal -->
        <ContactModal
            :is-open="isContactModalOpen"
            @close="isContactModalOpen = false"
        />
    </div>
</template>

<style scoped>
/* Base animations */
.scroll-animation {
    opacity: 0;
    transform: translateY(20px);
    transition: all 0.6s ease-out;
}

.scroll-animation.show {
    opacity: 1;
    transform: translateY(0);
}

/* Specific animations */
.fade-in-left {
    transform: translateX(-50px);
}

.fade-in-right {
    transform: translateX(50px);
}

.fade-in-up {
    transform: translateY(20px);
}

.fade-in {
    opacity: 0;
}

/* Show animations */
.fade-in-left.show,
.fade-in-right.show,
.fade-in-up.show,
.fade-in.show {
    opacity: 1;
    transform: translateY(0);
}

/* Navigation link hover effect */
.nav-link {
    position: relative;
}

.nav-link::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    width: 0;
    height: 2px;
    @apply bg-green-600 dark:bg-green-400;
    transition: width 0.3s ease;
}

.nav-link:hover::after {
    width: 100%;
}

/* Mobile menu animation */
.mobile-menu-enter-active,
.mobile-menu-leave-active {
    transition: all 0.3s ease;
}

.mobile-menu-enter-from,
.mobile-menu-leave-to {
    opacity: 0;
    transform: translateY(-10px);
}

/* Stats animation */
@keyframes countUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.stat-number {
    opacity: 0;
}

.stat-number.show {
    animation: countUp 0.6s ease-out forwards;
}
</style>
