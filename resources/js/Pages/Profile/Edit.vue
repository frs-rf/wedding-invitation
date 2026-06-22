<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import { Head } from '@inertiajs/vue3';

defineProps<{
    mustVerifyEmail?: boolean;
    status?: string;
}>();
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-zinc-800 dark:text-zinc-200">
                Profile
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-zinc-900 p-4 shadow sm:rounded-lg sm:p-8 border border-zinc-200 dark:border-zinc-800">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>

                <div class="bg-white dark:bg-zinc-900 p-4 shadow sm:rounded-lg sm:p-8 border border-zinc-200 dark:border-zinc-800">
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <div class="bg-white dark:bg-zinc-900 p-4 shadow sm:rounded-lg sm:p-8 border border-zinc-200 dark:border-zinc-800">
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
