<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue'
import { Head } from '@inertiajs/vue3'
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card'
import { Users, Store, Tent } from 'lucide-vue-next'

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

        <div class="space-y-8">
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">Dashboard Super Admin</h1>
                <p class="text-zinc-500 dark:text-zinc-400 mt-2">
                    Global observability &amp; manajemen seluruh vendor di sistem.
                </p>
            </div>

            <div class="grid gap-4 sm:grid-cols-3">
                <Card class="hover:shadow-md transition-shadow border-zinc-200 dark:border-zinc-800">
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Total Vendor</CardTitle>
                        <Store class="h-4 w-4 text-emerald-500" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-3xl font-bold">{{ stats?.total_vendors ?? 0 }}</div>
                        <p class="text-xs text-zinc-500 mt-1">
                            Vendor yang terdaftar di sistem
                        </p>
                    </CardContent>
                </Card>
                
                <Card class="hover:shadow-md transition-shadow border-zinc-200 dark:border-zinc-800">
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Total Pengantin</CardTitle>
                        <Tent class="h-4 w-4 text-indigo-500" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-3xl font-bold">{{ stats?.total_pengantin ?? 0 }}</div>
                        <p class="text-xs text-zinc-500 mt-1">
                            Klien pengantin yang aktif
                        </p>
                    </CardContent>
                </Card>
                
                <Card class="hover:shadow-md transition-shadow border-zinc-200 dark:border-zinc-800">
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Total Tamu</CardTitle>
                        <Users class="h-4 w-4 text-amber-500" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-3xl font-bold">{{ stats?.total_guests ?? 0 }}</div>
                        <p class="text-xs text-zinc-500 mt-1">
                            Tamu undangan keseluruhan
                        </p>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
