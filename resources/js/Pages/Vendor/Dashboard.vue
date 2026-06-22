<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue'
import { Head, Link } from '@inertiajs/vue3'
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card'
import { Button } from '@/Components/ui/button'
import { Users, UsersRound, Plus } from 'lucide-vue-next'

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

        <div class="space-y-8">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">Dashboard Vendor</h1>
                    <p class="text-zinc-500 dark:text-zinc-400 mt-2">
                        Kelola klien pengantin di bawah naungan Anda.
                    </p>
                </div>
                <div>
                    <Button asChild class="gap-2">
                        <Link :href="route('vendor.clients.create')">
                            <Plus class="w-4 h-4" />
                            Tambah Klien Baru
                        </Link>
                    </Button>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <Card class="hover:shadow-md transition-shadow border-zinc-200 dark:border-zinc-800">
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Klien Aktif</CardTitle>
                        <Users class="h-4 w-4 text-zinc-500" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-3xl font-bold">{{ stats?.active_clients ?? 0 }}</div>
                        <p class="text-xs text-zinc-500 mt-1">
                            Pasangan pengantin yang terdaftar
                        </p>
                    </CardContent>
                </Card>
                <Card class="hover:shadow-md transition-shadow border-zinc-200 dark:border-zinc-800">
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Total Tamu</CardTitle>
                        <UsersRound class="h-4 w-4 text-indigo-500" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-3xl font-bold">{{ stats?.total_guests ?? 0 }}</div>
                        <p class="text-xs text-zinc-500 mt-1">
                            Tamu undangan dari semua klien
                        </p>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
