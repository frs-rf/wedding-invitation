<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { watch, onMounted } from 'vue'
import { usePage } from '@inertiajs/vue3'
import { toast } from 'vue-sonner'

// Shadcn UI Components
import { Card, CardContent, CardDescription, CardHeader, CardTitle, CardFooter } from '@/Components/ui/card'
import { Button } from '@/Components/ui/button'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'

// Icons
import { CheckCircle2, LayoutTemplate, Type, MapPin, Heart, Loader2 } from 'lucide-vue-next'

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

// Watch for flash messages from Inertia
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
    <AppLayout title="Tema & Konten">
        <Head title="Tema & Konten" />

        <div class="space-y-6">
            <!-- Header -->
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-100">Tema & Konten Undangan</h2>
                <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Personalisasi tampilan undangan dan lengkapi detail acara pernikahan Anda.</p>
            </div>

            <form @submit.prevent="submit" class="space-y-8">
                <!-- Galeri Tema -->
                <Card class="border-zinc-200 dark:border-zinc-800 shadow-sm">
                    <CardHeader>
                        <div class="flex items-center gap-2">
                            <LayoutTemplate class="h-5 w-5 text-indigo-500" />
                            <CardTitle>Pilih Tema Undangan</CardTitle>
                        </div>
                        <CardDescription>Pilih desain visual yang paling cocok dengan nuansa pernikahan Anda.</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                            <div v-for="theme in themes" :key="theme.id" 
                                class="relative rounded-xl border-2 cursor-pointer transition-all duration-200 overflow-hidden group hover:shadow-md"
                                :class="[
                                    form.theme_id === theme.id 
                                        ? 'border-indigo-600 ring-2 ring-indigo-600/20' 
                                        : 'border-zinc-200 dark:border-zinc-800 hover:border-indigo-400 dark:hover:border-indigo-500'
                                ]"
                                @click="form.theme_id = theme.id">
                                
                                <div class="aspect-[4/3] bg-zinc-100 dark:bg-zinc-900 flex items-center justify-center p-4">
                                    <div class="text-center">
                                        <div class="w-16 h-16 mx-auto rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center mb-3">
                                            <LayoutTemplate class="h-8 w-8 text-indigo-600 dark:text-indigo-400" />
                                        </div>
                                        <h3 class="font-bold text-lg text-zinc-900 dark:text-zinc-100">{{ theme.theme_name }}</h3>
                                    </div>
                                </div>
                                
                                <div class="absolute inset-x-0 bottom-0 p-3 bg-white/90 dark:bg-zinc-950/90 backdrop-blur-sm border-t border-zinc-100 dark:border-zinc-800 flex justify-between items-center transform translate-y-full transition-transform duration-200"
                                     :class="{ 'translate-y-0': form.theme_id === theme.id }">
                                    <span class="text-sm font-medium text-indigo-600 dark:text-indigo-400">Terpilih</span>
                                    <CheckCircle2 class="h-5 w-5 text-indigo-600 dark:text-indigo-400" />
                                </div>
                            </div>
                        </div>
                        <p v-if="form.errors.theme_id" class="text-sm text-red-500 mt-3">{{ form.errors.theme_id }}</p>
                    </CardContent>
                </Card>

                <!-- Editor Konten -->
                <Card class="border-zinc-200 dark:border-zinc-800 shadow-sm">
                    <CardHeader>
                        <div class="flex items-center gap-2">
                            <Type class="h-5 w-5 text-indigo-500" />
                            <CardTitle>Isi Konten Undangan</CardTitle>
                        </div>
                        <CardDescription>Lengkapi data nama mempelai, lokasi, dan detail acara.</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-8">
                            <div class="space-y-2">
                                <Label for="bride_name">Nama Mempelai Wanita</Label>
                                <Input id="bride_name" v-model="form.theme_data.bride_name" placeholder="Riri Amelia" />
                            </div>
                            
                            <div class="space-y-2">
                                <Label for="groom_name">Nama Mempelai Pria</Label>
                                <Input id="groom_name" v-model="form.theme_data.groom_name" placeholder="Budi Santoso" />
                            </div>
                            
                            <div class="md:col-span-2 space-y-2">
                                <div class="flex items-center gap-2 mb-1">
                                    <MapPin class="h-4 w-4 text-zinc-500" />
                                    <Label for="venue_name" class="!mb-0">Nama Gedung / Lokasi Acara</Label>
                                </div>
                                <Input id="venue_name" v-model="form.theme_data.venue_name" placeholder="Gedung Balai Kartini" />
                            </div>
                            
                            <div class="md:col-span-2 space-y-2">
                                <Label for="venue_address">Alamat Lengkap Lokasi</Label>
                                <textarea id="venue_address" 
                                    class="flex min-h-[80px] w-full rounded-md border border-zinc-200 bg-transparent px-3 py-2 text-sm shadow-sm placeholder:text-zinc-500 focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-950 disabled:cursor-not-allowed disabled:opacity-50 dark:border-zinc-800 dark:placeholder:text-zinc-400 dark:focus-visible:ring-zinc-300" 
                                    v-model="form.theme_data.venue_address" 
                                    placeholder="Jl. Jend. Gatot Subroto Kav. 37, Jakarta Selatan"
                                    rows="3"></textarea>
                            </div>
                            
                            <div class="md:col-span-2 space-y-2">
                                <Label for="gmap_link">Link Google Maps</Label>
                                <Input id="gmap_link" v-model="form.theme_data.gmap_link" placeholder="https://maps.app.goo.gl/..." />
                            </div>
                            
                            <div class="md:col-span-2 space-y-2">
                                <div class="flex items-center gap-2 mb-1">
                                    <Heart class="h-4 w-4 text-pink-500" />
                                    <Label for="love_story" class="!mb-0">Kisah Cinta Singkat (Opsional)</Label>
                                </div>
                                <textarea id="love_story" 
                                    class="flex min-h-[100px] w-full rounded-md border border-zinc-200 bg-transparent px-3 py-2 text-sm shadow-sm placeholder:text-zinc-500 focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-zinc-950 disabled:cursor-not-allowed disabled:opacity-50 dark:border-zinc-800 dark:placeholder:text-zinc-400 dark:focus-visible:ring-zinc-300" 
                                    v-model="form.theme_data.love_story" 
                                    placeholder="Ceritakan awal mula kalian bertemu..."
                                    rows="4"></textarea>
                            </div>
                        </div>
                    </CardContent>
                    <CardFooter class="bg-zinc-50/50 dark:bg-zinc-900/50 px-6 py-4 border-t border-zinc-100 dark:border-zinc-800 flex justify-end">
                        <Button type="submit" :disabled="form.processing || !form.theme_id" class="min-w-[180px]">
                            <Loader2 v-if="form.processing" class="mr-2 h-4 w-4 animate-spin" />
                            Simpan Tema & Konten
                        </Button>
                    </CardFooter>
                </Card>
            </form>

        </div>
    </AppLayout>
</template>
