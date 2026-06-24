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
    <div class="min-h-screen relative bg-cover bg-center overflow-x-hidden font-sans text-gray-800" 
         style="background-image: url('https://images.unsplash.com/photo-1511285560929-80b456fea0bc?q=80&w=2069&auto=format&fit=crop'); background-attachment: fixed;">
        
        <Head :title="`Undangan Pernikahan - ${themeData.bride_name || 'Pengantin'} & ${themeData.groom_name || 'Pengantin'}`" />

        <!-- Overlay for better readability -->
        <div class="absolute inset-0 bg-black/50 backdrop-blur-[2px] fixed"></div>

        <div class="relative z-10 flex flex-col items-center min-h-screen py-12 px-4 sm:px-6 lg:px-8">
            
            <!-- Main Invitation Card with Glassmorphism -->
            <div class="w-full max-w-2xl bg-white/10 backdrop-blur-md border border-white/20 p-8 sm:p-12 rounded-3xl shadow-2xl mt-4 sm:mt-10 transform transition duration-500 hover:scale-[1.01]">
                
                <div class="text-center mb-10">
                    <p class="text-white/80 text-sm tracking-widest uppercase mb-3 font-medium">The Wedding Of</p>
                    <h1 class="text-5xl sm:text-6xl lg:text-7xl font-serif text-white mb-6 drop-shadow-md">
                        {{ themeData.bride_name || 'Budi' }} <span class="text-pink-300">&</span> {{ themeData.groom_name || 'Riri' }}
                    </h1>
                </div>

                <div class="text-center bg-white/10 rounded-2xl p-8 mb-10 border border-white/20 shadow-inner">
                    <p class="text-white/90 text-lg mb-2">Yth. Bapak/Ibu/Saudara/i</p>
                    <h2 class="text-3xl font-bold text-white mb-2 tracking-wide">{{ guest.name }}</h2>
                    <p class="text-white/80 text-sm">{{ guest.company_or_address }}</p>
                    <div v-if="guest.is_vip" class="mt-4 inline-block px-4 py-1.5 bg-gradient-to-r from-yellow-400 to-yellow-600 text-white text-xs font-bold rounded-full uppercase tracking-wider shadow-lg">
                        ⭐ VIP Guest
                    </div>
                </div>

                <div class="text-center mb-10 text-white space-y-4 px-4">
                    <p class="italic text-lg text-white/90 leading-relaxed font-serif">
                        "{{ themeData.love_story || "Kehadiran dan doa restu Anda adalah anugerah terindah bagi kami." }}"
                    </p>
                    <div class="w-16 h-px bg-white/40 mx-auto mt-6"></div>
                </div>

                <!-- Event Details -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-center text-white mb-10">
                    <div class="bg-white/10 p-6 rounded-2xl border border-white/10 hover:bg-white/20 transition duration-300">
                        <svg class="w-8 h-8 mx-auto mb-4 text-pink-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <h3 class="font-bold text-lg mb-2">Tanggal Acara</h3>
                        <p class="text-white/90">{{ wedding.event_date ? new Date(wedding.event_date).toLocaleDateString('id-ID', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' }) : 'Segera Diumumkan' }}</p>
                    </div>
                    <div class="bg-white/10 p-6 rounded-2xl border border-white/10 hover:bg-white/20 transition duration-300">
                        <svg class="w-8 h-8 mx-auto mb-4 text-pink-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <h3 class="font-bold text-lg mb-2">Lokasi</h3>
                        <p class="text-white/90">{{ themeData.venue_name || 'Gedung Pernikahan' }}</p>
                    </div>
                </div>

                <div v-if="themeData.venue_address" class="text-center text-white/80 mb-6 text-sm px-4">
                    {{ themeData.venue_address }}
                </div>
                
                <div v-if="themeData.gmap_link" class="text-center mb-12">
                    <a :href="themeData.gmap_link" target="_blank" class="inline-flex items-center px-8 py-3 bg-white/20 hover:bg-white/30 border border-white/40 text-white rounded-full font-medium transition duration-300 backdrop-blur-md shadow-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                        Buka di Google Maps
                    </a>
                </div>

                <!-- QR Code Section -->
                <div class="mt-8 bg-white/95 rounded-3xl p-8 sm:p-10 text-center shadow-2xl border-4 border-white/50">
                    <h3 class="text-2xl font-bold text-gray-800 mb-2">Tiket Masuk Anda</h3>
                    <p class="text-gray-500 mb-8 text-sm px-4">Tunjukkan QR Code ini kepada resepsionis saat Anda tiba di lokasi acara untuk proses Check-in.</p>
                    
                    <div class="inline-block p-4 bg-white rounded-2xl shadow-inner border border-gray-100">
                        <img :src="qrCode" alt="QR Code Tamu" class="w-56 h-56 mx-auto" />
                    </div>
                    
                    <div class="mt-6">
                        <p class="text-xs text-gray-400 font-medium uppercase tracking-widest mb-1">Kode Unik:</p>
                        <p class="text-[10px] sm:text-xs text-gray-400 font-mono tracking-widest break-all px-2 select-all">
                            {{ guest.secure_token }}
                        </p>
                    </div>
                </div>
                
            </div>
            
            <footer class="mt-16 text-white/50 text-sm pb-8 text-center relative z-10 tracking-wider">
                <p>Powered by <span class="font-semibold text-white/70">Wedding Invitation SaaS</span></p>
            </footer>
        </div>
    </div>
</template>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap');

h1, .font-serif {
    font-family: 'Playfair Display', serif;
}
</style>
