<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps<{
    wedding: any
    theme: any
    guest: any
    qrCode: string
}>()

const themeData = computed(() => props.wedding?.theme_data || {})
const blocks = computed(() => themeData.value.blocks || [])

const sortedBlocks = computed(() => {
    return blocks.value.filter((b: any) => b.enabled)
})
</script>

<template>
    <div class="min-h-screen bg-white font-sans text-gray-900 overflow-x-hidden selection:bg-black selection:text-white">
        <Head :title="`Undangan Pernikahan - ${themeData.bride_name || 'Pengantin'} & ${themeData.groom_name || 'Pengantin'}`" />

        <div class="relative z-10 max-w-2xl mx-auto px-6 py-16 sm:px-12 lg:px-16 flex flex-col gap-24">
            
            <template v-for="block in sortedBlocks" :key="block.id">

                <!-- HERO BLOCK -->
                <div v-if="block.type === 'hero'" v-motion-fade-visible-once class="text-left mt-12">
                    <p class="text-gray-400 text-xs tracking-[0.3em] uppercase mb-6 font-bold">The Wedding Of</p>
                    <h1 class="text-5xl sm:text-7xl font-bold tracking-tighter leading-none mb-6">
                        {{ themeData.bride_name || 'Budi' }} <br>
                        <span class="text-gray-300">&</span> <br>
                        {{ themeData.groom_name || 'Riri' }}
                    </h1>
                </div>

                <!-- GUEST BLOCK -->
                <div v-if="block.type === 'guest'" v-motion-slide-visible-once-bottom class="text-left border-l-4 border-black pl-6 py-2">
                    <p class="text-gray-500 mb-1 text-sm">Kepada Yth,</p>
                    <h2 class="text-2xl font-bold mb-1">{{ guest.name || 'Tamu Undangan' }}</h2>
                    <p class="text-gray-500 text-sm">{{ guest.company_or_address || 'Di Tempat' }}</p>
                    <div v-if="guest.is_vip" class="mt-4 inline-block px-3 py-1 bg-black text-white text-[10px] font-bold rounded-sm uppercase tracking-widest">
                        VIP
                    </div>
                </div>

                <!-- STORY BLOCK -->
                <div v-if="block.type === 'story'" v-motion-fade-visible-once class="text-left">
                    <p class="text-xl sm:text-2xl text-gray-800 leading-snug font-medium">
                        "{{ themeData.love_story || "Kehadiran dan doa restu Anda adalah anugerah terindah bagi kami." }}"
                    </p>
                </div>

                <!-- EVENT BLOCK -->
                <div v-if="block.type === 'event'" v-motion-slide-visible-once-bottom class="flex flex-col gap-8">
                    <div>
                        <h3 class="text-xs text-gray-400 tracking-[0.2em] uppercase font-bold mb-3">Waktu</h3>
                        <p class="text-xl font-medium">{{ wedding.event_date ? new Date(wedding.event_date).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) : 'Segera Diumumkan' }}</p>
                    </div>
                    <div>
                        <h3 class="text-xs text-gray-400 tracking-[0.2em] uppercase font-bold mb-3">Lokasi</h3>
                        <p class="text-xl font-medium mb-1">{{ themeData.venue_name || 'Gedung Pernikahan' }}</p>
                        <p class="text-gray-500 text-sm">{{ themeData.venue_address || 'Alamat lokasi akan diumumkan.' }}</p>
                    </div>
                </div>

                <!-- MAP BLOCK -->
                <div v-if="block.type === 'map' && themeData.gmap_link" v-motion-fade-visible-once class="text-left">
                    <a :href="themeData.gmap_link" target="_blank" class="inline-flex items-center pb-2 border-b-2 border-black hover:text-gray-500 hover:border-gray-500 font-bold transition duration-300">
                        Buka di Google Maps
                        <span class="material-symbols-outlined ml-2 text-sm">arrow_outward</span>
                    </a>
                </div>

                <!-- QR CODE BLOCK -->
                <div v-if="block.type === 'qrcode'" v-motion-slide-visible-once-bottom class="bg-gray-50 p-8 sm:p-12 text-center rounded-2xl">
                    <h3 class="text-sm font-bold tracking-widest uppercase mb-2">Tiket Akses</h3>
                    <p class="text-gray-500 mb-8 text-sm">Tunjukkan QR Code ini kepada resepsionis.</p>
                    
                    <div class="bg-white p-4 inline-block mb-6 shadow-sm border border-gray-100">
                        <img :src="qrCode" alt="QR Code Tamu" class="w-48 h-48 mx-auto object-cover" />
                    </div>
                    
                    <div>
                        <p class="text-[10px] text-gray-400 uppercase tracking-widest mb-1">Token Unik</p>
                        <p class="text-xs font-mono text-gray-800 tracking-wider">
                            {{ guest.secure_token || 'TOKEN-ABC-123' }}
                        </p>
                    </div>
                </div>

            </template>

            <footer class="mt-8 text-gray-400 text-xs tracking-widest uppercase">
                <p>Powered by <span class="font-bold text-gray-900">Wedding SaaS</span></p>
            </footer>
        </div>
    </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;700;800&display=swap');

.font-sans { font-family: 'Manrope', sans-serif; }
</style>
