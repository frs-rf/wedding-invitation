<script setup lang="ts">
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import { Button } from '@/Components/ui/button';
import { Input } from '@/Components/ui/input';
import { Label } from '@/Components/ui/label';
import { useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';

const confirmingUserDeletion = ref(false);
const passwordInput = ref<HTMLInputElement | null>(null);

const form = useForm({
    password: '',
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value?.focus());
};

const deleteUser = () => {
    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value?.focus(),
        onFinish: () => {
            form.reset();
        },
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.clearErrors();
    form.reset();
};
</script>

<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-medium text-zinc-900 dark:text-zinc-100">
                Delete Account
            </h2>

            <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                Once your account is deleted, all of its resources and data will
                be permanently deleted. Before deleting your account, please
                download any data or information that you wish to retain.
            </p>
        </header>

        <Button variant="destructive" @click="confirmUserDeletion">Delete Account</Button>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6 bg-white dark:bg-zinc-900 border dark:border-zinc-800 rounded-lg shadow-xl">
                <h2 class="text-lg font-medium text-zinc-900 dark:text-zinc-100">
                    Are you sure you want to delete your account?
                </h2>

                <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                    Once your account is deleted, all of its resources and data
                    will be permanently deleted. Please enter your password to
                    confirm you would like to permanently delete your account.
                </p>

                <div class="mt-6 space-y-2">
                    <Label for="password" class="sr-only">Password</Label>

                    <Input
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="block w-3/4"
                        placeholder="Password"
                        @keyup.enter="deleteUser"
                    />

                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <Button variant="outline" @click="closeModal">
                        Cancel
                    </Button>

                    <Button
                        variant="destructive"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        Delete Account
                    </Button>
                </div>
            </div>
        </Modal>
    </section>
</template>
