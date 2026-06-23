<script setup lang="ts">
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { Head, Link, useForm, router } from '@inertiajs/vue3'
import { ref, watch, onMounted } from 'vue'
import { toast } from 'vue-sonner'
import { usePage } from '@inertiajs/vue3'

const props = defineProps<{
    wedding: any
    guests: any
    filters: any
}>()

const search = ref(props.filters.search || '')
const filter = ref(props.filters.filter || 'all')
const pageProps = usePage().props

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

watch([search, filter], () => {
    router.get(
        route('pengantin.guests.index'),
        { search: search.value, filter: filter.value },
        { preserveState: true, replace: true }
    )
})

// Modals State
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

// Delete
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

// Import
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

// Actions
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

// Dropdown state logic
const activeDropdown = ref<number | null>(null)
const toggleDropdown = (id: number) => {
    activeDropdown.value = activeDropdown.value === id ? null : id
}
</script>

<template>
    <Head title="Buku Tamu" />

    <DashboardLayout>
        <template #sidebar-nav>
            <Link :href="route('dashboard')" class="flex items-center gap-md px-md py-sm text-[#5d5e66] hover:bg-[#eae7eb] rounded-xl transition-all text-[14px]">
                <span class="material-symbols-outlined text-[20px]">dashboard</span>
                <span>Dashboard</span>
            </Link>
            <Link :href="route('pengantin.themes.index')" class="flex items-center gap-md px-md py-sm text-[#5d5e66] hover:bg-[#eae7eb] rounded-xl transition-all text-[14px]">
                <span class="material-symbols-outlined text-[20px]">palette</span>
                <span>Tema & Undangan</span>
            </Link>
            <Link :href="route('pengantin.guests.index')" class="flex items-center gap-md px-md py-sm bg-[#e2dfff] text-[#1f108e] rounded-xl font-bold transition-all text-[14px]">
                <span class="material-symbols-outlined text-[20px]">book</span>
                <span>Buku Tamu</span>
            </Link>
        </template>

        <!-- Header Section -->
        <header class="flex flex-col md:flex-row md:items-end justify-between gap-lg mb-xl">
            <div>
                <h2 class="font-display text-[36px] tracking-tight text-[#1b1b1e] font-bold">Buku Tamu</h2>
                <p class="font-body-md text-[#5d5e66] mt-xs">Kelola daftar undangan dan pantau kehadiran tamu.</p>
            </div>
            <div class="flex gap-md">
                <a :href="route('pengantin.guests.template')" class="flex items-center gap-sm px-lg py-sm border border-[#c8c4d5] rounded-full font-label-sm text-[14px] hover:bg-[#f0edf1] transition-colors text-[#1b1b1e] font-bold">
                    <span class="material-symbols-outlined text-[18px]">download</span>
                    Template
                </a>
                <button @click="openImportModal" class="flex items-center gap-sm px-lg py-sm border border-[#c8c4d5] bg-[#fbf8fc] rounded-full font-label-sm text-[14px] hover:bg-[#f0edf1] transition-colors text-[#1b1b1e] font-bold">
                    <span class="material-symbols-outlined text-[18px]">upload</span>
                    Import
                </button>
                <button @click="openAddModal" class="flex items-center gap-sm px-lg py-sm bg-[#1b1b1e] text-white rounded-full font-label-sm text-[14px] hover:opacity-90 transition-opacity font-bold">
                    <span class="material-symbols-outlined text-[18px]">person_add</span>
                    Tambah Tamu
                </button>
            </div>
        </header>

        <!-- Filter & Search Toolbar -->
        <section class="bg-white border border-[#e4e1e6] rounded-xl p-md mb-lg flex flex-col md:flex-row justify-between items-center gap-md shadow-sm">
            <div class="flex flex-wrap gap-sm w-full md:w-auto">
                <div class="relative flex-1 md:flex-none">
                    <span class="material-symbols-outlined absolute left-sm top-1/2 -translate-y-1/2 text-[#777584]">search</span>
                    <input v-model="search" type="text" placeholder="Cari nama tamu..." class="w-full md:w-64 bg-[#f6f2f7] border border-[#e4e1e6] rounded-lg pl-10 pr-md py-sm font-body-md text-[14px] focus:ring-2 focus:ring-[#1f108e] focus:border-transparent outline-none transition-all">
                </div>
                <select v-model="filter" class="bg-[#f6f2f7] border border-[#e4e1e6] rounded-lg px-md py-sm font-label-sm text-[14px] focus:outline-none focus:ring-2 focus:ring-[#1f108e] cursor-pointer text-[#1b1b1e]">
                    <option value="all">Semua Tamu</option>
                    <option value="vip">Hanya VIP</option>
                    <option value="regular">Reguler</option>
                </select>
            </div>
            
            <div class="flex items-center gap-sm text-[#5d5e66]">
                <span class="font-label-sm text-[14px]">View:</span>
                <button class="p-xs bg-[#f0edf1] rounded text-[#1b1b1e] border border-[#e4e1e6]"><span class="material-symbols-outlined text-[18px]">list</span></button>
                <button class="p-xs hover:bg-[#f6f2f7] rounded transition-colors border border-transparent"><span class="material-symbols-outlined text-[18px]">grid_view</span></button>
            </div>
        </section>

        <!-- Guest Data Table -->
        <section class="bg-[#fcfcfc] border border-[#e4e1e6] rounded-xl overflow-visible shadow-sm flex-1 flex flex-col min-h-[400px]">
            <div class="overflow-visible">
                <table class="w-full text-left border-collapse bg-white">
                    <thead class="bg-[#fbf8fc] border-b border-[#e4e1e6]">
                        <tr>
                            <th class="px-lg py-md font-label-sm text-[12px] font-bold text-[#5d5e66] w-10">
                                <input type="checkbox" class="rounded border-[#c8c4d5] text-[#1b1b1e] cursor-pointer">
                            </th>
                            <th class="px-lg py-md font-label-sm text-[12px] font-bold text-[#5d5e66] uppercase tracking-wider">
                                <div class="flex items-center gap-xs">GUEST NAME <span class="material-symbols-outlined text-[16px]">arrow_drop_down</span></div>
                            </th>
                            <th class="px-lg py-md font-label-sm text-[12px] font-bold text-[#5d5e66] uppercase tracking-wider">ROLE / RELATION</th>
                            <th class="px-lg py-md font-label-sm text-[12px] font-bold text-[#5d5e66] uppercase tracking-wider">CONTACT</th>
                            <th class="px-lg py-md font-label-sm text-[12px] font-bold text-[#5d5e66] uppercase tracking-wider">STATUS</th>
                            <th class="px-lg py-md font-label-sm text-[12px] font-bold text-[#5d5e66] uppercase tracking-wider text-right">ACTIONS</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#e4e1e6]">
                        <tr v-for="guest in guests.data" :key="guest.id" class="hover:bg-[#fbf8fc] transition-colors relative">
                            <td class="px-lg py-md">
                                <input type="checkbox" class="rounded border-[#c8c4d5] text-[#1b1b1e] cursor-pointer">
                            </td>
                            <td class="px-lg py-md">
                                <div class="flex items-center gap-md">
                                    <div class="w-8 h-8 rounded-full bg-[#f0edf1] flex items-center justify-center font-bold text-xs text-[#1b1b1e]">
                                        {{ guest.name.substring(0, 2).toUpperCase() }}
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-sm">
                                            <span class="font-label-sm font-bold text-[#1b1b1e] text-[14px]">{{ guest.name }}</span>
                                            <span v-if="guest.is_vip" class="material-symbols-outlined text-[16px] text-[#752c00]">star</span>
                                        </div>
                                        <p v-if="guest.company_or_address" class="text-[12px] text-[#5d5e66]">{{ guest.company_or_address }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-lg py-md">
                                <span class="px-md py-xs rounded-full text-[10px] font-bold uppercase tracking-wider whitespace-nowrap" :class="guest.is_vip ? 'bg-[#ffdbcc] text-[#752c00]' : 'bg-[#e4e1e6] text-[#464553]'">
                                    {{ guest.is_vip ? 'VIP' : 'Standard' }}
                                </span>
                                <div class="text-[12px] text-[#5d5e66] mt-xs">{{ guest.relationship }}</div>
                            </td>
                            <td class="px-lg py-md">
                                <span class="font-label-sm text-[14px] text-[#5d5e66]">{{ guest.whatsapp_number || '-' }}</span>
                            </td>
                            <td class="px-lg py-md">
                                <div class="flex items-center gap-xs font-bold" :class="guest.is_checked_in ? 'text-green-600' : 'text-[#5d5e66]'">
                                    <span class="material-symbols-outlined text-[16px]">
                                        {{ guest.is_checked_in ? 'check_circle' : 'schedule' }}
                                    </span>
                                    <span class="font-label-sm text-[14px]">{{ guest.is_checked_in ? 'Hadir' : 'Belum Hadir' }}</span>
                                </div>
                            </td>
                            <td class="px-lg py-md text-right relative">
                                <button @click="toggleDropdown(guest.id)" class="p-xs hover:bg-[#e4e1e6] rounded transition-colors text-[#5d5e66]">
                                    <span class="material-symbols-outlined">more_vert</span>
                                </button>
                                
                                <!-- Custom Dropdown Menu -->
                                <div v-if="activeDropdown === guest.id" class="absolute right-0 top-12 w-48 bg-white border border-[#e4e1e6] rounded-xl shadow-lg z-50 py-xs text-left">
                                    <button @click="copyLink(guest); activeDropdown = null" class="w-full text-left px-md py-sm hover:bg-[#fbf8fc] flex items-center gap-sm text-[14px] text-[#1b1b1e]">
                                        <span class="material-symbols-outlined text-[18px]">content_copy</span> Salin Link
                                    </button>
                                    <button @click="sendWhatsapp(guest); activeDropdown = null" class="w-full text-left px-md py-sm hover:bg-[#fbf8fc] flex items-center gap-sm text-[14px] text-green-600 font-bold">
                                        <span class="material-symbols-outlined text-[18px]">chat</span> Kirim WhatsApp
                                    </button>
                                    <div class="h-px bg-[#e4e1e6] my-xs"></div>
                                    <button @click="openEditModal(guest); activeDropdown = null" class="w-full text-left px-md py-sm hover:bg-[#fbf8fc] flex items-center gap-sm text-[14px] text-[#1b1b1e]">
                                        <span class="material-symbols-outlined text-[18px]">edit</span> Edit Tamu
                                    </button>
                                    <button @click="confirmDelete(guest); activeDropdown = null" class="w-full text-left px-md py-sm hover:bg-[#ffdad6] flex items-center gap-sm text-[14px] text-[#ba1a1a] font-bold">
                                        <span class="material-symbols-outlined text-[18px]">delete</span> Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr v-if="guests.data.length === 0">
                            <td colspan="6" class="px-lg py-xl text-center text-[#5d5e66] font-body-md">
                                Tidak ada tamu ditemukan.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination Footer -->
            <div class="mt-auto px-lg py-md border-t border-[#e4e1e6] bg-white flex flex-col sm:flex-row justify-between items-center gap-sm" v-if="guests.links.length > 3">
                <span class="font-label-xs text-[12px] text-[#5d5e66]">
                    Menampilkan <span class="font-bold">{{ guests.from }}</span> - <span class="font-bold">{{ guests.to }}</span> dari <span class="font-bold">{{ guests.total }}</span> tamu
                </span>
                <div class="flex items-center gap-xs">
                    <template v-for="(link, i) in guests.links" :key="i">
                        <Link v-if="link.url" :href="link.url" 
                              class="px-sm py-xs border rounded font-label-sm text-[12px] transition-colors"
                              :class="link.active ? 'bg-[#1b1b1e] text-white border-[#1b1b1e]' : 'bg-white text-[#5d5e66] border-[#c8c4d5] hover:bg-[#f0edf1]'"
                              v-html="link.label"></Link>
                        <span v-else class="px-sm py-xs border border-[#e4e1e6] bg-[#fbf8fc] text-[#c8c4d5] rounded font-label-sm text-[12px]" v-html="link.label"></span>
                    </template>
                </div>
            </div>
        </section>

        <!-- Custom Modals Overlay -->
        <div v-if="showGuestModal || showImportModal || showDeleteModal" class="fixed inset-0 bg-[#1b1b1e]/50 backdrop-blur-sm z-[100] flex items-center justify-center p-md">
            
            <!-- Add/Edit Modal -->
            <div v-if="showGuestModal" class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden flex flex-col max-h-[90vh]">
                <div class="px-xl py-lg border-b border-[#e4e1e6] bg-[#fbf8fc]">
                    <h3 class="font-headline-md text-[20px] font-bold text-[#1b1b1e]">{{ isEditing ? 'Edit Data Tamu' : 'Tambah Tamu Baru' }}</h3>
                    <p class="text-[12px] text-[#5d5e66] mt-xs">Isi detail informasi tamu di bawah ini. Pastikan nama sudah benar.</p>
                </div>
                <div class="p-xl overflow-y-auto">
                    <form @submit.prevent="saveGuest" class="space-y-md">
                        <div class="space-y-xs">
                            <label class="font-label-sm text-[14px] font-bold text-[#1b1b1e]">Nama Tamu <span class="text-[#ba1a1a]">*</span></label>
                            <input v-model="guestForm.name" type="text" placeholder="John Doe" class="w-full border border-[#c8c4d5] rounded-xl px-md py-sm font-body-md focus:ring-2 focus:ring-[#1f108e] outline-none">
                        </div>
                        <div class="space-y-xs">
                            <label class="font-label-sm text-[14px] font-bold text-[#1b1b1e]">Nomor WhatsApp</label>
                            <input v-model="guestForm.whatsapp_number" type="text" placeholder="08123456789" class="w-full border border-[#c8c4d5] rounded-xl px-md py-sm font-body-md focus:ring-2 focus:ring-[#1f108e] outline-none">
                        </div>
                        <div class="flex items-center gap-sm">
                            <input type="checkbox" id="vip" v-model="guestForm.is_vip" class="w-4 h-4 text-[#1f108e] rounded border-[#c8c4d5] focus:ring-[#1f108e]">
                            <label for="vip" class="font-label-sm text-[14px] text-[#1b1b1e] cursor-pointer">Tandai sebagai tamu VIP</label>
                        </div>
                        <div class="space-y-xs">
                            <label class="font-label-sm text-[14px] font-bold text-[#1b1b1e]">Instansi / Alamat</label>
                            <input v-model="guestForm.company_or_address" type="text" placeholder="PT Maju Bersama / Jakarta" class="w-full border border-[#c8c4d5] rounded-xl px-md py-sm font-body-md focus:ring-2 focus:ring-[#1f108e] outline-none">
                        </div>
                        <div class="space-y-xs">
                            <label class="font-label-sm text-[14px] font-bold text-[#1b1b1e]">Relasi</label>
                            <input v-model="guestForm.relationship" type="text" placeholder="Teman Kuliah" class="w-full border border-[#c8c4d5] rounded-xl px-md py-sm font-body-md focus:ring-2 focus:ring-[#1f108e] outline-none">
                        </div>
                    </form>
                </div>
                <div class="px-xl py-md border-t border-[#e4e1e6] bg-[#fbf8fc] flex justify-end gap-sm mt-auto">
                    <button @click="showGuestModal = false" class="px-lg py-sm border border-[#c8c4d5] bg-white rounded-xl font-label-sm text-[14px] font-bold hover:bg-[#f0edf1]">Batal</button>
                    <button @click="saveGuest" :disabled="guestForm.processing" class="px-lg py-sm bg-[#1b1b1e] text-white rounded-xl font-label-sm text-[14px] font-bold hover:opacity-90 flex items-center gap-sm disabled:opacity-50">
                        <span v-if="guestForm.processing" class="material-symbols-outlined animate-spin text-[16px]">progress_activity</span>
                        Simpan
                    </button>
                </div>
            </div>

            <!-- Import Modal -->
            <div v-if="showImportModal" class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden flex flex-col">
                <div class="px-xl py-lg border-b border-[#e4e1e6] bg-[#fbf8fc]">
                    <h3 class="font-headline-md text-[20px] font-bold text-[#1b1b1e]">Import Data Tamu</h3>
                    <p class="text-[12px] text-[#5d5e66] mt-xs">Unggah file Excel (Template) yang telah diisi.</p>
                </div>
                <div class="p-xl">
                    <form @submit.prevent="submitImport" class="space-y-md">
                        <div class="space-y-xs">
                            <label class="font-label-sm text-[14px] font-bold text-[#1b1b1e]">File Excel (.xlsx)</label>
                            <input type="file" @change="handleFileUpload" accept=".csv, .xlsx, .xls" class="w-full border border-[#c8c4d5] rounded-xl px-md py-sm font-body-md focus:ring-2 focus:ring-[#1f108e] outline-none">
                        </div>
                    </form>
                </div>
                <div class="px-xl py-md border-t border-[#e4e1e6] bg-[#fbf8fc] flex justify-end gap-sm mt-auto">
                    <button @click="showImportModal = false" class="px-lg py-sm border border-[#c8c4d5] bg-white rounded-xl font-label-sm text-[14px] font-bold hover:bg-[#f0edf1]">Batal</button>
                    <button @click="submitImport" :disabled="importForm.processing || !importForm.file" class="px-lg py-sm bg-[#1b1b1e] text-white rounded-xl font-label-sm text-[14px] font-bold hover:opacity-90 flex items-center gap-sm disabled:opacity-50">
                        <span v-if="importForm.processing" class="material-symbols-outlined animate-spin text-[16px]">progress_activity</span>
                        Mulai Import
                    </button>
                </div>
            </div>

            <!-- Delete Modal -->
            <div v-if="showDeleteModal" class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden flex flex-col">
                <div class="px-xl py-lg border-b border-[#e4e1e6] bg-[#ffdad6]">
                    <h3 class="font-headline-md text-[20px] font-bold text-[#ba1a1a]">Hapus Tamu</h3>
                </div>
                <div class="p-xl">
                    <p class="text-[14px] text-[#1b1b1e]">Apakah Anda yakin ingin menghapus <b class="font-bold">{{ guestToDelete?.name }}</b> dari daftar tamu?</p>
                    <p class="text-[12px] text-[#5d5e66] mt-xs">Data yang dihapus tidak dapat dikembalikan.</p>
                </div>
                <div class="px-xl py-md border-t border-[#e4e1e6] bg-[#fbf8fc] flex justify-end gap-sm mt-auto">
                    <button @click="showDeleteModal = false" class="px-lg py-sm border border-[#c8c4d5] bg-white rounded-xl font-label-sm text-[14px] font-bold hover:bg-[#f0edf1]">Batal</button>
                    <button @click="deleteGuest" class="px-lg py-sm bg-[#ba1a1a] text-white rounded-xl font-label-sm text-[14px] font-bold hover:opacity-90">Hapus Permanen</button>
                </div>
            </div>

        </div>
    </DashboardLayout>
</template>
