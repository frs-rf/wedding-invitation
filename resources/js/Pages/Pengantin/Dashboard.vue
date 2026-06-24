<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import DonutChart from '@/Components/DonutChart.vue';
import { ref, computed } from 'vue';
import { toast } from 'vue-sonner';

const props = defineProps<{
    stats?: {
        total_guests?: number
        checked_in?: number
    }
    wedding?: any
}>()

const isGenerating = ref(false)

const generateToken = () => {
    isGenerating.value = true
    router.post(route('pengantin.generate-token'), {}, {
        onSuccess: () => {
            toast.success('Tautan berhasil dibuat!')
        },
        onFinish: () => { isGenerating.value = false }
    })
}

const copyToClipboard = (text: string) => {
    navigator.clipboard.writeText(text)
    toast.success('Disalin ke clipboard!')
}

const getScannerUrl = () => {
    if (!props.wedding || !props.wedding.receptionist_token) return ''
    return route('check-in.index', { slug: props.wedding.slug, token: props.wedding.receptionist_token })
}

// Stats computations
const totalGuests = computed(() => props.stats?.total_guests ?? 0);
const checkedIn = computed(() => props.stats?.checked_in ?? 0);
const attendanceRate = computed(() => {
    if (totalGuests.value === 0) return 0;
    return Math.round((checkedIn.value / totalGuests.value) * 100);
});
const pendingGuests = computed(() => totalGuests.value - checkedIn.value);

// Mock data for recent guests (ideally this should come from backend, but we keep mock for visual for now)
const recentGuests = [
    { name: 'Robert Montgomery', initials: 'RM', type: 'VIP', typeColor: 'bg-[#ffdbcc] text-[#7a3003]', status: 'Checked-in', statusIcon: 'check_circle', statusColor: 'text-[#752c00]', time: 'Baru saja', avatarColor: 'bg-[#f0edf1]' },
    { name: 'Eleanor Davis', initials: 'ED', type: 'Keluarga', typeColor: 'bg-[#e3e1ec] text-[#46464e]', status: 'Menunggu', statusIcon: 'schedule', statusColor: 'text-[#5d5e66]', time: '15 mnt lalu', avatarColor: 'bg-[#f0edf1]' },
];
</script>

<template>
    <Head title="Dashboard Pengantin" />

    <DashboardLayout>
        <!-- Welcome Header -->
        <section class="flex flex-col md:flex-row md:items-end justify-between gap-lg mb-xl">
            <div>
                <h2 class="font-display text-[36px] tracking-tight text-[#1b1b1e] font-bold">Your Big Day</h2>
                <p class="font-body-lg text-[16px] text-[#5d5e66]">Kelola undangan, tema, daftar tamu, dan analitik kehadiran.</p>
            </div>
            <div class="flex gap-md">
                <Link :href="route('pengantin.guests.index')" class="flex items-center gap-sm px-lg py-sm border border-[#c8c4d5] rounded-full font-label-sm text-[14px] hover:bg-[#f0edf1] transition-colors text-[#1b1b1e] font-bold">
                    <span class="material-symbols-outlined text-[18px]">group</span>
                    Kelola Tamu
                </Link>
                <Link :href="route('pengantin.themes.index')" class="flex items-center gap-sm px-lg py-sm bg-[#1b1b1e] text-white rounded-full font-label-sm text-[14px] hover:opacity-90 transition-opacity font-bold">
                    <span class="material-symbols-outlined text-[18px]">palette</span>
                    Ubah Tema
                </Link>
            </div>
        </section>

        <!-- Bento Grid Summary -->
        <section class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-lg mb-xl">
            <!-- Total Guests -->
            <div class="bg-white border border-[#e4e1e6] p-lg rounded-xl flex flex-col justify-between shadow-sm hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start">
                    <div class="w-10 h-10 rounded-lg bg-[#e2dfff] flex items-center justify-center">
                        <span class="material-symbols-outlined text-[#1f108e]">groups</span>
                    </div>
                    <span class="text-[#5d5e66] font-label-xs text-[12px] uppercase tracking-wider font-bold">Total Tamu</span>
                </div>
                <div class="mt-lg">
                    <p class="font-display text-[36px] font-bold text-[#1b1b1e]">{{ totalGuests }}</p>
                    <p class="font-label-sm text-[12px] text-[#5d5e66]">Tamu terdaftar di sistem</p>
                </div>
            </div>

            <!-- Checked-in -->
            <div class="bg-white border border-[#e4e1e6] p-lg rounded-xl flex flex-col justify-between shadow-sm hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start">
                    <div class="w-10 h-10 rounded-lg bg-[#e3e1ec] flex items-center justify-center">
                        <span class="material-symbols-outlined text-[#5d5e66]">how_to_reg</span>
                    </div>
                    <span class="text-[#5d5e66] font-label-xs text-[12px] uppercase tracking-wider font-bold">Sudah Check-in</span>
                </div>
                <div class="mt-lg">
                    <p class="font-display text-[36px] font-bold text-[#1b1b1e]">{{ checkedIn }}</p>
                    <div class="flex items-center gap-xs text-green-600">
                        <span class="material-symbols-outlined text-[14px]">trending_up</span>
                        <span class="font-label-xs text-[12px]">{{ attendanceRate }}% Kehadiran</span>
                    </div>
                </div>
            </div>

            <!-- Scanner Magic Link Section -->
            <div class="md:col-span-2 bg-[#1b1b1e] text-white p-lg rounded-xl flex flex-col justify-between shadow-sm hover:shadow-md transition-shadow">
                <div class="flex justify-between items-center mb-md">
                    <span class="font-label-sm text-[14px] font-bold">Akses Scanner Resepsionis</span>
                    <span class="material-symbols-outlined text-[#c8c4d5]">qr_code_scanner</span>
                </div>
                <div class="flex-1 flex flex-col justify-center">
                    <p class="font-label-xs text-[12px] text-[#c8c4d5] mb-sm">Bagikan tautan ini ke staf resepsionis.</p>
                    <div v-if="wedding?.receptionist_token" class="flex gap-sm items-center">
                        <div class="flex-1 bg-[#464553] px-md py-sm rounded-lg overflow-hidden truncate text-[12px] font-mono">
                            {{ getScannerUrl() }}
                        </div>
                        <button @click="copyToClipboard(getScannerUrl())" class="px-md py-sm bg-white text-[#1b1b1e] rounded-lg font-bold text-[12px] hover:bg-gray-200 transition-colors">
                            Salin
                        </button>
                    </div>
                    <div v-else>
                        <button @click="generateToken" :disabled="isGenerating" class="w-full py-sm bg-white text-[#1b1b1e] rounded-lg font-label-sm text-[12px] font-bold hover:bg-gray-200 transition-colors flex items-center justify-center gap-sm disabled:opacity-50">
                            <span v-if="isGenerating" class="material-symbols-outlined animate-spin text-[16px]">progress_activity</span>
                            <span v-else class="material-symbols-outlined text-[16px]">add_link</span>
                            {{ isGenerating ? 'Membuat Tautan...' : 'Buat Tautan Scanner' }}
                        </button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Analytics & Actions -->
        <section class="grid grid-cols-1 lg:grid-cols-3 gap-lg mb-xl">
            <!-- Guest Attendance Analytics -->
            <div class="lg:col-span-2 bg-white border border-[#e4e1e6] rounded-xl p-xl flex flex-col md:flex-row gap-xl items-center shadow-sm">
                <DonutChart :percentage="attendanceRate" label="Hadir" />
                <div class="flex-1 w-full space-y-lg">
                    <div>
                        <h3 class="font-headline-md text-[24px] font-bold text-[#1b1b1e] mb-xs">Ringkasan Kehadiran</h3>
                        <p class="font-body-md text-[14px] text-[#5d5e66]">Data respons tamu untuk acara utama.</p>
                    </div>
                    <div class="space-y-md">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-md">
                                <div class="w-3 h-3 rounded-full bg-[#1b1b1e]"></div>
                                <span class="font-body-md text-[14px] font-bold text-[#1b1b1e]">Hadir (Check-in)</span>
                            </div>
                            <span class="font-label-sm text-[14px] font-bold text-[#1b1b1e]">{{ checkedIn }} Tamu</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-md">
                                <div class="w-3 h-3 rounded-full bg-[#c8c4d5]"></div>
                                <span class="font-body-md text-[14px] font-bold text-[#1b1b1e]">Belum Hadir</span>
                            </div>
                            <span class="font-label-sm text-[14px] font-bold text-[#1b1b1e]">{{ pendingGuests }} Tamu</span>
                        </div>
                    </div>
                    <Link :href="route('pengantin.guests.index')" class="block text-center w-full py-sm border border-[#e4e1e6] rounded-lg font-label-sm text-[14px] text-[#1b1b1e] font-bold hover:bg-[#f0edf1] transition-all">
                        Lihat Daftar Tamu Penuh
                    </Link>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white border border-[#e4e1e6] rounded-xl p-xl flex flex-col gap-lg shadow-sm">
                <h3 class="font-headline-md text-[24px] font-bold text-[#1b1b1e]">Aksi Cepat</h3>
                
                <a :href="getScannerUrl()" target="_blank" class="flex items-center justify-between p-md bg-[#fbf8fc] border border-[#e4e1e6] rounded-xl hover:border-[#1b1b1e] transition-colors group">
                    <div class="flex items-center gap-md">
                        <div class="w-10 h-10 rounded-lg bg-white border border-[#e4e1e6] flex items-center justify-center text-[#1b1b1e]">
                            <span class="material-symbols-outlined">qr_code_scanner</span>
                        </div>
                        <div class="text-left">
                            <p class="font-label-sm text-[14px] font-bold text-[#1b1b1e]">Buka Scanner</p>
                            <p class="font-label-xs text-[12px] text-[#5d5e66]">Uji coba halaman resepsionis</p>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-[#777584] group-hover:text-[#1b1b1e] transition-colors">open_in_new</span>
                </a>

                <a :href="route('pengantin.guests.template')" class="flex items-center justify-between p-md bg-[#fbf8fc] border border-[#e4e1e6] rounded-xl hover:border-[#1b1b1e] transition-colors group">
                    <div class="flex items-center gap-md">
                        <div class="w-10 h-10 rounded-lg bg-white border border-[#e4e1e6] flex items-center justify-center text-[#1b1b1e]">
                            <span class="material-symbols-outlined">download</span>
                        </div>
                        <div class="text-left">
                            <p class="font-label-sm text-[14px] font-bold text-[#1b1b1e]">Template Excel</p>
                            <p class="font-label-xs text-[12px] text-[#5d5e66]">Unduh format buku tamu</p>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-[#777584] group-hover:text-[#1b1b1e] transition-colors">description</span>
                </a>

                <Link :href="route('pengantin.guests.index')" class="flex items-center justify-between p-md bg-[#fbf8fc] border border-[#e4e1e6] rounded-xl hover:border-[#1b1b1e] transition-colors group">
                    <div class="flex items-center gap-md">
                        <div class="w-10 h-10 rounded-lg bg-[#1b1b1e] border border-[#1b1b1e] flex items-center justify-center text-white">
                            <span class="material-symbols-outlined">upload_file</span>
                        </div>
                        <div class="text-left">
                            <p class="font-label-sm text-[14px] font-bold text-[#1b1b1e]">Import Excel</p>
                            <p class="font-label-xs text-[12px] text-[#5d5e66]">Unggah massal via daftar tamu</p>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-[#777584] group-hover:text-[#1b1b1e] transition-colors">arrow_forward</span>
                </Link>
            </div>
        </section>

    </DashboardLayout>
</template>
