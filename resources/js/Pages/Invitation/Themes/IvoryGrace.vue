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
    <div class="min-h-screen bg-[#FCFBF9] font-sans text-[#8B7D72] overflow-x-hidden selection:bg-[#C5A059] selection:text-white">
        <Head :title="`Undangan Pernikahan - ${themeData.bride_name || 'Pengantin'} & ${themeData.groom_name || 'Pengantin'}`" />

        <div class="relative z-10 max-w-lg mx-auto px-6 py-20 sm:px-12 flex flex-col gap-32">
            
            <template v-for="block in sortedBlocks" :key="block.id">

                <!-- HERO BLOCK -->
                <div v-if="block.type === 'hero'" v-motion-fade-visible-once class="text-center mt-12">
                    <p class="text-[#C5A059] text-xs tracking-[0.4em] uppercase mb-8 font-medium">The Wedding Of</p>
                    <h1 class="text-6xl sm:text-7xl font-serif text-[#5a5049] mb-8 leading-tight">
                        {{ themeData.bride_name || 'Budi' }} <br>
                        <span class="text-[#C5A059] text-5xl">&</span> <br>
                        {{ themeData.groom_name || 'Riri' }}
                    </h1>
                </div>

                <!-- GUEST BLOCK -->
                <div v-if="block.type === 'guest'" v-motion-slide-visible-once-bottom class="text-center bg-[#F5F0E6] p-10 rounded-t-full rounded-b-xl border border-[#ede3d1] shadow-sm relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-transparent via-[#C5A059] to-transparent opacity-30"></div>
                    <p class="text-[#8B7D72] mb-2 text-sm tracking-wider uppercase">Kepada Yth,</p>
                    <h2 class="text-2xl font-serif text-[#5a5049] mb-2">{{ guest.name || 'Tamu Undangan' }}</h2>
                    <p class="text-[#8B7D72] text-sm mb-4">{{ guest.company_or_address || 'Di Tempat' }}</p>
                    <div v-if="guest.is_vip" class="mt-2 inline-block px-4 py-1 bg-[#C5A059] text-white text-[10px] font-bold rounded-full uppercase tracking-widest shadow-sm">
                        VIP Guest
                    </div>
                </div>

                <!-- STORY BLOCK -->
                <div v-if="block.type === 'story'" v-motion-fade-visible-once class="text-center relative">
                    <span class="text-6xl text-[#F5F0E6] absolute -top-8 left-1/2 -translate-x-1/2 font-serif opacity-50">"</span>
                    <p class="text-xl sm:text-2xl text-[#5a5049] leading-relaxed font-serif relative z-10 italic px-4">
                        {{ themeData.love_story || "Kehadiran dan doa restu Anda adalah anugerah terindah bagi kami." }}
                    </p>
                    <div class="w-12 h-[1px] bg-[#C5A059] mx-auto mt-8"></div>
                </div>

                <!-- EVENT BLOCK -->
                <div v-if="block.type === 'event'" v-motion-slide-visible-once-bottom class="flex flex-col gap-12 text-center">
                    <div>
                        <h3 class="text-xs text-[#C5A059] tracking-[0.3em] uppercase font-bold mb-4">Waktu</h3>
                        <p class="text-xl font-serif text-[#5a5049]">{{ wedding.event_date ? new Date(wedding.event_date).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) : 'Segera Diumumkan' }}</p>
                    </div>
                    <div class="w-1 h-12 bg-[#F5F0E6] mx-auto rounded-full"></div>
                    <div>
                        <h3 class="text-xs text-[#C5A059] tracking-[0.3em] uppercase font-bold mb-4">Lokasi</h3>
                        <p class="text-2xl font-serif text-[#5a5049] mb-2">{{ themeData.venue_name || 'Gedung Pernikahan' }}</p>
                        <p class="text-[#8B7D72] text-sm leading-relaxed max-w-xs mx-auto">{{ themeData.venue_address || 'Alamat lokasi akan diumumkan.' }}</p>
                    </div>
                </div>

                <!-- MAP BLOCK -->
                <div v-if="block.type === 'map' && themeData.gmap_link" v-motion-fade-visible-once class="text-center">
                    <a :href="themeData.gmap_link" target="_blank" class="inline-flex items-center px-8 py-3 bg-transparent border border-[#C5A059] text-[#C5A059] hover:bg-[#C5A059] hover:text-white rounded-full text-sm font-medium tracking-widest uppercase transition duration-500">
                        Buka di Google Maps
                    </a>
                </div>

                <!-- QR CODE BLOCK -->
                <div v-if="block.type === 'qrcode'" v-motion-slide-visible-once-bottom class="bg-white p-8 sm:p-12 text-center rounded-[2rem] border border-[#F5F0E6] shadow-sm">
                    <h3 class="text-xs font-bold tracking-[0.2em] uppercase mb-2 text-[#C5A059]">Access Pass</h3>
                    <p class="text-[#8B7D72] mb-8 text-sm">Tunjukkan QR Code ini kepada resepsionis di lokasi acara.</p>
                    
                    <div class="bg-[#FCFBF9] p-4 inline-block mb-8 rounded-2xl border border-[#F5F0E6]">
                        <img :src="qrCode" alt="QR Code Tamu" class="w-48 h-48 mx-auto object-contain rounded-xl" />
                    </div>
                    
                    <div>
                        <p class="text-[10px] text-[#C5A059] uppercase tracking-[0.2em] mb-1">Token Unik</p>
                        <p class="text-xs font-mono text-[#5a5049] tracking-widest bg-[#F5F0E6] inline-block px-4 py-2 rounded-lg">
                            {{ guest.secure_token || 'TOKEN-ABC-123' }}
                        </p>
                    </div>
                </div>

            </template>

            <footer class="mt-16 pb-8 text-center">
                <div class="w-24 h-[1px] bg-[#e8decb] mx-auto mb-8"></div>
                <p class="text-[#8B7D72] text-[10px] tracking-widest uppercase">
                    Powered by <span class="font-bold text-[#C5A059]">Wedding SaaS</span>
                </p>
            </footer>
        </div>
    </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&family=Montserrat:wght@300;400;500&display=swap');

.font-serif { font-family: 'Cormorant Garamond', serif; }
.font-sans { font-family: 'Montserrat', sans-serif; }
</style>
