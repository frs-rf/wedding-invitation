<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'

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

const submit = () => {
    form.put(route('pengantin.themes.update'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <AppLayout title="Manajemen Tema">
        <Head title="Manajemen Tema" />

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8 space-y-6">
                <!-- Status Notifikasi -->
                <div v-if="$page.props.flash?.success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ $page.props.flash.success }}
                </div>

                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Galeri Tema -->
                    <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Pilih Tema</h2>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div v-for="theme in themes" :key="theme.id" 
                                class="border-2 rounded-lg p-4 cursor-pointer hover:border-indigo-500 transition"
                                :class="{'border-indigo-600 bg-indigo-50': form.theme_id === theme.id}"
                                @click="form.theme_id = theme.id">
                                <h3 class="font-bold text-lg text-center">{{ theme.theme_name }}</h3>
                                <p class="text-sm text-center text-gray-500 mt-2">Pilih tema ini</p>
                            </div>
                        </div>
                        <InputError class="mt-2" :message="form.errors.theme_id" />
                    </div>

                    <!-- Editor Konten -->
                    <div class="bg-white p-6 shadow-sm sm:rounded-lg">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">Isi Konten Undangan</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <InputLabel for="bride_name" value="Nama Mempelai Wanita" />
                                <TextInput id="bride_name" type="text" class="mt-1 block w-full" v-model="form.theme_data.bride_name" />
                            </div>
                            <div>
                                <InputLabel for="groom_name" value="Nama Mempelai Pria" />
                                <TextInput id="groom_name" type="text" class="mt-1 block w-full" v-model="form.theme_data.groom_name" />
                            </div>
                            <div class="md:col-span-2">
                                <InputLabel for="venue_name" value="Nama Gedung / Lokasi Acara" />
                                <TextInput id="venue_name" type="text" class="mt-1 block w-full" v-model="form.theme_data.venue_name" />
                            </div>
                            <div class="md:col-span-2">
                                <InputLabel for="venue_address" value="Alamat Lengkap Lokasi" />
                                <textarea id="venue_address" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" v-model="form.theme_data.venue_address" rows="3"></textarea>
                            </div>
                            <div class="md:col-span-2">
                                <InputLabel for="gmap_link" value="Link Google Maps" />
                                <TextInput id="gmap_link" type="text" class="mt-1 block w-full" v-model="form.theme_data.gmap_link" />
                            </div>
                            <div class="md:col-span-2">
                                <InputLabel for="love_story" value="Kisah Cinta Singkat (Opsional)" />
                                <textarea id="love_story" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" v-model="form.theme_data.love_story" rows="4"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end">
                        <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing || !form.theme_id">
                            Simpan Tema & Konten
                        </PrimaryButton>
                    </div>
                </form>

            </div>
        </div>
    </AppLayout>
</template>
