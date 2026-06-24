<script setup lang="ts">
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { Head, router } from '@inertiajs/vue3'

const props = defineProps<{
    stats?: {
        total_vendors?: number
        total_pengantin?: number
        total_guests?: number
    }
    users?: Array<{
        id: number
        name: string
        email: string
        role: string
        created_at: string
    }>
}>()

const impersonate = (user: any) => {
    router.post(route('superadmin.impersonate', user.id));
}
</script>

<template>
    <Head title="Dashboard Super Admin" />

    <DashboardLayout>
        <!-- Welcome Header -->
        <header class="flex flex-col md:flex-row md:items-end justify-between gap-lg mb-xl">
            <div>
                <h2 class="font-display text-[36px] tracking-tight text-[#1b1b1e] font-bold">Dashboard Super Admin</h2>
                <p class="font-body-lg text-[16px] text-[#5d5e66]">Global observability &amp; manajemen seluruh vendor di sistem.</p>
            </div>
        </header>

        <!-- Bento Grid Summary -->
        <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-lg mb-xl">
            <!-- Total Vendor -->
            <div class="bg-white border border-[#e4e1e6] p-lg rounded-2xl flex flex-col justify-between shadow-sm hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start">
                    <div class="w-10 h-10 rounded-lg bg-[#e2f5ea] flex items-center justify-center">
                        <span class="material-symbols-outlined text-[#006b3f]">storefront</span>
                    </div>
                    <span class="text-[#5d5e66] font-label-xs text-[12px] uppercase tracking-wider font-bold">Total Vendor</span>
                </div>
                <div class="mt-lg">
                    <p class="font-display text-[36px] font-bold text-[#1b1b1e]">{{ stats?.total_vendors ?? 0 }}</p>
                    <p class="font-label-sm text-[12px] text-[#5d5e66]">Vendor yang terdaftar di sistem</p>
                </div>
            </div>

            <!-- Total Pengantin -->
            <div class="bg-white border border-[#e4e1e6] p-lg rounded-2xl flex flex-col justify-between shadow-sm hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start">
                    <div class="w-10 h-10 rounded-lg bg-[#e2dfff] flex items-center justify-center">
                        <span class="material-symbols-outlined text-[#1f108e]">festival</span>
                    </div>
                    <span class="text-[#5d5e66] font-label-xs text-[12px] uppercase tracking-wider font-bold">Total Pengantin</span>
                </div>
                <div class="mt-lg">
                    <p class="font-display text-[36px] font-bold text-[#1b1b1e]">{{ stats?.total_pengantin ?? 0 }}</p>
                    <p class="font-label-sm text-[12px] text-[#5d5e66]">Klien pengantin yang aktif</p>
                </div>
            </div>

            <!-- Total Tamu -->
            <div class="bg-white border border-[#e4e1e6] p-lg rounded-2xl flex flex-col justify-between shadow-sm hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start">
                    <div class="w-10 h-10 rounded-lg bg-[#fff0d6] flex items-center justify-center">
                        <span class="material-symbols-outlined text-[#8a5100]">groups</span>
                    </div>
                    <span class="text-[#5d5e66] font-label-xs text-[12px] uppercase tracking-wider font-bold">Total Tamu</span>
                </div>
                <div class="mt-lg">
                    <p class="font-display text-[36px] font-bold text-[#1b1b1e]">{{ stats?.total_guests ?? 0 }}</p>
                    <p class="font-label-sm text-[12px] text-[#5d5e66]">Tamu undangan keseluruhan</p>
                </div>
            </div>
        </section>

        <!-- User Management Table -->
        <section class="mb-xl">
            <h3 class="font-headline-md text-[24px] font-bold text-[#1b1b1e] mb-md">Manajemen Pengguna (Vendor & Pengantin)</h3>
            <div class="bg-white border border-[#e4e1e6] rounded-xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-[#fbf8fc] border-b border-[#e4e1e6]">
                            <tr>
                                <th class="px-xl py-md font-label-sm text-[12px] font-bold text-[#5d5e66] uppercase">Nama</th>
                                <th class="px-xl py-md font-label-sm text-[12px] font-bold text-[#5d5e66] uppercase">Email</th>
                                <th class="px-xl py-md font-label-sm text-[12px] font-bold text-[#5d5e66] uppercase">Peran (Role)</th>
                                <th class="px-xl py-md font-label-sm text-[12px] font-bold text-[#5d5e66] uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e4e1e6]">
                            <tr v-for="user in users" :key="user.id" class="hover:bg-[#fbf8fc] transition-colors">
                                <td class="px-xl py-md font-label-sm text-[14px] font-bold text-[#1b1b1e]">{{ user.name }}</td>
                                <td class="px-xl py-md text-[14px] text-[#5d5e66]">{{ user.email }}</td>
                                <td class="px-xl py-md">
                                    <span class="px-md py-xs rounded-full text-[10px] font-bold uppercase"
                                          :class="user.role === 'Vendor' ? 'bg-[#e2f5ea] text-[#006b3f]' : 'bg-[#e2dfff] text-[#1f108e]'">
                                        {{ user.role }}
                                    </span>
                                </td>
                                <td class="px-xl py-md">
                                    <button @click="impersonate(user)" class="flex items-center gap-xs text-[12px] font-bold text-[#1b1b1e] border border-[#c8c4d5] px-sm py-xs rounded hover:bg-[#f0edf1] transition-colors">
                                        <span class="material-symbols-outlined text-[16px]">login</span>
                                        Akses Akun
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="!users || users.length === 0">
                                <td colspan="4" class="px-xl py-xl text-center text-[#5d5e66] text-[14px]">Belum ada pengguna.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </DashboardLayout>
</template>
