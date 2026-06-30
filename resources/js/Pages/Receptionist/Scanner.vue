<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import { onMounted, onUnmounted, ref, computed } from 'vue';
import { Html5Qrcode } from 'html5-qrcode';
import axios from 'axios';

const props = defineProps<{
    wedding: any;
    token: string;
    guests: any[];
    totalGuests: number;
    checkedInGuests: number;
    recentActivity: any[];
}>();

const html5QrCode = ref<Html5Qrcode | null>(null);
const cameraError = ref('');
const isProcessing = ref(false);

const localCheckedInCount = ref(props.checkedInGuests);
const localRecentActivity = ref([...props.recentActivity]);
const lastCheckIn = ref<any>(localRecentActivity.value[0] || null);
const manualSearch = ref('');
const showManualSearch = ref(false);

const filteredGuests = computed(() => {
    if (!manualSearch.value) return [];
    return props.guests.filter(g => 
        g.name.toLowerCase().includes(manualSearch.value.toLowerCase()) && !g.is_checked_in
    ).slice(0, 5); // show top 5
});

const startScanner = async () => {
    try {
        html5QrCode.value = new Html5Qrcode("qr-reader");
        await html5QrCode.value.start(
            { facingMode: "environment" },
            {
                fps: 10,
                qrbox: { width: 250, height: 250 }
            },
            (decodedText, decodedResult) => {
                // handle success
                processCheckIn(decodedText);
            },
            (errorMessage) => {
                // parse error, ignore
            }
        );
    } catch (err) {
        cameraError.value = "Failed to start camera";
    }
};

const stopScanner = async () => {
    if (html5QrCode.value && html5QrCode.value.isScanning) {
        await html5QrCode.value.stop();
        html5QrCode.value.clear();
    }
};

const triggerFlash = () => {
    const scanner = document.querySelector('.scanner-frame');
    const lastCheckinEl = document.querySelector('.status-pulse');
    if (scanner && lastCheckinEl) {
        const flash = document.createElement('div');
        flash.className = 'absolute inset-0 bg-[#1f108e] z-50 pointer-events-none opacity-0';
        scanner.appendChild(flash);
        flash.animate([{ opacity: 0 }, { opacity: 0.3 }, { opacity: 0 }], { duration: 150 });

        lastCheckinEl.classList.remove('status-pulse');
        void (lastCheckinEl as HTMLElement).offsetWidth;
        lastCheckinEl.classList.add('status-pulse');
    }
};

const processCheckIn = async (guestToken: string) => {
    if (isProcessing.value) return;
    isProcessing.value = true;
    
    try {
        const response = await axios.post(`/check-in/${props.wedding.slug}/process`, {
            token: props.token,
            guest_token: guestToken
        });
        
        if (response.data.success) {
            triggerFlash();
            const guest = response.data.guest;
            
            // Update local state
            localCheckedInCount.value++;
            localRecentActivity.value.unshift(guest);
            if (localRecentActivity.value.length > 5) {
                localRecentActivity.value.pop();
            }
            lastCheckIn.value = guest;
            
            // Mark guest in local list as checked in
            const guestInList = props.guests.find(g => g.id === guest.id);
            if (guestInList) guestInList.is_checked_in = true;
            
            manualSearch.value = '';
        }
    } catch (error: any) {
        console.error("Check-in error:", error);
        alert(error.response?.data?.error || "Terjadi kesalahan saat check-in.");
    } finally {
        setTimeout(() => {
            isProcessing.value = false;
        }, 1500); // Prevent spamming
    }
};

const processManualCheckIn = (guest: any) => {
    processCheckIn(guest.secure_token);
};

const handleBlur = () => {
    setTimeout(() => {
        showManualSearch.value = false;
    }, 200);
};

onMounted(() => {
    startScanner();
    
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
        if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
            e.preventDefault();
            document.querySelector('input')?.focus();
        }
    };
    document.addEventListener('keydown', handleKeydown);

    onUnmounted(() => {
        stopScanner();
        document.removeEventListener('keydown', handleKeydown);
        document.getElementById('scanner-styles')?.remove();
    });
});

const getInitials = (name: string) => {
    if (!name) return '';
    return name.split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
};

const formatTime = (dateStr: string) => {
    if (!dateStr) return '';
    const date = new Date(dateStr);
    return date.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
};
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
                        </div>

                        <!-- Simulated Camera Area -->
                        <div class="relative flex-1 bg-white rounded-2xl overflow-hidden border border-[#e4e1e6] scanner-frame shadow-md min-h-[400px]">
                            <!-- The actual QR reader container -->
                            <div id="qr-reader" class="absolute inset-0 w-full h-full object-cover"></div>
                            
                            <!-- HUD Elements -->
                            <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                                <div class="w-64 h-64 border-2 border-[#1f108e] rounded-3xl opacity-80 flex items-center justify-center relative bg-white/10 backdrop-blur-sm shadow-xl">
                                    <div class="w-8 h-8 border-t-4 border-l-4 border-[#1f108e] absolute top-0 left-0 rounded-tl-lg"></div>
                                    <div class="w-8 h-8 border-t-4 border-r-4 border-[#1f108e] absolute top-0 right-0 rounded-tr-lg"></div>
                                    <div class="w-8 h-8 border-b-4 border-l-4 border-[#1f108e] absolute bottom-0 left-0 rounded-bl-lg"></div>
                                    <div class="w-8 h-8 border-b-4 border-r-4 border-[#1f108e] absolute bottom-0 right-0 rounded-br-lg"></div>
                                </div>
                            </div>

                            <!-- Manual Entry Overlay -->
                            <div class="absolute bottom-md left-md right-md">
                                <div class="relative z-50">
                                    <input v-model="manualSearch" class="w-full bg-white/90 backdrop-blur-xl border border-[#c8c4d5] rounded-xl px-xl py-md text-[#1b1b1e] font-body-md focus:ring-2 focus:ring-[#1f108e] focus:border-transparent outline-none transition-all placeholder:text-[#5d5e66] shadow-lg" placeholder="Manual search: Guest name..." type="text" @focus="showManualSearch = true" @blur="handleBlur"/>
                                    <span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-[#5d5e66]">search</span>
                                    <div class="absolute right-md top-1/2 -translate-y-1/2 flex gap-xs">
                                        <kbd class="hidden sm:inline-flex px-sm py-xs bg-white rounded border border-[#e4e1e6] text-[12px] font-bold text-[#5d5e66]">⌘</kbd>
                                        <kbd class="hidden sm:inline-flex px-sm py-xs bg-white rounded border border-[#e4e1e6] text-[12px] font-bold text-[#5d5e66]">K</kbd>
                                    </div>
                                    
                                    <!-- Search Results -->
                                    <div v-if="showManualSearch && filteredGuests.length > 0" class="absolute bottom-[110%] mb-2 left-0 right-0 bg-white border border-[#c8c4d5] rounded-xl shadow-xl max-h-64 overflow-y-auto">
                                        <div v-for="guest in filteredGuests" :key="guest.id" @click="processManualCheckIn(guest)" class="px-md py-sm hover:bg-[#f0edf1] cursor-pointer border-b border-[#e4e1e6] last:border-0 flex justify-between items-center">
                                            <div>
                                                <p class="font-label-sm font-bold">{{ guest.name }}</p>
                                                <p class="text-[12px] text-[#5d5e66]">{{ guest.relationship || 'Guest' }}</p>
                                            </div>
                                            <button class="px-sm py-1 bg-[#1b1b1e] text-white rounded text-xs font-bold">Check In</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Last Check-in Card & Stats -->
                    <div class="lg:col-span-5 flex flex-col gap-xl">
                        <!-- Real-time Result Card -->
                        <div v-if="lastCheckIn" class="bg-white border border-[#e4e1e6] rounded-2xl p-xl shadow-md status-pulse">
                            <div class="flex justify-between items-start mb-lg">
                                <span class="px-md py-xs bg-[#e2dfff] text-[#1f108e] border border-[#1f108e]/30 rounded-full font-label-sm text-[12px] font-bold flex items-center gap-sm">
                                    <span class="w-2 h-2 rounded-full bg-[#1f108e] animate-pulse"></span>
                                    LAST CHECK-IN
                                </span>
                            </div>
                            <div class="flex items-center gap-xl">
                                <div class="relative">
                                    <div class="w-20 h-20 rounded-2xl bg-[#f0edf1] border-2 border-[#1f108e] flex items-center justify-center shadow-sm">
                                        <span class="font-display text-[32px] text-[#1b1b1e] font-bold">{{ getInitials(lastCheckIn.name) }}</span>
                                    </div>
                                    <div class="absolute -bottom-xs -right-xs bg-[#1b1b1e] p-xs rounded-lg border-2 border-white">
                                        <span class="material-symbols-outlined text-white text-sm" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                                    </div>
                                </div>
                                <div>
                                    <h3 class="font-headline-md text-[24px] font-bold text-[#1b1b1e]">{{ lastCheckIn.name }}</h3>
                                    <p class="font-body-md text-[14px] text-[#5d5e66] mb-xs">{{ lastCheckIn.relationship || 'Guest' }} {{ lastCheckIn.is_vip ? '• VIP' : '' }}</p>
                                </div>
                            </div>
                        </div>
                        <div v-else class="bg-white border border-[#e4e1e6] rounded-2xl p-xl shadow-md flex items-center justify-center text-[#5d5e66] italic h-[200px]">
                            Waiting for check-in...
                        </div>

                        <!-- Live Tally Grid -->
                        <div class="grid grid-cols-2 gap-md">
                            <div class="bg-white border border-[#e4e1e6] p-lg rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                                <p class="font-label-sm text-[12px] font-bold text-[#5d5e66] uppercase tracking-widest mb-sm">Checked In</p>
                                <div class="flex items-baseline gap-sm">
                                    <span class="font-display text-[36px] font-bold text-[#1b1b1e]">{{ localCheckedInCount }}</span>
                                    <span class="font-body-md text-[#5d5e66]">/ {{ totalGuests }}</span>
                                </div>
                                <div class="w-full bg-[#f0edf1] h-1.5 rounded-full mt-md overflow-hidden">
                                    <div class="h-full bg-[#1b1b1e]" :style="{ width: (localCheckedInCount / Math.max(totalGuests, 1) * 100) + '%' }"></div>
                                </div>
                            </div>
                            <div class="bg-white border border-[#e4e1e6] p-lg rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                                <p class="font-label-sm text-[12px] font-bold text-[#5d5e66] uppercase tracking-widest mb-sm">Expected Now</p>
                                <div class="flex items-baseline gap-sm">
                                    <span class="font-display text-[36px] font-bold text-[#752c00]">{{ Math.max(totalGuests - localCheckedInCount, 0) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Recent History List -->
                        <div class="bg-white border border-[#e4e1e6] rounded-2xl overflow-hidden mt-auto shadow-sm">
                            <div class="px-xl py-md border-b border-[#e4e1e6] flex justify-between items-center bg-[#fbf8fc]">
                                <h4 class="font-label-sm text-[14px] font-bold text-[#1b1b1e]">Recent Activity</h4>
                            </div>
                            <div class="divide-y divide-[#e4e1e6]">
                                <div v-for="guest in localRecentActivity" :key="guest.id" class="px-xl py-md flex justify-between items-center hover:bg-[#fbf8fc] transition-colors">
                                    <div class="flex items-center gap-md">
                                        <div class="w-8 h-8 rounded bg-[#f0edf1] flex items-center justify-center font-label-sm text-[12px] font-bold text-[#1b1b1e]">{{ getInitials(guest.name) }}</div>
                                        <div>
                                            <p class="font-label-sm text-[14px] font-bold text-[#1b1b1e]">{{ guest.name }}</p>
                                            <p class="font-label-xs text-[12px] text-[#5d5e66]">{{ formatTime(guest.checked_in_at) }}</p>
                                        </div>
                                    </div>
                                    <span class="material-symbols-outlined text-green-600 text-[18px]">check_circle</span>
                                </div>
                                <div v-if="localRecentActivity.length === 0" class="p-md text-center text-[#5d5e66] italic text-sm">
                                    No recent check-ins.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</template>

<style>
/* ensure html5-qrcode controls don't look broken */
#qr-reader {
    width: 100%;
    height: 100%;
    border: none !important;
}
#qr-reader video {
    object-fit: cover;
    width: 100% !important;
    height: 100% !important;
}
#qr-reader__scan_region {
    height: 100%;
}
#qr-reader__dashboard {
    display: none; /* Hide default controls */
}
</style>
