<script setup lang="ts">
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { Head, router, Link } from '@inertiajs/vue3'
import { ref } from 'vue'
import { toast } from 'vue-sonner'

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
    toast.success('Tautan disalin ke clipboard!')
}

const getScannerUrl = () => {
    if (!props.wedding || !props.wedding.receptionist_token) return ''
    return route('check-in.index', { slug: props.wedding.slug, token: props.wedding.receptionist_token })
}
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
        <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-lg mb-xl">
            <!-- Total Guests -->
            <div class="bg-white border border-[#e4e1e6] p-lg rounded-2xl flex flex-col justify-between shadow-sm hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start">
                    <div class="w-10 h-10 rounded-lg bg-[#e2dfff] flex items-center justify-center">
                        <span class="material-symbols-outlined text-[#1f108e]">groups</span>
                    </div>
                    <span class="text-[#5d5e66] font-label-xs text-[12px] uppercase tracking-wider font-bold">Total Tamu</span>
                </div>
                <div class="mt-lg">
                    <p class="font-display text-[36px] font-bold text-[#1b1b1e]">{{ stats?.total_guests ?? 0 }}</p>
                    <p class="font-label-sm text-[12px] text-[#5d5e66]">Tamu terdaftar di sistem</p>
                </div>
            </div>

            <!-- Checked-in -->
            <div class="bg-white border border-[#e4e1e6] p-lg rounded-2xl flex flex-col justify-between shadow-sm hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start">
                    <div class="w-10 h-10 rounded-lg bg-[#e3e1ec] flex items-center justify-center">
                        <span class="material-symbols-outlined text-[#5d5e66]">how_to_reg</span>
                    </div>
                    <span class="text-[#5d5e66] font-label-xs text-[12px] uppercase tracking-wider font-bold">Sudah Check-in</span>
                </div>
                <div class="mt-lg">
                    <p class="font-display text-[36px] font-bold text-[#1b1b1e]">{{ stats?.checked_in ?? 0 }}</p>
                    <div class="flex items-center gap-xs text-green-600">
                        <span class="material-symbols-outlined text-[14px]">qr_code_scanner</span>
                        <span class="font-label-xs text-[12px]">Sudah memindai QR</span>
                    </div>
                </div>
            </div>

            <!-- Scanner Magic Link Section -->
            <div class="bg-white border border-[#e4e1e6] rounded-2xl flex flex-col justify-between shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                <div class="bg-[#fbf8fc] px-lg py-md border-b border-[#e4e1e6] flex items-center gap-sm">
                    <span class="material-symbols-outlined text-[#1f108e]">qr_code_scanner</span>
                    <h3 class="font-label-sm text-[14px] font-bold text-[#1b1b1e]">Akses Scanner Resepsionis</h3>
                </div>
                <div class="p-lg flex-1 flex flex-col justify-center">
                    <p class="text-[12px] text-[#5d5e66] mb-md leading-relaxed">
                        Bagikan tautan ini kepada resepsionis di hari acara. Resepsionis tidak perlu login.
                    </p>
                    
                    <div v-if="wedding?.receptionist_token" class="space-y-sm">
                        <div class="bg-[#f6f2f7] border border-[#c8c4d5] rounded-lg px-md py-sm font-mono text-[12px] overflow-x-auto whitespace-nowrap text-[#1b1b1e]">
                            {{ getScannerUrl() }}
                        </div>
                        <div class="flex gap-sm">
                            <button @click="copyToClipboard(getScannerUrl())" class="flex-1 flex items-center justify-center gap-sm border border-[#c8c4d5] py-xs rounded-lg text-[#1b1b1e] font-label-sm text-[12px] font-bold hover:bg-[#f0edf1] transition-colors">
                                <span class="material-symbols-outlined text-[16px]">content_copy</span> Salin
                            </button>
                            <a :href="getScannerUrl()" target="_blank" class="flex-1 flex items-center justify-center gap-sm bg-[#1b1b1e] text-white py-xs rounded-lg font-label-sm text-[12px] font-bold hover:opacity-90 transition-opacity">
                                Buka <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                            </a>
                        </div>
                    </div>
                    
                    <div v-else>
                        <button @click="generateToken" :disabled="isGenerating" class="w-full py-sm bg-[#1b1b1e] text-white rounded-lg font-label-sm text-[12px] font-bold hover:opacity-90 transition-opacity flex items-center justify-center gap-sm disabled:opacity-50">
                            <span v-if="isGenerating" class="material-symbols-outlined animate-spin text-[16px]">progress_activity</span>
                            <span v-else class="material-symbols-outlined text-[16px]">add_link</span>
                            {{ isGenerating ? 'Membuat Tautan...' : 'Buat Tautan Scanner' }}
                        </button>
                    </div>
                </div>
            </div>
        </section>
    </DashboardLayout>
</template>
