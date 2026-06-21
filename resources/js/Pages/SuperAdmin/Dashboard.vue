<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue'
import { Head } from '@inertiajs/vue3'

const props = defineProps<{
    stats?: {
        total_vendors?: number
        total_pengantin?: number
        total_guests?: number
    }
}>()
</script>

<template>
    <AppLayout title="Dashboard Super Admin">
        <Head title="Dashboard Super Admin" />

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Dashboard Super Admin</h1>
                    <p class="mt-1 text-sm text-gray-600">
                        Global observability &amp; manajemen seluruh vendor di sistem.
                    </p>
                </div>

                <div class="grid gap-4 sm:grid-cols-3">
                    <div class="rounded-xl border bg-white p-5 shadow-sm">
                        <p class="text-xs uppercase tracking-wide text-gray-500">Total Vendor</p>
                        <p class="mt-2 text-3xl font-bold">{{ stats?.total_vendors ?? 0 }}</p>
                    </div>
                    <div class="rounded-xl border bg-white p-5 shadow-sm">
                        <p class="text-xs uppercase tracking-wide text-gray-500">Total Pengantin</p>
                        <p class="mt-2 text-3xl font-bold">{{ stats?.total_pengantin ?? 0 }}</p>
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
