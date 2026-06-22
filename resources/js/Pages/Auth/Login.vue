<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { Checkbox } from '@/Components/ui/checkbox';

defineProps<{
    canResetPassword?: boolean;
    status?: string;
}>();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => {
            form.reset('password');
        },
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Log in" />

        <div class="mb-6 text-center">
            <h1 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-50">Selamat Datang</h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-2">Silakan masuk ke akun Anda</p>
        </div>

        <div v-if="status" class="mb-4 text-sm font-medium text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/30 p-3 rounded-md">
            {{ status }}
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <div class="space-y-2">
                <Label for="email" :class="{ 'text-red-500': form.errors.email }">Email</Label>
                <Input
                    id="email"
                    type="email"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                    :class="{ 'border-red-500 focus-visible:ring-red-500': form.errors.email }"
                />
                <p v-if="form.errors.email" class="text-sm text-red-500">{{ form.errors.email }}</p>
            </div>

            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <Label for="password" :class="{ 'text-red-500': form.errors.password }">Password</Label>
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-sm font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400 dark:hover:text-indigo-300"
                    >
                        Lupa password?
                    </Link>
                </div>
                <Input
                    id="password"
                    type="password"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    :class="{ 'border-red-500 focus-visible:ring-red-500': form.errors.password }"
                />
                <p v-if="form.errors.password" class="text-sm text-red-500">{{ form.errors.password }}</p>
            </div>

            <div class="flex items-center space-x-2">
                <Checkbox id="remember" :checked="form.remember" @update:checked="(v: boolean) => form.remember = !!v" />
                <label
                    for="remember"
                    class="text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 text-zinc-700 dark:text-zinc-300"
                >
                    Ingat saya
                </label>
            </div>

            <Button
                type="submit"
                class="w-full"
                :disabled="form.processing"
            >
                Masuk
            </Button>
            
            <div class="text-center text-sm text-zinc-500 mt-4">
                Belum punya akun vendor?
                <Link :href="route('register')" class="font-medium text-indigo-600 hover:text-indigo-500 dark:text-indigo-400">Daftar sekarang</Link>
            </div>
        </form>
    </GuestLayout>
</template>
