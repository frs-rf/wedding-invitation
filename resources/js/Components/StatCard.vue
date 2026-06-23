<script setup lang="ts">
defineProps<{
    title: string;
    value: string | number;
    icon: string;
    iconColorClass?: string; // e.g., 'text-primary bg-primary-fixed'
    badgeText?: string;
    badgeColorClass?: string; // e.g., 'text-green-600 bg-green-50'
}>();
</script>

<template>
    <div class="bg-surface-container-lowest dark:bg-zinc-900 border border-outline-variant dark:border-zinc-800 p-xl rounded-xl shadow-[0_15px_30px_-15px_rgba(0,0,0,0.04)] hover:shadow-sm transition-shadow flex flex-col justify-between">
        <div class="flex items-center justify-between mb-md">
            <span class="material-symbols-outlined p-sm rounded-lg" :class="iconColorClass || 'text-primary bg-primary-fixed'">{{ icon }}</span>
            <span v-if="badgeText" class="text-label-xs font-label-xs px-sm py-xs rounded-full" :class="badgeColorClass || 'text-on-surface-variant bg-surface-container-high'">
                {{ badgeText }}
            </span>
        </div>
        <div>
            <p class="text-on-surface-variant font-label-sm text-label-sm uppercase tracking-wider">{{ title }}</p>
            <p class="text-display-mobile font-display mt-xs text-on-background">{{ value }}</p>
            <slot name="footer"></slot>
        </div>
    </div>
</template>
