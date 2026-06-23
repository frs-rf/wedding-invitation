<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';

// Mock data
const guests = [
    { name: 'Robert Montgomery', initials: 'RM', table: 'Table 1 (VIP)', role: 'VIP', status: 'Checked-in', party: '2', rsvp: 'Confirmed', statusColor: 'text-tertiary', roleColor: 'bg-tertiary-fixed text-on-tertiary-fixed-variant' },
    { name: 'Eleanor Davis', initials: 'ED', table: 'Table 4', role: 'Family', status: 'Pending Arrival', party: '4', rsvp: 'Confirmed', statusColor: 'text-on-surface-variant', roleColor: 'bg-secondary-fixed text-on-secondary-fixed-variant' },
    { name: 'James Henderson', initials: 'JH', table: 'Table 7', role: 'Standard', status: 'Checked-in', party: '1', rsvp: 'Confirmed', statusColor: 'text-tertiary', roleColor: 'bg-surface-variant text-on-surface-variant' },
    { name: 'Michael Chen', initials: 'MC', table: 'Unassigned', role: 'Standard', status: 'RSVP Sent', party: '-', rsvp: 'Pending', statusColor: 'text-on-surface-variant', roleColor: 'bg-surface-variant text-on-surface-variant' },
    { name: 'Sarah Williams', initials: 'SW', table: 'Table 2', role: 'Friend', status: 'Checked-in', party: '2', rsvp: 'Confirmed', statusColor: 'text-tertiary', roleColor: 'bg-primary-fixed text-primary' },
];
</script>

<template>
    <Head title="Guest List Management" />

    <DashboardLayout>
        <template #user-role>Sarah & James</template>

        <!-- Header Section -->
        <section class="flex flex-col md:flex-row md:items-end justify-between gap-lg mb-xl">
            <div>
                <h2 class="font-display text-display-mobile md:text-display text-on-background">Guest List</h2>
                <p class="font-body-lg text-secondary mt-xs">Manage RSVPs, table assignments, and invitations.</p>
            </div>
            <div class="flex gap-md">
                <button class="flex items-center gap-sm px-lg py-sm border border-outline rounded-full font-label-sm hover:bg-surface-container transition-colors">
                    <span class="material-symbols-outlined text-md">cloud_download</span>
                    Export CSV
                </button>
                <button class="flex items-center gap-sm px-lg py-sm bg-primary text-on-primary rounded-full font-label-sm hover:opacity-90 transition-opacity">
                    <span class="material-symbols-outlined text-md">person_add</span>
                    Add Guest
                </button>
            </div>
        </section>

        <!-- Filter & Search Toolbar -->
        <section class="bg-surface border border-outline-variant rounded-xl p-md mb-lg flex flex-col md:flex-row justify-between items-center gap-md shadow-sm">
            <div class="flex flex-wrap gap-sm w-full md:w-auto">
                <div class="relative flex-1 md:flex-none">
                    <span class="material-symbols-outlined absolute left-sm top-1/2 -translate-y-1/2 text-outline">search</span>
                    <input type="text" placeholder="Search guests..." class="w-full md:w-64 bg-surface-container-lowest border border-outline-variant rounded-lg pl-xl pr-md py-sm font-body-md focus:ring-2 focus:ring-primary-fixed focus:border-primary transition-all">
                </div>
                <select class="bg-surface-container-lowest border border-outline-variant rounded-lg px-md py-sm font-label-sm focus:ring-2 focus:ring-primary-fixed focus:border-primary cursor-pointer text-on-surface">
                    <option>All Statuses</option>
                    <option>Checked-in</option>
                    <option>Pending Arrival</option>
                    <option>RSVP Pending</option>
                </select>
                <select class="bg-surface-container-lowest border border-outline-variant rounded-lg px-md py-sm font-label-sm focus:ring-2 focus:ring-primary-fixed focus:border-primary cursor-pointer text-on-surface">
                    <option>All Roles</option>
                    <option>VIP</option>
                    <option>Family</option>
                    <option>Friend</option>
                    <option>Standard</option>
                </select>
            </div>
            
            <div class="flex items-center gap-sm text-on-surface-variant">
                <span class="font-label-sm">View:</span>
                <button class="p-xs bg-surface-container-high rounded text-primary"><span class="material-symbols-outlined">list</span></button>
                <button class="p-xs hover:bg-surface-container-low rounded transition-colors"><span class="material-symbols-outlined">grid_view</span></button>
            </div>
        </section>

        <!-- Guest Data Table -->
        <section class="bg-surface border border-outline-variant rounded-xl overflow-hidden shadow-sm flex-1 flex flex-col">
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-surface-container-low border-b border-outline-variant sticky top-0 z-10">
                        <tr>
                            <th class="px-lg py-md font-label-sm text-on-surface-variant w-10">
                                <input type="checkbox" class="rounded border-outline-variant text-primary focus:ring-primary-fixed cursor-pointer">
                            </th>
                            <th class="px-lg py-md font-label-sm text-on-surface-variant font-bold uppercase tracking-wider cursor-pointer hover:bg-surface-container-highest transition-colors">
                                <div class="flex items-center gap-xs">Guest Name <span class="material-symbols-outlined text-[16px]">arrow_drop_down</span></div>
                            </th>
                            <th class="px-lg py-md font-label-sm text-on-surface-variant font-bold uppercase tracking-wider">Role</th>
                            <th class="px-lg py-md font-label-sm text-on-surface-variant font-bold uppercase tracking-wider">RSVP</th>
                            <th class="px-lg py-md font-label-sm text-on-surface-variant font-bold uppercase tracking-wider">Party Size</th>
                            <th class="px-lg py-md font-label-sm text-on-surface-variant font-bold uppercase tracking-wider">Table</th>
                            <th class="px-lg py-md font-label-sm text-on-surface-variant font-bold uppercase tracking-wider">Current Status</th>
                            <th class="px-lg py-md font-label-sm text-on-surface-variant font-bold uppercase tracking-wider text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        <tr v-for="guest in guests" :key="guest.name" class="hover:bg-surface-container-low transition-colors group cursor-pointer">
                            <td class="px-lg py-md">
                                <input type="checkbox" class="rounded border-outline-variant text-primary focus:ring-primary-fixed cursor-pointer">
                            </td>
                            <td class="px-lg py-md">
                                <div class="flex items-center gap-md">
                                    <div class="w-8 h-8 rounded-full bg-surface-container-highest flex items-center justify-center font-bold text-xs text-on-surface">
                                        {{ guest.initials }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-label-sm font-bold text-on-background">{{ guest.name }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-lg py-md">
                                <span class="px-md py-xs rounded-full text-xs font-bold whitespace-nowrap" :class="guest.roleColor">{{ guest.role }}</span>
                            </td>
                            <td class="px-lg py-md">
                                <span class="font-label-sm" :class="guest.rsvp === 'Confirmed' ? 'text-green-600' : 'text-on-surface-variant italic'">
                                    {{ guest.rsvp }}
                                </span>
                            </td>
                            <td class="px-lg py-md font-body-md text-on-surface">{{ guest.party }}</td>
                            <td class="px-lg py-md font-body-md" :class="guest.table === 'Unassigned' ? 'text-on-surface-variant italic' : 'text-on-surface'">
                                {{ guest.table }}
                            </td>
                            <td class="px-lg py-md">
                                <div class="flex items-center gap-xs" :class="guest.statusColor">
                                    <span class="material-symbols-outlined text-[18px]">
                                        {{ guest.status === 'Checked-in' ? 'check_circle' : 'schedule' }}
                                    </span>
                                    <span class="font-label-sm">{{ guest.status }}</span>
                                </div>
                            </td>
                            <td class="px-lg py-md text-right">
                                <div class="flex items-center justify-end gap-sm opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button class="p-xs hover:bg-surface-container-highest rounded-full text-on-surface-variant hover:text-primary transition-colors" title="Edit Guest">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </button>
                                    <button class="p-xs hover:bg-surface-container-highest rounded-full text-on-surface-variant hover:text-primary transition-colors" title="Send Reminder">
                                        <span class="material-symbols-outlined text-[20px]">send</span>
                                    </button>
                                    <button class="p-xs hover:bg-error-container rounded-full text-on-surface-variant hover:text-error transition-colors" title="Delete">
                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination Footer -->
            <div class="px-lg py-md border-t border-outline-variant bg-surface-container-lowest flex flex-col sm:flex-row justify-between items-center gap-sm">
                <span class="font-label-xs text-on-surface-variant">Showing 1 to 5 of 184 guests</span>
                <div class="flex items-center gap-xs">
                    <button class="px-sm py-xs border border-outline-variant rounded hover:bg-surface-container-low disabled:opacity-50 transition-colors font-label-sm" disabled>Previous</button>
                    <button class="w-8 h-8 rounded bg-primary text-on-primary font-bold font-label-sm flex items-center justify-center">1</button>
                    <button class="w-8 h-8 rounded hover:bg-surface-container-low font-label-sm flex items-center justify-center transition-colors">2</button>
                    <button class="w-8 h-8 rounded hover:bg-surface-container-low font-label-sm flex items-center justify-center transition-colors">3</button>
                    <span class="text-outline-variant">...</span>
                    <button class="w-8 h-8 rounded hover:bg-surface-container-low font-label-sm flex items-center justify-center transition-colors">37</button>
                    <button class="px-sm py-xs border border-outline-variant rounded hover:bg-surface-container-low transition-colors font-label-sm">Next</button>
                </div>
            </div>
        </section>
    </DashboardLayout>
</template>
