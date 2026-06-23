<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import StatCard from '@/Components/StatCard.vue';

// Mock data
const clients = [
    { name: 'Sarah & Michael', initials: 'SM', plan: 'Premium Plus', date: 'June 14, 2024', theme: 'Vintage Romance', status: 'Designing', statusColor: 'text-[#1f108e]', statusPulse: true, avatarColor: 'bg-[#e2dfff] text-[#1f108e]' },
    { name: 'James & Wei', initials: 'JW', plan: 'Standard', date: 'Aug 22, 2024', theme: 'Modern Minimalist', status: 'Live', statusColor: 'text-green-600', statusPulse: false, avatarColor: 'bg-[#e3e1ec] text-[#5d5e66]' },
    { name: 'Anita & Leo', initials: 'AL', plan: 'Premium Plus', date: 'Sept 05, 2024', theme: 'Garden Boho', status: 'Onboarding', statusColor: 'text-[#752c00]', statusPulse: false, avatarColor: 'bg-[#ffdbcc] text-[#752c00]' },
];
</script>

<template>
    <Head title="Vendor Dashboard" />

    <DashboardLayout>
        <template #user-role>Admin Dashboard</template>

        <!-- Welcome Header -->
        <section class="mb-2xl flex flex-col md:flex-row md:items-end justify-between gap-md">
            <div>
                <h2 class="font-display text-[36px] tracking-tight text-[#1b1b1e] font-bold">Vendor Dashboard</h2>
                <p class="font-body-md text-[#5d5e66] mt-xs">Managing 24 couples across the next 12 months.</p>
            </div>
            <div class="flex gap-sm">
                <button class="flex items-center gap-sm px-lg py-sm border border-[#c8c4d5] rounded-full font-label-sm text-[14px] hover:bg-[#f0edf1] transition-colors">
                    <span class="material-symbols-outlined text-[18px]">file_download</span>
                    Export Report
                </button>
            </div>
        </section>

        <!-- Stats Grid -->
        <section class="grid grid-cols-1 md:grid-cols-3 gap-lg mb-2xl">
            <StatCard 
                title="Total Clients" 
                value="148" 
                icon="person_add" 
                badgeText="+12% vs last month"
                badgeColorClass="text-green-600 bg-green-50"
                class="bg-white border-[#e4e1e6] shadow-sm"
            />
            <StatCard 
                title="Active Weddings" 
                value="12" 
                icon="event_available" 
                iconColorClass="text-[#752c00] bg-[#ffdbcc]"
                badgeText="Current Period"
                class="bg-white border-[#e4e1e6] shadow-sm"
            />
            <StatCard 
                title="Pending Invites" 
                value="34" 
                icon="mail_outline" 
                iconColorClass="text-[#5d5e66] bg-[#e3e1ec]"
                badgeText="Requires Action"
                badgeColorClass="text-[#ba1a1a] bg-[#ffdad6]"
                class="bg-white border-[#e4e1e6] shadow-sm"
            />
        </section>

        <!-- Client Management Table -->
        <section class="bg-[#fcfcfc] border border-[#e4e1e6] rounded-xl overflow-hidden shadow-sm">
            <div class="px-xl py-lg border-b border-[#e4e1e6] bg-white flex flex-col sm:flex-row justify-between items-start sm:items-center gap-md">
                <div>
                    <h3 class="font-headline-md text-[24px] font-bold text-[#1b1b1e]">Client Management</h3>
                    <p class="font-body-md text-[14px] text-[#5d5e66]">View and manage your active couple accounts.</p>
                </div>
                <div class="flex items-center gap-sm">
                    <button class="px-md py-sm rounded-lg border border-[#c8c4d5] font-label-sm text-[14px] text-[#1b1b1e] hover:bg-[#f0edf1] transition-all bg-white">Filter</button>
                    <button class="px-md py-sm rounded-lg border border-[#c8c4d5] font-label-sm text-[14px] text-[#1b1b1e] hover:bg-[#f0edf1] transition-all bg-white">Sort by Date</button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse bg-white">
                    <thead class="bg-[#fbf8fc] border-b border-[#e4e1e6]">
                        <tr>
                            <th class="px-xl py-md font-label-sm text-[12px] font-bold text-[#5d5e66] uppercase">CLIENT NAME</th>
                            <th class="px-xl py-md font-label-sm text-[12px] font-bold text-[#5d5e66] uppercase">WEDDING DATE</th>
                            <th class="px-xl py-md font-label-sm text-[12px] font-bold text-[#5d5e66] uppercase">THEME SELECTED</th>
                            <th class="px-xl py-md font-label-sm text-[12px] font-bold text-[#5d5e66] uppercase">STATUS</th>
                            <th class="px-xl py-md font-label-sm text-[12px] font-bold text-[#5d5e66] uppercase text-right">ACTION</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e4e1e6]">
                        <tr v-for="client in clients" :key="client.name" class="hover:bg-[#fbf8fc] transition-colors">
                            <td class="px-xl py-lg">
                                <div class="flex items-center gap-md">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-[14px]" :class="client.avatarColor">
                                        {{ client.initials }}
                                    </div>
                                    <div>
                                        <p class="font-body-md text-[14px] font-bold text-[#1b1b1e]">{{ client.name }}</p>
                                        <p class="font-label-xs text-[12px] text-[#5d5e66]">Plan: {{ client.plan }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-xl py-lg font-body-md text-[14px] text-[#1b1b1e]">{{ client.date }}</td>
                            <td class="px-xl py-lg">
                                <span class="px-md py-xs bg-[#f0edf1] text-[#1b1b1e] font-label-xs text-[12px] rounded-full font-bold">
                                    {{ client.theme }}
                                </span>
                            </td>
                            <td class="px-xl py-lg">
                                <div class="flex items-center gap-xs font-bold" :class="client.statusColor">
                                    <span class="w-2 h-2 rounded-full" :class="[client.statusColor.replace('text-', 'bg-'), client.statusPulse ? 'animate-pulse' : '']"></span>
                                    <span class="font-label-sm text-[14px]">{{ client.status }}</span>
                                </div>
                            </td>
                            <td class="px-xl py-lg text-right">
                                <button class="group flex items-center justify-end gap-xs ml-auto text-[#1f108e] font-label-sm text-[14px] font-bold hover:underline">
                                    <span class="material-symbols-outlined text-[20px]">login</span>
                                    Impersonate
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-xl py-md border-t border-[#e4e1e6] bg-white flex justify-between items-center">
                <p class="font-label-xs text-[12px] text-[#5d5e66]">Showing 1-3 of 148 clients</p>
                <div class="flex gap-xs">
                    <button class="p-xs rounded border border-[#c8c4d5] bg-white text-[#5d5e66] hover:bg-[#f0edf1] disabled:opacity-50 transition-colors" disabled>
                        <span class="material-symbols-outlined">chevron_left</span>
                    </button>
                    <button class="p-xs rounded border border-[#c8c4d5] bg-white text-[#1b1b1e] hover:bg-[#f0edf1] transition-colors">
                        <span class="material-symbols-outlined">chevron_right</span>
                    </button>
                </div>
            </div>
        </section>
    </DashboardLayout>
</template>
