<script setup lang="ts">
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import type { BreadcrumbItem, SharedData, User } from '@/types';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

interface Props {
    mustVerifyEmail: boolean;
    status?: string;
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Profile settings', href: '/settings/profile' }];

const page = usePage<SharedData>();
const user = page.props.auth.user as User;

const avatarPreview = ref<string | null>(null);

const form = useForm({
    name: user.name,
    email: user.email,
    avatar: null as File | null,
});

function handleAvatarChange(e: Event) {
    const input = e.target as HTMLInputElement;
    if (input.files?.[0]) {
        const file = input.files[0];
        form.avatar = file;
        const reader = new FileReader();
        reader.onload = () => {
            avatarPreview.value = reader.result as string;
        };
        reader.readAsDataURL(file);
    }
}

function submit() {
    form.transform((data) => ({ ...data, _method: 'PATCH' })).post(route('profile.update'), {
        forceFormData: true,
        preserveScroll: true,
        preserveState: false, // <-- force reload du composant
        onSuccess: () => {
            avatarPreview.value = null;
            form.reset('avatar');
        },
    });
}
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Profile settings" />

        <SettingsLayout>
            <div class="mb-6 flex flex-col items-center">
                <div
                    class="group relative h-32 w-32 overflow-hidden rounded-full border-4 border-[#654bc3] bg-gradient-to-br from-[#e0f7ef] to-[#f8fafc] shadow-lg transition-all duration-300"
                >
                    <template v-if="avatarPreview">
                        <img
                            :src="avatarPreview"
                            alt="Preview avatar"
                            class="h-full w-full rounded-full object-cover transition-transform duration-300 group-hover:scale-105"
                        />
                    </template>
                    <template v-else-if="user.avatar">
                        <img
                            :src="`/storage/${user.avatar}`"
                            alt="Current avatar"
                            class="h-full w-full rounded-full object-cover transition-transform duration-300 group-hover:scale-105"
                        />
                    </template>
                    <template v-else>
                        <div class="flex h-full w-full items-center justify-center rounded-full bg-[#e0f7ef] text-5xl font-bold text-[#654bc3]">
                            {{ user.name.charAt(0).toUpperCase() }}
                        </div>
                    </template>
                    <div
                        class="absolute inset-0 flex items-center justify-center bg-black/30 opacity-0 transition-opacity duration-300 group-hover:opacity-100"
                    >
                        <span class="text-xs font-semibold text-white">Changer l’avatar</span>
                    </div>
                </div>
                <p v-if="avatarPreview" class="mt-2 text-sm text-muted-foreground">Prévisualisation du nouvel avatar</p>
            </div>

            <div class="flex flex-col space-y-6">
                <HeadingSmall title="Profile information" description="Update your name and email address" />

                <form @submit.prevent="submit" enctype="multipart/form-data" class="grid gap-4">
                    <!-- Champ Nom -->
                    <div class="grid gap-2">
                        <Label for="name">Nom</Label>
                        <Input id="name" type="text" v-model="form.name" class="w-full" />
                        <InputError :message="form.errors.name" />
                    </div>

                    <!-- Champ Email -->
                    <div class="grid gap-2">
                        <Label for="email">Email</Label>
                        <Input id="email" type="email" v-model="form.email" class="w-full" />
                        <InputError :message="form.errors.email" />
                    </div>

                    <!-- Champ Avatar -->
                    <div class="grid gap-2">
                        <Label for="avatar">Avatar</Label>
                        <Input id="avatar" type="file" accept="image/*" @change="handleAvatarChange" class="w-full" />
                        <InputError :message="form.errors.avatar" />
                    </div>

                    <div class="flex items-center gap-4 pt-4">
                        <Button type="submit" :disabled="form.processing"> Enregistrer les modifications </Button>
                        <transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-to-class="opacity-0"
                        >
                            <p v-if="form.recentlySuccessful" class="text-sm text-muted-foreground">Sauvegardé.</p>
                        </transition>
                    </div>
                </form>

                <!-- <DeleteUser /> -->
            </div>
        </SettingsLayout>
    </AppLayout>
</template>
