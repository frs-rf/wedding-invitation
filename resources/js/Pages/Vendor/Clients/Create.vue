<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue'
import { Head, useForm, Link } from '@inertiajs/vue3'
import { Card, CardContent, CardHeader, CardTitle, CardDescription, CardFooter } from '@/Components/ui/card'
import { Button } from '@/Components/ui/button'
import { Label } from '@/Components/ui/label'
import { Input } from '@/Components/ui/input'
import { ArrowLeft, Save } from 'lucide-vue-next'

const form = useForm({
    name: '',
    email: '',
    password: '',
    slug: '',
    event_date: '',
});

const submit = () => {
    form.post(route('vendor.clients.store'));
};
</script>

<template>
    <AppLayout title="Tambah Klien">
        <Head title="Tambah Klien" />

        <div class="space-y-6 max-w-2xl mx-auto">
            <div class="flex items-center gap-4">
                <Button variant="outline" size="icon" asChild class="h-8 w-8 rounded-full">
                    <Link :href="route('vendor.clients.index')">
                        <ArrowLeft class="h-4 w-4" />
                    </Link>
                </Button>
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-100">Tambah Klien Baru</h2>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400">Masukkan detail pasangan pengantin untuk membuat undangan mereka.</p>
                </div>
            </div>

            <form @submit.prevent="submit">
                <Card class="border-zinc-200 dark:border-zinc-800 shadow-sm">
                    <CardHeader class="border-b border-zinc-100 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/50">
                        <CardTitle class="text-lg">Informasi Dasar</CardTitle>
                        <CardDescription>Akun ini akan digunakan oleh klien untuk masuk ke dasbor mereka.</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-6 pt-6">
                        <div class="space-y-2">
                            <Label for="name" :class="{ 'text-red-500': form.errors.name }">Nama Pasangan</Label>
                            <Input id="name" type="text" v-model="form.name" required autofocus placeholder="Misal: Budi & Riri" :class="{ 'border-red-500 focus-visible:ring-red-500': form.errors.name }" />
                            <p v-if="form.errors.name" class="text-sm text-red-500">{{ form.errors.name }}</p>
                        </div>

                        <div class="space-y-2">
                            <Label for="email" :class="{ 'text-red-500': form.errors.email }">Email Login Klien</Label>
                            <Input id="email" type="email" v-model="form.email" required placeholder="pengantin@email.com" :class="{ 'border-red-500 focus-visible:ring-red-500': form.errors.email }" />
                            <p v-if="form.errors.email" class="text-sm text-red-500">{{ form.errors.email }}</p>
                        </div>

                        <div class="space-y-2">
                            <Label for="password" :class="{ 'text-red-500': form.errors.password }">Password Login</Label>
                            <Input id="password" type="password" v-model="form.password" required placeholder="Minimal 8 karakter" :class="{ 'border-red-500 focus-visible:ring-red-500': form.errors.password }" />
                            <p v-if="form.errors.password" class="text-sm text-red-500">{{ form.errors.password }}</p>
                        </div>

                        <div class="border-t border-zinc-200 dark:border-zinc-800 my-6"></div>

                        <div class="space-y-2">
                            <Label for="slug" :class="{ 'text-red-500': form.errors.slug }">Slug Undangan (URL)</Label>
                            <div class="flex items-center gap-2">
                                <span class="text-zinc-500 dark:text-zinc-400 bg-zinc-100 dark:bg-zinc-800 px-3 py-2 rounded-md border border-zinc-200 dark:border-zinc-700 text-sm hidden sm:inline-block">domain.com/</span>
                                <Input id="slug" type="text" v-model="form.slug" required placeholder="budi-riri" class="flex-1" :class="{ 'border-red-500 focus-visible:ring-red-500': form.errors.slug }" />
                            </div>
                            <p class="text-xs text-zinc-500 mt-1">Hanya huruf kecil, angka, dan strip (-).</p>
                            <p v-if="form.errors.slug" class="text-sm text-red-500">{{ form.errors.slug }}</p>
                        </div>

                        <div class="space-y-2">
                            <Label for="event_date" :class="{ 'text-red-500': form.errors.event_date }">Tanggal Acara</Label>
                            <Input id="event_date" type="date" v-model="form.event_date" required :class="{ 'border-red-500 focus-visible:ring-red-500': form.errors.event_date }" />
                            <p v-if="form.errors.event_date" class="text-sm text-red-500">{{ form.errors.event_date }}</p>
                        </div>
                    </CardContent>
                    <CardFooter class="border-t border-zinc-100 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/50 px-6 py-4 justify-end gap-3">
                        <Button type="button" variant="outline" asChild>
                            <Link :href="route('vendor.clients.index')">Batal</Link>
                        </Button>
                        <Button type="submit" :disabled="form.processing" class="gap-2">
                            <Save class="w-4 h-4" />
                            Simpan Klien
                        </Button>
                    </CardFooter>
                </Card>
            </form>
        </div>
    </AppLayout>
</template>
