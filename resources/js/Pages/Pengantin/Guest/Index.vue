<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue'
import { Head, Link, useForm, router } from '@inertiajs/vue3'
import { ref, watch, onMounted } from 'vue'
import { toast } from 'vue-sonner'
import { usePage } from '@inertiajs/vue3'

// Shadcn UI Components
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/Components/ui/table'
import { Dialog, DialogContent, DialogDescription, DialogFooter, DialogHeader, DialogTitle } from '@/Components/ui/dialog'
import { Button } from '@/Components/ui/button'
import { Input } from '@/Components/ui/input'
import { Label } from '@/Components/ui/label'
import { Checkbox } from '@/Components/ui/checkbox'
import { Badge } from '@/Components/ui/badge'
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card'
import { DropdownMenu, DropdownMenuContent, DropdownMenuItem, DropdownMenuSeparator, DropdownMenuTrigger } from '@/Components/ui/dropdown-menu'

// Icons
import { Plus, Download, Upload, Search, Filter, MoreHorizontal, Copy, MessageCircle, Edit, Trash2, ShieldCheck, Loader2 } from 'lucide-vue-next'

const props = defineProps<{
    wedding: any
    guests: any
    filters: any
}>()

const search = ref(props.filters.search || '')
const filter = ref(props.filters.filter || 'all')
const pageProps = usePage().props

// Watch for flash messages from Inertia
watch(() => pageProps.flash, (flash: any) => {
    if (flash?.success) {
        toast.success(flash.success)
    }
}, { deep: true })

onMounted(() => {
    if (pageProps.flash?.success) {
        toast.success(pageProps.flash.success)
    }
})

// Fetch data when filters change
watch([search, filter], () => {
    router.get(
        route('pengantin.guests.index'),
        { search: search.value, filter: filter.value },
        { preserveState: true, replace: true }
    )
})

// Guest Form Modal
const showGuestModal = ref(false)
const isEditing = ref(false)
const editingGuestId = ref<number | null>(null)

const guestForm = useForm({
    name: '',
    whatsapp_number: '',
    is_vip: false,
    company_or_address: '',
    relationship: '',
})

const openAddModal = () => {
    isEditing.value = false
    editingGuestId.value = null
    guestForm.reset()
    guestForm.clearErrors()
    showGuestModal.value = true
}

const openEditModal = (guest: any) => {
    isEditing.value = true
    editingGuestId.value = guest.id
    guestForm.name = guest.name
    guestForm.whatsapp_number = guest.whatsapp_number || ''
    guestForm.is_vip = guest.is_vip
    guestForm.company_or_address = guest.company_or_address || ''
    guestForm.relationship = guest.relationship || ''
    guestForm.clearErrors()
    showGuestModal.value = true
}

const saveGuest = () => {
    if (isEditing.value && editingGuestId.value) {
        guestForm.put(route('pengantin.guests.update', editingGuestId.value), {
            onSuccess: () => {
                showGuestModal.value = false
                toast.success('Data tamu berhasil diperbarui')
            },
        })
    } else {
        guestForm.post(route('pengantin.guests.store'), {
            onSuccess: () => {
                showGuestModal.value = false
                toast.success('Tamu baru berhasil ditambahkan')
            },
        })
    }
}

// Delete Confirmation Modal
const showDeleteModal = ref(false)
const guestToDelete = ref<any>(null)

const confirmDelete = (guest: any) => {
    guestToDelete.value = guest
    showDeleteModal.value = true
}

const deleteGuest = () => {
    if (guestToDelete.value) {
        router.delete(route('pengantin.guests.destroy', guestToDelete.value.id), {
            onSuccess: () => {
                showDeleteModal.value = false
                toast.success(`Tamu ${guestToDelete.value.name} berhasil dihapus`)
                guestToDelete.value = null
            }
        })
    }
}

// Import Modal
const showImportModal = ref(false)
const importForm = useForm({
    file: null as File | null,
})

const openImportModal = () => {
    importForm.reset()
    importForm.clearErrors()
    showImportModal.value = true
}

const handleFileUpload = (e: Event) => {
    const target = e.target as HTMLInputElement
    if (target.files && target.files.length > 0) {
        importForm.file = target.files[0]
    }
}

const submitImport = () => {
    importForm.post(route('pengantin.guests.import'), {
        onSuccess: () => {
            showImportModal.value = false
            toast.success('Proses import data sedang berjalan di background')
        }
    })
}

const copyLink = (guest: any) => {
    const url = route('invitation.show', { slug: props.wedding.slug, guest: guest.secure_token })
    navigator.clipboard.writeText(url).then(() => {
        toast.success(`Link undangan ${guest.name} berhasil disalin!`)
    }).catch(err => {
        toast.error('Gagal menyalin link undangan')
    })
}

const sendWhatsapp = (guest: any) => {
    const url = route('invitation.show', { slug: props.wedding.slug, guest: guest.secure_token })
    const text = `Kepada Yth. ${guest.name},\n\nTanpa mengurangi rasa hormat, perkenankan kami mengundang Bapak/Ibu/Saudara/i untuk hadir dan memberikan doa restu pada acara pernikahan kami.\n\nBerikut adalah tautan undangan pernikahan kami:\n${url}\n\nKehadiran Anda adalah suatu kehormatan dan kebahagiaan bagi kami.\n\nTerima kasih.`
    
    let waNumber = guest.whatsapp_number
    if (waNumber) {
        waNumber = waNumber.replace(/[^0-9]/g, '')
        if (waNumber.startsWith('0')) {
            waNumber = '62' + waNumber.substring(1)
        }
        window.open(`https://wa.me/${waNumber}?text=${encodeURIComponent(text)}`, '_blank')
    } else {
        window.open(`https://wa.me/?text=${encodeURIComponent(text)}`, '_blank')
    }
}
</script>

<template>
    <AppLayout title="Buku Tamu">
        <Head title="Buku Tamu" />

        <div class="space-y-6">
            <!-- Header -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-zinc-900 dark:text-zinc-100">Buku Tamu</h2>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Kelola daftar undangan dan pantau kehadiran tamu.</p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <Button variant="outline" as="a" :href="route('pengantin.guests.template')" class="gap-2">
                        <Download class="h-4 w-4" />
                        Template
                    </Button>
                    <Button variant="secondary" @click="openImportModal" class="gap-2 bg-indigo-50 text-indigo-700 hover:bg-indigo-100 border border-indigo-200">
                        <Upload class="h-4 w-4" />
                        Import
                    </Button>
                    <Button @click="openAddModal" class="gap-2">
                        <Plus class="h-4 w-4" />
                        Tambah Tamu
                    </Button>
                </div>
            </div>

            <Card>
                <CardHeader class="p-4 sm:px-6 border-b border-zinc-100 dark:border-zinc-800">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                        <div class="relative flex-1 max-w-sm">
                            <Search class="absolute left-2.5 top-2.5 h-4 w-4 text-zinc-500" />
                            <Input v-model="search" type="search" placeholder="Cari nama tamu..." class="pl-9 bg-zinc-50 dark:bg-zinc-900" />
                        </div>
                        <div class="flex items-center gap-2">
                            <Filter class="h-4 w-4 text-zinc-500" />
                            <select v-model="filter" class="h-10 w-[160px] rounded-md border border-zinc-200 bg-white px-3 py-2 text-sm ring-offset-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-zinc-950 focus-visible:ring-offset-2 dark:border-zinc-800 dark:bg-zinc-950 dark:ring-offset-zinc-950 dark:focus-visible:ring-zinc-300">
                                <option value="all">Semua Tamu</option>
                                <option value="vip">Hanya VIP</option>
                                <option value="regular">Reguler</option>
                            </select>
                        </div>
                    </div>
                </CardHeader>
                <CardContent class="p-0">
                    <Table>
                        <TableHeader class="bg-zinc-50/50 dark:bg-zinc-900/50">
                            <TableRow>
                                <TableHead>Nama Tamu</TableHead>
                                <TableHead>Kontak</TableHead>
                                <TableHead class="text-center">Status</TableHead>
                                <TableHead class="text-right">Aksi</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="guest in guests.data" :key="guest.id">
                                <TableCell>
                                    <div class="font-medium text-zinc-900 dark:text-zinc-100 flex items-center gap-2">
                                        {{ guest.name }}
                                        <ShieldCheck v-if="guest.is_vip" class="h-4 w-4 text-amber-500" />
                                    </div>
                                    <div class="text-sm text-zinc-500">
                                        {{ guest.relationship }} <span v-if="guest.company_or_address">&bull; {{ guest.company_or_address }}</span>
                                    </div>
                                </TableCell>
                                <TableCell>
                                    <span class="text-sm text-zinc-600 dark:text-zinc-400">{{ guest.whatsapp_number || '-' }}</span>
                                </TableCell>
                                <TableCell class="text-center">
                                    <Badge :variant="guest.is_checked_in ? 'default' : 'secondary'" :class="guest.is_checked_in ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-400' : ''">
                                        {{ guest.is_checked_in ? 'Hadir' : 'Belum Hadir' }}
                                    </Badge>
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
                                            <DropdownMenuItem @click="copyLink(guest)" class="cursor-pointer gap-2">
                                                <Copy class="h-4 w-4" />
                                                <span>Salin Link</span>
                                            </DropdownMenuItem>
                                            <DropdownMenuItem @click="sendWhatsapp(guest)" class="cursor-pointer gap-2">
                                                <MessageCircle class="h-4 w-4 text-emerald-500" />
                                                <span>Kirim WhatsApp</span>
                                            </DropdownMenuItem>
                                            <DropdownMenuSeparator />
                                            <DropdownMenuItem @click="openEditModal(guest)" class="cursor-pointer gap-2">
                                                <Edit class="h-4 w-4" />
                                                <span>Edit Tamu</span>
                                            </DropdownMenuItem>
                                            <DropdownMenuItem @click="confirmDelete(guest)" class="cursor-pointer gap-2 text-red-600 dark:text-red-400 focus:text-red-600">
                                                <Trash2 class="h-4 w-4" />
                                                <span>Hapus</span>
                                            </DropdownMenuItem>
                                        </DropdownMenuContent>
                                    </DropdownMenu>
                                </TableCell>
                            </TableRow>
                            <TableRow v-if="guests.data.length === 0">
                                <TableCell colspan="4" class="h-24 text-center text-zinc-500">
                                    Tidak ada tamu ditemukan.
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>

                    <!-- Pagination -->
                    <div class="px-6 py-4 flex items-center justify-between border-t border-zinc-100 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/50" v-if="guests.links.length > 3">
                        <div class="flex-1 flex justify-between sm:hidden">
                            <Button variant="outline" asChild v-if="guests.prev_page_url">
                                <Link :href="guests.prev_page_url">Sebelumnya</Link>
                            </Button>
                            <Button variant="outline" asChild v-if="guests.next_page_url" class="ml-auto">
                                <Link :href="guests.next_page_url">Selanjutnya</Link>
                            </Button>
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-zinc-700 dark:text-zinc-300">
                                    Menampilkan <span class="font-medium">{{ guests.from }}</span> - <span class="font-medium">{{ guests.to }}</span> dari <span class="font-medium">{{ guests.total }}</span> tamu
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex shadow-sm -space-x-px" aria-label="Pagination">
                                    <Link v-for="(link, i) in guests.links" :key="i"
                                        :href="link.url || '#'"
                                        class="relative inline-flex items-center px-4 py-2 border text-sm font-medium first:rounded-l-md last:rounded-r-md"
                                        :class="[
                                            link.active ? 'z-10 bg-zinc-100 border-zinc-300 text-zinc-900 dark:bg-zinc-800 dark:border-zinc-700 dark:text-zinc-100' : 'bg-white border-zinc-300 text-zinc-500 hover:bg-zinc-50 dark:bg-zinc-950 dark:border-zinc-800 dark:text-zinc-400 dark:hover:bg-zinc-900',
                                            !link.url ? 'opacity-50 cursor-not-allowed' : ''
                                        ]"
                                        v-html="link.label"
                                    />
                                </nav>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>

        <!-- Add/Edit Modal -->
        <Dialog :open="showGuestModal" @update:open="(val) => { if (!val) showGuestModal = false }">
            <DialogContent class="sm:max-w-[425px]">
                <DialogHeader>
                    <DialogTitle>{{ isEditing ? 'Edit Data Tamu' : 'Tambah Tamu Baru' }}</DialogTitle>
                    <DialogDescription>
                        Isi detail informasi tamu di bawah ini. Pastikan nama sudah benar.
                    </DialogDescription>
                </DialogHeader>

                <form @submit.prevent="saveGuest" class="space-y-4 py-4">
                    <div class="space-y-2">
                        <Label for="name">Nama Tamu <span class="text-red-500">*</span></Label>
                        <Input id="name" v-model="guestForm.name" placeholder="John Doe" required />
                        <p v-if="guestForm.errors.name" class="text-sm text-red-500">{{ guestForm.errors.name }}</p>
                    </div>

                    <div class="space-y-2">
                        <Label for="whatsapp">Nomor WhatsApp</Label>
                        <Input id="whatsapp" v-model="guestForm.whatsapp_number" placeholder="08123456789" />
                        <p v-if="guestForm.errors.whatsapp_number" class="text-sm text-red-500">{{ guestForm.errors.whatsapp_number }}</p>
                    </div>

                    <div class="flex items-center space-x-2 pt-2">
                        <Checkbox id="is_vip" :checked="guestForm.is_vip" @update:checked="guestForm.is_vip = $event" />
                        <Label for="is_vip" class="font-normal cursor-pointer">Tandai sebagai tamu VIP</Label>
                    </div>

                    <div class="space-y-2 pt-2">
                        <Label for="company">Instansi / Alamat</Label>
                        <Input id="company" v-model="guestForm.company_or_address" placeholder="PT Maju Bersama / Jakarta" />
                    </div>

                    <div class="space-y-2">
                        <Label for="relation">Relasi</Label>
                        <Input id="relation" v-model="guestForm.relationship" placeholder="Teman Kuliah" />
                    </div>

                    <DialogFooter class="pt-4">
                        <Button variant="outline" type="button" @click="showGuestModal = false">Batal</Button>
                        <Button type="submit" :disabled="guestForm.processing">
                            <Loader2 v-if="guestForm.processing" class="mr-2 h-4 w-4 animate-spin" />
                            Simpan
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>

        <!-- Import Modal -->
        <Dialog :open="showImportModal" @update:open="(val) => { if (!val) showImportModal = false }">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Import Data Tamu via Excel</DialogTitle>
                    <DialogDescription>
                        Unggah file Excel (Template) yang telah diisi. Proses ini akan berjalan di belakang layar.
                    </DialogDescription>
                </DialogHeader>

                <form @submit.prevent="submitImport" class="space-y-6 py-4">
                    <div class="grid w-full max-w-sm items-center gap-1.5">
                        <Label for="file">File Excel (.xlsx)</Label>
                        <Input id="file" type="file" @change="handleFileUpload" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" class="cursor-pointer" />
                        <p v-if="importForm.errors.file" class="text-sm text-red-500 mt-1">{{ importForm.errors.file }}</p>
                    </div>

                    <DialogFooter>
                        <Button variant="outline" type="button" @click="showImportModal = false">Batal</Button>
                        <Button type="submit" :disabled="importForm.processing || !importForm.file">
                            <Loader2 v-if="importForm.processing" class="mr-2 h-4 w-4 animate-spin" />
                            Mulai Import
                        </Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>

        <!-- Delete Modal -->
        <Dialog :open="showDeleteModal" @update:open="(val) => { if (!val) showDeleteModal = false }">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Hapus Tamu</DialogTitle>
                    <DialogDescription>
                        Apakah Anda yakin ingin menghapus <b>{{ guestToDelete?.name }}</b> dari daftar tamu? Data yang dihapus tidak dapat dikembalikan.
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter class="mt-4">
                    <Button variant="outline" @click="showDeleteModal = false">Batal</Button>
                    <Button variant="destructive" @click="deleteGuest">Hapus Permanen</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

    </AppLayout>
</template>
