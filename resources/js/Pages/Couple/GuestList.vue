<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import DashboardLayout from '@/Layouts/DashboardLayout.vue';

// Mock data based on the updated design
const guests = [
    { name: 'Julianne Doe', email: 'julianne@example.com', initials: 'JD', group: 'Family', groupColor: 'bg-tertiary-fixed text-on-tertiary-fixed-variant', link: 'w-saas.com/i/8x2j9k', status: 'Sent', statusIcon: true, statusColor: 'text-primary', avatarColor: 'bg-secondary-fixed text-on-secondary-fixed', hasWhatsApp: true, actionsEnabled: true },
    { name: 'Benjamin Mercer', email: '+1 (555) 092-1234', initials: 'BM', group: 'VIP', groupColor: 'bg-primary-container text-on-primary-container', link: 'w-saas.com/i/1p4q...', status: 'Checked-in', statusIcon: false, statusColor: 'text-secondary', avatarColor: 'bg-primary-fixed text-primary', hasWhatsApp: true, actionsEnabled: false },
    { name: 'Sarah Chen', email: 'sarah.chen@web.com', initials: 'SC', group: 'Friends', groupColor: 'bg-surface-container-highest text-on-surface-variant', link: 'w-saas.com/i/9z1k...', status: 'Draft', statusIcon: false, statusColor: 'text-on-surface-variant opacity-60 italic', avatarColor: 'bg-secondary-fixed text-on-secondary-fixed', hasWhatsApp: true, actionsEnabled: true },
];

const copyToClipboard = (text: string) => {
    navigator.clipboard.writeText(text).then(() => {
        const toast = document.createElement('div');
        toast.className = 'fixed bottom-xl right-xl bg-on-background text-background px-lg py-md rounded-xl font-label-sm shadow-xl z-50 flex items-center gap-md animate-bounce';
        toast.innerHTML = `<span class="material-symbols-outlined text-green-400">check_circle</span> Link copied to clipboard!`;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.classList.add('opacity-0', 'transition-opacity', 'duration-500');
            setTimeout(() => toast.remove(), 500);
        }, 3000);
    });
};
</script>

<template>
    <Head title="Guest List Management" />

    <DashboardLayout>
        <!-- Header Section -->
        <header class="flex flex-col md:flex-row justify-between md:items-end mb-2xl gap-lg">
            <div>
                <h2 class="font-display text-display-mobile md:text-display tracking-tight text-on-background mb-xs">Guest Management</h2>
                <p class="font-body-md text-on-surface-variant max-w-2xl">Effortlessly organize your wedding attendees, track RSVPs, and manage communication across different circles and priority levels.</p>
            </div>
            <div class="flex gap-md">
                <div class="flex flex-col items-end gap-xs">
                    <span class="font-label-xs text-on-surface-variant uppercase tracking-widest">Total Guests</span>
                    <span class="font-display text-headline-lg text-primary">248</span>
                </div>
                <div class="h-12 w-px bg-outline-variant mx-sm"></div>
                <div class="flex flex-col items-end gap-xs">
                    <span class="font-label-xs text-on-surface-variant uppercase tracking-widest">Confirmed</span>
                    <span class="font-display text-headline-lg text-secondary">182</span>
                </div>
            </div>
        </header>

        <!-- Public URL Section -->
        <section class="bg-surface/80 backdrop-blur-md rounded-xl p-lg mb-xl flex flex-col md:flex-row items-start md:items-center justify-between border border-primary/20 gap-md">
            <div class="flex items-center gap-lg">
                <div class="p-md bg-primary-fixed rounded-full text-primary flex items-center justify-center">
                    <span class="material-symbols-outlined">link</span>
                </div>
                <div>
                    <h3 class="font-label-sm text-on-background font-bold">Public Invitation URL</h3>
                    <p class="font-body-md text-on-surface-variant">Share this link for general guest registration or direct RSVPs.</p>
                </div>
            </div>
            <div class="flex items-center bg-surface-container-low border border-outline-variant rounded-full pl-lg pr-sm py-sm gap-md w-full md:w-auto">
                <span class="font-label-sm text-on-surface-variant truncate max-w-[200px] sm:max-w-none">wedding-saas.com/v/the-smith-wedding-2024</span>
                <button @click="copyToClipboard('wedding-saas.com/v/the-smith-wedding-2024')" class="bg-primary text-on-primary px-lg py-xs rounded-full font-label-sm hover:opacity-90 transition-opacity whitespace-nowrap ml-auto">
                    Copy Link
                </button>
            </div>
        </section>

        <!-- Toolbar Section -->
        <div class="flex flex-col md:flex-row gap-md items-center justify-between mb-lg bg-surface-container-lowest p-md rounded-xl border border-outline-variant shadow-sm">
            <div class="flex flex-col sm:flex-row items-center gap-md w-full md:w-auto">
                <!-- Search -->
                <div class="relative w-full sm:w-80">
                    <span class="material-symbols-outlined absolute left-md top-1/2 -translate-y-1/2 text-on-surface-variant">search</span>
                    <input class="w-full bg-surface border border-outline-variant rounded-xl pl-2xl pr-md py-sm font-body-md focus:ring-2 focus:ring-primary focus:border-transparent outline-none transition-all" placeholder="Search guests..." type="text"/>
                </div>
                <!-- Group By Filter -->
                <div class="relative inline-block text-left w-full sm:w-auto">
                    <button class="w-full sm:w-auto flex items-center justify-between sm:justify-start gap-sm px-md py-sm border border-outline-variant rounded-xl font-label-sm text-on-surface-variant hover:bg-surface-container-high transition-colors">
                        <div class="flex items-center gap-sm">
                            <span class="material-symbols-outlined">filter_list</span> Group By
                        </div>
                        <span class="material-symbols-outlined">expand_more</span>
                    </button>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row items-center gap-md w-full md:w-auto">
                <!-- Mass Action -->
                <div class="relative inline-block text-left w-full sm:w-auto">
                    <button class="w-full sm:w-auto flex items-center justify-between sm:justify-start gap-sm px-md py-sm bg-surface-container-high rounded-xl font-label-sm text-on-surface-variant hover:bg-outline-variant transition-colors">
                        <div class="flex items-center gap-sm">
                            <span class="material-symbols-outlined">bolt</span> Mass Actions
                        </div>
                        <span class="material-symbols-outlined">expand_more</span>
                    </button>
                </div>
                <div class="hidden sm:block h-8 w-px bg-outline-variant"></div>
                <button class="w-full sm:w-auto flex items-center justify-center gap-sm px-lg py-sm bg-primary text-on-primary rounded-xl font-label-sm hover:opacity-90 transition-opacity">
                    <span class="material-symbols-outlined">person_add</span> Add Guest
                </button>
            </div>
        </div>

        <!-- Table Content -->
        <div class="bg-surface rounded-xl border border-outline-variant overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low border-b border-outline-variant">
                            <th class="px-lg py-md font-label-sm text-on-surface-variant w-10">
                                <input class="rounded border-outline-variant text-primary focus:ring-primary" type="checkbox"/>
                            </th>
                            <th class="px-lg py-md font-label-sm text-on-surface-variant">Name</th>
                            <th class="px-lg py-md font-label-sm text-on-surface-variant">Group</th>
                            <th class="px-lg py-md font-label-sm text-on-surface-variant">Invitation Link</th>
                            <th class="px-lg py-md font-label-sm text-on-surface-variant">Status</th>
                            <th class="px-lg py-md font-label-sm text-on-surface-variant text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        <tr v-for="(guest, index) in guests" :key="index" class="hover:bg-surface-container-lowest transition-colors group">
                            <td class="px-lg py-md">
                                <input class="rounded border-outline-variant text-primary focus:ring-primary" type="checkbox"/>
                            </td>
                            <td class="px-lg py-md">
                                <div class="flex items-center gap-md">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-xs" :class="guest.avatarColor">{{ guest.initials }}</div>
                                    <div>
                                        <p class="font-label-sm text-on-background">{{ guest.name }}</p>
                                        <p class="font-label-xs text-on-surface-variant opacity-60">{{ guest.email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-lg py-md">
                                <span class="inline-flex items-center px-sm py-0.5 rounded-full text-label-xs font-semibold" :class="guest.groupColor">
                                    {{ guest.group }}
                                </span>
                            </td>
                            <td class="px-lg py-md">
                                <div class="flex items-center gap-xs">
                                    <span class="font-label-xs text-on-surface-variant opacity-50 truncate max-w-[120px]">{{ guest.link }}</span>
                                    <button @click="copyToClipboard(guest.link)" class="p-xs hover:bg-surface-container-high rounded transition-colors text-primary">
                                        <span class="material-symbols-outlined text-[18px]">content_copy</span>
                                    </button>
                                </div>
                            </td>
                            <td class="px-lg py-md">
                                <span class="inline-flex items-center gap-xs font-label-sm font-bold" :class="guest.statusColor">
                                    <span v-if="guest.statusIcon" class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                                    <span v-else-if="guest.status === 'Checked-in'" class="w-2 h-2 rounded-full bg-secondary"></span>
                                    {{ guest.status }}
                                </span>
                            </td>
                            <td class="px-lg py-md text-right">
                                <div class="flex items-center justify-end gap-sm" :class="guest.actionsEnabled ? '' : 'opacity-40 grayscale pointer-events-none'">
                                    <button v-if="guest.hasWhatsApp" class="flex items-center gap-xs px-sm py-xs bg-[#25D366] text-white rounded-lg font-label-xs hover:opacity-90 transition-opacity">
                                        <span class="material-symbols-outlined text-[18px]">chat</span> WhatsApp
                                    </button>
                                    <button class="p-xs text-on-surface-variant hover:text-on-background transition-colors">
                                        <span class="material-symbols-outlined">more_vert</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination/Footer -->
            <div class="px-lg py-md bg-surface-container-lowest border-t border-outline-variant flex flex-col sm:flex-row items-center justify-between gap-sm">
                <p class="font-label-xs text-on-surface-variant">Showing 1-10 of 248 guests</p>
                <div class="flex items-center gap-xs">
                    <button class="p-xs border border-outline-variant rounded hover:bg-surface-container-high disabled:opacity-30" disabled>
                        <span class="material-symbols-outlined">chevron_left</span>
                    </button>
                    <button class="px-sm py-xs border border-primary bg-primary text-on-primary rounded font-label-xs">1</button>
                    <button class="px-sm py-xs border border-outline-variant rounded hover:bg-surface-container-high font-label-xs">2</button>
                    <button class="px-sm py-xs border border-outline-variant rounded hover:bg-surface-container-high font-label-xs">3</button>
                    <span class="text-on-surface-variant px-xs">...</span>
                    <button class="px-sm py-xs border border-outline-variant rounded hover:bg-surface-container-high font-label-xs">25</button>
                    <button class="p-xs border border-outline-variant rounded hover:bg-surface-container-high">
                        <span class="material-symbols-outlined">chevron_right</span>
                    </button>
                </div>
            </div>
        </div>
    </DashboardLayout>
</template>

<style scoped>
/* Glassmorphism subtle effect used in the public URL section */
.backdrop-blur-md {
    backdrop-filter: blur(8px);
}
</style>
