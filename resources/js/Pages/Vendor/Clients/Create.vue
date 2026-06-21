<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue'
import { Head, useForm, Link } from '@inertiajs/vue3'
import InputError from '@/Components/InputError.vue'
import InputLabel from '@/Components/InputLabel.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import TextInput from '@/Components/TextInput.vue'

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

        <div class="py-12">
            <div class="mx-auto max-w-2xl sm:px-6 lg:px-8">
                <div class="mb-6 flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-gray-900">Tambah Klien Pengantin Baru</h2>
                    <Link :href="route('vendor.clients.index')" class="text-indigo-600 hover:text-indigo-900">
                        &larr; Kembali
                    </Link>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <form @submit.prevent="submit" class="space-y-6">
                        <div>
                            <InputLabel for="name" value="Nama Pasangan (Misal: Budi & Riri)" />
                            <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus />
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>

                        <div>
                            <InputLabel for="email" value="Email Login Klien" />
                            <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required />
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>

                        <div>
                            <InputLabel for="password" value="Password Login" />
                            <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required />
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>

                        <hr class="my-4" />

                        <div>
                            <InputLabel for="slug" value="Slug Undangan (Misal: budi-riri)" />
                            <TextInput id="slug" type="text" class="mt-1 block w-full" v-model="form.slug" required />
                            <InputError class="mt-2" :message="form.errors.slug" />
                            <p class="mt-1 text-xs text-gray-500">Akan menjadi URL: domain.com/invitation/budi-riri</p>
                        </div>

                        <div>
                            <InputLabel for="event_date" value="Tanggal Acara" />
                            <TextInput id="event_date" type="date" class="mt-1 block w-full" v-model="form.event_date" required />
                            <InputError class="mt-2" :message="form.errors.event_date" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                Simpan Klien
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
