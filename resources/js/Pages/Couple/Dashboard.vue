<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';
import DonutChart from '@/Components/DonutChart.vue';

// Mock data
const recentGuests = [
    { name: 'Robert Montgomery', initials: 'RM', type: 'VIP', typeColor: 'bg-tertiary-fixed text-on-tertiary-fixed-variant', status: 'Checked-in', statusIcon: 'check_circle', statusColor: 'text-tertiary', time: '2 mins ago', avatarColor: 'bg-primary-fixed text-primary' },
    { name: 'Eleanor Davis', initials: 'ED', type: 'Family', typeColor: 'bg-secondary-fixed text-on-secondary-fixed-variant', status: 'RSVP Sent', statusIcon: 'mail', statusColor: 'text-on-surface-variant', time: '15 mins ago', avatarColor: 'bg-secondary-fixed text-on-secondary-fixed' },
    { name: 'James Henderson', initials: 'JH', type: 'Standard', typeColor: 'bg-surface-variant text-on-surface-variant', status: 'Checked-in', statusIcon: 'check_circle', statusColor: 'text-tertiary', time: '1 hour ago', avatarColor: 'bg-primary-fixed text-primary' },
];

const copyScannerUrl = (e: Event) => {
    const btn = e.currentTarget as HTMLElement;
    btn.classList.add('border-primary');
    const icon = btn.querySelector('.material-symbols-outlined:last-child') as HTMLElement;
    if (icon) {
        icon.innerText = 'done';
        icon.classList.add('text-primary');
        setTimeout(() => {
            btn.classList.remove('border-primary');
            icon.innerText = 'content_copy';
            icon.classList.remove('text-primary');
        }, 2000);
    }
};
</script>

<template>
    <Head title="Bride/Groom Dashboard" />

    <DashboardLayout>
        <template #user-role>Sarah & James</template>
        <template #user-avatar>
            <img class="w-full h-full object-cover" src="https://lh3.googleusercontent.com/aida-public/AB6AXuBm6rWFiJ2_xBbuX6QUvXUAkM4pdxeAswR7ohgcxOhbN4ZBEQifN_PMdTUhTse2oyqIfASCvH15mc2SyTyMZqGKauaY0I_TSHI8hY5uKcQfu7Xd1YoAjjJxYo053lAziLJBEARXblCXb_uTI4KVEu4P2TSWLP1Rq938vxGfwtVz6F8i-nZug-FYXz9QsC_LDTYm5Ndh5DMBEljvNBbioMyAbkgPIC5z63oz_wjHUGASb-5ZuhQ4E6DA2GdbZ-OfGC_63o48yTsyDclL" alt="Couple Avatar"/>
        </template>

        <!-- Welcome Header -->
        <section class="flex flex-col md:flex-row md:items-end justify-between gap-lg mb-xl">
            <div>
                <h2 class="font-display text-display-mobile md:text-display text-on-background">Your Big Day</h2>
                <p class="font-body-lg text-secondary">The wedding is in 142 days. Here's your planning status.</p>
            </div>
            <div class="flex gap-md">
                <button class="flex items-center gap-sm px-lg py-sm border border-outline rounded-full font-label-sm hover:bg-surface-container transition-colors">
                    <span class="material-symbols-outlined text-md">share</span>
                    Share Access
                </button>
                <button class="flex items-center gap-sm px-lg py-sm bg-primary text-on-primary rounded-full font-label-sm hover:opacity-90 transition-opacity">
                    <span class="material-symbols-outlined text-md">edit</span>
                    Edit Details
                </button>
            </div>
        </section>

        <!-- Bento Grid Summary -->
        <section class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-lg mb-xl">
            <!-- Total Guests -->
            <div class="bg-surface border border-outline-variant p-lg rounded-xl flex flex-col justify-between hover:shadow-sm transition-shadow">
                <div class="flex justify-between items-start">
                    <div class="w-10 h-10 rounded-lg bg-primary-fixed flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary">groups</span>
                    </div>
                    <span class="text-on-surface-variant font-label-xs uppercase tracking-wider">Total Guests</span>
                </div>
                <div class="mt-lg">
                    <p class="font-display text-display-mobile text-primary">184</p>
                    <p class="font-label-sm text-on-surface-variant">Target: 200 Guests</p>
                </div>
            </div>

            <!-- Checked-in -->
            <div class="bg-surface border border-outline-variant p-lg rounded-xl flex flex-col justify-between hover:shadow-sm transition-shadow">
                <div class="flex justify-between items-start">
                    <div class="w-10 h-10 rounded-lg bg-secondary-fixed flex items-center justify-center">
                        <span class="material-symbols-outlined text-secondary">how_to_reg</span>
                    </div>
                    <span class="text-on-surface-variant font-label-xs uppercase tracking-wider">Checked-in</span>
                </div>
                <div class="mt-lg">
                    <p class="font-display text-display-mobile text-on-background">132</p>
                    <div class="flex items-center gap-xs text-on-tertiary-fixed-variant">
                        <span class="material-symbols-outlined text-sm">trending_up</span>
                        <span class="font-label-xs">72% Arrival Rate</span>
                    </div>
                </div>
            </div>

            <!-- VIP Guests -->
            <div class="bg-surface border border-outline-variant p-lg rounded-xl flex flex-col justify-between hover:shadow-sm transition-shadow">
                <div class="flex justify-between items-start">
                    <div class="w-10 h-10 rounded-lg bg-tertiary-fixed flex items-center justify-center">
                        <span class="material-symbols-outlined text-tertiary">star</span>
                    </div>
                    <span class="text-on-surface-variant font-label-xs uppercase tracking-wider">VIP Guests</span>
                </div>
                <div class="mt-lg">
                    <p class="font-display text-display-mobile text-tertiary">24</p>
                    <p class="font-label-sm text-on-surface-variant">Priority Management</p>
                </div>
            </div>

            <!-- Invitation Progress -->
            <div class="md:col-span-3 lg:col-span-1 bg-primary text-on-primary p-lg rounded-xl flex flex-col justify-between shadow-xl">
                <div class="flex justify-between items-center mb-md">
                    <span class="font-label-sm opacity-80">Invitation Completion</span>
                    <span class="material-symbols-outlined">send</span>
                </div>
                <div class="flex-1 flex flex-col justify-center">
                    <div class="flex justify-between items-end mb-sm">
                        <span class="font-display text-headline-lg">92%</span>
                        <span class="font-label-sm opacity-80">170 / 184 Sent</span>
                    </div>
                    <div class="w-full bg-on-primary/20 h-2 rounded-full overflow-hidden">
                        <div class="bg-white h-full" style="width: 92%"></div>
                    </div>
                </div>
                <p class="mt-md font-label-xs opacity-60">14 invitations pending final review</p>
            </div>
        </section>

        <!-- Main Analytics & Actions -->
        <section class="grid grid-cols-1 lg:grid-cols-3 gap-lg mb-xl">
            <!-- Guest Attendance Analytics -->
            <div class="lg:col-span-2 bg-surface border border-outline-variant rounded-xl p-xl flex flex-col md:flex-row gap-xl items-center">
                <DonutChart :percentage="72" label="RSVP Yes" />
                <div class="flex-1 w-full space-y-lg">
                    <div>
                        <h3 class="font-headline-md text-on-background mb-xs">Attendance Breakdown</h3>
                        <p class="font-body-md text-on-surface-variant">Guest response summary for the main reception.</p>
                    </div>
                    <div class="space-y-md">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-md">
                                <div class="w-3 h-3 rounded-full bg-primary"></div>
                                <span class="font-body-md">Attending (Present)</span>
                            </div>
                            <span class="font-label-sm font-bold">132 Guests</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-md">
                                <div class="w-3 h-3 rounded-full bg-primary-fixed"></div>
                                <span class="font-body-md">Invited (Pending)</span>
                            </div>
                            <span class="font-label-sm font-bold">52 Guests</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-md">
                                <div class="w-3 h-3 rounded-full bg-error/30"></div>
                                <span class="font-body-md">Declined</span>
                            </div>
                            <span class="font-label-sm font-bold">12 Guests</span>
                        </div>
                    </div>
                    <button class="w-full py-sm border border-outline-variant rounded-lg font-label-sm text-primary hover:bg-surface-container-low transition-all">
                        View Full Guest List
                    </button>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-xl flex flex-col gap-lg">
                <h3 class="font-headline-md text-on-background">Quick Actions</h3>
                
                <button @click="copyScannerUrl" class="flex items-center justify-between p-md bg-surface border border-outline-variant rounded-xl hover:border-primary transition-colors group">
                    <div class="flex items-center gap-md">
                        <div class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined">qr_code_scanner</span>
                        </div>
                        <div class="text-left">
                            <p class="font-label-sm font-bold">Scanner URL</p>
                            <p class="font-label-xs text-on-surface-variant">Secret link for receptionists</p>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-outline group-hover:text-primary transition-colors">content_copy</span>
                </button>

                <button class="flex items-center justify-between p-md bg-surface border border-outline-variant rounded-xl hover:border-primary transition-colors group">
                    <div class="flex items-center gap-md">
                        <div class="w-10 h-10 rounded-lg bg-surface-container flex items-center justify-center text-primary">
                            <span class="material-symbols-outlined">download</span>
                        </div>
                        <div class="text-left">
                            <p class="font-label-sm font-bold">Guest Template</p>
                            <p class="font-label-xs text-on-surface-variant">Download Excel format</p>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-outline group-hover:text-primary transition-colors">description</span>
                </button>

                <button class="flex items-center justify-between p-md bg-surface border border-outline-variant rounded-xl hover:border-primary transition-colors group">
                    <div class="flex items-center gap-md">
                        <div class="w-10 h-10 rounded-lg bg-primary-container flex items-center justify-center text-on-primary-container">
                            <span class="material-symbols-outlined">upload_file</span>
                        </div>
                        <div class="text-left">
                            <p class="font-label-sm font-bold">Import Excel</p>
                            <p class="font-label-xs text-on-surface-variant">Batch upload your list</p>
                        </div>
                    </div>
                    <span class="material-symbols-outlined text-outline group-hover:text-primary transition-colors">arrow_forward</span>
                </button>

                <div class="mt-auto pt-md text-center">
                    <p class="font-label-xs text-on-surface-variant italic">Last synced: 10 minutes ago</p>
                </div>
            </div>
        </section>

        <!-- Recent Guest Updates -->
        <section class="space-y-md">
            <div class="flex items-center justify-between">
                <h3 class="font-headline-md text-on-background">Recent Guest Updates</h3>
                <Link href="#" class="text-primary font-label-sm hover:underline">See all activity</Link>
            </div>
            <div class="bg-surface border border-outline-variant rounded-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-surface-container-low border-b border-outline-variant">
                            <tr>
                                <th class="px-xl py-md font-label-sm text-on-surface-variant">Guest Name</th>
                                <th class="px-xl py-md font-label-sm text-on-surface-variant">Type</th>
                                <th class="px-xl py-md font-label-sm text-on-surface-variant">Status</th>
                                <th class="px-xl py-md font-label-sm text-on-surface-variant text-right">Activity Time</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant">
                            <tr v-for="guest in recentGuests" :key="guest.name" class="hover:bg-surface-container-low transition-colors">
                                <td class="px-xl py-md">
                                    <div class="flex items-center gap-md">
                                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs" :class="guest.avatarColor">
                                            {{ guest.initials }}
                                        </div>
                                        <span class="font-label-sm font-bold">{{ guest.name }}</span>
                                    </div>
                                </td>
                                <td class="px-xl py-md">
                                    <span class="px-md py-xs rounded-full text-xs font-bold" :class="guest.typeColor">{{ guest.type }}</span>
                                </td>
                                <td class="px-xl py-md">
                                    <div class="flex items-center gap-xs" :class="guest.statusColor">
                                        <span class="material-symbols-outlined text-sm">{{ guest.statusIcon }}</span>
                                        <span class="font-label-sm">{{ guest.status }}</span>
                                    </div>
                                </td>
                                <td class="px-xl py-md text-right font-label-xs text-on-surface-variant">{{ guest.time }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </DashboardLayout>
</template>
