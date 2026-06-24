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

const isBlockEnabled = (type: string) => {
    const block = blocks.value.find((b: any) => b.type === type)
    return block ? block.enabled : true
}

const sortedBlocks = computed(() => {
    return blocks.value.filter((b: any) => b.enabled)
})

</script>

<template>
    <div class="min-h-screen relative bg-[#F9F6F0] font-serif text-[#4A3B32] overflow-x-hidden selection:bg-[#D4A373] selection:text-white">
        
        <Head :title="`Undangan Pernikahan - ${themeData.bride_name || 'Pengantin'} & ${themeData.groom_name || 'Pengantin'}`" />

        <!-- Floral Accents Top Left and Bottom Right (Using generic SVG or Tailwind shapes) -->
        <div class="fixed top-0 left-0 w-64 h-64 bg-[#EADDCB] rounded-br-full opacity-40 blur-3xl -z-10"></div>
        <div class="fixed bottom-0 right-0 w-80 h-80 bg-[#E8D1C5] rounded-tl-full opacity-40 blur-3xl -z-10"></div>

        <div class="relative z-10 flex flex-col items-center min-h-screen py-16 px-4 sm:px-6 lg:px-8">
            
            <!-- Main Content Card -->
            <div class="w-full max-w-2xl bg-white border border-[#E8E0D5] p-8 sm:p-14 shadow-xl mt-4 sm:mt-10 relative overflow-hidden">
                
                <!-- Inner Border Accent -->
                <div class="absolute inset-2 border border-[#D4A373]/30 pointer-events-none z-50"></div>

                <div class="flex flex-col gap-12 relative z-20">
                    <template v-for="block in sortedBlocks" :key="block.id">
                        
                        <!-- HERO BLOCK -->
                        <div v-if="block.type === 'hero'" v-motion-fade-visible-once class="text-center">
                            <p class="text-[#8C7A6B] text-sm tracking-[0.2em] uppercase mb-4 font-sans font-medium">The Wedding Of</p>
                            <h1 class="text-5xl sm:text-7xl font-serif text-[#4A3B32] mb-6 tracking-tight">
                                {{ themeData.bride_name || 'Budi' }} <br><span class="text-[#D4A373] text-4xl italic">&</span><br> {{ themeData.groom_name || 'Riri' }}
                            </h1>
                        </div>

                        <!-- GUEST BLOCK -->
                        <div v-if="block.type === 'guest'" v-motion-slide-visible-once-bottom class="text-center bg-[#FDFBF7] p-8 border-y border-[#E8E0D5]">
                            <p class="text-[#8C7A6B] text-md mb-3 italic">Kepada Yth. Bapak/Ibu/Saudara/i,</p>
                            <h2 class="text-3xl font-bold text-[#4A3B32] mb-2">{{ guest.name || 'Tamu Undangan' }}</h2>
                            <p class="text-[#8C7A6B] text-sm font-sans">{{ guest.company_or_address || 'Di Tempat' }}</p>
                            <div v-if="guest.is_vip" class="mt-5 inline-block px-4 py-1 border border-[#D4A373] text-[#D4A373] text-xs font-bold rounded-full uppercase tracking-wider font-sans">
                                VIP Guest
                            </div>
                        </div>

                        <!-- STORY BLOCK -->
                        <div v-if="block.type === 'story'" v-motion-fade-visible-once class="text-center px-4">
                            <p class="text-lg text-[#6A5A4E] leading-relaxed italic">
                                "{{ themeData.love_story || "Kehadiran dan doa restu Anda adalah anugerah terindah bagi kami." }}"
                            </p>
                            <div class="w-16 h-px bg-[#D4A373] mx-auto mt-8"></div>
                        </div>

                        <!-- EVENT BLOCK -->
                        <div v-if="block.type === 'event'" v-motion-slide-visible-once-bottom class="grid grid-cols-1 md:grid-cols-2 gap-8 text-center font-sans">
                            <div class="p-6 bg-[#FDFBF7] border border-[#E8E0D5]">
                                <span class="material-symbols-outlined text-3xl text-[#D4A373] mb-4">calendar_month</span>
                                <h3 class="font-serif text-xl text-[#4A3B32] mb-2">Tanggal Acara</h3>
                                <p class="text-[#8C7A6B] text-sm">{{ wedding.event_date ? new Date(wedding.event_date).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) : 'Segera Diumumkan' }}</p>
                            </div>
                            <div class="p-6 bg-[#FDFBF7] border border-[#E8E0D5]">
                                <span class="material-symbols-outlined text-3xl text-[#D4A373] mb-4">location_on</span>
                                <h3 class="font-serif text-xl text-[#4A3B32] mb-2">Lokasi</h3>
                                <p class="text-[#8C7A6B] text-sm font-semibold">{{ themeData.venue_name || 'Gedung Pernikahan' }}</p>
                                <p class="text-[#8C7A6B] text-xs mt-2">{{ themeData.venue_address || 'Alamat lokasi akan diumumkan segera.' }}</p>
                            </div>
                        </div>

                        <!-- MAP BLOCK -->
                        <div v-if="block.type === 'map' && themeData.gmap_link" v-motion-fade-visible-once class="text-center">
                            <a :href="themeData.gmap_link" target="_blank" class="inline-flex items-center px-8 py-3 bg-[#4A3B32] hover:bg-[#362A22] text-[#F9F6F0] font-sans text-sm tracking-widest uppercase transition duration-300 shadow-md">
                                <span class="material-symbols-outlined text-lg mr-2">map</span>
                                Buka di Peta
                            </a>
                        </div>

                        <!-- QR CODE BLOCK -->
                        <div v-if="block.type === 'qrcode'" v-motion-slide-visible-once-bottom class="bg-[#FDFBF7] p-8 sm:p-10 text-center border border-[#E8E0D5]">
                            <h3 class="text-xl font-serif text-[#4A3B32] mb-3">Tiket Akses</h3>
                            <p class="text-[#8C7A6B] mb-8 text-sm px-4 font-sans">Tunjukkan QR Code ini kepada resepsionis saat kedatangan.</p>
                            
                            <div class="inline-block p-4 bg-white border border-[#E8E0D5] shadow-sm">
                                <img :src="qrCode" alt="QR Code Tamu" class="w-48 h-48 mx-auto object-cover" />
                            </div>
                            
                            <div class="mt-8 font-sans">
                                <p class="text-xs text-[#8C7A6B] uppercase tracking-[0.2em] mb-2">Token Unik</p>
                                <p class="text-[10px] sm:text-xs text-[#6A5A4E] font-mono tracking-widest break-all px-2 select-all bg-white p-2 border border-[#E8E0D5]">
                                    {{ guest.secure_token || 'TOKEN-ABC-123' }}
                                </p>
                            </div>
                        </div>

                    </template>
                </div>
                
            </div>
            
            <footer class="mt-16 text-[#8C7A6B] text-sm pb-8 text-center font-sans tracking-widest uppercase relative z-10">
                <p>Powered by <span class="font-semibold text-[#4A3B32]">Wedding SaaS</span></p>
            </footer>
        </div>
    </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&display=swap');
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500&display=swap');

.font-serif { font-family: 'Playfair Display', serif; }
.font-sans { font-family: 'Inter', sans-serif; }
</style>
