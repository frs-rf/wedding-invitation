<script setup lang="ts">
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { ref } from 'vue'

defineProps<{
    clients: any[]
}>()

const showDeleteModal = ref(false)
const clientToDelete = ref<any>(null)

const confirmDelete = (client: any) => {
    clientToDelete.value = client
    showDeleteModal.value = true
}

const deleteClient = () => {
    if (clientToDelete.value) {
        // Asumsi rute penghapusan klien ada.
        router.delete(route('vendor.clients.destroy', clientToDelete.value.id), {
            onSuccess: () => {
                showDeleteModal.value = false
                clientToDelete.value = null
            }
        })
    }
}

// Dropdown state logic
const activeDropdown = ref<number | null>(null)
const toggleDropdown = (id: number) => {
    activeDropdown.value = activeDropdown.value === id ? null : id
}
</script>

<template>
    <Head title="Daftar Klien" />

    <DashboardLayout>
        <!-- Header Section -->
        <header class="flex flex-col md:flex-row md:items-end justify-between gap-lg mb-xl">
            <div>
                <h2 class="font-display text-[36px] tracking-tight text-[#1b1b1e] font-bold">Daftar Klien Pengantin</h2>
                <p class="font-body-md text-[#5d5e66] mt-xs">Kelola data klien dan akses dasbor mereka secara instan.</p>
            </div>
            <div class="flex gap-md">
                <Link :href="route('vendor.clients.create')" class="flex items-center gap-sm px-lg py-sm bg-[#1b1b1e] text-white rounded-full font-label-sm text-[14px] hover:opacity-90 transition-opacity font-bold">
                    <span class="material-symbols-outlined text-[18px]">add</span>
                    Tambah Klien Baru
                </Link>
            </div>
        </header>

        <!-- Client Data Table -->
        <section class="bg-[#fcfcfc] border border-[#e4e1e6] rounded-xl overflow-visible shadow-sm flex-1 flex flex-col min-h-[400px]">
            <div class="px-xl py-lg border-b border-[#e4e1e6] bg-[#fbf8fc] flex items-center gap-sm">
                <span class="material-symbols-outlined text-[#1f108e]">group</span>
                <h3 class="font-label-sm text-[14px] font-bold text-[#1b1b1e]">Semua Klien</h3>
            </div>
            <div class="overflow-visible">
                <table class="w-full text-left border-collapse bg-white">
                    <thead class="bg-[#fbf8fc] border-b border-[#e4e1e6]">
                        <tr>
                            <th class="px-lg py-md font-label-sm text-[12px] font-bold text-[#5d5e66] uppercase tracking-wider">NAMA PASANGAN</th>
                            <th class="px-lg py-md font-label-sm text-[12px] font-bold text-[#5d5e66] uppercase tracking-wider">EMAIL LOGIN</th>
                            <th class="px-lg py-md font-label-sm text-[12px] font-bold text-[#5d5e66] uppercase tracking-wider text-center">TOTAL TAMU</th>
                            <th class="px-lg py-md font-label-sm text-[12px] font-bold text-[#5d5e66] uppercase tracking-wider text-right">AKSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e4e1e6]">
                        <tr v-for="client in clients" :key="client.id" class="hover:bg-[#fbf8fc] transition-colors relative">
                            <td class="px-lg py-md">
                                <div class="flex items-center gap-md">
                                    <div class="w-8 h-8 rounded-full bg-[#f0edf1] flex items-center justify-center font-bold text-xs text-[#1b1b1e]">
                                        {{ client.name.substring(0, 2).toUpperCase() }}
                                    </div>
                                    <div>
                                        <div class="font-label-sm font-bold text-[#1b1b1e] text-[14px]">{{ client.name }}</div>
                                        <div v-if="client.weddings && client.weddings.length > 0" class="text-[12px] text-[#5d5e66] flex items-center gap-xs mt-1">
                                            <span class="material-symbols-outlined text-[14px]">calendar_today</span>
                                            {{ new Date(client.weddings[0].event_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-lg py-md">
                                <span class="font-label-sm text-[14px] text-[#5d5e66]">{{ client.email }}</span>
                            </td>
                            <td class="px-lg py-md text-center">
                                <span class="px-md py-xs rounded-full text-[10px] font-bold uppercase tracking-wider bg-[#e2dfff] text-[#1f108e]">
                                    {{ client.weddings && client.weddings.length > 0 ? client.weddings[0].guests_count : 0 }} Tamu
                                </span>
                            </td>
                            <td class="px-lg py-md text-right relative">
                                <button @click="toggleDropdown(client.id)" class="p-xs hover:bg-[#e4e1e6] rounded transition-colors text-[#5d5e66]">
                                    <span class="material-symbols-outlined">more_vert</span>
                                </button>
                                
                                <!-- Custom Dropdown Menu -->
                                <div v-if="activeDropdown === client.id" class="absolute right-0 top-12 w-56 bg-white border border-[#e4e1e6] rounded-xl shadow-lg z-50 py-xs text-left">
                                    <Link :href="route('vendor.clients.impersonate', client.id)" method="post" as="button" class="w-full text-left px-md py-sm hover:bg-[#fbf8fc] flex items-center gap-sm text-[14px] text-[#1f108e] font-bold">
                                        <span class="material-symbols-outlined text-[18px]">login</span> Masuk Dasbor Klien
                                    </Link>
                                    <!-- Uncomment for delete
                                    <div class="h-px bg-[#e4e1e6] my-xs"></div>
                                    <button @click="confirmDelete(client); activeDropdown = null" class="w-full text-left px-md py-sm hover:bg-[#ffdad6] flex items-center gap-sm text-[14px] text-[#ba1a1a] font-bold">
                                        <span class="material-symbols-outlined text-[18px]">delete</span> Hapus Klien
                                    </button>
                                    -->
                                </div>
                            </td>
                        </tr>
                        <tr v-if="clients.length === 0">
                            <td colspan="4" class="px-lg py-xl text-center text-[#5d5e66] font-body-md">
                                Belum ada klien pengantin yang terdaftar.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Custom Delete Modal Overlay -->
        <div v-if="showDeleteModal" class="fixed inset-0 bg-[#1b1b1e]/50 backdrop-blur-sm z-[100] flex items-center justify-center p-md">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden flex flex-col">
                <div class="px-xl py-lg border-b border-[#e4e1e6] bg-[#ffdad6]">
                    <h3 class="font-headline-md text-[20px] font-bold text-[#ba1a1a]">Hapus Klien Pengantin</h3>
                </div>
                <div class="p-xl">
                    <p class="text-[14px] text-[#1b1b1e]">Apakah Anda yakin ingin menghapus data klien <b class="font-bold">{{ clientToDelete?.name }}</b> beserta seluruh tamu undangannya?</p>
                    <p class="text-[12px] text-[#5d5e66] mt-xs">Data yang dihapus tidak dapat dikembalikan.</p>
                </div>
                <div class="px-xl py-md border-t border-[#e4e1e6] bg-[#fbf8fc] flex justify-end gap-sm mt-auto">
                    <button @click="showDeleteModal = false" class="px-lg py-sm border border-[#c8c4d5] bg-white rounded-xl font-label-sm text-[14px] font-bold hover:bg-[#f0edf1]">Batal</button>
                    <button @click="deleteClient" class="px-lg py-sm bg-[#ba1a1a] text-white rounded-xl font-label-sm text-[14px] font-bold hover:opacity-90">Hapus Permanen</button>
                </div>
            </div>
        </div>

    </DashboardLayout>
</template>
