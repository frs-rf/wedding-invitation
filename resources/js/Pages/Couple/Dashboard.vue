<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import DonutChart from '@/Components/DonutChart.vue';

// Mock data
const recentGuests = [
    { name: 'Robert Montgomery', initials: 'RM', type: 'VIP', typeColor: 'bg-[#ffdbcc] text-[#7a3003]', status: 'Checked-in', statusIcon: 'check_circle', statusColor: 'text-[#752c00]', time: '2 mins ago', avatarColor: 'bg-[#f0edf1]' },
    { name: 'Eleanor Davis', initials: 'ED', type: 'Family', typeColor: 'bg-[#e3e1ec] text-[#46464e]', status: 'RSVP Sent', statusIcon: 'mail', statusColor: 'text-[#5d5e66]', time: '15 mins ago', avatarColor: 'bg-[#f0edf1]' },
    { name: 'James Henderson', initials: 'JH', type: 'Standard', typeColor: 'bg-[#e4e1e6] text-[#464553]', status: 'Checked-in', statusIcon: 'check_circle', statusColor: 'text-[#752c00]', time: '1 hour ago', avatarColor: 'bg-[#f0edf1]' },
];
</script>

<template>
    <Head title="Bride/Groom Dashboard" />

    <DashboardLayout>
        <!-- Welcome Header -->
        <section class="flex flex-col md:flex-row md:items-end justify-between gap-lg mb-xl">
            <div>
                <h2 class="font-display text-[36px] tracking-tight text-[#1b1b1e] font-bold">Your Big Day</h2>
                <p class="font-body-lg text-[16px] text-[#5d5e66]">The wedding is in 142 days. Here's your planning status.</p>
            </div>
            <div class="flex gap-md">
                <button class="flex items-center gap-sm px-lg py-sm border border-[#c8c4d5] rounded-full font-label-sm text-[14px] hover:bg-[#f0edf1] transition-colors">
                    <span class="material-symbols-outlined text-[18px]">share</span>
                    Share Access
                </button>
                <button class="flex items-center gap-sm px-lg py-sm bg-[#1b1b1e] text-white rounded-full font-label-sm text-[14px] hover:opacity-90 transition-opacity">
                    <span class="material-symbols-outlined text-[18px]">edit</span>
                    Edit Details
                </button>
            </div>
        </section>

        <!-- Bento Grid Summary -->
        <section class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-lg mb-xl">
            <!-- Total Guests -->
            <div class="bg-white border border-[#e4e1e6] p-lg rounded-xl flex flex-col justify-between shadow-sm hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start">
                    <div class="w-10 h-10 rounded-lg bg-[#e2dfff] flex items-center justify-center">
                        <span class="material-symbols-outlined text-[#1f108e]">groups</span>
                    </div>
                    <span class="text-[#5d5e66] font-label-xs text-[12px] uppercase tracking-wider font-bold">Total Guests</span>
                </div>
                <div class="mt-lg">
                    <p class="font-display text-[36px] font-bold text-[#1b1b1e]">184</p>
                    <p class="font-label-sm text-[12px] text-[#5d5e66]">Target: 200 Guests</p>
                </div>
            </div>

            <!-- Checked-in -->
            <div class="bg-white border border-[#e4e1e6] p-lg rounded-xl flex flex-col justify-between shadow-sm hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start">
                    <div class="w-10 h-10 rounded-lg bg-[#e3e1ec] flex items-center justify-center">
                        <span class="material-symbols-outlined text-[#5d5e66]">how_to_reg</span>
                    </div>
                    <span class="text-[#5d5e66] font-label-xs text-[12px] uppercase tracking-wider font-bold">Checked-in</span>
                </div>
                <div class="mt-lg">
                    <p class="font-display text-[36px] font-bold text-[#1b1b1e]">132</p>
                    <div class="flex items-center gap-xs text-green-600">
                        <span class="material-symbols-outlined text-[14px]">trending_up</span>
                        <span class="font-label-xs text-[12px]">72% Arrival Rate</span>
                    </div>
                </div>
            </div>

            <!-- VIP Guests -->
            <div class="bg-white border border-[#e4e1e6] p-lg rounded-xl flex flex-col justify-between shadow-sm hover:shadow-md transition-shadow">
                <div class="flex justify-between items-start">
                    <div class="w-10 h-10 rounded-lg bg-[#ffdbcc] flex items-center justify-center">
                        <span class="material-symbols-outlined text-[#752c00]">star</span>
                    </div>
                    <span class="text-[#5d5e66] font-label-xs text-[12px] uppercase tracking-wider font-bold">VIP Guests</span>
                </div>
                <div class="mt-lg">
                    <p class="font-display text-[36px] font-bold text-[#1b1b1e]">24</p>
                    <p class="font-label-sm text-[12px] text-[#5d5e66]">Priority Management</p>
                </div>
            </div>

            <!-- Invitation Progress -->
            <div class="md:col-span-3 lg:col-span-1 bg-[#1b1b1e] text-white p-lg rounded-xl flex flex-col justify-between shadow-sm hover:shadow-md transition-shadow">
                <div class="flex justify-between items-center mb-md">
                    <span class="font-label-sm text-[14px] font-bold">Invitation Completion</span>
                    <span class="material-symbols-outlined text-[#c8c4d5]">send</span>
                </div>
                <div class="flex-1 flex flex-col justify-center">
                    <div class="flex justify-between items-end mb-sm">
                        <span class="font-display text-[30px] font-bold text-white">92%</span>
                        <span class="font-label-sm text-[12px] text-[#c8c4d5]">170 / 184 Sent</span>
                    </div>
                    <div class="w-full bg-[#464553] h-2 rounded-full overflow-hidden">
                        <div class="bg-white h-full" style="width: 92%"></div>
                    </div>
                </div>
                <p class="mt-md font-label-xs text-[12px] text-[#c8c4d5]">14 invitations pending final review</p>
            </div>
        </section>

        <!-- Main Analytics & Actions -->
        <section class="grid grid-cols-1 lg:grid-cols-3 gap-lg mb-xl">
            <!-- Guest Attendance Analytics -->
            <div class="lg:col-span-2 bg-white border border-[#e4e1e6] rounded-xl p-xl flex flex-col md:flex-row gap-xl items-center shadow-sm">
                <DonutChart :percentage="72" label="RSVP Yes" />
                <div class="flex-1 w-full space-y-lg">
                    <div>
                        <h3 class="font-headline-md text-[24px] font-bold text-[#1b1b1e] mb-xs">Attendance Breakdown</h3>
                        <p class="font-body-md text-[14px] text-[#5d5e66]">Guest response summary for the main reception.</p>
                    </div>
                    <div class="space-y-md">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-md">
                                <div class="w-3 h-3 rounded-full bg-[#1b1b1e]"></div>
                                <span class="font-body-md text-[14px] font-bold text-[#1b1b1e]">Attending (Present)</span>
                            </div>
                            <span class="font-label-sm text-[14px] font-bold text-[#1b1b1e]">132 Guests</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-md">
                                <div class="w-3 h-3 rounded-full bg-[#c8c4d5]"></div>
                                <span class="font-body-md text-[14px] font-bold text-[#1b1b1e]">Invited (Pending)</span>
                            </div>
                            <span class="font-label-sm text-[14px] font-bold text-[#1b1b1e]">52 Guests</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-md">
                                <div class="w-3 h-3 rounded-full bg-[#ba1a1a]"></div>
                                <span class="font-body-md text-[14px] font-bold text-[#1b1b1e]">Declined</span>
                            </div>
                            <span class="font-label-sm text-[14px] font-bold text-[#1b1b1e]">12 Guests</span>
                        </div>
                    </div>
                    <button class="w-full py-sm border border-[#e4e1e6] rounded-lg font-label-sm text-[14px] text-[#1b1b1e] font-bold hover:bg-[#f0edf1] transition-all">
                        View Full Guest List
                    </button>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white border border-[#e4e1e6] rounded-xl p-xl flex flex-col gap-lg shadow-sm">
                <h3 class="font-headline-md text-[24px] font-bold text-[#1b1b1e]">Quick Actions</h3>
                
                <button class="flex items-center justify-between p-md bg-[#fbf8fc] border border-[#e4e1e6] rounded-xl hover:border-[#1b1b1e] transition-colors group">
                    <div class="flex items-center gap-md">
                        <div class="w-10 h-10 rounded-lg bg-white border border-[#e4e1e6] flex items-center justify-center text-[#1b1b1e]">
                            <span class="material-symbols-outlined">qr_code_scanner</span>
                        </div>
                        <div class="text-left">
                            <p class="font-label-sm text-[14px] font-bold text-[#1b1b1e]">Scanner URL</p>
                            <p class="font-label-xs text-[12px] text-[#5d5e66]">Secret link for receptionists</p>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-[#777584] group-hover:text-[#1b1b1e] transition-colors">content_copy</span>
                </button>

                <button class="flex items-center justify-between p-md bg-[#fbf8fc] border border-[#e4e1e6] rounded-xl hover:border-[#1b1b1e] transition-colors group">
                    <div class="flex items-center gap-md">
                        <div class="w-10 h-10 rounded-lg bg-white border border-[#e4e1e6] flex items-center justify-center text-[#1b1b1e]">
                            <span class="material-symbols-outlined">download</span>
                        </div>
                        <div class="text-left">
                            <p class="font-label-sm text-[14px] font-bold text-[#1b1b1e]">Guest Template</p>
                            <p class="font-label-xs text-[12px] text-[#5d5e66]">Download Excel format</p>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-[#777584] group-hover:text-[#1b1b1e] transition-colors">description</span>
                </button>

                <button class="flex items-center justify-between p-md bg-[#fbf8fc] border border-[#e4e1e6] rounded-xl hover:border-[#1b1b1e] transition-colors group">
                    <div class="flex items-center gap-md">
                        <div class="w-10 h-10 rounded-lg bg-[#1b1b1e] border border-[#1b1b1e] flex items-center justify-center text-white">
                            <span class="material-symbols-outlined">upload_file</span>
                        </div>
                        <div class="text-left">
                            <p class="font-label-sm text-[14px] font-bold text-[#1b1b1e]">Import Excel</p>
                            <p class="font-label-xs text-[12px] text-[#5d5e66]">Batch upload your list</p>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-[#777584] group-hover:text-[#1b1b1e] transition-colors">arrow_forward</span>
                </button>
            </div>
        </section>

        <!-- Recent Guest Updates -->
        <section class="space-y-md">
            <div class="flex items-center justify-between">
                <h3 class="font-headline-md text-[24px] font-bold text-[#1b1b1e]">Recent Guest Updates</h3>
                <Link href="#" class="text-[#1b1b1e] font-label-sm text-[14px] font-bold hover:underline">See all activity</Link>
            </div>
            <div class="bg-white border border-[#e4e1e6] rounded-xl overflow-hidden shadow-sm">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-[#fbf8fc] border-b border-[#e4e1e6]">
                            <tr>
                                <th class="px-xl py-md font-label-sm text-[12px] font-bold text-[#5d5e66] uppercase">Guest Name</th>
                                <th class="px-xl py-md font-label-sm text-[12px] font-bold text-[#5d5e66] uppercase">Type</th>
                                <th class="px-xl py-md font-label-sm text-[12px] font-bold text-[#5d5e66] uppercase">Status</th>
                                <th class="px-xl py-md font-label-sm text-[12px] font-bold text-[#5d5e66] uppercase text-right">Activity Time</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#e4e1e6]">
                            <tr v-for="guest in recentGuests" :key="guest.name" class="hover:bg-[#fbf8fc] transition-colors">
                                <td class="px-xl py-md">
                                    <div class="flex items-center gap-md">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-[12px] text-[#1b1b1e]" :class="guest.avatarColor">
                                            {{ guest.initials }}
                                        </div>
                                        <span class="font-label-sm text-[14px] font-bold text-[#1b1b1e]">{{ guest.name }}</span>
                                    </div>
                                </td>
                                <td class="px-xl py-md">
                                    <span class="px-md py-xs rounded-full text-[10px] font-bold uppercase" :class="guest.typeColor">{{ guest.type }}</span>
                                </td>
                                <td class="px-xl py-md">
                                    <div class="flex items-center gap-xs" :class="guest.statusColor">
                                        <span class="material-symbols-outlined text-[16px]">{{ guest.statusIcon }}</span>
                                        <span class="font-label-sm text-[14px] font-bold">{{ guest.status }}</span>
                                    </div>
                                </td>
                                <td class="px-xl py-md text-right font-label-xs text-[14px] text-[#5d5e66]">{{ guest.time }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </DashboardLayout>
</template>
