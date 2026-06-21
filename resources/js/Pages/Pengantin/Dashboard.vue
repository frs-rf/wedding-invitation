<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import { ref } from 'vue'

const props = defineProps<{
    stats?: {
        total_guests?: number
        checked_in?: number
    }
    wedding?: any
}>()

const isGenerating = ref(false)

const generateToken = () => {
    isGenerating.value = true
    router.post(route('pengantin.generate-token'), {}, {
        onFinish: () => { isGenerating.value = false }
    })
}

const copyToClipboard = (text: string) => {
    navigator.clipboard.writeText(text)
    alert('Tautan disalin ke clipboard!')
}

const getScannerUrl = () => {
    if (!props.wedding || !props.wedding.receptionist_token) return ''
    return route('check-in.index', { slug: props.wedding.slug, token: props.wedding.receptionist_token })
}
</script>

<template>
    <AppLayout title="Dashboard Pengantin">
        <Head title="Dashboard Pengantin" />

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Dashboard Pengantin</h1>
                    <p class="mt-1 text-sm text-gray-600">
                        Kelola undangan, tema, daftar tamu, dan analitik kehadiran.
                    </p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-xl border bg-white p-5 shadow-sm">
                        <p class="text-xs uppercase tracking-wide text-gray-500">Total Tamu</p>
                        <p class="mt-2 text-3xl font-bold">{{ stats?.total_guests ?? 0 }}</p>
                    </div>
                    <div class="rounded-xl border bg-white p-5 shadow-sm">
                        <p class="text-xs uppercase tracking-wide text-gray-500">Sudah Check-in</p>
                        <p class="mt-2 text-3xl font-bold">{{ stats?.checked_in ?? 0 }}</p>
                    </div>
                </div>

                <!-- Scanner Magic Link Section -->
                <div class="rounded-xl border bg-white p-6 shadow-sm mt-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-2">Akses Scanner Resepsionis</h2>
                    <p class="text-sm text-gray-600 mb-4">Bagikan tautan ini kepada resepsionis di hari acara. Resepsionis tidak perlu login, cukup buka tautan untuk mengaktifkan kamera pemindai QR Code.</p>
                    
                    <div v-if="wedding?.receptionist_token" class="flex flex-col sm:flex-row gap-3">
                        <div class="flex-1 bg-gray-50 border border-gray-200 rounded-md px-4 py-2 font-mono text-xs overflow-x-auto whitespace-nowrap flex items-center">
                            {{ getScannerUrl() }}
                        </div>
                        <div class="flex gap-2">
                            <PrimaryButton @click="copyToClipboard(getScannerUrl())" class="bg-indigo-600">Salin</PrimaryButton>
                            <a :href="getScannerUrl()" target="_blank" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">Buka</a>
                        </div>
                    </div>
                    
                    <div v-else>
                        <PrimaryButton @click="generateToken" :disabled="isGenerating" :class="{ 'opacity-50': isGenerating }">
                            {{ isGenerating ? 'Membuat Tautan...' : 'Buat Tautan Scanner' }}
                        </PrimaryButton>
                    </div>
                </div>

            </div>
        </div>
    </AppLayout>
</template>
