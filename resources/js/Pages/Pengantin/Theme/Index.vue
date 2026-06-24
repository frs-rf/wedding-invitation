<script setup lang="ts">
import { Head, useForm, Link } from '@inertiajs/vue3'
import { watch, onMounted, computed, defineAsyncComponent } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { toast } from 'vue-sonner'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { VueDraggable } from 'vue-draggable-plus'

const props = defineProps<{
    wedding: any
    themes: any[]
}>()

const defaultBlocks = [
    { id: 'hero', type: 'hero', label: 'Sampul & Nama', enabled: true },
    { id: 'guest', type: 'guest', label: 'Kepada Yth.', enabled: true },
    { id: 'story', type: 'story', label: 'Kisah Cinta', enabled: true },
    { id: 'event', type: 'event', label: 'Waktu & Lokasi Acara', enabled: true },
    { id: 'map', type: 'map', label: 'Google Maps', enabled: true },
    { id: 'qrcode', type: 'qrcode', label: 'QR Code Tamu', enabled: true }
]

const form = useForm({
    theme_id: props.wedding?.theme_id || null,
    theme_data: props.wedding?.theme_data?.blocks ? props.wedding.theme_data : {
        bride_name: props.wedding?.theme_data?.bride_name || '',
        groom_name: props.wedding?.theme_data?.groom_name || '',
        venue_name: props.wedding?.theme_data?.venue_name || '',
        venue_address: props.wedding?.theme_data?.venue_address || '',
        gmap_link: props.wedding?.theme_data?.gmap_link || '',
        love_story: props.wedding?.theme_data?.love_story || '',
        blocks: defaultBlocks
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

const ThemeComponents: Record<string, any> = {
    'Themes/FloralClassic': defineAsyncComponent(() => import('@/Pages/Invitation/Themes/FloralClassic.vue')),
    'Themes/ModernMinimal': defineAsyncComponent(() => import('@/Pages/Invitation/Themes/ModernMinimal.vue')),
    'Themes/GardenParty': defineAsyncComponent(() => import('@/Pages/Invitation/Themes/GardenParty.vue')),
}

const selectedThemeData = computed(() => props.themes.find(t => t.id === form.theme_id))
const selectedThemeComponent = computed(() => {
    if (!selectedThemeData.value) return null;
    return ThemeComponents[selectedThemeData.value.view_path] || null;
})

const mockGuest = {
    name: 'Bapak/Ibu Tamu',
    company_or_address: 'Di Tempat',
    is_vip: true,
    secure_token: 'PREVIEW-XYZ'
}
const mockQrCode = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=PREVIEW'

</script>

<template>
    <Head title="Tema & Konten" />

    <DashboardLayout>

        <div class="mb-xl">
            <h2 class="font-display text-[28px] tracking-tight text-[#1b1b1e] font-bold">Tema & Live Builder</h2>
            <p class="font-body-md text-[#5d5e66] mt-xs">Personalisasi tampilan undangan, geser blok konten, dan lihat hasilnya secara langsung.</p>
        </div>

        <div class="flex flex-col lg:flex-row gap-8 items-start relative max-w-7xl mx-auto">
            
            <!-- Left Panel: Editor & Settings -->
            <form @submit.prevent="submit" class="w-full lg:w-[55%] space-y-xl pb-32">
                
                <!-- Theme Selection -->
                <section class="bg-white border border-[#e4e1e6] rounded-2xl overflow-hidden shadow-sm">
                    <div class="px-lg py-md border-b border-[#e4e1e6] bg-[#fbf8fc] flex items-center gap-sm">
                        <span class="material-symbols-outlined text-[#1f108e]">web</span>
                        <h3 class="font-headline-md text-[16px] font-bold text-[#1b1b1e]">1. Pilih Desain Utama</h3>
                    </div>
                    <div class="p-lg">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-md">
                            <div v-for="theme in themes" :key="theme.id" 
                                class="relative rounded-2xl border-2 cursor-pointer transition-all duration-200 overflow-hidden group h-32 bg-[#f6f2f7] hover:shadow-md flex flex-col items-center justify-center"
                                :class="[
                                    form.theme_id === theme.id 
                                        ? 'border-[#1f108e]' 
                                        : 'border-[#e4e1e6] hover:border-[#a9a7ff]'
                                ]"
                                @click="form.theme_id = theme.id">
                                
                                <span class="material-symbols-outlined text-[#1f108e] mb-2 text-[24px]">web_stories</span>
                                <h3 class="font-bold text-[14px] text-[#1b1b1e] text-center">{{ theme.theme_name }}</h3>
                                
                                <div v-if="form.theme_id === theme.id" class="absolute top-2 right-2 w-5 h-5 bg-[#1f108e] rounded-full flex items-center justify-center">
                                    <span class="material-symbols-outlined text-white text-[12px]">check</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Block Builder (Drag and Drop) -->
                <section class="bg-white border border-[#e4e1e6] rounded-2xl overflow-hidden shadow-sm">
                    <div class="px-lg py-md border-b border-[#e4e1e6] bg-[#fbf8fc] flex items-center gap-sm">
                        <span class="material-symbols-outlined text-[#1f108e]">view_agenda</span>
                        <h3 class="font-headline-md text-[16px] font-bold text-[#1b1b1e]">2. Susun Urutan Blok (Drag & Drop)</h3>
                    </div>
                    <div class="p-lg bg-[#f9f9fb]">
                        <p class="text-xs text-[#5d5e66] mb-4">Geser (drag) ikon <span class="material-symbols-outlined text-[14px] align-middle">drag_indicator</span> untuk mengubah urutan bagian undangan. Matikan saklar (toggle) untuk menyembunyikan bagian tersebut.</p>
                        
                        <VueDraggable 
                            v-model="form.theme_data.blocks" 
                            handle=".drag-handle" 
                            :animation="200" 
                            class="space-y-3"
                        >
                            <div v-for="block in form.theme_data.blocks" :key="block.id" 
                                class="bg-white border border-[#e4e1e6] rounded-xl p-3 flex items-center gap-3 shadow-sm hover:border-[#1f108e] transition-colors"
                                :class="{ 'opacity-60': !block.enabled }"
                            >
                                <span class="drag-handle material-symbols-outlined text-[#c8c4d5] hover:text-[#1f108e] cursor-grab active:cursor-grabbing text-[24px]">drag_indicator</span>
                                
                                <div class="w-8 h-8 rounded-lg bg-[#f6f2f7] flex items-center justify-center text-[#1f108e]">
                                    <span class="material-symbols-outlined text-[16px]">
                                        {{ block.type === 'hero' ? 'image' : 
                                           block.type === 'guest' ? 'mail' : 
                                           block.type === 'story' ? 'favorite' : 
                                           block.type === 'event' ? 'event' : 
                                           block.type === 'map' ? 'map' : 'qr_code' }}
                                    </span>
                                </div>
                                
                                <div class="flex-1 font-bold text-[14px] text-[#1b1b1e]">
                                    {{ block.label }}
                                </div>
                                
                                <label class="relative inline-flex items-center cursor-pointer">
                                  <input type="checkbox" v-model="block.enabled" class="sr-only peer">
                                  <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-[#1f108e]"></div>
                                </label>
                            </div>
                        </VueDraggable>
                    </div>
                </section>

                <!-- Content Form -->
                <section class="bg-white border border-[#e4e1e6] rounded-2xl overflow-hidden shadow-sm">
                    <div class="px-lg py-md border-b border-[#e4e1e6] bg-[#fbf8fc] flex items-center gap-sm">
                        <span class="material-symbols-outlined text-[#1f108e]">edit_document</span>
                        <h3 class="font-headline-md text-[16px] font-bold text-[#1b1b1e]">3. Isi Konten Teks</h3>
                    </div>
                    <div class="p-lg">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-lg gap-y-md">
                            <div class="space-y-1">
                                <label class="font-label-sm text-[12px] font-bold text-[#1b1b1e]">Nama Pria</label>
                                <input v-model="form.theme_data.groom_name" type="text" class="w-full border border-[#c8c4d5] rounded-xl px-3 py-2 text-[14px] focus:ring-2 focus:ring-[#1f108e]">
                            </div>
                            <div class="space-y-1">
                                <label class="font-label-sm text-[12px] font-bold text-[#1b1b1e]">Nama Wanita</label>
                                <input v-model="form.theme_data.bride_name" type="text" class="w-full border border-[#c8c4d5] rounded-xl px-3 py-2 text-[14px] focus:ring-2 focus:ring-[#1f108e]">
                            </div>
                            <div class="sm:col-span-2 space-y-1">
                                <label class="font-label-sm text-[12px] font-bold text-[#1b1b1e]">Nama Lokasi</label>
                                <input v-model="form.theme_data.venue_name" type="text" class="w-full border border-[#c8c4d5] rounded-xl px-3 py-2 text-[14px] focus:ring-2 focus:ring-[#1f108e]">
                            </div>
                            <div class="sm:col-span-2 space-y-1">
                                <label class="font-label-sm text-[12px] font-bold text-[#1b1b1e]">Alamat Lokasi</label>
                                <textarea v-model="form.theme_data.venue_address" rows="2" class="w-full border border-[#c8c4d5] rounded-xl px-3 py-2 text-[14px] focus:ring-2 focus:ring-[#1f108e]"></textarea>
                            </div>
                            <div class="sm:col-span-2 space-y-1">
                                <label class="font-label-sm text-[12px] font-bold text-[#1b1b1e]">Link Gmaps</label>
                                <input v-model="form.theme_data.gmap_link" type="text" class="w-full border border-[#c8c4d5] rounded-xl px-3 py-2 text-[14px] focus:ring-2 focus:ring-[#1f108e]">
                            </div>
                            <div class="sm:col-span-2 space-y-1">
                                <label class="font-label-sm text-[12px] font-bold text-[#1b1b1e]">Kisah Cinta</label>
                                <textarea v-model="form.theme_data.love_story" rows="3" class="w-full border border-[#c8c4d5] rounded-xl px-3 py-2 text-[14px] focus:ring-2 focus:ring-[#1f108e]"></textarea>
                            </div>
                        </div>
                    </div>
                </section>
                
                <div class="fixed bottom-0 left-0 right-0 lg:left-[260px] p-4 bg-white/80 backdrop-blur-md border-t border-[#e4e1e6] z-40 flex justify-end">
                    <div class="max-w-7xl mx-auto w-full flex justify-end">
                        <button type="submit" :disabled="form.processing || !form.theme_id" 
                            class="px-6 py-3 bg-[#1b1b1e] text-white rounded-xl font-bold hover:opacity-90 transition-opacity disabled:opacity-50 flex items-center gap-2">
                            <span v-if="form.processing" class="material-symbols-outlined animate-spin text-[18px]">progress_activity</span>
                            <span class="material-symbols-outlined text-[18px]" v-else>save</span>
                            Simpan Perubahan
                        </button>
                    </div>
                </div>
            </form>

            <!-- Right Panel: Live Preview Phone Mockup -->
            <div class="hidden lg:block w-[45%] sticky top-8" style="height: calc(100vh - 100px);">
                <div class="bg-[#f6f2f7] rounded-3xl p-6 h-full flex flex-col items-center border border-[#e4e1e6]">
                    <div class="w-full flex justify-between items-center mb-4">
                        <h3 class="font-bold text-[#1b1b1e] flex items-center gap-2">
                            <span class="material-symbols-outlined text-green-600 text-[18px]">motion_sensor_active</span>
                            Live Preview
                        </h3>
                        <span class="text-xs text-[#5d5e66] bg-white px-2 py-1 rounded-full border border-[#e4e1e6]">Auto-update</span>
                    </div>
                    
                    <!-- iPhone Mockup Container -->
                    <div class="relative w-[375px] h-[812px] bg-black rounded-[3rem] border-[12px] border-black shadow-2xl overflow-hidden scale-[0.85] origin-top">
                        <!-- Dynamic Theme Component -->
                        <div class="absolute inset-0 bg-white overflow-y-auto overflow-x-hidden scrollbar-hide">
                            <div v-if="!selectedThemeComponent" class="flex items-center justify-center h-full text-[#5d5e66] text-sm text-center px-8">
                                Pilih desain tema di panel kiri untuk melihat preview.
                            </div>
                            <component 
                                v-else
                                :is="selectedThemeComponent"
                                :wedding="{ ...props.wedding, theme_data: form.theme_data }"
                                :theme="selectedThemeData"
                                :guest="mockGuest"
                                :qrCode="mockQrCode"
                            />
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </DashboardLayout>
</template>

<style scoped>
.scrollbar-hide::-webkit-scrollbar {
    display: none;
}
.scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
