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
    <div class="min-h-screen bg-[#F0F4EC] font-sans text-[#2C3E2D] overflow-x-hidden selection:bg-[#8AA889] selection:text-white">
        <Head :title="`Undangan Pernikahan - ${themeData.bride_name || 'Pengantin'} & ${themeData.groom_name || 'Pengantin'}`" />

        <!-- Green Leaves Decoration -->
        <div class="fixed top-0 left-0 w-72 h-72 bg-[#D1E2C4] rounded-full mix-blend-multiply filter blur-3xl opacity-50 -z-10 translate-x-[-20%] translate-y-[-20%]"></div>
        <div class="fixed bottom-0 right-0 w-80 h-80 bg-[#E8E2C1] rounded-full mix-blend-multiply filter blur-3xl opacity-50 -z-10 translate-x-[20%] translate-y-[20%]"></div>

        <div class="relative z-10 max-w-3xl mx-auto px-4 py-12 sm:px-6 lg:px-8 flex flex-col gap-12">
            
            <template v-for="block in sortedBlocks" :key="block.id">

                <!-- HERO BLOCK -->
                <div v-if="block.type === 'hero'" v-motion-fade-visible-once class="text-center bg-white/70 backdrop-blur-md rounded-[3rem] p-12 shadow-lg border border-white/50">
                    <p class="text-[#5F7A61] text-sm tracking-widest uppercase mb-4">You are invited to the wedding of</p>
                    <h1 class="text-5xl sm:text-6xl font-serif text-[#2C3E2D] mb-4 flex flex-col items-center gap-2">
                        <span>{{ themeData.bride_name || 'Budi' }}</span>
                        <span class="text-[#8AA889] text-4xl">&</span>
                        <span>{{ themeData.groom_name || 'Riri' }}</span>
                    </h1>
                </div>

                <!-- GUEST BLOCK -->
                <div v-if="block.type === 'guest'" v-motion-slide-visible-once-bottom class="text-center bg-white rounded-3xl p-8 shadow-sm">
                    <p class="text-[#5F7A61] mb-2">Dear,</p>
                    <h2 class="text-2xl font-bold text-[#2C3E2D] mb-1">{{ guest.name || 'Tamu Undangan' }}</h2>
                    <p class="text-[#5F7A61] text-sm">{{ guest.company_or_address || 'Di Tempat' }}</p>
                    <div v-if="guest.is_vip" class="mt-4 inline-block px-3 py-1 bg-[#8AA889] text-white text-xs font-bold rounded-full uppercase tracking-wider">
                        VIP
                    </div>
                </div>

                <!-- STORY BLOCK -->
                <div v-if="block.type === 'story'" v-motion-fade-visible-once class="text-center px-4">
                    <div class="w-12 h-12 bg-[#8AA889]/20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <span class="material-symbols-outlined text-[#5F7A61]">favorite</span>
                    </div>
                    <p class="text-lg text-[#2C3E2D] italic">
                        "{{ themeData.love_story || "Kehadiran dan doa restu Anda adalah anugerah terindah bagi kami." }}"
                    </p>
                </div>

                <!-- EVENT BLOCK -->
                <div v-if="block.type === 'event'" v-motion-slide-visible-once-bottom class="flex flex-col gap-6">
                    <div class="bg-white rounded-3xl p-6 shadow-sm text-center">
                        <div class="w-12 h-12 bg-[#8AA889]/20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="material-symbols-outlined text-[#5F7A61]">event</span>
                        </div>
                        <h3 class="font-bold text-[#2C3E2D] text-lg mb-2">Waktu</h3>
                        <p class="text-[#5F7A61]">{{ wedding.event_date ? new Date(wedding.event_date).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) : 'Segera Diumumkan' }}</p>
                    </div>
                    <div class="bg-white rounded-3xl p-6 shadow-sm text-center">
                        <div class="w-12 h-12 bg-[#8AA889]/20 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="material-symbols-outlined text-[#5F7A61]">location_on</span>
                        </div>
                        <h3 class="font-bold text-[#2C3E2D] text-lg mb-2">Lokasi</h3>
                        <p class="text-[#2C3E2D] font-medium">{{ themeData.venue_name || 'Gedung Pernikahan' }}</p>
                        <p class="text-[#5F7A61] text-sm mt-1">{{ themeData.venue_address || 'Alamat lokasi akan diumumkan.' }}</p>
                    </div>
                </div>

                <!-- MAP BLOCK -->
                <div v-if="block.type === 'map' && themeData.gmap_link" v-motion-fade-visible-once class="text-center">
                    <a :href="themeData.gmap_link" target="_blank" class="inline-flex items-center px-8 py-3 bg-[#8AA889] hover:bg-[#728F71] text-white rounded-full font-bold transition duration-300 shadow-md">
                        <span class="material-symbols-outlined mr-2">map</span>
                        Buka di Google Maps
                    </a>
                </div>

                <!-- QR CODE BLOCK -->
                <div v-if="block.type === 'qrcode'" v-motion-slide-visible-once-bottom class="bg-white/80 backdrop-blur-md rounded-[3rem] p-10 text-center shadow-lg border border-white/50">
                    <h3 class="text-xl font-bold text-[#2C3E2D] mb-2">Akses Masuk</h3>
                    <p class="text-[#5F7A61] mb-8 text-sm">Tunjukkan QR Code ini kepada resepsionis.</p>
                    
                    <div class="bg-white p-4 rounded-2xl shadow-sm inline-block mb-6">
                        <img :src="qrCode" alt="QR Code Tamu" class="w-48 h-48 mx-auto rounded-xl object-contain" />
                    </div>
                    
                    <div>
                        <p class="text-xs text-[#5F7A61] uppercase tracking-widest mb-2">Token Unik</p>
                        <p class="text-sm font-mono font-bold text-[#2C3E2D] bg-[#F0F4EC] py-2 px-4 rounded-xl inline-block">
                            {{ guest.secure_token || 'TOKEN-ABC-123' }}
                        </p>
                    </div>
                </div>

            </template>

            <footer class="mt-8 text-[#5F7A61] text-sm text-center opacity-70">
                <p>Powered by <span class="font-bold">Wedding SaaS</span></p>
            </footer>
        </div>
    </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Lora:ital,wght@0,400;0,600;1,400&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&display=swap');

.font-serif { font-family: 'Lora', serif; }
.font-sans { font-family: 'Nunito', sans-serif; }
</style>
