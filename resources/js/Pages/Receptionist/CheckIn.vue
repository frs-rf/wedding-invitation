<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'

/**
 * Halaman scanner QR untuk resepsionis.
 * Diakses via Magic Link (tanpa login password) — PRD FR-CHK-01 / FR-CHK-02.
 *
 * Catatan: integrasi html5-qrcode + axios POST ke /api/v1/checkin
 * akan diimplementasikan di Fase 5. Saat ini baru placeholder UI.
 */
defineProps<{
    wedding_name?: string
    event_date?: string
}>()

const lastScan = ref<string | null>(null)
</script>

<template>
    <Head title="Scanner Check-in" />

    <div class="min-h-screen bg-gray-900 text-white">
        <header class="border-b border-gray-700 px-4 py-3">
            <p class="text-xs uppercase tracking-wide text-gray-400">Resepsionis</p>
            <h1 class="text-lg font-semibold">{{ wedding_name ?? 'Check-in' }}</h1>
        </header>

        <main class="mx-auto max-w-md p-4">
            <!-- Area kamera scanner (placeholder) -->
            <div class="aspect-square rounded-2xl border-2 border-dashed border-gray-600 flex items-center justify-center text-center text-sm text-gray-400">
                <p>Area pemindai kamera<br />(html5-qrcode)</p>
            </div>

            <div v-if="lastScan" class="mt-4 rounded-lg bg-gray-800 p-3 text-sm">
                Scan terakhir: <code>{{ lastScan }}</code>
            </div>
        </main>
    </div>
</template>
