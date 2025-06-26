<script setup lang="ts">
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';

// import DeleteUser from '@/components/DeleteUser.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsLayout from '@/layouts/settings/Layout.vue';
import { type BreadcrumbItem, type User } from '@/types';

interface Props {
    mustVerifyEmail: boolean;
    status?: string;
}

defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Profile settings',
        href: '/settings/profile',
    },
];

const page = usePage();
const user = page.props.auth.user as User;

const form = useForm({
    name: user.name,
    email: user.email,
    avatar: null as File | null,
});

const submit = () => {
    form.transform(data => ({
        ...data,
        _method: 'PATCH' //
    })).post(route('profile.update'), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            form.avatar = null;
            form.reset('avatar');
        },
    });
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <Head title="Profile settings" />

        <SettingsLayout>

             <!-- Avatar display section -->
            <div class="flex flex-col items-center mb-6">
                <div
                    class="relative group w-32 h-32 rounded-full border-4 border-primary bg-gradient-to-br from-[#e0f7ef] to-[#b6b2ff] shadow-lg overflow-hidden transition-all duration-300">
                    <img v-if="user.avatar" :src="`/storage/${user.avatar}`" alt="Avatar"
                        class="w-full h-full object-cover rounded-full transition-transform duration-300 group-hover:scale-105" />


                    <div v-else
                        class="w-full h-full flex items-center justify-center bg-[#b6b2ff] text-primaborder-primary text-5xl font-bold rounded-full">
                        {{ user.name.charAt(0).toUpperCase() }}
                    </div>
                    <div
                        class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity duration-300">
                        <span class="text-white text-xs font-semibold">
                           
                            Changer l'avatar
                        </span>
                    </div>
                </div>
            </div>
            <div class="flex flex-col space-y-6">
                <HeadingSmall title="Profile information" description="Update your name and email address" />

                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid gap-2">
                        <Label for="name">Name</Label>
                        <Input id="name" class="mt-1 block w-full" v-model="form.name" required autocomplete="name" placeholder="Full name" />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <div class="grid gap-2">
                        <Label for="email">Email address</Label>
                        <Input
                            id="email"
                            type="email"
                            class="mt-1 block w-full"
                            v-model="form.email"
                            required
                            autocomplete="username"
                            placeholder="Email address"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <div v-if="mustVerifyEmail && !user.email_verified_at">
                        <p class="-mt-4 text-sm text-muted-foreground">
                            Your email address is unverified.
                            <Link
                                :href="route('verification.send')"
                                method="post"
                                as="button"
                                class="text-foreground underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500"
                            >
                                Click here to resend the verification email.
                            </Link>
                        </p>

                        <div v-if="status === 'verification-link-sent'" class="mt-2 text-sm font-medium text-green-600">
                            A new verification link has been sent to your email address.
                        </div>
                    </div>

                    <div class="grid gap-2">
                            <Label for="avatar">Avatar</Label>
                            <Input type="file" id="avatar" accept="image/*"  
                                @change="e => form.avatar = e.target.files?.[0] || null" class="w-full" />
                            <InputError :message="form.errors.avatar" />
                    </div>

                    <div class="flex items-center gap-4">
                        <Button :disabled="form.processing">Save</Button>

                        <Transition
                            enter-active-class="transition ease-in-out"
                            enter-from-class="opacity-0"
                            leave-active-class="transition ease-in-out"
                            leave-to-class="opacity-0"
                        >
                            <p v-show="form.recentlySuccessful" class="text-sm text-neutral-600">Saved.</p>
                        </Transition>
                    </div>
                </form>
            </div>

            <!-- <DeleteUser /> -->
        </SettingsLayout>
    </AppLayout>
</template>
