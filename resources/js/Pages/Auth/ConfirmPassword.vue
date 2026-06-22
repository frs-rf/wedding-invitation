<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Confirm Password" />

        <div class="mb-4 text-sm text-zinc-600 dark:text-zinc-400">
            This is a secure area of the application. Please confirm your
            password before continuing.
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <div class="space-y-2">
                <Label for="password" :class="{ 'text-red-500': form.errors.password }">Password</Label>
                <Input
                    id="password"
                    type="password"
                    class="block w-full"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    autofocus
                    :class="{ 'border-red-500 focus-visible:ring-red-500': form.errors.password }"
                />
                <p v-if="form.errors.password" class="mt-2 text-sm text-red-500">{{ form.errors.password }}</p>
            </div>

            <div class="mt-4 flex justify-end">
                <Button
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Confirm
                </Button>
            </div>
        </form>
    </GuestLayout>
</template>
