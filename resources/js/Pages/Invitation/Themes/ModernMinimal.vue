<script setup lang="ts">
import { Head } from '@inertiajs/vue3'

const props = defineProps<{
    wedding: any
    theme: any
    guest: any
    qrCode: string
}>()

const themeData = props.wedding.theme_data || {}
</script>

<template>
    <div class="min-h-screen bg-zinc-50 font-sans text-zinc-900 overflow-x-hidden selection:bg-zinc-900 selection:text-white">
        
        <Head :title="`Undangan Pernikahan - ${themeData.bride_name || 'Pengantin'} & ${themeData.groom_name || 'Pengantin'}`" />

        <div class="flex flex-col items-center min-h-screen py-16 px-6 sm:px-12 lg:px-24">
            
            <div class="w-full max-w-3xl mt-10">
                
                <!-- Minimal Header -->
                <div class="mb-20">
                    <p class="text-zinc-400 text-xs tracking-[0.3em] uppercase mb-6 font-medium">Invitation</p>
                    <h1 class="text-6xl sm:text-8xl font-light text-zinc-900 mb-4 tracking-tighter">
                        {{ themeData.bride_name || 'Budi' }}
                    </h1>
                    <span class="text-zinc-300 text-6xl font-light mx-2">&</span>
                    <h1 class="text-6xl sm:text-8xl font-light text-zinc-900 tracking-tighter mt-4 text-right">
                        {{ themeData.groom_name || 'Riri' }}
                    </h1>
                </div>

                <div class="w-full h-px bg-zinc-200 mb-16"></div>

                <!-- Guest Addressed Section -->
                <div class="mb-16">
                    <p class="text-zinc-500 text-sm mb-4 tracking-wide uppercase">Dear</p>
                    <h2 class="text-4xl font-medium text-zinc-900 mb-2 tracking-tight">{{ guest.name }}</h2>
                    <p class="text-zinc-500 text-sm">{{ guest.company_or_address }}</p>
                    <div v-if="guest.is_vip" class="mt-6 inline-block px-3 py-1 bg-zinc-900 text-white text-[10px] font-bold uppercase tracking-[0.2em]">
                        VIP Access
                    </div>
                </div>

                <div class="mb-20 max-w-lg">
                    <p class="text-xl text-zinc-600 leading-relaxed font-light">
                        {{ themeData.love_story || "Kehadiran dan doa restu Anda adalah melengkapi kebahagiaan kami." }}
                    </p>
                </div>

                <!-- Event Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-20 border-t border-zinc-200 pt-12">
                    <div>
                        <p class="text-zinc-400 text-xs tracking-[0.2em] uppercase mb-4">Date & Time</p>
                        <p class="text-zinc-900 text-lg font-light">
                            {{ wedding.event_date ? new Date(wedding.event_date).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) : 'TBA' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-zinc-400 text-xs tracking-[0.2em] uppercase mb-4">Venue</p>
                        <p class="text-zinc-900 text-lg font-light mb-1">{{ themeData.venue_name || 'Gedung Pernikahan' }}</p>
                        <p class="text-zinc-500 text-sm">{{ themeData.venue_address }}</p>
                    </div>
                </div>
                
                <div v-if="themeData.gmap_link" class="mb-24">
                    <a :href="themeData.gmap_link" target="_blank" class="inline-flex items-center text-zinc-900 border-b border-zinc-900 pb-1 hover:text-zinc-500 hover:border-zinc-500 transition-colors text-sm tracking-[0.1em] uppercase">
                        View Map Direction
                        <span class="material-symbols-outlined text-sm ml-2">north_east</span>
                    </a>
                </div>

                <!-- QR Code Section -->
                <div class="mt-8 bg-white p-12 text-center border border-zinc-100 shadow-sm">
                    <h3 class="text-sm font-medium text-zinc-900 tracking-[0.2em] uppercase mb-2">Entry Pass</h3>
                    <p class="text-zinc-400 mb-10 text-xs">Present this QR code upon arrival.</p>
                    
                    <div class="inline-block">
                        <img :src="qrCode" alt="QR Code Tamu" class="w-64 h-64 mx-auto" />
                    </div>
                    
                    <div class="mt-10">
                        <p class="text-[10px] text-zinc-400 uppercase tracking-[0.3em] mb-2">Identifier</p>
                        <p class="text-xs text-zinc-900 font-mono tracking-widest break-all px-4 select-all">
                            {{ guest.secure_token }}
                        </p>
                    </div>
                </div>
                
            </div>
            
            <footer class="mt-32 text-zinc-400 text-xs pb-8 text-center tracking-[0.2em] uppercase w-full border-t border-zinc-200 pt-8 max-w-3xl">
                <p>Wedding SaaS Platform</p>
            </footer>
        </div>
    </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500&display=swap');

.font-sans { font-family: 'Outfit', sans-serif; }
</style>
