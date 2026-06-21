<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue'
import { Head, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/Components/ui/card'
import { Button } from '@/Components/ui/button'
import { Users, CheckCircle, QrCode, Copy, ExternalLink, Loader2 } from 'lucide-vue-next'
import { toast } from 'vue-sonner'

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
        onSuccess: () => {
            toast.success('Tautan berhasil dibuat!')
        },
        onFinish: () => { isGenerating.value = false }
    })
}

const copyToClipboard = (text: string) => {
    navigator.clipboard.writeText(text)
    toast.success('Tautan disalin ke clipboard!')
}

const getScannerUrl = () => {
    if (!props.wedding || !props.wedding.receptionist_token) return ''
    return route('check-in.index', { slug: props.wedding.slug, token: props.wedding.receptionist_token })
}
</script>

<template>
    <AppLayout title="Dashboard Pengantin">
        <Head title="Dashboard Pengantin" />

        <div class="space-y-8">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">Dashboard</h1>
                <p class="text-zinc-500 dark:text-zinc-400 mt-2">
                    Kelola undangan, tema, daftar tamu, dan analitik kehadiran.
                </p>
            </div>

            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                <Card class="hover:shadow-md transition-shadow">
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Total Tamu</CardTitle>
                        <Users class="h-4 w-4 text-zinc-500" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-3xl font-bold">{{ stats?.total_guests ?? 0 }}</div>
                        <p class="text-xs text-zinc-500 mt-1">
                            Tamu terdaftar di sistem
                        </p>
                    </CardContent>
                </Card>
                <Card class="hover:shadow-md transition-shadow">
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Sudah Check-in</CardTitle>
                        <CheckCircle class="h-4 w-4 text-emerald-500" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-3xl font-bold">{{ stats?.checked_in ?? 0 }}</div>
                        <p class="text-xs text-zinc-500 mt-1">
                            Tamu yang sudah memindai QR
                        </p>
                    </CardContent>
                </Card>
            </div>

            <!-- Scanner Magic Link Section -->
            <Card class="border-indigo-100 dark:border-indigo-900/50 shadow-sm overflow-hidden">
                <div class="bg-indigo-50/50 dark:bg-indigo-900/10 px-6 py-4 border-b border-indigo-100 dark:border-indigo-900/50">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg shrink-0">
                            <QrCode class="w-5 h-5 text-indigo-600 dark:text-indigo-400" />
                        </div>
                        <div>
                            <h3 class="font-semibold text-zinc-900 dark:text-zinc-100">Akses Scanner Resepsionis</h3>
                            <p class="text-sm text-zinc-500 dark:text-zinc-400">
                                Bagikan tautan ini kepada resepsionis di hari acara. Resepsionis tidak perlu login.
                            </p>
                        </div>
                    </div>
                </div>
                <CardContent class="pt-6">
                    <div v-if="wedding?.receptionist_token" class="flex flex-col sm:flex-row gap-3">
                        <div class="flex-1 bg-zinc-100 dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-md px-4 py-3 font-mono text-sm overflow-x-auto whitespace-nowrap flex items-center text-zinc-600 dark:text-zinc-400">
                            {{ getScannerUrl() }}
                        </div>
                        <div class="flex gap-2 shrink-0">
                            <Button variant="outline" @click="copyToClipboard(getScannerUrl())" class="flex items-center gap-2">
                                <Copy class="w-4 h-4" />
                                Salin
                            </Button>
                            <Button as="a" :href="getScannerUrl()" target="_blank" class="flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white">
                                Buka
                                <ExternalLink class="w-4 h-4" />
                            </Button>
                        </div>
                    </div>
                    
                    <div v-else>
                        <Button @click="generateToken" :disabled="isGenerating" class="w-full sm:w-auto bg-indigo-600 hover:bg-indigo-700">
                            <Loader2 v-if="isGenerating" class="mr-2 h-4 w-4 animate-spin" />
                            <QrCode v-else class="mr-2 h-4 w-4" />
                            {{ isGenerating ? 'Membuat Tautan...' : 'Buat Tautan Scanner' }}
                        </Button>
                    </div>
                </CardContent>
            </Card>

        </div>
    </AppLayout>
</template>
