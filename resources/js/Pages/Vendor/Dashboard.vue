<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue'
import { Head } from '@inertiajs/vue3'

defineProps<{
    stats?: {
        active_clients?: number
        total_guests?: number
    }
}>()
</script>

<template>
    <AppLayout title="Dashboard Vendor">
        <Head title="Dashboard Vendor" />

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Dashboard Vendor (WO)</h1>
                    <p class="mt-1 text-sm text-gray-600">
                        Kelola klien pengantin di bawah naungan Anda.
                    </p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div class="rounded-xl border bg-white p-5 shadow-sm">
                        <p class="text-xs uppercase tracking-wide text-gray-500">Klien Aktif</p>
                        <p class="mt-2 text-3xl font-bold">{{ stats?.active_clients ?? 0 }}</p>
                    </div>
                    <div class="rounded-xl border bg-white p-5 shadow-sm">
                        <p class="text-xs uppercase tracking-wide text-gray-500">Total Tamu</p>
                        <p class="mt-2 text-3xl font-bold">{{ stats?.total_guests ?? 0 }}</p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
