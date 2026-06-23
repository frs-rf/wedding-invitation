<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="bg-white text-[#1b1b1e] min-h-screen font-body-md">
        <!-- Sidebar Navigation -->
        <aside class="fixed left-0 top-0 h-full flex flex-col pt-2xl pb-md px-md z-40 bg-[#fbf8fc] border-r border-[#e4e1e6] w-64 hidden md:flex">
            <div class="mb-xl px-sm">
                <h1 class="font-display text-[24px] font-bold tracking-tight text-[#1b1b1e]">WeddingSaaS</h1>
                <p class="font-label-sm text-[12px] text-[#5d5e66]">Professional Planner</p>
            </div>
            
            <nav class="flex-1 space-y-xs">
                <slot name="sidebar-nav">
                    <!-- Nav for Pengantin -->
                    <template v-if="$page.props.auth?.user?.role === 'Pengantin'">
                        <Link :href="route('dashboard')" class="flex items-center gap-md px-md py-sm rounded-xl font-bold transition-all text-[14px]" :class="route().current('dashboard') ? 'bg-[#e2dfff] text-[#1f108e]' : 'text-[#5d5e66] hover:bg-[#eae7eb]'">
                            <span class="material-symbols-outlined text-[20px]">dashboard</span>
                            <span>Dashboard</span>
                        </Link>
                        <Link :href="route('pengantin.themes.index')" class="flex items-center gap-md px-md py-sm rounded-xl font-bold transition-all text-[14px]" :class="route().current('pengantin.themes.*') ? 'bg-[#e2dfff] text-[#1f108e]' : 'text-[#5d5e66] hover:bg-[#eae7eb]'">
                            <span class="material-symbols-outlined text-[20px]">palette</span>
                            <span>Tema & Undangan</span>
                        </Link>
                        <Link :href="route('pengantin.guests.index')" class="flex items-center gap-md px-md py-sm rounded-xl font-bold transition-all text-[14px]" :class="route().current('pengantin.guests.*') ? 'bg-[#e2dfff] text-[#1f108e]' : 'text-[#5d5e66] hover:bg-[#eae7eb]'">
                            <span class="material-symbols-outlined text-[20px]">book</span>
                            <span>Buku Tamu</span>
                        </Link>
                    </template>
                    
                    <!-- Nav for Vendor (Default) -->
                    <template v-else>
                        <Link :href="route('dashboard')" class="flex items-center gap-md px-md py-sm bg-[#e2dfff] text-[#1f108e] rounded-xl font-bold transition-all text-[14px]">
                            <span class="material-symbols-outlined text-[20px]">dashboard</span>
                            <span>Dashboard</span>
                        </Link>
                    </template>
                </slot>
            </nav>
            
            <div class="mt-auto space-y-sm">
                <slot name="sidebar-bottom">
                    <button v-if="$page.props.auth?.user?.role === 'Vendor'" class="w-full bg-[#1b1b1e] text-white font-label-sm text-[14px] py-sm rounded-xl mb-md flex items-center justify-center gap-xs hover:opacity-90 transition-opacity">
                        <span class="material-symbols-outlined text-[18px]">add</span>
                        Add New Wedding
                    </button>
                    <Link href="#" class="flex items-center gap-md px-md py-sm text-[#5d5e66] hover:bg-[#eae7eb] rounded-xl transition-all text-[14px]">
                        <span class="material-symbols-outlined text-[20px]">help</span>
                        <span>Support</span>
                    </Link>
                    <Link :href="route('logout')" method="post" as="button" class="w-full flex items-center gap-md px-md py-sm text-[#5d5e66] hover:bg-[#eae7eb] rounded-xl transition-all text-[14px]">
                        <span class="material-symbols-outlined text-[20px]">logout</span>
                        <span>Sign Out</span>
                    </Link>
                </slot>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="md:pl-64 flex flex-col min-h-screen">
            <!-- Top Navigation Bar -->
            <header class="sticky top-0 z-50 w-full bg-white px-xl py-md flex justify-between items-center max-w-[1280px] mx-auto">
                <div class="flex items-center gap-lg">
                    <button class="md:hidden p-xs">
                        <span class="material-symbols-outlined">menu</span>
                    </button>
                    <div class="relative group hidden sm:block">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 material-symbols-outlined text-[#5d5e66] text-[18px]">search</span>
                        <input class="bg-[#f6f2f7] border-none rounded-full pl-10 pr-4 py-xs text-[14px] w-64 focus:ring-1 focus:ring-[#c8c4d5] transition-all outline-none" placeholder="Search..." type="text"/>
                    </div>
                </div>
                
                <div class="flex items-center gap-md">
                    <button class="p-xs text-[#5d5e66] hover:bg-[#f6f2f7] transition-colors rounded-full flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">dark_mode</span>
                    </button>
                    <span class="text-[#1b1b1e] font-label-sm text-[14px] hidden sm:block">
                        <slot name="user-role">Sarah & James</slot>
                    </span>
                    <button class="p-xs text-[#5d5e66] hover:bg-[#f6f2f7] transition-colors rounded-full relative">
                        <span class="material-symbols-outlined text-[20px]">notifications</span>
                        <span class="absolute top-1 right-1 w-1.5 h-1.5 bg-[#ba1a1a] rounded-full"></span>
                    </button>
                    <div class="flex items-center gap-sm pl-sm">
                        <div class="w-8 h-8 rounded-full overflow-hidden bg-[#eae7eb]">
                            <slot name="user-avatar">
                                <img class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBb58eBAbfQAzm4-Uqz4TRdiIJIHQG7YSFll-osTR-5IFymJu5EWhruntHm1g9MJ5EQy-mOsoMVFvdw73b9eMQV8CRGfln6YwFReYgK0EyBSMsN8Dqm4I7mcO2-jFDbF6vORZlI9XePCxEvXUMQL20KEl2Jv83wC4EbBf8xOkPQwMGEd4zogkveaPrQTM9VCjvdIfT6jDVGxWfnRj_R3GzNS0gWJxliLShz-6fablBaCZxiZMyDTVD4gfaCxqZy1PEnXtl2rKFOvCOm" alt="Avatar"/>
                            </slot>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Canvas -->
            <main class="flex-1 p-xl max-w-[1280px] w-full mx-auto flex flex-col">
                <slot />
            </main>

            <!-- Footer Shell -->
            <footer class="mt-auto px-xl py-lg border-t border-[#e4e1e6] bg-[#fbf8fc]">
                <div class="max-w-[1280px] mx-auto flex flex-col md:flex-row justify-between items-center gap-md">
                    <p class="font-label-sm text-[12px] font-bold text-[#5d5e66]">© {{ new Date().getFullYear() }} WeddingSaaS. All rights reserved.</p>
                    <div class="flex gap-lg">
                        <Link href="#" class="font-label-sm text-[12px] font-bold text-[#5d5e66] hover:text-[#1f108e] transition-colors">Privacy Policy</Link>
                        <Link href="#" class="font-label-sm text-[12px] font-bold text-[#5d5e66] hover:text-[#1f108e] transition-colors">Terms of Service</Link>
                        <Link href="#" class="font-label-sm text-[12px] font-bold text-[#5d5e66] hover:text-[#1f108e] transition-colors">Contact Support</Link>
                    </div>
                </div>
            </footer>
        </div>

        <!-- Mobile Bottom Navigation -->
        <nav class="md:hidden fixed bottom-0 left-0 w-full bg-white border-t border-[#e4e1e6] z-50 flex justify-around p-sm shadow-lg">
            <slot name="mobile-nav">
                <Link href="#" class="flex flex-col items-center gap-xs text-[#1f108e] font-bold">
                    <span class="material-symbols-outlined">dashboard</span>
                    <span class="text-[10px]">Dashboard</span>
                </Link>
                <Link href="#" class="flex flex-col items-center gap-xs text-[#5d5e66]">
                    <span class="material-symbols-outlined">group</span>
                    <span class="text-[10px]">Guests</span>
                </Link>
                <button class="bg-[#1b1b1e] text-white w-12 h-12 rounded-full -mt-6 flex items-center justify-center shadow-md">
                    <span class="material-symbols-outlined">add</span>
                </button>
                <Link href="#" class="flex flex-col items-center gap-xs text-[#5d5e66]">
                    <span class="material-symbols-outlined">palette</span>
                    <span class="text-[10px]">Gallery</span>
                </Link>
                <Link href="#" class="flex flex-col items-center gap-xs text-[#5d5e66]">
                    <span class="material-symbols-outlined">settings</span>
                    <span class="text-[10px]">Settings</span>
                </Link>
            </slot>
        </nav>
    </div>
</template>
