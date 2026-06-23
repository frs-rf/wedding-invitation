<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3'
import { watch, onMounted } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { toast } from 'vue-sonner'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'

const props = defineProps<{
    wedding: any
    themes: any[]
}>()

const form = useForm({
    theme_id: props.wedding?.theme_id || null,
    theme_data: props.wedding?.theme_data || {
        bride_name: '',
        groom_name: '',
        venue_name: '',
        venue_address: '',
        gmap_link: '',
        love_story: '',
    },
});

const pageProps = usePage().props

watch(() => pageProps.flash, (flash: any) => {
    if (flash?.success) {
        toast.success(flash.success)
    }
}, { deep: true })

onMounted(() => {
    if (pageProps.flash?.success) {
        toast.success(pageProps.flash.success)
    }
})

const submit = () => {
    form.put(route('pengantin.themes.update'), {
        preserveScroll: true,
        onSuccess: () => {
            toast.success('Tema & Konten berhasil disimpan!')
        }
    });
};
</script>

<template>
    <Head title="Tema & Konten" />

    <DashboardLayout>
        <!-- Top Navigation Area / Header specific to this page inside DashboardLayout -->
        <template #sidebar-nav>
            <Link href="#" class="flex items-center gap-md px-md py-sm text-[#5d5e66] hover:bg-[#eae7eb] rounded-xl transition-all text-[14px]">
                <span class="material-symbols-outlined text-[20px]">dashboard</span>
                <span>Dashboard</span>
            </Link>
            <Link :href="route('pengantin.themes.index')" class="flex items-center gap-md px-md py-sm bg-[#e2dfff] text-[#1f108e] rounded-xl font-bold transition-all text-[14px]">
                <span class="material-symbols-outlined text-[20px]">palette</span>
                <span>Tema & Undangan</span>
            </Link>
            <Link href="#" class="flex items-center gap-md px-md py-sm text-[#5d5e66] hover:bg-[#eae7eb] rounded-xl transition-all text-[14px]">
                <span class="material-symbols-outlined text-[20px]">book</span>
                <span>Buku Tamu</span>
            </Link>
        </template>

        <div class="mb-xl">
            <h2 class="font-display text-[28px] tracking-tight text-[#1b1b1e] font-bold">Tema & Konten Undangan</h2>
            <p class="font-body-md text-[#5d5e66] mt-xs">Personalisasi tampilan undangan dan lengkapi detail acara pernikahan Anda.</p>
        </div>

        <form @submit.prevent="submit" class="space-y-xl max-w-5xl">
            <!-- Theme Selection Section -->
            <section class="bg-white border border-[#e4e1e6] rounded-2xl overflow-hidden shadow-sm">
                <div class="px-xl py-lg border-b border-[#e4e1e6] bg-[#fbf8fc] flex items-center gap-sm">
                    <span class="material-symbols-outlined text-[#1f108e]">web</span>
                    <h3 class="font-headline-md text-[18px] font-bold text-[#1b1b1e]">Pilih Tema Undangan</h3>
                </div>
                <div class="p-xl">
                    <p class="font-body-md text-[14px] text-[#5d5e66] mb-lg">Pilih desain visual yang paling cocok dengan nuansa pernikahan Anda.</p>
                    
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-lg">
                        <div v-for="theme in themes" :key="theme.id" 
                            class="relative rounded-2xl border-2 cursor-pointer transition-all duration-200 overflow-hidden group h-48 bg-[#f6f2f7] hover:shadow-md flex flex-col items-center justify-center"
                            :class="[
                                form.theme_id === theme.id 
                                    ? 'border-[#1f108e]' 
                                    : 'border-[#e4e1e6] hover:border-[#a9a7ff]'
                            ]"
                            @click="form.theme_id = theme.id">
                            
                            <div class="w-12 h-12 rounded-full bg-[#e2dfff] flex items-center justify-center mb-md">
                                <span class="material-symbols-outlined text-[#1f108e]">web_stories</span>
                            </div>
                            <h3 class="font-bold text-[16px] text-[#1b1b1e]">{{ theme.theme_name }}</h3>
                            
                            <!-- Selected Indicator -->
                            <div v-if="form.theme_id === theme.id" class="absolute top-sm right-sm w-6 h-6 bg-[#1f108e] rounded-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-white text-[16px]">check</span>
                            </div>
                        </div>
                    </div>
                    <p v-if="form.errors.theme_id" class="text-sm text-[#ba1a1a] mt-md">{{ form.errors.theme_id }}</p>
                </div>
            </section>

            <!-- Content Editor Section -->
            <section class="bg-white border border-[#e4e1e6] rounded-2xl overflow-hidden shadow-sm">
                <div class="px-xl py-lg border-b border-[#e4e1e6] bg-[#fbf8fc] flex items-center gap-sm">
                    <span class="material-symbols-outlined text-[#1f108e]">edit_document</span>
                    <h3 class="font-headline-md text-[18px] font-bold text-[#1b1b1e]">Isi Konten Undangan</h3>
                </div>
                <div class="p-xl">
                    <p class="font-body-md text-[14px] text-[#5d5e66] mb-lg">Lengkapi data nama mempelai, lokasi, dan detail acara.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-xl gap-y-lg">
                        <!-- Bride Name -->
                        <div class="space-y-sm">
                            <label for="bride_name" class="font-label-sm text-[14px] font-bold text-[#1b1b1e]">Nama Mempelai Wanita</label>
                            <input id="bride_name" v-model="form.theme_data.bride_name" type="text" placeholder="Budi Santoso 1" 
                                class="w-full border border-[#c8c4d5] rounded-xl px-md py-sm font-body-md text-[14px] focus:ring-2 focus:ring-[#1f108e] focus:border-transparent outline-none transition-all placeholder:text-[#5d5e66]">
                        </div>
                        
                        <!-- Groom Name -->
                        <div class="space-y-sm">
                            <label for="groom_name" class="font-label-sm text-[14px] font-bold text-[#1b1b1e]">Nama Mempelai Pria</label>
                            <input id="groom_name" v-model="form.theme_data.groom_name" type="text" placeholder="Riri Andayani 1" 
                                class="w-full border border-[#c8c4d5] rounded-xl px-md py-sm font-body-md text-[14px] focus:ring-2 focus:ring-[#1f108e] focus:border-transparent outline-none transition-all placeholder:text-[#5d5e66]">
                        </div>
                        
                        <!-- Venue Name -->
                        <div class="md:col-span-2 space-y-sm">
                            <label for="venue_name" class="font-label-sm text-[14px] font-bold text-[#1b1b1e] flex items-center gap-xs">
                                <span class="material-symbols-outlined text-[18px]">location_on</span> Nama Gedung / Lokasi Acara
                            </label>
                            <input id="venue_name" v-model="form.theme_data.venue_name" type="text" placeholder="Gedung Balai Kartini" 
                                class="w-full border border-[#c8c4d5] rounded-xl px-md py-sm font-body-md text-[14px] focus:ring-2 focus:ring-[#1f108e] focus:border-transparent outline-none transition-all placeholder:text-[#5d5e66]">
                        </div>
                        
                        <!-- Venue Address -->
                        <div class="md:col-span-2 space-y-sm">
                            <label for="venue_address" class="font-label-sm text-[14px] font-bold text-[#1b1b1e]">Alamat Lengkap Lokasi</label>
                            <textarea id="venue_address" v-model="form.theme_data.venue_address" rows="3" placeholder="Jl. Jend. Gatot Subroto Kav. 37, Jakarta Selatan"
                                class="w-full border border-[#c8c4d5] rounded-xl px-md py-sm font-body-md text-[14px] focus:ring-2 focus:ring-[#1f108e] focus:border-transparent outline-none transition-all placeholder:text-[#5d5e66]"></textarea>
                        </div>
                        
                        <!-- Google Maps Link -->
                        <div class="md:col-span-2 space-y-sm">
                            <label for="gmap_link" class="font-label-sm text-[14px] font-bold text-[#1b1b1e]">Link Google Maps</label>
                            <input id="gmap_link" v-model="form.theme_data.gmap_link" type="text" placeholder="https://maps.app.goo.gl/..." 
                                class="w-full border border-[#c8c4d5] rounded-xl px-md py-sm font-body-md text-[14px] focus:ring-2 focus:ring-[#1f108e] focus:border-transparent outline-none transition-all placeholder:text-[#5d5e66]">
                        </div>
                        
                        <!-- Love Story -->
                        <div class="md:col-span-2 space-y-sm">
                            <label for="love_story" class="font-label-sm text-[14px] font-bold text-[#1b1b1e] flex items-center gap-xs">
                                <span class="material-symbols-outlined text-[18px] text-[#ba1a1a]">favorite</span> Kisah Cinta Singkat (Opsional)
                            </label>
                            <textarea id="love_story" v-model="form.theme_data.love_story" rows="4" placeholder="Ceritakan awal mula kalian bertemu..."
                                class="w-full border border-[#c8c4d5] rounded-xl px-md py-sm font-body-md text-[14px] focus:ring-2 focus:ring-[#1f108e] focus:border-transparent outline-none transition-all placeholder:text-[#5d5e66]"></textarea>
                        </div>
                    </div>
                </div>
                
                <!-- Footer actions -->
                <div class="px-xl py-lg border-t border-[#e4e1e6] bg-[#fbf8fc] flex justify-end">
                    <button type="submit" :disabled="form.processing || !form.theme_id" 
                        class="px-xl py-sm bg-[#1b1b1e] text-white rounded-xl font-label-sm text-[14px] font-bold hover:opacity-90 transition-opacity disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-sm">
                        <span v-if="form.processing" class="material-symbols-outlined animate-spin text-[18px]">progress_activity</span>
                        Simpan Tema & Konten
                    </button>
                </div>
            </section>
        </form>
    </DashboardLayout>
</template>
