<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue'
import { Head, Link, useForm, router } from '@inertiajs/vue3'
import Modal from '@/Components/Modal.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import DangerButton from '@/Components/DangerButton.vue'
import TextInput from '@/Components/TextInput.vue'
import InputLabel from '@/Components/InputLabel.vue'
import InputError from '@/Components/InputError.vue'
import Checkbox from '@/Components/Checkbox.vue'
import { ref, watch } from 'vue'

const props = defineProps<{
    wedding: any
    guests: any
    filters: any
}>()

const search = ref(props.filters.search || '')
const filter = ref(props.filters.filter || 'all')

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

const closeGuestModal = () => {
    showGuestModal.value = false
}

const saveGuest = () => {
    if (isEditing.value && editingGuestId.value) {
        guestForm.put(route('pengantin.guests.update', editingGuestId.value), {
            onSuccess: () => closeGuestModal(),
        })
    } else {
        guestForm.post(route('pengantin.guests.store'), {
            onSuccess: () => closeGuestModal(),
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
        }
    })
}

const copyLink = (guest: any) => {
    const url = route('invitation.show', { slug: props.wedding.slug, guest: guest.secure_token })
    navigator.clipboard.writeText(url).then(() => {
        alert('Link undangan berhasil disalin!')
    }).catch(err => {
        console.error('Gagal menyalin link: ', err)
        alert('Gagal menyalin link. Silakan salin manual: ' + url)
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

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                
                <!-- Status Flash -->
                <div v-if="$page.props.flash?.success" class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                    {{ $page.props.flash.success }}
                </div>

                <!-- Header Actions -->
                <div class="flex flex-col sm:flex-row justify-between items-center mb-6 gap-4">
                    <h2 class="text-2xl font-bold text-gray-900">Manajemen Buku Tamu</h2>
                    <div class="flex space-x-2">
                        <PrimaryButton @click="openAddModal">Tambah Tamu</PrimaryButton>
                        <SecondaryButton @click="openImportModal">Import Excel</SecondaryButton>
                        <a :href="route('pengantin.guests.template')" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                            Template
                        </a>
                    </div>
                </div>

                <!-- Filters -->
                <div class="bg-white p-4 shadow-sm sm:rounded-lg mb-6 flex gap-4 items-center">
                    <div class="flex-1">
                        <TextInput type="text" class="block w-full" placeholder="Cari nama tamu..." v-model="search" />
                    </div>
                    <div>
                        <select v-model="filter" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="all">Semua Tamu</option>
                            <option value="vip">Hanya VIP</option>
                            <option value="regular">Reguler</option>
                        </select>
                    </div>
                </div>

                <!-- Table -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. WhatsApp</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">VIP</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status Hadir</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="guest in guests.data" :key="guest.id">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ guest.name }}</div>
                                        <div class="text-xs text-gray-500">{{ guest.relationship }} <span v-if="guest.company_or_address">- {{ guest.company_or_address }}</span></div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ guest.whatsapp_number || '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                        <span v-if="guest.is_vip" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">VIP</span>
                                        <span v-else>-</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-500">
                                        <span v-if="guest.is_checked_in" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Sudah Hadir</span>
                                        <span v-else class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Belum</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button @click="copyLink(guest)" class="text-blue-600 hover:text-blue-900 mr-3" title="Salin Link Undangan">Salin Link</button>
                                        <button @click="sendWhatsapp(guest)" class="text-green-600 hover:text-green-900 mr-3" title="Kirim via WhatsApp">WhatsApp</button>
                                        <button @click="openEditModal(guest)" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                        <button @click="confirmDelete(guest)" class="text-red-600 hover:text-red-900">Hapus</button>
                                    </td>
                                </tr>
                                <tr v-if="guests.data.length === 0">
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada data tamu.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="px-6 py-3 flex items-center justify-between border-t border-gray-200" v-if="guests.links.length > 3">
                        <div class="flex-1 flex justify-between sm:hidden">
                            <Link :href="guests.prev_page_url" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50" v-if="guests.prev_page_url">Sebelumnya</Link>
                            <Link :href="guests.next_page_url" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50" v-if="guests.next_page_url">Selanjutnya</Link>
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Menampilkan <span class="font-medium">{{ guests.from }}</span> sampai <span class="font-medium">{{ guests.to }}</span> dari <span class="font-medium">{{ guests.total }}</span> tamu
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                    <Link v-for="(link, i) in guests.links" :key="i"
                                        :href="link.url || '#'"
                                        class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                                        :class="[
                                            link.active ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                                            !link.url ? 'opacity-50 cursor-not-allowed' : ''
                                        ]"
                                        v-html="link.label"
                                    />
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- Add/Edit Modal -->
        <Modal :show="showGuestModal" @close="closeGuestModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">
                    {{ isEditing ? 'Edit Data Tamu' : 'Tambah Tamu Baru' }}
                </h2>

                <form @submit.prevent="saveGuest" class="space-y-4">
                    <div>
                        <InputLabel for="name" value="Nama Tamu *" />
                        <TextInput id="name" type="text" class="mt-1 block w-full" v-model="guestForm.name" required />
                        <InputError class="mt-2" :message="guestForm.errors.name" />
                    </div>

                    <div>
                        <InputLabel for="whatsapp" value="Nomor WhatsApp (Opsional)" />
                        <TextInput id="whatsapp" type="text" class="mt-1 block w-full" v-model="guestForm.whatsapp_number" placeholder="Contoh: 0812..." />
                        <InputError class="mt-2" :message="guestForm.errors.whatsapp_number" />
                    </div>

                    <div class="block mt-4">
                        <label class="flex items-center">
                            <Checkbox name="is_vip" v-model:checked="guestForm.is_vip" />
                            <span class="ml-2 text-sm text-gray-600">Tandai sebagai tamu VIP</span>
                        </label>
                    </div>

                    <div>
                        <InputLabel for="company" value="Instansi / Alamat (Opsional)" />
                        <TextInput id="company" type="text" class="mt-1 block w-full" v-model="guestForm.company_or_address" />
                        <InputError class="mt-2" :message="guestForm.errors.company_or_address" />
                    </div>

                    <div>
                        <InputLabel for="relation" value="Relasi (Opsional)" />
                        <TextInput id="relation" type="text" class="mt-1 block w-full" v-model="guestForm.relationship" placeholder="Contoh: Teman Kantor" />
                        <InputError class="mt-2" :message="guestForm.errors.relationship" />
                    </div>

                    <div class="mt-6 flex justify-end">
                        <SecondaryButton @click="closeGuestModal" class="mr-3">Batal</SecondaryButton>
                        <PrimaryButton :class="{ 'opacity-25': guestForm.processing }" :disabled="guestForm.processing">
                            Simpan
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- Import Modal -->
        <Modal :show="showImportModal" @close="showImportModal = false">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Import Data Tamu via Excel</h2>
                <div class="mb-4 text-sm text-gray-600">
                    <p>Pastikan Anda telah mengisi data sesuai dengan <b>Template_Tamu.xlsx</b>.</p>
                    <p>Proses ini akan berjalan di latar belakang (Queue), sehingga sangat aman untuk ratusan baris data.</p>
                </div>
                
                <form @submit.prevent="submitImport" class="space-y-4">
                    <div>
                        <input type="file" @change="handleFileUpload" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" />
                        <InputError class="mt-2" :message="importForm.errors.file" />
                    </div>

                    <div class="mt-6 flex justify-end">
                        <SecondaryButton @click="showImportModal = false" class="mr-3">Batal</SecondaryButton>
                        <PrimaryButton :class="{ 'opacity-25': importForm.processing }" :disabled="importForm.processing || !importForm.file">
                            Mulai Import
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

        <!-- Delete Modal -->
        <Modal :show="showDeleteModal" @close="showDeleteModal = false">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Hapus Tamu</h2>
                <p class="text-sm text-gray-600">Apakah Anda yakin ingin menghapus data tamu <b v-if="guestToDelete">{{ guestToDelete.name }}</b>? Tindakan ini tidak dapat dibatalkan.</p>
                
                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="showDeleteModal = false" class="mr-3">Batal</SecondaryButton>
                    <DangerButton @click="deleteGuest">Hapus Permanen</DangerButton>
                </div>
            </div>
        </Modal>

    </AppLayout>
</template>
