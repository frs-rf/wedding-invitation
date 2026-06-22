<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount } from 'vue'
import { Head } from '@inertiajs/vue3'
import { Html5Qrcode } from 'html5-qrcode'
import axios from 'axios'

const props = defineProps<{
    wedding: any
    token: string
}>()

const readerId = 'qr-reader'
let html5QrCode: Html5Qrcode | null = null

const isScanning = ref(false)
const scanResult = ref<any>(null)
const scanError = ref<string | null>(null)
const isProcessing = ref(false)

const startScanner = async () => {
    try {
        html5QrCode = new Html5Qrcode(readerId)
        isScanning.value = true
        scanResult.value = null
        scanError.value = null
        
        await html5QrCode.start(
            { facingMode: "environment" },
            {
                fps: 10,
                qrbox: { width: 250, height: 250 }
            },
            onScanSuccess,
            () => {} // ignore scan failures (happens every frame)
        )
    } catch (err) {
        console.error("Error starting scanner:", err)
        scanError.value = "Kamera tidak dapat diakses. Pastikan Anda memberikan izin kamera pada browser."
        isScanning.value = false
    }
}

const stopScanner = async () => {
    if (html5QrCode && html5QrCode.isScanning) {
        try {
            await html5QrCode.stop()
            isScanning.value = false
        } catch (err) {
            console.error("Error stopping scanner:", err)
        }
    }
}

const onScanSuccess = async (decodedText: string) => {
    if (isProcessing.value) return
    
    // Stop scanner temporarily to process
    await stopScanner()
    isProcessing.value = true
    scanError.value = null
    scanResult.value = null

    try {
        const response = await axios.post(`/check-in/${props.wedding.slug}/process`, {
            token: props.token,
            guest_token: decodedText
        })

        scanResult.value = {
            success: true,
            message: response.data.message,
            guest: response.data.guest
        }

    } catch (err: any) {
        scanResult.value = {
            success: false,
            message: err.response?.data?.error || "Terjadi kesalahan saat check-in.",
        }
    } finally {
        isProcessing.value = false
        // Automatically restart scanner after 3.5 seconds
        setTimeout(() => {
            startScanner()
        }, 3500)
    }
}

onMounted(() => {
    startScanner()
})

onBeforeUnmount(() => {
    stopScanner()
})
</script>

<template>
    <div class="min-h-screen bg-zinc-50 dark:bg-zinc-950 flex flex-col items-center justify-center py-6 sm:py-12 px-4">
        <Head title="Scanner Resepsionis" />

        <div class="w-full max-w-md bg-white dark:bg-zinc-900 p-6 sm:p-8 rounded-3xl shadow-xl border border-zinc-200 dark:border-zinc-800 text-center">
            
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-zinc-900 dark:text-zinc-100 mb-1">Digital Scanner</h1>
                <p class="text-zinc-500 dark:text-zinc-400 text-sm font-medium">Buku Tamu: {{ wedding.slug }}</p>
            </div>

            <!-- Scanner Container -->
            <div class="relative rounded-2xl overflow-hidden bg-black mb-8 shadow-inner mx-auto" style="width: 280px; height: 280px;">
                <div v-show="isScanning" id="qr-reader" class="w-full h-full"></div>
                
                <div v-if="!isScanning && !isProcessing" class="absolute inset-0 flex items-center justify-center bg-zinc-900 text-white flex-col">
                    <svg class="w-12 h-12 mb-3 text-zinc-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    <span class="text-zinc-300 font-medium">Kamera Jeda</span>
                    <button @click="startScanner" class="mt-4 px-5 py-2 bg-indigo-600 rounded-full text-sm font-semibold hover:bg-indigo-700 transition shadow-lg shadow-indigo-900/20 text-white">
                        Nyalakan Kamera
                    </button>
                </div>

                <div v-if="isProcessing" class="absolute inset-0 flex items-center justify-center bg-zinc-900/80 backdrop-blur-sm text-white flex-col">
                    <svg class="animate-spin -ml-1 mr-3 h-10 w-10 text-white mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="font-medium tracking-wide">Memproses...</span>
                </div>
            </div>

            <!-- Error Camera Message -->
            <div v-if="scanError" class="p-4 bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 rounded-2xl mb-6 text-sm border border-red-100 dark:border-red-900/50 font-medium">
                {{ scanError }}
            </div>

            <!-- Result Alert -->
            <div v-if="scanResult" class="p-6 rounded-2xl mb-2 transition-all duration-300 transform scale-100 shadow-lg border"
                 :class="scanResult.success ? 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-900/50' : 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-900/50'">
                 
                <div v-if="scanResult.success" class="flex flex-col items-center">
                    <div class="w-16 h-16 bg-white dark:bg-zinc-800 text-green-500 dark:text-green-400 rounded-full flex items-center justify-center mb-4 shadow border border-green-100 dark:border-green-900">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h2 class="text-2xl font-extrabold text-green-800 dark:text-green-300 mb-1">Hadir!</h2>
                    <p class="text-green-700 dark:text-green-400 text-lg font-bold tracking-tight">{{ scanResult.guest.name }}</p>
                    <div class="flex items-center justify-center space-x-2 mt-3">
                        <span v-if="scanResult.guest.is_vip" class="px-3 py-1 bg-yellow-400 dark:bg-yellow-500 text-yellow-900 dark:text-yellow-950 text-xs font-black rounded-full uppercase shadow-sm">VIP Guest</span>
                        <span class="text-sm text-green-600 dark:text-green-400 font-medium bg-green-100 dark:bg-green-900/50 px-3 py-1 rounded-full">{{ scanResult.guest.relationship || 'Tamu Reguler' }}</span>
                    </div>
                </div>

                <div v-else class="flex flex-col items-center">
                    <div class="w-16 h-16 bg-white dark:bg-zinc-800 text-red-500 dark:text-red-400 rounded-full flex items-center justify-center mb-4 shadow border border-red-100 dark:border-red-900">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </div>
                    <h2 class="text-xl font-bold text-red-800 dark:text-red-300 mb-2">Akses Ditolak</h2>
                    <p class="text-red-700 dark:text-red-400 font-medium text-center leading-snug">{{ scanResult.message }}</p>
                </div>
            </div>

            <!-- Footer Stats -->
            <div class="mt-8 pt-6 border-t border-zinc-100 dark:border-zinc-800">
                <p class="text-xs text-zinc-400 font-medium">Sistem Pemindai Otomatis &bull; Resepsionis</p>
            </div>
        </div>
    </div>
</template>

<style>
/* html5-qrcode overrides */
#qr-reader {
    border: none !important;
}
#qr-reader__scan_region {
    background-color: black;
}
#qr-reader__dashboard {
    display: none !important;
}
</style>
