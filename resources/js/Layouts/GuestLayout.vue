<script setup lang="ts">
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
import { Button } from '@/Components/ui/button';
import { Sun, Moon } from 'lucide-vue-next';
import { useDark, useToggle } from '@vueuse/core';

const isDark = useDark();
const toggleDark = useToggle(isDark);
</script>

<template>
    <div class="min-h-screen flex flex-col items-center justify-center bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-zinc-50 font-sans p-4 relative">
        <!-- Theme Toggle -->
        <div class="absolute top-4 right-4">
            <Button variant="ghost" size="icon" @click="toggleDark()" class="h-9 w-9 rounded-full">
                <Sun v-if="isDark" class="h-5 w-5" />
                <Moon v-else class="h-5 w-5" />
                <span class="sr-only">Toggle theme</span>
            </Button>
        </div>

        <div class="mb-8">
            <Link href="/">
                <ApplicationLogo class="h-20 w-auto fill-current text-indigo-600 dark:text-indigo-400" />
            </Link>
        </div>

        <div class="w-full max-w-md bg-white dark:bg-zinc-900 shadow-xl rounded-2xl overflow-hidden border border-zinc-100 dark:border-zinc-800 p-8">
            <slot />
        </div>
    </div>
</template>
