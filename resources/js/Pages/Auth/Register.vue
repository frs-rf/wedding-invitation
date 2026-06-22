<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Daftar Akun" />

        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">Daftar Akun Vendor</h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-2">Buat akun untuk mengelola klien pengantin Anda</p>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <div class="space-y-2">
                <Label for="name" :class="{ 'text-red-500': form.errors.name }">Nama Lengkap / Nama WO</Label>
                <Input
                    id="name"
                    type="text"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                    :class="{ 'border-red-500 focus-visible:ring-red-500': form.errors.name }"
                />
                <p v-if="form.errors.name" class="text-sm text-red-500">{{ form.errors.name }}</p>
            </div>

            <div class="space-y-2">
                <Label for="email" :class="{ 'text-red-500': form.errors.email }">Email</Label>
                <Input
                    id="email"
                    type="email"
                    v-model="form.email"
                    required
                    autocomplete="username"
                    :class="{ 'border-red-500 focus-visible:ring-red-500': form.errors.email }"
                />
                <p v-if="form.errors.email" class="text-sm text-red-500">{{ form.errors.email }}</p>
            </div>

            <div class="space-y-2">
                <Label for="password" :class="{ 'text-red-500': form.errors.password }">Password</Label>
                <Input
                    id="password"
                    type="password"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                    :class="{ 'border-red-500 focus-visible:ring-red-500': form.errors.password }"
                />
                <p v-if="form.errors.password" class="text-sm text-red-500">{{ form.errors.password }}</p>
            </div>

            <div class="space-y-2">
                <Label for="password_confirmation" :class="{ 'text-red-500': form.errors.password_confirmation }">Konfirmasi Password</Label>
                <Input
                    id="password_confirmation"
                    type="password"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                    :class="{ 'border-red-500 focus-visible:ring-red-500': form.errors.password_confirmation }"
                />
                <p v-if="form.errors.password_confirmation" class="text-sm text-red-500">{{ form.errors.password_confirmation }}</p>
            </div>

            <Button
                type="submit"
                class="w-full"
                :disabled="form.processing"
            >
                Daftar
            </Button>
            
            <div class="text-center text-sm text-zinc-500 mt-4">
                Sudah punya akun?
                <Link :href="route('login')" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">Masuk di sini</Link>
            </div>
        </form>
    </GuestLayout>
</template>
