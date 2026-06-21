<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue'
import { Head, Link } from '@inertiajs/vue3'

defineProps<{
    clients: any[]
}>()
</script>

<template>
    <AppLayout title="Daftar Klien">
        <Head title="Daftar Klien" />

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Daftar Klien Pengantin</h2>
                    <Link :href="route('vendor.clients.create')" class="bg-indigo-600 px-4 py-2 text-white rounded-md hover:bg-indigo-700">
                        Tambah Klien
                    </Link>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Tamu</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="client in clients" :key="client.id">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ client.name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ client.email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ client.weddings && client.weddings.length > 0 ? client.weddings[0].guests_count : 0 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <Link :href="route('vendor.clients.impersonate', client.id)" method="post" as="button" class="text-indigo-600 hover:text-indigo-900 underline font-bold">
                                            Masuk Dasbor Klien
                                        </Link>
                                    </td>
                                </tr>
                                <tr v-if="clients.length === 0">
                                    <td colspan="4" class="px-6 py-4 whitespace-nowrap text-center text-gray-500">
                                        Belum ada klien pengantin.
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
