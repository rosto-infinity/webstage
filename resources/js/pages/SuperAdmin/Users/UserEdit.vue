<script setup lang="ts">
import InputError from '@/components/InputError.vue';
import { Card } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';

// Typage TypeScript strict
interface User {
    id: number | string;
    name: string;
    email: string;
}

const props = defineProps<{
    user: User;
}>();

// Configuration des breadcrumbs
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Utilisateurs Admin', href: '/users' },
    { title: `Modifier utilisateur : ${props.user.name}`, href: '' },
];

// Initialisation du formulaire avec typage
const form = useForm({
    name: props.user.name,
    email: props.user.email,
    password: '',
    password_confirmation: '',
});

// Soumission du formulaire
const submit = () => {
    form.put(route('users.update', props.user.id), {
        preserveScroll: true,
        onSuccess: () => form.reset('password', 'password_confirmation'),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
            }
        },
    });
};

// Réinitialisation contrôlée
const resetForm = () => {
    form.reset();
    form.clearErrors();
};
</script>

<template>
    <Head :title="`Modifier ${user.name}`" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-col gap-6 p-6">
            <header class="space-y-1">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Modifier l'utilisateur</h1>
                <p class="text-sm text-muted-foreground">ID: {{ user.id }} • Dernière modification: {{ new Date().toLocaleDateString() }}</p>
            </header>

            <Card class="p-6">
                <form @submit.prevent="submit" class="space-y-6">
                    <div class="grid gap-4">
                        <!-- Champ Nom -->
                        <div class="space-y-2">
                            <Label for="name">Nom complet</Label>
                            <Input id="name" v-model="form.name" placeholder="Jean Dupont" :disabled="form.processing" />
                            <InputError :message="form.errors.name" />
                        </div>

                        <!-- Champ Email -->
                        <div class="space-y-2">
                            <Label for="email">Adresse email</Label>
                            <Input id="email" type="email" v-model="form.email" placeholder="email@exemple.com" :disabled="form.processing" />
                            <InputError :message="form.errors.email" />
                        </div>

                        <!-- Section Mot de passe -->
                        <div class="space-y-4 border-t pt-4">
                            <div class="space-y-1">
                                <h3 class="text-lg font-medium">Changer le mot de passe</h3>
                                <p class="text-sm text-muted-foreground">Laissez vide pour conserver le mot de passe actuel</p>
                            </div>

                            <div class="grid gap-4 md:grid-cols-2">
                                <!-- Nouveau mot de passe -->
                                <div class="space-y-2">
                                    <Label for="password">Nouveau mot de passe</Label>
                                    <Input id="password" type="password" v-model="form.password" :disabled="form.processing" />
                                    <InputError :message="form.errors.password" />
                                </div>

                                <!-- Confirmation -->
                                <div class="space-y-2">
                                    <Label for="password_confirmation">Confirmation</Label>
                                    <Input
                                        id="password_confirmation"
                                        type="password"
                                        v-model="form.password_confirmation"
                                        :disabled="form.processing"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex justify-end gap-3 pt-2">
                        <button
                            type="button"
                            @click="resetForm"
                            class="rounded-md bg-secondary px-4 py-2 text-sm font-medium text-secondary-foreground shadow-sm hover:bg-secondary/80"
                            :disabled="form.processing"
                        >
                            Réinitialiser
                        </button>
                        <button
                            type="submit"
                            class="rounded-md bg-primary px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-primary/90 focus-visible:ring-2 focus-visible:ring-primary focus-visible:outline-none"
                            :disabled="form.processing"
                        >
                            <span v-if="form.processing">Enregistrement...</span>
                            <span v-else>Mettre à jour</span>
                        </button>
                    </div>
                </form>
            </Card>
        </div>
    </AppLayout>
</template>
