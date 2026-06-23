<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import StatCard from '@/Components/StatCard.vue';

// Mock data for the table, this will typically come from the Inertia props
const clients = [
    { name: 'Sarah & Michael', initials: 'SM', plan: 'Premium Plus', date: 'June 14, 2024', theme: 'Vintage Romance', status: 'Designing', statusColor: 'text-primary', statusPulse: true, avatarColor: 'bg-primary-fixed text-primary' },
    { name: 'James & Wei', initials: 'JW', plan: 'Standard', date: 'Aug 22, 2024', theme: 'Modern Minimalist', status: 'Live', statusColor: 'text-green-600', statusPulse: false, avatarColor: 'bg-secondary-fixed text-secondary' },
    { name: 'Anita & Leo', initials: 'AL', plan: 'Premium Plus', date: 'Sept 05, 2024', theme: 'Garden Boho', status: 'Onboarding', statusColor: 'text-tertiary', statusPulse: false, avatarColor: 'bg-tertiary-fixed text-tertiary' },
];
</script>

<template>
    <Head title="Vendor Dashboard" />

    <DashboardLayout>
        <!-- Welcome Header -->
        <section class="mb-2xl flex flex-col md:flex-row md:items-end justify-between gap-md">
            <div>
                <h2 class="font-display text-display-mobile md:text-display text-on-surface tracking-tight">Vendor Dashboard</h2>
                <p class="text-body-lg font-body-lg text-on-surface-variant mt-xs">Managing 24 couples across the next 12 months.</p>
            </div>
            <div class="flex gap-sm">
                <button class="bg-secondary-container text-on-secondary-container px-lg py-sm rounded-xl font-label-sm text-label-sm btn-hover-effect flex items-center gap-xs">
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
                badgeColorClass="text-green-600 bg-green-50 dark:bg-green-900/20"
            />
            <StatCard 
                title="Active Weddings" 
                value="12" 
                icon="event_available" 
                iconColorClass="text-tertiary bg-tertiary-fixed"
                badgeText="Current Period"
            />
            <StatCard 
                title="Pending Invites" 
                value="34" 
                icon="mail_outline" 
                iconColorClass="text-secondary bg-secondary-fixed"
                badgeText="Requires Action"
                badgeColorClass="text-error bg-error-container"
            />
        </section>

        <!-- Client Management Table -->
        <section class="bg-surface-container-lowest dark:bg-zinc-900 border border-outline-variant dark:border-zinc-800 rounded-xl overflow-hidden shadow-[0_15px_30px_-15px_rgba(0,0,0,0.04)]">
            <div class="px-xl py-lg border-b border-outline-variant dark:border-zinc-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-md">
                <div>
                    <h3 class="font-headline-md text-headline-md text-on-surface">Client Management</h3>
                    <p class="font-body-md text-body-md text-on-surface-variant">View and manage your active couple accounts.</p>
                </div>
                <div class="flex items-center gap-sm">
                    <button class="px-md py-sm rounded-lg border border-outline-variant font-label-sm text-label-sm hover:bg-surface-container-low transition-all">Filter</button>
                    <button class="px-md py-sm rounded-lg border border-outline-variant font-label-sm text-label-sm hover:bg-surface-container-low transition-all">Sort by Date</button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low dark:bg-zinc-800/50">
                            <th class="px-xl py-md font-label-sm text-label-sm text-on-surface-variant">CLIENT NAME</th>
                            <th class="px-xl py-md font-label-sm text-label-sm text-on-surface-variant">WEDDING DATE</th>
                            <th class="px-xl py-md font-label-sm text-label-sm text-on-surface-variant">THEME SELECTED</th>
                            <th class="px-xl py-md font-label-sm text-label-sm text-on-surface-variant">STATUS</th>
                            <th class="px-xl py-md font-label-sm text-label-sm text-on-surface-variant text-right">ACTION</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant dark:divide-zinc-800">
                        <tr v-for="client in clients" :key="client.name" class="hover:bg-surface-container-lowest dark:hover:bg-zinc-800/30 transition-colors">
                            <td class="px-xl py-lg">
                                <div class="flex items-center gap-md">
                                    <div class="w-10 h-10 rounded-full overflow-hidden flex items-center justify-center font-bold" :class="client.avatarColor">
                                        {{ client.initials }}
                                    </div>
                                    <div>
                                        <p class="font-body-md text-body-md text-on-surface">{{ client.name }}</p>
                                        <p class="font-label-xs text-label-xs text-on-surface-variant">Plan: {{ client.plan }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-xl py-lg font-body-md text-body-md text-on-surface">{{ client.date }}</td>
                            <td class="px-xl py-lg">
                                <span class="px-md py-xs bg-surface-container-high dark:bg-zinc-800 text-on-surface font-label-xs text-label-xs rounded-full">
                                    {{ client.theme }}
                                </span>
                            </td>
                            <td class="px-xl py-lg">
                                <div class="flex items-center gap-xs" :class="client.statusColor">
                                    <span class="w-2 h-2 rounded-full" :class="[client.statusColor.replace('text-', 'bg-'), client.statusPulse ? 'animate-pulse' : '']"></span>
                                    <span class="font-label-sm text-label-sm">{{ client.status }}</span>
                                </div>
                            </td>
                            <td class="px-xl py-lg text-right">
                                <button class="group flex items-center gap-xs ml-auto text-primary font-label-sm text-label-sm hover:underline">
                                    <span class="material-symbols-outlined text-[20px]">login</span>
                                    Impersonate
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="px-xl py-md border-t border-outline-variant dark:border-zinc-800 flex justify-between items-center">
                <p class="font-label-xs text-label-xs text-on-surface-variant">Showing 1-3 of 148 clients</p>
                <div class="flex gap-xs">
                    <button class="p-xs rounded border border-outline-variant hover:bg-surface-container-low disabled:opacity-50" disabled>
                        <span class="material-symbols-outlined">chevron_left</span>
                    </button>
                    <button class="p-xs rounded border border-outline-variant hover:bg-surface-container-low">
                        <span class="material-symbols-outlined">chevron_right</span>
                    </button>
                </div>
            </div>
        </section>
    </DashboardLayout>
</template>
