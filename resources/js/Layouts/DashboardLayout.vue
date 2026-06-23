<script setup lang="ts">
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const isDarkMode = ref(false);

const toggleDarkMode = () => {
    isDarkMode.value = !isDarkMode.value;
    if (isDarkMode.value) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }
};
</script>

<template>
    <div class="bg-background text-on-background min-h-screen selection:bg-primary-fixed selection:text-on-primary-fixed font-body-md">
        <!-- Sidebar Navigation Shell -->
        <aside class="fixed left-0 top-0 h-full flex flex-col p-md z-40 bg-surface dark:bg-surface-dim border-r border-outline-variant dark:border-outline w-64 hidden md:flex">
            <div class="mb-xl px-sm">
                <h1 class="font-display text-headline-lg text-primary dark:text-primary-fixed-dim">WeddingSaaS</h1>
                <p class="font-label-sm text-label-sm text-on-surface-variant opacity-70">Professional Planner</p>
            </div>
            <nav class="flex-1 space-y-sm">
                <slot name="sidebar-nav">
                    <!-- Default fallback sidebar navigation -->
                    <Link href="#" class="flex items-center gap-md px-md py-sm text-primary dark:text-primary-fixed-dim bg-primary-fixed dark:bg-primary-container rounded-xl font-bold transition-transform duration-200">
                        <span class="material-symbols-outlined" data-icon="dashboard">dashboard</span>
                        <span class="font-label-sm text-label-sm">Dashboard</span>
                    </Link>
                </slot>
            </nav>
            <div class="mt-auto space-y-sm">
                <slot name="sidebar-bottom">
                    <button class="w-full bg-primary text-on-primary font-label-sm text-label-sm py-sm rounded-xl mb-md btn-hover-effect flex items-center justify-center gap-xs hover:opacity-90 transition-opacity">
                        <span class="material-symbols-outlined text-[18px]" data-icon="add">add</span>
                        Add New Wedding
                    </button>
                    <Link href="#" class="flex items-center gap-md px-md py-sm text-on-surface-variant dark:text-on-secondary-fixed-variant hover:bg-surface-container-high rounded-xl transition-all">
                        <span class="material-symbols-outlined" data-icon="help">help</span>
                        <span class="font-label-sm text-label-sm">Support</span>
                    </Link>
                    <Link href="#" class="flex items-center gap-md px-md py-sm text-on-surface-variant dark:text-on-secondary-fixed-variant hover:bg-surface-container-high rounded-xl transition-all">
                        <span class="material-symbols-outlined" data-icon="logout">logout</span>
                        <span class="font-label-sm text-label-sm">Sign Out</span>
                    </Link>
                </slot>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="md:pl-64 flex flex-col min-h-screen">
            <!-- Top Navigation Bar -->
            <header class="sticky top-0 z-50 w-full bg-surface dark:bg-surface-dim border-b border-outline-variant dark:border-outline px-xl py-md flex justify-between items-center max-w-container-max mx-auto">
                <div class="flex items-center gap-lg">
                    <button class="md:hidden p-xs">
                        <span class="material-symbols-outlined" data-icon="menu">menu</span>
                    </button>
                    <div class="relative group hidden sm:block">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-on-surface-variant text-[20px]" data-icon="search">search</span>
                        <input class="bg-surface-container-low dark:bg-surface-container-highest border-none rounded-full pl-10 pr-4 py-xs text-body-md font-body-md focus:ring-2 focus:ring-primary/20 w-64 transition-all" placeholder="Search..." type="text"/>
                    </div>
                </div>
                <div class="flex items-center gap-md">
                    <button @click="toggleDarkMode" class="p-xs text-on-surface-variant hover:bg-surface-container-low transition-colors rounded-full flex items-center justify-center">
                        <span class="material-symbols-outlined">{{ isDarkMode ? 'light_mode' : 'dark_mode' }}</span>
                    </button>
                    <span class="text-on-surface-variant font-label-sm text-label-sm hidden sm:block">
                        <slot name="user-role">Admin Role</slot>
                    </span>
                    <button class="p-xs text-on-surface-variant hover:bg-surface-container-low transition-colors rounded-full relative">
                        <span class="material-symbols-outlined" data-icon="notifications">notifications</span>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-error rounded-full border-2 border-surface"></span>
                    </button>
                    <div class="flex items-center gap-sm pl-sm border-l border-outline-variant">
                        <div class="w-10 h-10 rounded-full overflow-hidden bg-surface-container-high border border-outline-variant">
                            <slot name="user-avatar">
                                <img class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBb58eBAbfQAzm4-Uqz4TRdiIJIHQG7YSFll-osTR-5IFymJu5EWhruntHm1g9MJ5EQy-mOsoMVFvdw73b9eMQV8CRGfln6YwFReYgK0EyBSMsN8Dqm4I7mcO2-jFDbF6vORZlI9XePCxEvXUMQL20KEl2Jv83wC4EbBf8xOkPQwMGEd4zogkveaPrQTM9VCjvdIfT6jDVGxWfnRj_R3GzNS0gWJxliLShz-6fablBaCZxiZMyDTVD4gfaCxqZy1PEnXtl2rKFOvCOm" alt="Avatar"/>
                            </slot>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Canvas -->
            <main class="flex-1 p-xl max-w-container-max mx-auto w-full flex flex-col">
                <slot />
            </main>

            <!-- Footer Shell -->
            <footer class="mt-auto px-xl py-lg border-t border-outline-variant dark:border-zinc-800 bg-surface dark:bg-surface-dim">
                <div class="max-w-container-max mx-auto flex flex-col md:flex-row justify-between items-center gap-md">
                    <p class="font-label-sm text-label-sm text-on-surface-variant">© {{ new Date().getFullYear() }} WeddingSaaS. All rights reserved.</p>
                    <div class="flex gap-lg">
                        <Link href="#" class="font-label-sm text-label-sm text-on-surface-variant hover:text-primary transition-colors">Privacy Policy</Link>
                        <Link href="#" class="font-label-sm text-label-sm text-on-surface-variant hover:text-primary transition-colors">Terms of Service</Link>
                        <Link href="#" class="font-label-sm text-label-sm text-on-surface-variant hover:text-primary transition-colors">Contact Support</Link>
                    </div>
                </div>
            </footer>
        </div>

        <!-- Mobile Bottom Navigation -->
        <nav class="md:hidden fixed bottom-0 left-0 w-full bg-surface border-t border-outline-variant z-50 flex justify-around p-sm shadow-lg">
            <slot name="mobile-nav">
                <Link href="#" class="flex flex-col items-center gap-xs text-primary font-bold">
                    <span class="material-symbols-outlined">dashboard</span>
                    <span class="text-[10px]">Dashboard</span>
                </Link>
                <Link href="#" class="flex flex-col items-center gap-xs text-on-surface-variant">
                    <span class="material-symbols-outlined">group</span>
                    <span class="text-[10px]">Guests</span>
                </Link>
                <button class="bg-primary text-on-primary w-12 h-12 rounded-full -mt-6 flex items-center justify-center shadow-md">
                    <span class="material-symbols-outlined">add</span>
                </button>
                <Link href="#" class="flex flex-col items-center gap-xs text-on-surface-variant">
                    <span class="material-symbols-outlined">palette</span>
                    <span class="text-[10px]">Gallery</span>
                </Link>
                <Link href="#" class="flex flex-col items-center gap-xs text-on-surface-variant">
                    <span class="material-symbols-outlined">settings</span>
                    <span class="text-[10px]">Settings</span>
                </Link>
            </slot>
        </nav>
    </div>
</template>
