<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, onUnmounted } from 'vue';

onMounted(() => {
    document.documentElement.classList.add('dark');
    
    // Add custom CSS for scanner animation
    const style = document.createElement('style');
    style.id = 'scanner-styles';
    style.innerHTML = `
        body { background-color: #1b1b1e; color: #fbf8fc; -webkit-font-smoothing: antialiased; }
        .scanner-frame::before {
            content: ''; position: absolute; top: 20%; left: 50%; transform: translateX(-50%);
            width: 80%; height: 2px; background: #a9a7ff; box-shadow: 0 0 15px #a9a7ff;
            animation: scan 2.5s infinite ease-in-out; z-index: 10;
        }
        @keyframes scan { 0%, 100% { top: 20%; opacity: 0.2; } 50% { top: 80%; opacity: 1; } }
        .status-pulse { animation: pulse-success 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
        @keyframes pulse-success { 0%, 100% { opacity: 1; transform: scale(1); } 50% { opacity: 0.7; transform: scale(0.98); } }
    `;
    document.head.appendChild(style);

    const handleKeydown = (e: KeyboardEvent) => {
        if (e.key === 'Enter') {
            const scanner = document.querySelector('.scanner-frame');
            const lastCheckin = document.querySelector('.status-pulse');
            if (scanner && lastCheckin) {
                const flash = document.createElement('div');
                flash.className = 'absolute inset-0 bg-white z-50 pointer-events-none opacity-0';
                scanner.appendChild(flash);
                flash.animate([{ opacity: 0 }, { opacity: 0.8 }, { opacity: 0 }], { duration: 150 });

                lastCheckin.classList.remove('status-pulse');
                void (lastCheckin as HTMLElement).offsetWidth; // trigger reflow
                lastCheckin.classList.add('status-pulse');
            }
        }
        if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
            e.preventDefault();
            document.querySelector('input')?.focus();
        }
    };
    document.addEventListener('keydown', handleKeydown);

    onUnmounted(() => {
        document.removeEventListener('keydown', handleKeydown);
        document.getElementById('scanner-styles')?.remove();
        // Option to remove dark mode if navigating away, but usually Inertia handles this if layout changes
    });
});
</script>

<template>
    <Head title="Receptionist Scanner" />

    <div class="font-body-md text-body-md overflow-hidden h-screen flex flex-col bg-[#1b1b1e] text-[#fbf8fc]">
        <!-- Top Navigation Anchor -->
        <header class="sticky top-0 z-50 bg-surface dark:bg-surface-dim border-b border-outline-variant dark:border-outline px-xl py-md flex justify-between items-center w-full">
            <div class="flex items-center gap-md">
                <span class="font-display text-display-mobile text-primary dark:text-primary-fixed-dim tracking-tight">WeddingSaaS</span>
                <div class="h-6 w-px bg-outline-variant mx-sm"></div>
                <span class="font-label-sm text-label-sm uppercase tracking-widest text-on-surface-variant">Check-in Portal</span>
            </div>
            <div class="flex items-center gap-lg">
                <div class="flex items-center gap-xs px-md py-xs bg-surface-container-high rounded-full border border-outline-variant">
                    <span class="material-symbols-outlined text-primary-fixed-dim" style="font-variation-settings: 'FILL' 1;">sensors</span>
                    <span class="font-label-sm text-label-sm text-on-surface">Live Sync</span>
                </div>
                <div class="flex items-center gap-sm">
                    <div class="text-right hidden sm:block">
                        <p class="font-label-sm text-label-sm text-on-surface leading-tight">Admin Role</p>
                        <p class="font-label-xs text-label-xs text-on-surface-variant">Receptionist #4</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-primary-container flex items-center justify-center border border-primary">
                        <span class="material-symbols-outlined text-on-primary-container">account_circle</span>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 flex flex-col md:flex-row h-[calc(100vh-80px)] overflow-hidden relative">
            <!-- Sidebar Shell -->
            <nav class="hidden md:flex flex-col p-md bg-surface dark:bg-surface-dim border-r border-outline-variant dark:border-outline w-64 h-full z-40">
                <div class="flex flex-col gap-xs flex-1">
                    <Link href="#" class="flex items-center gap-md p-md text-on-surface-variant dark:text-on-secondary-fixed-variant hover:bg-surface-container-high rounded-xl transition-all">
                        <span class="material-symbols-outlined">dashboard</span>
                        <span class="font-label-sm text-label-sm">Dashboard</span>
                    </Link>
                    <Link href="#" class="flex items-center gap-md p-md text-primary dark:text-primary-fixed-dim bg-primary-fixed dark:bg-primary-container rounded-xl font-bold transition-transform duration-200 active:scale-95">
                        <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">qr_code_scanner</span>
                        <span class="font-label-sm text-label-sm">Clients/Guests</span>
                    </Link>
                    <Link href="#" class="flex items-center gap-md p-md text-on-surface-variant dark:text-on-secondary-fixed-variant hover:bg-surface-container-high rounded-xl transition-all">
                        <span class="material-symbols-outlined">palette</span>
                        <span class="font-label-sm text-label-sm">Theme Gallery</span>
                    </Link>
                    <Link href="#" class="flex items-center gap-md p-md text-on-surface-variant dark:text-on-secondary-fixed-variant hover:bg-surface-container-high rounded-xl transition-all">
                        <span class="material-symbols-outlined">settings</span>
                        <span class="font-label-sm text-label-sm">Settings</span>
                    </Link>
                </div>
                <div class="mt-auto pt-md border-t border-outline-variant">
                    <button class="w-full flex items-center gap-md p-md text-on-surface-variant hover:bg-surface-container-high rounded-xl">
                        <span class="material-symbols-outlined">help</span>
                        <span class="font-label-sm text-label-sm">Support</span>
                    </button>
                    <button class="w-full flex items-center gap-md p-md text-error hover:bg-error-container/10 rounded-xl">
                        <span class="material-symbols-outlined">logout</span>
                        <span class="font-label-sm text-label-sm">Sign Out</span>
                    </button>
                </div>
            </nav>

            <!-- Main Workspace -->
            <section class="flex-1 bg-zinc-950 flex flex-col p-md lg:p-xl gap-xl overflow-y-auto">
                <div class="max-w-6xl mx-auto w-full grid grid-cols-1 lg:grid-cols-12 gap-xl h-full items-start">
                    <!-- Scanner View (Primary Interaction) -->
                    <div class="lg:col-span-7 flex flex-col gap-md h-[600px] lg:h-full">
                        <div class="flex justify-between items-end px-sm">
                            <div>
                                <h1 class="font-headline-md text-headline-md text-surface-bright mb-xs">Guest Entrance</h1>
                                <p class="font-body-md text-body-md text-on-surface-variant">Align QR code within the frame to check-in</p>
                            </div>
                            <div class="flex gap-sm">
                                <button class="w-12 h-12 flex items-center justify-center rounded-xl bg-zinc-900 border border-zinc-800 text-surface-bright hover:bg-zinc-800 transition-colors">
                                    <span class="material-symbols-outlined">flash_on</span>
                                </button>
                                <button class="w-12 h-12 flex items-center justify-center rounded-xl bg-zinc-900 border border-zinc-800 text-surface-bright hover:bg-zinc-800 transition-colors">
                                    <span class="material-symbols-outlined">flip_camera_ios</span>
                                </button>
                            </div>
                        </div>

                        <!-- Simulated Camera Area -->
                        <div class="relative flex-1 bg-zinc-900 rounded-2xl overflow-hidden border border-zinc-800 scanner-frame shadow-2xl min-h-[400px]">
                            <div class="absolute inset-0 bg-cover bg-center opacity-60 grayscale brightness-50" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBx2bLNXeK26QP2vjMO0WgBjxwhp8R20F-XnTLuGWFDFUG0rj53RttYsHAzwPUTYkDSmu7qkpx_ouMAGcSuoilYv0OvsgH56grrhOzOPeE59TG3mSGz-ov8g_BDDYApBjejmrQAJ6_HizQEV312MBgOqd_NHLRCRrYo-K2jA4uIbFfArxU1tKVix4oAWkSYkYjG6sY13AUbvZbjpt9CCnwGphscnvaRrNhCepFVsbXSiO-I8tlsKS4HE2UWT4N790BhqSP_eBncf1si')"></div>
                            
                            <!-- HUD Elements -->
                            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                <div class="w-64 h-64 border-2 border-primary-fixed rounded-3xl opacity-80 flex items-center justify-center relative">
                                    <div class="w-8 h-8 border-t-4 border-l-4 border-primary-fixed-dim absolute top-0 left-0 rounded-tl-lg"></div>
                                    <div class="w-8 h-8 border-t-4 border-r-4 border-primary-fixed-dim absolute top-0 right-0 rounded-tr-lg"></div>
                                    <div class="w-8 h-8 border-b-4 border-l-4 border-primary-fixed-dim absolute bottom-0 left-0 rounded-bl-lg"></div>
                                    <div class="w-8 h-8 border-b-4 border-r-4 border-primary-fixed-dim absolute bottom-0 right-0 rounded-br-lg"></div>
                                    <span class="material-symbols-outlined text-surface-bright/20 scale-[4]">qr_code_2</span>
                                </div>
                            </div>

                            <!-- Manual Entry Overlay -->
                            <div class="absolute bottom-md left-md right-md">
                                <div class="relative">
                                    <input class="w-full bg-black/80 backdrop-blur-xl border border-zinc-700 rounded-xl px-xl py-md text-surface-bright font-body-md focus:ring-2 focus:ring-primary focus:border-transparent transition-all placeholder:text-zinc-500" placeholder="Manual search: Guest name or invite ID..." type="text"/>
                                    <span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-zinc-400">search</span>
                                    <div class="absolute right-md top-1/2 -translate-y-1/2 flex gap-xs">
                                        <kbd class="hidden sm:inline-flex px-sm py-xs bg-zinc-800 rounded border border-zinc-700 text-label-xs text-zinc-400">⌘</kbd>
                                        <kbd class="hidden sm:inline-flex px-sm py-xs bg-zinc-800 rounded border border-zinc-700 text-label-xs text-zinc-400">K</kbd>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Last Check-in Card & Stats -->
                    <div class="lg:col-span-5 flex flex-col gap-xl">
                        <!-- Real-time Result Card -->
                        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-xl shadow-xl status-pulse">
                            <div class="flex justify-between items-start mb-lg">
                                <span class="px-md py-xs bg-primary-container/20 text-on-primary-container border border-primary-container/30 rounded-full font-label-sm text-label-sm flex items-center gap-sm">
                                    <span class="w-2 h-2 rounded-full bg-on-primary-container animate-pulse"></span>
                                    LAST CHECK-IN (2m ago)
                                </span>
                                <button class="text-zinc-500 hover:text-surface-bright">
                                    <span class="material-symbols-outlined">more_horiz</span>
                                </button>
                            </div>
                            <div class="flex items-center gap-xl">
                                <div class="relative">
                                    <div class="w-24 h-24 rounded-2xl bg-zinc-800 border-2 border-primary overflow-hidden">
                                        <img class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCvvCh-EJw8CmiSflVRvWvnhc2TWp99vpaKvQu9tcZP1dBVrvBhQZxUXIFwHVKuPxbggvV15JROSB2jBQKT_d_uecQ3RKaLeAKupLEMRSL7brLb2J-Ag-PXgOYwWaBw9PE9kc_oFkujXFHQ-TGCZgwPBHt9AWrSiYKX-Y-Qo77Xn4ADSNv9F9ymhizizdx9-k-MkyGzk8ITEBI1Ms54hSJEImWVzELGkF4xtloI05Z9fW2HK3MIacJVxuEJF_roVic68olvehuJhZcu" alt="Guest"/>
                                    </div>
                                    <div class="absolute -bottom-xs -right-xs bg-primary p-xs rounded-lg border-2 border-zinc-900">
                                        <span class="material-symbols-outlined text-on-primary text-sm" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="font-headline-md text-headline-md text-surface-bright">Benjamin Sinclair</h3>
                                    <p class="font-body-md text-body-md text-zinc-400 mb-xs">Table 08 • VIP Family</p>
                                    <div class="flex gap-xs">
                                        <span class="px-sm py-xs bg-zinc-800 text-zinc-300 rounded text-label-xs uppercase tracking-tight">Plus One: Sarah S.</span>
                                        <span class="px-sm py-xs bg-zinc-800 text-zinc-300 rounded text-label-xs uppercase tracking-tight">Meal: Vegan</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-xl grid grid-cols-2 gap-md">
                                <button class="flex items-center justify-center gap-sm bg-zinc-800 hover:bg-zinc-700 py-md rounded-xl font-label-sm transition-colors border border-zinc-700">
                                    <span class="material-symbols-outlined">print</span>
                                    PRINT BADGE
                                </button>
                                <button class="flex items-center justify-center gap-sm bg-primary hover:bg-primary/90 py-md rounded-xl font-label-sm text-on-primary transition-colors">
                                    <span class="material-symbols-outlined">undo</span>
                                    REVERT
                                </button>
                            </div>
                        </div>

                        <!-- Live Tally Grid -->
                        <div class="grid grid-cols-2 gap-md">
                            <div class="bg-zinc-900/50 border border-zinc-800 p-lg rounded-2xl">
                                <p class="font-label-sm text-label-sm text-zinc-500 uppercase tracking-widest mb-sm">Checked In</p>
                                <div class="flex items-baseline gap-sm">
                                    <span class="font-display text-display text-surface-bright">184</span>
                                    <span class="font-body-md text-zinc-500">/ 250</span>
                                </div>
                                <div class="w-full bg-zinc-800 h-1.5 rounded-full mt-md overflow-hidden">
                                    <div class="h-full bg-primary" style="width: 73.6%"></div>
                                </div>
                            </div>
                            <div class="bg-zinc-900/50 border border-zinc-800 p-lg rounded-2xl">
                                <p class="font-label-sm text-label-sm text-zinc-500 uppercase tracking-widest mb-sm">Expected Now</p>
                                <div class="flex items-baseline gap-sm">
                                    <span class="font-display text-display text-on-tertiary-container">22</span>
                                    <span class="material-symbols-outlined text-on-tertiary-container">trending_up</span>
                                </div>
                                <p class="font-label-xs text-label-xs text-zinc-600 mt-md">Arrival peak expected in 15m</p>
                            </div>
                        </div>

                        <!-- Recent History List -->
                        <div class="bg-zinc-900/30 border border-zinc-800 rounded-2xl overflow-hidden mt-auto">
                            <div class="px-xl py-md border-b border-zinc-800 flex justify-between items-center bg-zinc-900/50">
                                <h4 class="font-label-sm text-label-sm text-surface-bright">Recent Activity</h4>
                                <Link href="#" class="text-label-xs text-primary-fixed-dim hover:underline">View All</Link>
                            </div>
                            <div class="divide-y divide-zinc-800/50">
                                <div class="px-xl py-md flex justify-between items-center hover:bg-zinc-800/30 transition-colors">
                                    <div class="flex items-center gap-md">
                                        <div class="w-8 h-8 rounded bg-zinc-800 flex items-center justify-center font-label-sm text-zinc-400">CM</div>
                                        <div>
                                            <p class="font-label-sm text-label-sm text-surface-bright">Catherine Miller</p>
                                            <p class="font-label-xs text-label-xs text-zinc-500">Table 12 • 20:14</p>
                                        </div>
                                    </div>
                                    <span class="material-symbols-outlined text-green-500 text-sm">check_circle</span>
                                </div>
                                <div class="px-xl py-md flex justify-between items-center hover:bg-zinc-800/30 transition-colors">
                                    <div class="flex items-center gap-md">
                                        <div class="w-8 h-8 rounded bg-zinc-800 flex items-center justify-center font-label-sm text-zinc-400">RD</div>
                                        <div>
                                            <p class="font-label-sm text-label-sm text-surface-bright">Robert Downey</p>
                                            <p class="font-label-xs text-label-xs text-zinc-500">Table 05 • 20:12</p>
                                        </div>
                                    </div>
                                    <span class="material-symbols-outlined text-green-500 text-sm">check_circle</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- Mobile Navigation Shell -->
        <nav class="md:hidden sticky bottom-0 z-50 bg-surface dark:bg-surface-dim border-t border-outline-variant flex justify-around p-sm">
            <button class="flex flex-col items-center gap-xs p-xs text-primary">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">qr_code_scanner</span>
                <span class="text-[10px] font-label-xs">Scan</span>
            </button>
            <button class="flex flex-col items-center gap-xs p-xs text-on-surface-variant">
                <span class="material-symbols-outlined">group</span>
                <span class="text-[10px] font-label-xs">Guests</span>
            </button>
            <button class="flex flex-col items-center gap-xs p-xs text-on-surface-variant">
                <span class="material-symbols-outlined">bar_chart</span>
                <span class="text-[10px] font-label-xs">Stats</span>
            </button>
            <button class="flex flex-col items-center gap-xs p-xs text-on-surface-variant">
                <span class="material-symbols-outlined">settings</span>
                <span class="text-[10px] font-label-xs">Menu</span>
            </button>
        </nav>

        <!-- FAB: Manual Override -->
        <button class="fixed bottom-24 right-md md:bottom-md md:right-md w-14 h-14 bg-primary text-on-primary rounded-full shadow-2xl flex items-center justify-center hover:scale-105 transition-transform z-50">
            <span class="material-symbols-outlined text-2xl">add</span>
        </button>
    </div>
</template>
