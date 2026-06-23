<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, onUnmounted } from 'vue';

onMounted(() => {
    // We intentionally DO NOT add 'dark' class here anymore because it's unified to the light minimal theme.
    // However, we still add scanner animation styles.
    const style = document.createElement('style');
    style.id = 'scanner-styles';
    style.innerHTML = `
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
                flash.className = 'absolute inset-0 bg-[#1f108e] z-50 pointer-events-none opacity-0';
                scanner.appendChild(flash);
                flash.animate([{ opacity: 0 }, { opacity: 0.3 }, { opacity: 0 }], { duration: 150 });

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
    });
});
</script>

<template>
    <Head title="Receptionist Scanner" />

    <div class="bg-white text-[#1b1b1e] min-h-screen font-body-md overflow-hidden flex flex-col">
        <!-- Top Navigation Anchor -->
        <header class="sticky top-0 z-50 bg-white border-b border-[#e4e1e6] px-xl py-md flex justify-between items-center w-full">
            <div class="flex items-center gap-md">
                <span class="font-display text-display-mobile text-[#1f108e] font-bold tracking-tight">WeddingSaaS</span>
                <div class="h-6 w-px bg-[#e4e1e6] mx-sm"></div>
                <span class="font-label-sm text-[12px] uppercase tracking-widest text-[#5d5e66]">Check-in Portal</span>
            </div>
            <div class="flex items-center gap-lg">
                <div class="flex items-center gap-xs px-md py-xs bg-[#f0edf1] rounded-full border border-[#c8c4d5]">
                    <span class="material-symbols-outlined text-[#1f108e]">sensors</span>
                    <span class="font-label-sm text-[14px] font-bold text-[#1b1b1e]">Live Sync</span>
                </div>
                <div class="flex items-center gap-sm">
                    <div class="text-right hidden sm:block">
                        <p class="font-label-sm text-[14px] font-bold text-[#1b1b1e] leading-tight">Admin Role</p>
                        <p class="font-label-xs text-[12px] text-[#5d5e66]">Receptionist #4</p>
                    </div>
                    <div class="w-10 h-10 rounded-full bg-[#e2dfff] flex items-center justify-center border border-[#1f108e]">
                        <span class="material-symbols-outlined text-[#1f108e]">account_circle</span>
                    </div>
                </div>
            </div>
        </header>

        <main class="flex-1 flex flex-col md:flex-row h-[calc(100vh-80px)] overflow-hidden relative">
            <!-- Sidebar Shell -->
            <nav class="hidden md:flex flex-col p-md bg-[#fbf8fc] border-r border-[#e4e1e6] w-64 h-full z-40 pt-2xl">
                <div class="flex flex-col gap-xs flex-1">
                    <Link href="#" class="flex items-center gap-md px-md py-sm text-[#5d5e66] hover:bg-[#eae7eb] rounded-xl transition-all font-label-sm text-[14px]">
                        <span class="material-symbols-outlined">dashboard</span>
                        <span>Dashboard</span>
                    </Link>
                    <Link href="#" class="flex items-center gap-md px-md py-sm text-[#1f108e] bg-[#e2dfff] rounded-xl font-bold transition-transform duration-200 text-[14px]">
                        <span class="material-symbols-outlined">qr_code_scanner</span>
                        <span>Clients/Guests</span>
                    </Link>
                    <Link href="#" class="flex items-center gap-md px-md py-sm text-[#5d5e66] hover:bg-[#eae7eb] rounded-xl transition-all font-label-sm text-[14px]">
                        <span class="material-symbols-outlined">palette</span>
                        <span>Theme Gallery</span>
                    </Link>
                    <Link href="#" class="flex items-center gap-md px-md py-sm text-[#5d5e66] hover:bg-[#eae7eb] rounded-xl transition-all font-label-sm text-[14px]">
                        <span class="material-symbols-outlined">settings</span>
                        <span>Settings</span>
                    </Link>
                </div>
                <div class="mt-auto pt-md border-t border-[#e4e1e6]">
                    <button class="w-full flex items-center gap-md p-md text-[#5d5e66] hover:bg-[#eae7eb] rounded-xl text-[14px]">
                        <span class="material-symbols-outlined">help</span>
                        <span class="font-label-sm">Support</span>
                    </button>
                    <button class="w-full flex items-center gap-md p-md text-[#ba1a1a] hover:bg-[#ffdad6] rounded-xl text-[14px]">
                        <span class="material-symbols-outlined">logout</span>
                        <span class="font-label-sm font-bold">Sign Out</span>
                    </button>
                </div>
            </nav>

            <!-- Main Workspace -->
            <section class="flex-1 bg-white flex flex-col p-md lg:p-xl gap-xl overflow-y-auto">
                <div class="max-w-6xl mx-auto w-full grid grid-cols-1 lg:grid-cols-12 gap-xl h-full items-start">
                    <!-- Scanner View (Primary Interaction) -->
                    <div class="lg:col-span-7 flex flex-col gap-md h-[600px] lg:h-full">
                        <div class="flex justify-between items-end px-sm">
                            <div>
                                <h1 class="font-headline-md text-[36px] font-bold text-[#1b1b1e] mb-xs">Guest Entrance</h1>
                                <p class="font-body-md text-[14px] text-[#5d5e66]">Align QR code within the frame to check-in</p>
                            </div>
                            <div class="flex gap-sm">
                                <button class="w-12 h-12 flex items-center justify-center rounded-xl bg-white border border-[#c8c4d5] text-[#1b1b1e] hover:bg-[#f0edf1] transition-colors shadow-sm">
                                    <span class="material-symbols-outlined">flash_on</span>
                                </button>
                                <button class="w-12 h-12 flex items-center justify-center rounded-xl bg-white border border-[#c8c4d5] text-[#1b1b1e] hover:bg-[#f0edf1] transition-colors shadow-sm">
                                    <span class="material-symbols-outlined">flip_camera_ios</span>
                                </button>
                            </div>
                        </div>

                        <!-- Simulated Camera Area -->
                        <div class="relative flex-1 bg-white rounded-2xl overflow-hidden border border-[#e4e1e6] scanner-frame shadow-md min-h-[400px]">
                            <div class="absolute inset-0 bg-cover bg-center opacity-80" style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuBx2bLNXeK26QP2vjMO0WgBjxwhp8R20F-XnTLuGWFDFUG0rj53RttYsHAzwPUTYkDSmu7qkpx_ouMAGcSuoilYv0OvsgH56grrhOzOPeE59TG3mSGz-ov8g_BDDYApBjejmrQAJ6_HizQEV312MBgOqd_NHLRCRrYo-K2jA4uIbFfArxU1tKVix4oAWkSYkYjG6sY13AUbvZbjpt9CCnwGphscnvaRrNhCepFVsbXSiO-I8tlsKS4HE2UWT4N790BhqSP_eBncf1si')"></div>
                            <div class="absolute inset-0 bg-white/20 backdrop-blur-[2px]"></div>
                            
                            <!-- HUD Elements -->
                            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                <div class="w-64 h-64 border-2 border-[#1f108e] rounded-3xl opacity-80 flex items-center justify-center relative bg-white/10 backdrop-blur-sm shadow-xl">
                                    <div class="w-8 h-8 border-t-4 border-l-4 border-[#1f108e] absolute top-0 left-0 rounded-tl-lg"></div>
                                    <div class="w-8 h-8 border-t-4 border-r-4 border-[#1f108e] absolute top-0 right-0 rounded-tr-lg"></div>
                                    <div class="w-8 h-8 border-b-4 border-l-4 border-[#1f108e] absolute bottom-0 left-0 rounded-bl-lg"></div>
                                    <div class="w-8 h-8 border-b-4 border-r-4 border-[#1f108e] absolute bottom-0 right-0 rounded-br-lg"></div>
                                    <span class="material-symbols-outlined text-[#1f108e] scale-[4] opacity-30">qr_code_2</span>
                                </div>
                            </div>

                            <!-- Manual Entry Overlay -->
                            <div class="absolute bottom-md left-md right-md">
                                <div class="relative">
                                    <input class="w-full bg-white/90 backdrop-blur-xl border border-[#c8c4d5] rounded-xl px-xl py-md text-[#1b1b1e] font-body-md focus:ring-2 focus:ring-[#1f108e] focus:border-transparent outline-none transition-all placeholder:text-[#5d5e66] shadow-lg" placeholder="Manual search: Guest name or invite ID..." type="text"/>
                                    <span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-[#5d5e66]">search</span>
                                    <div class="absolute right-md top-1/2 -translate-y-1/2 flex gap-xs">
                                        <kbd class="hidden sm:inline-flex px-sm py-xs bg-white rounded border border-[#e4e1e6] text-[12px] font-bold text-[#5d5e66]">⌘</kbd>
                                        <kbd class="hidden sm:inline-flex px-sm py-xs bg-white rounded border border-[#e4e1e6] text-[12px] font-bold text-[#5d5e66]">K</kbd>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Last Check-in Card & Stats -->
                    <div class="lg:col-span-5 flex flex-col gap-xl">
                        <!-- Real-time Result Card -->
                        <div class="bg-white border border-[#e4e1e6] rounded-2xl p-xl shadow-md status-pulse">
                            <div class="flex justify-between items-start mb-lg">
                                <span class="px-md py-xs bg-[#e2dfff] text-[#1f108e] border border-[#1f108e]/30 rounded-full font-label-sm text-[12px] font-bold flex items-center gap-sm">
                                    <span class="w-2 h-2 rounded-full bg-[#1f108e] animate-pulse"></span>
                                    LAST CHECK-IN (2m ago)
                                </span>
                                <button class="text-[#5d5e66] hover:text-[#1b1b1e]">
                                    <span class="material-symbols-outlined">more_horiz</span>
                                </button>
                            </div>
                            <div class="flex items-center gap-xl">
                                <div class="relative">
                                    <div class="w-24 h-24 rounded-2xl bg-[#f0edf1] border-2 border-[#1f108e] overflow-hidden shadow-sm">
                                        <img class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCvvCh-EJw8CmiSflVRvWvnhc2TWp99vpaKvQu9tcZP1dBVrvBhQZxUXIFwHVKuPxbggvV15JROSB2jBQKT_d_uecQ3RKaLeAKupLEMRSL7brLb2J-Ag-PXgOYwWaBw9PE9kc_oFkujXFHQ-TGCZgwPBHt9AWrSiYKX-Y-Qo77Xn4ADSNv9F9ymhizizdx9-k-MkyGzk8ITEBI1Ms54hSJEImWVzELGkF4xtloI05Z9fW2HK3MIacJVxuEJF_roVic68olvehuJhZcu" alt="Guest"/>
                                    </div>
                                    <div class="absolute -bottom-xs -right-xs bg-[#1b1b1e] p-xs rounded-lg border-2 border-white">
                                        <span class="material-symbols-outlined text-white text-sm" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="font-headline-md text-[24px] font-bold text-[#1b1b1e]">Benjamin Sinclair</h3>
                                    <p class="font-body-md text-[14px] text-[#5d5e66] mb-xs">Table 08 • VIP Family</p>
                                    <div class="flex gap-xs">
                                        <span class="px-sm py-xs bg-[#f0edf1] text-[#1b1b1e] rounded font-bold text-[10px] uppercase tracking-tight">Plus One: Sarah S.</span>
                                        <span class="px-sm py-xs bg-[#f0edf1] text-[#1b1b1e] rounded font-bold text-[10px] uppercase tracking-tight">Meal: Vegan</span>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-xl grid grid-cols-2 gap-md">
                                <button class="flex items-center justify-center gap-sm bg-white hover:bg-[#f0edf1] text-[#1b1b1e] py-md rounded-xl font-label-sm text-[14px] font-bold transition-colors border border-[#c8c4d5]">
                                    <span class="material-symbols-outlined">print</span>
                                    PRINT BADGE
                                </button>
                                <button class="flex items-center justify-center gap-sm bg-[#1b1b1e] hover:opacity-90 py-md rounded-xl font-label-sm text-[14px] font-bold text-white transition-opacity shadow-md">
                                    <span class="material-symbols-outlined">undo</span>
                                    REVERT
                                </button>
                            </div>
                        </div>

                        <!-- Live Tally Grid -->
                        <div class="grid grid-cols-2 gap-md">
                            <div class="bg-white border border-[#e4e1e6] p-lg rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                                <p class="font-label-sm text-[12px] font-bold text-[#5d5e66] uppercase tracking-widest mb-sm">Checked In</p>
                                <div class="flex items-baseline gap-sm">
                                    <span class="font-display text-[36px] font-bold text-[#1b1b1e]">184</span>
                                    <span class="font-body-md text-[#5d5e66]">/ 250</span>
                                </div>
                                <div class="w-full bg-[#f0edf1] h-1.5 rounded-full mt-md overflow-hidden">
                                    <div class="h-full bg-[#1b1b1e]" style="width: 73.6%"></div>
                                </div>
                            </div>
                            <div class="bg-white border border-[#e4e1e6] p-lg rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                                <p class="font-label-sm text-[12px] font-bold text-[#5d5e66] uppercase tracking-widest mb-sm">Expected Now</p>
                                <div class="flex items-baseline gap-sm">
                                    <span class="font-display text-[36px] font-bold text-[#752c00]">22</span>
                                    <span class="material-symbols-outlined text-[#752c00]">trending_up</span>
                                </div>
                                <p class="font-label-xs text-[12px] text-[#5d5e66] mt-md">Arrival peak expected in 15m</p>
                            </div>
                        </div>

                        <!-- Recent History List -->
                        <div class="bg-white border border-[#e4e1e6] rounded-2xl overflow-hidden mt-auto shadow-sm">
                            <div class="px-xl py-md border-b border-[#e4e1e6] flex justify-between items-center bg-[#fbf8fc]">
                                <h4 class="font-label-sm text-[14px] font-bold text-[#1b1b1e]">Recent Activity</h4>
                                <Link href="#" class="text-[12px] text-[#1f108e] font-bold hover:underline">View All</Link>
                            </div>
                            <div class="divide-y divide-[#e4e1e6]">
                                <div class="px-xl py-md flex justify-between items-center hover:bg-[#fbf8fc] transition-colors">
                                    <div class="flex items-center gap-md">
                                        <div class="w-8 h-8 rounded bg-[#f0edf1] flex items-center justify-center font-label-sm text-[12px] font-bold text-[#1b1b1e]">CM</div>
                                        <div>
                                            <p class="font-label-sm text-[14px] font-bold text-[#1b1b1e]">Catherine Miller</p>
                                            <p class="font-label-xs text-[12px] text-[#5d5e66]">Table 12 • 20:14</p>
                                        </div>
                                    </div>
                                    <span class="material-symbols-outlined text-green-600 text-[18px]">check_circle</span>
                                </div>
                                <div class="px-xl py-md flex justify-between items-center hover:bg-[#fbf8fc] transition-colors">
                                    <div class="flex items-center gap-md">
                                        <div class="w-8 h-8 rounded bg-[#f0edf1] flex items-center justify-center font-label-sm text-[12px] font-bold text-[#1b1b1e]">RD</div>
                                        <div>
                                            <p class="font-label-sm text-[14px] font-bold text-[#1b1b1e]">Robert Downey</p>
                                            <p class="font-label-xs text-[12px] text-[#5d5e66]">Table 05 • 20:12</p>
                                        </div>
                                    </div>
                                    <span class="material-symbols-outlined text-green-600 text-[18px]">check_circle</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- Mobile Navigation Shell -->
        <nav class="md:hidden sticky bottom-0 z-50 bg-white border-t border-[#e4e1e6] flex justify-around p-sm shadow-lg">
            <button class="flex flex-col items-center gap-xs p-xs text-[#1f108e] font-bold">
                <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">qr_code_scanner</span>
                <span class="text-[10px] font-label-xs">Scan</span>
            </button>
            <button class="flex flex-col items-center gap-xs p-xs text-[#5d5e66]">
                <span class="material-symbols-outlined">group</span>
                <span class="text-[10px] font-label-xs">Guests</span>
            </button>
            <button class="flex flex-col items-center gap-xs p-xs text-[#5d5e66]">
                <span class="material-symbols-outlined">bar_chart</span>
                <span class="text-[10px] font-label-xs">Stats</span>
            </button>
            <button class="flex flex-col items-center gap-xs p-xs text-[#5d5e66]">
                <span class="material-symbols-outlined">settings</span>
                <span class="text-[10px] font-label-xs">Menu</span>
            </button>
        </nav>

        <!-- FAB: Manual Override -->
        <button class="fixed bottom-24 right-md md:bottom-md md:right-md w-14 h-14 bg-[#1b1b1e] text-white rounded-full shadow-2xl flex items-center justify-center hover:scale-105 transition-transform z-50">
            <span class="material-symbols-outlined text-2xl">add</span>
        </button>
    </div>
</template>
