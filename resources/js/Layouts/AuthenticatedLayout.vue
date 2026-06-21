<script setup lang="ts">
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuLabel,
  DropdownMenuSeparator,
  DropdownMenuTrigger,
} from '@/Components/ui/dropdown-menu'
import { Button } from '@/Components/ui/button'
import { Menu, X, User } from 'lucide-vue-next'

const showingNavigationDropdown = ref(false);
</script>

<template>
    <div class="min-h-screen bg-zinc-50 dark:bg-zinc-950 text-zinc-900 dark:text-zinc-50 font-sans">
        <div v-if="$page.props.auth.is_impersonating" class="bg-indigo-600 px-4 py-2 text-center text-white text-sm shadow-sm relative z-50">
            Anda sedang login sebagai Pengantin. 
            <Link :href="route('impersonate.stop')" method="post" as="button" class="ml-2 font-bold underline hover:text-indigo-200">Kembali ke Vendor</Link>
        </div>
        
        <nav class="sticky top-0 z-40 w-full backdrop-blur-lg bg-white/80 dark:bg-zinc-950/80 border-b border-zinc-200 dark:border-zinc-800">
            <div class="container flex h-16 items-center justify-between mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-6">
                    <Link :href="route('dashboard')" class="flex items-center gap-2 transition-transform hover:scale-105">
                        <ApplicationLogo class="h-8 w-auto fill-current text-zinc-900 dark:text-white" />
                        <span class="font-bold text-lg tracking-tight hidden sm:inline-block">WeddingSaaS</span>
                    </Link>
                    
                    <!-- Desktop Nav -->
                    <div class="hidden sm:flex items-center space-x-1 ml-6">
                        <Link :href="route('dashboard')" :class="[route().current('dashboard') ? 'bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-zinc-50' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-100 dark:hover:bg-zinc-800 dark:hover:text-zinc-50', 'px-4 py-2 rounded-md text-sm font-medium transition-colors']">Dashboard</Link>
                        
                        <Link v-if="$page.props.auth.user.role === 2" :href="route('vendor.clients.index')" :class="[route().current('vendor.clients.*') ? 'bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-zinc-50' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-100 dark:hover:bg-zinc-800 dark:hover:text-zinc-50', 'px-4 py-2 rounded-md text-sm font-medium transition-colors']">Daftar Klien</Link>
                        
                        <Link v-if="$page.props.auth.user.role === 3" :href="route('pengantin.themes.index')" :class="[route().current('pengantin.themes.*') ? 'bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-zinc-50' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-100 dark:hover:bg-zinc-800 dark:hover:text-zinc-50', 'px-4 py-2 rounded-md text-sm font-medium transition-colors']">Tema & Undangan</Link>
                        
                        <Link v-if="$page.props.auth.user.role === 3" :href="route('pengantin.guests.index')" :class="[route().current('pengantin.guests.*') ? 'bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-zinc-50' : 'text-zinc-500 hover:text-zinc-900 hover:bg-zinc-100 dark:hover:bg-zinc-800 dark:hover:text-zinc-50', 'px-4 py-2 rounded-md text-sm font-medium transition-colors']">Buku Tamu</Link>
                    </div>
                </div>

                <div class="hidden sm:flex items-center gap-4">
                    <DropdownMenu>
                        <DropdownMenuTrigger asChild>
                            <Button variant="ghost" class="relative h-9 w-9 rounded-full focus-visible:ring-1 focus-visible:ring-zinc-400">
                                <div class="flex h-full w-full items-center justify-center rounded-full bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700">
                                    <User class="h-4 w-4" />
                                </div>
                            </Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end" class="w-56">
                            <DropdownMenuLabel class="font-normal">
                                <div class="flex flex-col space-y-1">
                                    <p class="text-sm font-medium leading-none">{{ $page.props.auth.user.name }}</p>
                                    <p class="text-xs leading-none text-zinc-500">{{ $page.props.auth.user.email }}</p>
                                </div>
                            </DropdownMenuLabel>
                            <DropdownMenuSeparator />
                            <DropdownMenuItem asChild>
                                <Link :href="route('profile.edit')" class="w-full cursor-pointer flex items-center">Profil</Link>
                            </DropdownMenuItem>
                            <DropdownMenuSeparator />
                            <DropdownMenuItem asChild>
                                <Link :href="route('logout')" method="post" as="button" class="w-full cursor-pointer flex items-center text-red-600 dark:text-red-400">Keluar</Link>
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>
                </div>

                <!-- Mobile Menu Button -->
                <div class="flex sm:hidden">
                    <Button variant="ghost" size="icon" @click="showingNavigationDropdown = !showingNavigationDropdown">
                        <Menu v-if="!showingNavigationDropdown" class="h-6 w-6" />
                        <X v-else class="h-6 w-6" />
                    </Button>
                </div>
            </div>

            <!-- Mobile Navigation -->
            <div v-if="showingNavigationDropdown" class="sm:hidden border-t border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-950">
                <div class="space-y-1 px-4 py-3">
                    <Link :href="route('dashboard')" :class="[route().current('dashboard') ? 'bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-zinc-50' : 'text-zinc-500 hover:text-zinc-900', 'block px-3 py-2 rounded-md text-base font-medium']">Dashboard</Link>
                    <Link v-if="$page.props.auth.user.role === 2" :href="route('vendor.clients.index')" :class="[route().current('vendor.clients.*') ? 'bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-zinc-50' : 'text-zinc-500 hover:text-zinc-900', 'block px-3 py-2 rounded-md text-base font-medium']">Daftar Klien</Link>
                    <Link v-if="$page.props.auth.user.role === 3" :href="route('pengantin.themes.index')" :class="[route().current('pengantin.themes.*') ? 'bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-zinc-50' : 'text-zinc-500 hover:text-zinc-900', 'block px-3 py-2 rounded-md text-base font-medium']">Tema & Undangan</Link>
                    <Link v-if="$page.props.auth.user.role === 3" :href="route('pengantin.guests.index')" :class="[route().current('pengantin.guests.*') ? 'bg-zinc-100 dark:bg-zinc-800 text-zinc-900 dark:text-zinc-50' : 'text-zinc-500 hover:text-zinc-900', 'block px-3 py-2 rounded-md text-base font-medium']">Buku Tamu</Link>
                </div>
                <div class="border-t border-zinc-200 dark:border-zinc-800 pt-4 pb-3">
                    <div class="px-4 flex items-center">
                        <div class="flex-shrink-0">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-zinc-100 dark:bg-zinc-800">
                                <User class="h-6 w-6" />
                            </div>
                        </div>
                        <div class="ml-3">
                            <div class="text-base font-medium text-zinc-800 dark:text-zinc-100">{{ $page.props.auth.user.name }}</div>
                            <div class="text-sm font-medium text-zinc-500">{{ $page.props.auth.user.email }}</div>
                        </div>
                    </div>
                    <div class="mt-3 space-y-1 px-2">
                        <Link :href="route('profile.edit')" class="block px-3 py-2 rounded-md text-base font-medium text-zinc-500 hover:text-zinc-900 hover:bg-zinc-100 dark:hover:bg-zinc-800">Profil</Link>
                        <Link :href="route('logout')" method="post" as="button" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-red-600 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-950/50">Keluar</Link>
                    </div>
                </div>
            </div>
        </nav>

        <header v-if="$slots.header" class="bg-white/50 dark:bg-zinc-950/50 border-b border-zinc-200 dark:border-zinc-800 backdrop-blur-sm">
            <div class="container mx-auto px-4 py-8 sm:px-6 lg:px-8">
                <slot name="header" />
            </div>
        </header>

        <main class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 min-h-[calc(100vh-4rem)]">
            <slot />
        </main>
    </div>
</template>
