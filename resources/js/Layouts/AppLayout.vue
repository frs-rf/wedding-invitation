<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

/**
 * Wrapper layout untuk halaman dashboard berbasis role.
 * Memanfaatkan AuthenticatedLayout bawaan Breeze (sidebar + nav).
 */
defineProps<{
    title?: string
}>()
</script>

<template>
    <AuthenticatedLayout>
        <slot />
    </AuthenticatedLayout>
</template>
