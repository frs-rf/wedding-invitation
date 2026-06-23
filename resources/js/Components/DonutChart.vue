<script setup lang="ts">
import { onMounted, ref } from 'vue';

const props = defineProps<{
    percentage: number;
    label: string;
}>();

const chartRef = ref<HTMLElement | null>(null);

onMounted(() => {
    if (chartRef.value) {
        chartRef.value.style.transition = 'transform 1s ease-out';
        chartRef.value.style.transform = 'rotate(-90deg)';
        setTimeout(() => {
            if (chartRef.value) {
                chartRef.value.style.transform = 'rotate(0deg)';
            }
        }, 100);
    }
});
</script>

<template>
    <div class="relative w-48 h-48 flex-shrink-0">
        <!-- Donut chart uses conic-gradient set in CSS -->
        <div 
            ref="chartRef" 
            class="chart-donut w-full h-full"
            :style="`background: conic-gradient(var(--tw-color-primary) 0% ${percentage}%, var(--tw-color-primary-fixed) ${percentage}% 100%);`"
        ></div>
        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
            <span class="font-display text-headline-lg text-primary">{{ percentage }}%</span>
            <span class="font-label-xs text-on-surface-variant uppercase">{{ label }}</span>
        </div>
    </div>
</template>
