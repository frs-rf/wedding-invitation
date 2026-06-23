<script setup lang="ts">
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { Head, useForm, Link } from '@inertiajs/vue3'

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
    <Head title="Tambah Klien" />

    <DashboardLayout>
        <!-- Header Section -->
        <header class="flex flex-col md:flex-row md:items-end justify-between gap-lg mb-xl">
            <div>
                <div class="flex items-center gap-sm mb-xs">
                    <Link :href="route('vendor.clients.index')" class="flex items-center justify-center w-8 h-8 rounded-full bg-[#f0edf1] text-[#5d5e66] hover:bg-[#e4e1e6] transition-colors">
                        <span class="material-symbols-outlined text-[18px]">arrow_back</span>
                    </Link>
                    <span class="font-label-sm text-[14px] text-[#5d5e66]">Kembali ke Daftar Klien</span>
                </div>
                <h2 class="font-display text-[36px] tracking-tight text-[#1b1b1e] font-bold">Tambah Klien Baru</h2>
                <p class="font-body-md text-[#5d5e66] mt-xs">Masukkan detail pasangan pengantin untuk membuat undangan mereka.</p>
            </div>
        </header>

        <!-- Form Section -->
        <section class="max-w-2xl">
            <form @submit.prevent="submit" class="bg-white border border-[#e4e1e6] rounded-2xl shadow-sm overflow-hidden">
                <div class="px-xl py-lg border-b border-[#e4e1e6] bg-[#fbf8fc]">
                    <h3 class="font-headline-md text-[20px] font-bold text-[#1b1b1e]">Informasi Dasar</h3>
                    <p class="text-[12px] text-[#5d5e66] mt-xs">Akun ini akan digunakan oleh klien untuk masuk ke dasbor mereka.</p>
                </div>
                
                <div class="p-xl space-y-lg">
                    <div class="space-y-xs">
                        <label for="name" class="font-label-sm text-[14px] font-bold text-[#1b1b1e]">Nama Pasangan <span class="text-[#ba1a1a]">*</span></label>
                        <input id="name" type="text" v-model="form.name" required autofocus placeholder="Misal: Budi & Riri" 
                               class="w-full border rounded-xl px-md py-sm font-body-md focus:ring-2 outline-none transition-all"
                               :class="form.errors.name ? 'border-[#ba1a1a] focus:ring-[#ba1a1a]' : 'border-[#c8c4d5] focus:ring-[#1f108e]'" />
                        <p v-if="form.errors.name" class="text-[12px] text-[#ba1a1a] mt-xs">{{ form.errors.name }}</p>
                    </div>

                    <div class="space-y-xs">
                        <label for="email" class="font-label-sm text-[14px] font-bold text-[#1b1b1e]">Email Login Klien <span class="text-[#ba1a1a]">*</span></label>
                        <input id="email" type="email" v-model="form.email" required placeholder="pengantin@email.com" 
                               class="w-full border rounded-xl px-md py-sm font-body-md focus:ring-2 outline-none transition-all"
                               :class="form.errors.email ? 'border-[#ba1a1a] focus:ring-[#ba1a1a]' : 'border-[#c8c4d5] focus:ring-[#1f108e]'" />
                        <p v-if="form.errors.email" class="text-[12px] text-[#ba1a1a] mt-xs">{{ form.errors.email }}</p>
                    </div>

                    <div class="space-y-xs">
                        <label for="password" class="font-label-sm text-[14px] font-bold text-[#1b1b1e]">Password Login <span class="text-[#ba1a1a]">*</span></label>
                        <input id="password" type="password" v-model="form.password" required placeholder="Minimal 8 karakter" 
                               class="w-full border rounded-xl px-md py-sm font-body-md focus:ring-2 outline-none transition-all"
                               :class="form.errors.password ? 'border-[#ba1a1a] focus:ring-[#ba1a1a]' : 'border-[#c8c4d5] focus:ring-[#1f108e]'" />
                        <p v-if="form.errors.password" class="text-[12px] text-[#ba1a1a] mt-xs">{{ form.errors.password }}</p>
                    </div>

                    <div class="h-px bg-[#e4e1e6] w-full my-lg"></div>

                    <div class="space-y-xs">
                        <label for="slug" class="font-label-sm text-[14px] font-bold text-[#1b1b1e]">Slug Undangan (URL) <span class="text-[#ba1a1a]">*</span></label>
                        <div class="flex items-center gap-sm">
                            <span class="font-mono text-[14px] text-[#5d5e66] bg-[#f0edf1] border border-[#e4e1e6] rounded-xl px-md py-sm hidden sm:block">domain.com/</span>
                            <input id="slug" type="text" v-model="form.slug" required placeholder="budi-riri" 
                                   class="flex-1 border rounded-xl px-md py-sm font-body-md focus:ring-2 outline-none transition-all"
                                   :class="form.errors.slug ? 'border-[#ba1a1a] focus:ring-[#ba1a1a]' : 'border-[#c8c4d5] focus:ring-[#1f108e]'" />
                        </div>
                        <p class="text-[12px] text-[#5d5e66] mt-xs">Hanya huruf kecil, angka, dan strip (-).</p>
                        <p v-if="form.errors.slug" class="text-[12px] text-[#ba1a1a] mt-xs">{{ form.errors.slug }}</p>
                    </div>

                    <div class="space-y-xs">
                        <label for="event_date" class="font-label-sm text-[14px] font-bold text-[#1b1b1e]">Tanggal Acara <span class="text-[#ba1a1a]">*</span></label>
                        <input id="event_date" type="date" v-model="form.event_date" required 
                               class="w-full border rounded-xl px-md py-sm font-body-md focus:ring-2 outline-none transition-all"
                               :class="form.errors.event_date ? 'border-[#ba1a1a] focus:ring-[#ba1a1a]' : 'border-[#c8c4d5] focus:ring-[#1f108e]'" />
                        <p v-if="form.errors.event_date" class="text-[12px] text-[#ba1a1a] mt-xs">{{ form.errors.event_date }}</p>
                    </div>
                </div>
                
                <div class="px-xl py-md border-t border-[#e4e1e6] bg-[#fbf8fc] flex justify-end gap-sm">
                    <Link :href="route('vendor.clients.index')" class="px-lg py-sm border border-[#c8c4d5] bg-white rounded-xl font-label-sm text-[14px] font-bold hover:bg-[#f0edf1] flex items-center justify-center">Batal</Link>
                    <button type="submit" :disabled="form.processing" class="px-lg py-sm bg-[#1b1b1e] text-white rounded-xl font-label-sm text-[14px] font-bold hover:opacity-90 flex items-center justify-center gap-sm disabled:opacity-50">
                        <span v-if="form.processing" class="material-symbols-outlined animate-spin text-[16px]">progress_activity</span>
                        <span v-else class="material-symbols-outlined text-[16px]">save</span>
                        Simpan Klien
                    </button>
                </div>
            </form>
        </section>
    </DashboardLayout>
</template>
