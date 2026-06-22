<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';

defineProps<{
    status?: string;
}>();

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<template>
    <GuestLayout>
        <Head title="Lupa Password" />

        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">Lupa Password?</h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-2">
                Tidak masalah. Masukkan alamat email Anda dan kami akan mengirimkan tautan untuk mengatur ulang password Anda.
            </p>
        </div>

        <div
            v-if="status"
            class="mb-6 text-sm font-medium text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/30 p-3 rounded-md text-center"
        >
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <div class="space-y-2">
                <Label for="email" :class="{ 'text-red-500': form.errors.email }">Alamat Email</Label>
                <Input
                    id="email"
                    type="email"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="nama@email.com"
                    :class="{ 'border-red-500 focus-visible:ring-red-500': form.errors.email }"
                />
                <p v-if="form.errors.email" class="text-sm text-red-500">{{ form.errors.email }}</p>
            </div>

            <Button
                type="submit"
                class="w-full"
                :disabled="form.processing"
            >
                Kirim Tautan Reset Password
            </Button>

            <div class="text-center text-sm text-zinc-500 mt-4">
                <Link :href="route('login')" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">&larr; Kembali ke halaman masuk</Link>
            </div>
        </form>
    </GuestLayout>
</template>
