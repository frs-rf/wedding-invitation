<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Toaster } from '@/Components/ui/sonner'

/**
 * Wrapper layout untuk halaman dashboard berbasis role.
 * Memanfaatkan AuthenticatedLayout (sidebar + nav) serta menyediakan Global Toaster.
 */
defineProps<{
    title?: string
}>()
</script>

<template>
    <AuthenticatedLayout>
        <slot />
    </AuthenticatedLayout>
    <Toaster position="top-right" richColors />
</template>
