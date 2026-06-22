<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue'
import { Head, Link, router } from '@inertiajs/vue3'
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card'
import { Button } from '@/Components/ui/button'
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/Components/ui/table'
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuSeparator, DropdownMenuTrigger } from '@/Components/ui/dropdown-menu'
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/Components/ui/dialog'
import { Users, Plus, MoreHorizontal, LogIn, Trash2, CalendarDays } from 'lucide-vue-next'
import { ref } from 'vue'

defineProps<{
    clients: any[]
}>()

const showDeleteModal = ref(false)
const clientToDelete = ref<any>(null)

const confirmDelete = (client: any) => {
    clientToDelete.value = client
    showDeleteModal.value = true
}

const deleteClient = () => {
    if (clientToDelete.value) {
        // Asumsi rute penghapusan klien ada. Jika belum ada, Anda bisa menambahkannya di web.php
        router.delete(route('vendor.clients.destroy', clientToDelete.value.id), {
            onSuccess: () => {
                showDeleteModal.value = false
                clientToDelete.value = null
            }
        })
    }
}
</script>

<template>
    <AppLayout title="Daftar Klien">
        <Head title="Daftar Klien" />

        <div class="space-y-6">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-100">Daftar Klien Pengantin</h2>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Kelola data klien dan akses dasbor mereka secara instan.</p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <Button asChild class="gap-2">
                        <Link :href="route('vendor.clients.create')">
                            <Plus class="h-4 w-4" />
                            Tambah Klien Baru
                        </Link>
                    </Button>
                </div>
            </div>

            <Card class="border-zinc-200 dark:border-zinc-800 shadow-sm">
                <CardHeader class="p-4 sm:px-6 border-b border-zinc-100 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/50">
                    <CardTitle class="text-sm font-medium flex items-center gap-2">
                        <Users class="h-4 w-4 text-zinc-500" />
                        Semua Klien
                    </CardTitle>
                </CardHeader>
                <CardContent class="p-0">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Nama Pasangan</TableHead>
                                <TableHead>Email Login</TableHead>
                                <TableHead class="text-center">Total Tamu</TableHead>
                                <TableHead class="text-right">Aksi</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="client in clients" :key="client.id">
                                <TableCell>
                                    <div class="font-medium text-zinc-900 dark:text-zinc-100">{{ client.name }}</div>
                                    <div v-if="client.weddings && client.weddings.length > 0" class="text-xs text-zinc-500 flex items-center gap-1 mt-1">
                                        <CalendarDays class="h-3 w-3" />
                                        {{ new Date(client.weddings[0].event_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) }}
                                    </div>
                                </TableCell>
                                <TableCell>
                                    <span class="text-sm text-zinc-600 dark:text-zinc-400">{{ client.email }}</span>
                                </TableCell>
                                <TableCell class="text-center">
                                    <span class="inline-flex items-center justify-center bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 font-semibold px-2.5 py-0.5 rounded-full text-xs">
                                        {{ client.weddings && client.weddings.length > 0 ? client.weddings[0].guests_count : 0 }} Tamu
                                    </span>
                                </TableCell>
                                <TableCell class="text-right">
                                    <DropdownMenu>
                                        <DropdownMenuTrigger asChild>
                                            <Button variant="ghost" size="icon" class="h-8 w-8">
                                                <MoreHorizontal class="h-4 w-4" />
                                                <span class="sr-only">Buka menu aksi</span>
                                            </Button>
                                        </DropdownMenuTrigger>
                                        <DropdownMenuContent align="end">
                                            <DropdownMenuItem asChild>
                                                <Link :href="route('vendor.clients.impersonate', client.id)" method="post" as="button" class="w-full cursor-pointer gap-2 font-medium text-indigo-600 dark:text-indigo-400 focus:text-indigo-700 dark:focus:text-indigo-300">
                                                    <LogIn class="h-4 w-4" />
                                                    <span>Masuk Dasbor Klien</span>
                                                </Link>
                                            </DropdownMenuItem>
                                            <!-- Fitur hapus klien dinonaktifkan sementara atau bisa diaktifkan jika route-nya ada -->
                                            <!--
                                            <DropdownMenuSeparator />
                                            <DropdownMenuItem @click="confirmDelete(client)" class="cursor-pointer gap-2 text-red-600 dark:text-red-400 focus:text-red-600">
                                                <Trash2 class="h-4 w-4" />
                                                <span>Hapus Klien</span>
                                            </DropdownMenuItem>
                                            -->
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </TableCell>
                            </TableRow>
                            <TableRow v-if="clients.length === 0">
                                <TableCell colspan="4" class="h-24 text-center text-zinc-500">
                                    Belum ada klien pengantin yang terdaftar.
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
        </div>

        <!-- Delete Modal -->
        <Dialog :open="showDeleteModal" @update:open="(val) => { if (!val) showDeleteModal = false }">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Hapus Klien Pengantin</DialogTitle>
                    <DialogDescription>
                        Apakah Anda yakin ingin menghapus data klien <b>{{ clientToDelete?.name }}</b> beserta seluruh tamu undangannya? Data yang dihapus tidak dapat dikembalikan.
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter class="mt-4">
                    <Button variant="outline" @click="showDeleteModal = false">Batal</Button>
                    <Button variant="destructive" @click="deleteClient">Hapus Permanen</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
