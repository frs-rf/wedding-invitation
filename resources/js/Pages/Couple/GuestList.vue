<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';

// Mock data based strictly on the user's screenshot
const guests = [
    { name: 'Robert Montgomery', initials: 'RM', role: 'VIP', rsvp: 'Confirmed', rsvpColor: 'text-green-600', party: '2', table: 'Table 1 (VIP)', status: 'Checked-in', statusColor: 'text-[#752c00]', roleBg: 'bg-[#ffdbcc]', roleColor: 'text-[#7a3003]' },
    { name: 'Eleanor Davis', initials: 'ED', role: 'Family', rsvp: 'Confirmed', rsvpColor: 'text-green-600', party: '4', table: 'Table 4', status: 'Pending Arrival', statusColor: 'text-[#5d5e66]', roleBg: 'bg-[#e3e1ec]', roleColor: 'text-[#46464e]' },
    { name: 'James Henderson', initials: 'JH', role: 'Standard', rsvp: 'Confirmed', rsvpColor: 'text-green-600', party: '1', table: 'Table 7', status: 'Checked-in', statusColor: 'text-[#752c00]', roleBg: 'bg-[#e4e1e6]', roleColor: 'text-[#464553]' },
    { name: 'Michael Chen', initials: 'MC', role: 'Standard', rsvp: 'Pending', rsvpColor: 'text-[#5d5e66] italic', party: '-', table: 'Unassigned', tableClass: 'italic', status: 'RSVP Sent', statusColor: 'text-[#5d5e66]', roleBg: 'bg-[#e4e1e6]', roleColor: 'text-[#464553]' },
    { name: 'Sarah Williams', initials: 'SW', role: 'Friend', rsvp: 'Confirmed', rsvpColor: 'text-green-600', party: '2', table: 'Table 2', status: 'Checked-in', statusColor: 'text-[#752c00]', roleBg: 'bg-[#e2dfff]', roleColor: 'text-[#3b35a7]' },
];
</script>

<template>
    <Head title="Guest List Management" />

    <DashboardLayout>
        <!-- Header Section -->
        <header class="flex flex-col md:flex-row md:items-end justify-between gap-lg mb-xl">
            <div>
                <h2 class="font-display text-[36px] tracking-tight text-[#1b1b1e] font-bold">Guest List</h2>
                <p class="font-body-md text-[#5d5e66] mt-xs">Manage RSVPs, table assignments, and invitations.</p>
            </div>
            <div class="flex gap-md">
                <button class="flex items-center gap-sm px-lg py-sm border border-[#c8c4d5] rounded-full font-label-sm text-[14px] hover:bg-[#f0edf1] transition-colors">
                    <span class="material-symbols-outlined text-[18px]">cloud_download</span>
                    Export CSV
                </button>
                <button class="flex items-center gap-sm px-lg py-sm bg-[#1b1b1e] text-white rounded-full font-label-sm text-[14px] hover:opacity-90 transition-opacity">
                    <span class="material-symbols-outlined text-[18px]">person_add</span>
                    Add Guest
                </button>
            </div>
        </header>

        <!-- Filter & Search Toolbar -->
        <section class="bg-white border border-[#e4e1e6] rounded-xl p-md mb-lg flex flex-col md:flex-row justify-between items-center gap-md shadow-sm">
            <div class="flex flex-wrap gap-sm w-full md:w-auto">
                <div class="relative flex-1 md:flex-none">
                    <span class="material-symbols-outlined absolute left-sm top-1/2 -translate-y-1/2 text-[#777584]">search</span>
                    <input type="text" placeholder="Search guests..." class="w-full md:w-64 bg-white border border-[#c8c4d5] rounded-lg pl-10 pr-md py-sm font-body-md text-[14px] focus:ring-1 focus:ring-[#c8c4d5] focus:outline-none transition-all">
                </div>
                <select class="bg-white border border-[#c8c4d5] rounded-lg px-md py-sm font-label-sm text-[14px] focus:outline-none focus:ring-1 focus:ring-[#c8c4d5] cursor-pointer text-[#1b1b1e]">
                    <option>All Statuses</option>
                    <option>Checked-in</option>
                    <option>Pending Arrival</option>
                    <option>RSVP Pending</option>
                </select>
                <select class="bg-white border border-[#c8c4d5] rounded-lg px-md py-sm font-label-sm text-[14px] focus:outline-none focus:ring-1 focus:ring-[#c8c4d5] cursor-pointer text-[#1b1b1e]">
                    <option>All Roles</option>
                    <option>VIP</option>
                    <option>Family</option>
                    <option>Friend</option>
                    <option>Standard</option>
                </select>
            </div>
            
            <div class="flex items-center gap-sm text-[#5d5e66]">
                <span class="font-label-sm text-[14px]">View:</span>
                <button class="p-xs bg-[#f0edf1] rounded text-[#1b1b1e] border border-[#e4e1e6]"><span class="material-symbols-outlined text-[18px]">list</span></button>
                <button class="p-xs hover:bg-[#f6f2f7] rounded transition-colors border border-transparent"><span class="material-symbols-outlined text-[18px]">grid_view</span></button>
            </div>
        </section>

        <!-- Guest Data Table -->
        <section class="bg-[#fcfcfc] border border-[#e4e1e6] rounded-xl overflow-hidden shadow-sm flex-1 flex flex-col">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse bg-white">
                    <thead class="bg-[#fbf8fc] border-b border-[#e4e1e6]">
                        <tr>
                            <th class="px-lg py-md font-label-sm text-[12px] font-bold text-[#5d5e66] w-10">
                                <input type="checkbox" class="rounded border-[#c8c4d5] text-[#1b1b1e] cursor-pointer">
                            </th>
                            <th class="px-lg py-md font-label-sm text-[12px] font-bold text-[#5d5e66] uppercase tracking-wider">
                                <div class="flex items-center gap-xs">GUEST NAME <span class="material-symbols-outlined text-[16px]">arrow_drop_down</span></div>
                            </th>
                            <th class="px-lg py-md font-label-sm text-[12px] font-bold text-[#5d5e66] uppercase tracking-wider">ROLE</th>
                            <th class="px-lg py-md font-label-sm text-[12px] font-bold text-[#5d5e66] uppercase tracking-wider">RSVP</th>
                            <th class="px-lg py-md font-label-sm text-[12px] font-bold text-[#5d5e66] uppercase tracking-wider">PARTY SIZE</th>
                            <th class="px-lg py-md font-label-sm text-[12px] font-bold text-[#5d5e66] uppercase tracking-wider">TABLE</th>
                            <th class="px-lg py-md font-label-sm text-[12px] font-bold text-[#5d5e66] uppercase tracking-wider">CURRENT STATUS</th>
                            <th class="px-lg py-md font-label-sm text-[12px] font-bold text-[#5d5e66] uppercase tracking-wider text-right">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e4e1e6]">
                        <tr v-for="guest in guests" :key="guest.name" class="hover:bg-[#fbf8fc] transition-colors">
                            <td class="px-lg py-md">
                                <input type="checkbox" class="rounded border-[#c8c4d5] text-[#1b1b1e] cursor-pointer">
                            </td>
                            <td class="px-lg py-md">
                                <div class="flex items-center gap-md">
                                    <div class="w-8 h-8 rounded-full bg-[#f0edf1] flex items-center justify-center font-bold text-xs text-[#1b1b1e]">
                                        {{ guest.initials }}
                                    </div>
                                    <span class="font-label-sm font-bold text-[#1b1b1e] text-[14px]">{{ guest.name }}</span>
                                </div>
                            </td>
                            <td class="px-lg py-md">
                                <span class="px-md py-xs rounded-full text-[10px] font-bold uppercase tracking-wider whitespace-nowrap" :class="[guest.roleBg, guest.roleColor]">{{ guest.role }}</span>
                            </td>
                            <td class="px-lg py-md">
                                <span class="font-label-sm text-[14px]" :class="guest.rsvpColor">{{ guest.rsvp }}</span>
                            </td>
                            <td class="px-lg py-md font-body-md text-[14px] text-[#1b1b1e]">{{ guest.party }}</td>
                            <td class="px-lg py-md font-body-md text-[14px] text-[#1b1b1e]" :class="guest.tableClass">
                                {{ guest.table }}
                            </td>
                            <td class="px-lg py-md">
                                <div class="flex items-center gap-xs" :class="guest.statusColor">
                                    <span class="material-symbols-outlined text-[16px]">
                                        {{ guest.status === 'Checked-in' ? 'check_circle' : 'schedule' }}
                                    </span>
                                    <span class="font-label-sm text-[14px]">{{ guest.status }}</span>
                                </div>
                            </td>
                            <td class="px-lg py-md text-right">
                                <!-- Actions placeholder -->
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination Footer -->
            <div class="px-lg py-md border-t border-[#e4e1e6] bg-white flex flex-col sm:flex-row justify-between items-center gap-sm">
                <span class="font-label-xs text-[12px] text-[#5d5e66]">Showing 1 to 5 of 184 guests</span>
                <div class="flex items-center gap-xs">
                    <button class="px-sm py-xs border border-[#c8c4d5] bg-white text-[#5d5e66] rounded hover:bg-[#f0edf1] transition-colors font-label-sm text-[12px]" disabled>Previous</button>
                    <button class="w-8 h-8 rounded bg-[#1b1b1e] text-white font-bold font-label-sm text-[14px] flex items-center justify-center">1</button>
                    <button class="w-8 h-8 rounded hover:bg-[#f0edf1] text-[#1b1b1e] font-label-sm text-[14px] flex items-center justify-center transition-colors">2</button>
                    <button class="w-8 h-8 rounded hover:bg-[#f0edf1] text-[#1b1b1e] font-label-sm text-[14px] flex items-center justify-center transition-colors">3</button>
                    <span class="text-[#c8c4d5] px-1">...</span>
                    <button class="w-8 h-8 rounded hover:bg-[#f0edf1] text-[#1b1b1e] font-label-sm text-[14px] flex items-center justify-center transition-colors">37</button>
                    <button class="px-sm py-xs border border-[#c8c4d5] bg-white text-[#1b1b1e] rounded hover:bg-[#f0edf1] transition-colors font-label-sm text-[12px]">Next</button>
                </div>
            </div>
        </section>
    </DashboardLayout>
</template>
