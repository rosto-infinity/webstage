<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { watch } from 'vue';

interface PresenceForm {
    user_id: number | null;
    date: string;
    heure_arrivee: string | null;
    heure_depart: string | null;
    minutes_retard: number | null;
    absent: boolean;
    en_retard: boolean;
}

const { props } = usePage<{
    presence: PresenceForm;
    users: Array<{ id: number; name: string; email: string }>;
}>();

// Initialisation du formulaire avec les données existantes
const form = useForm<PresenceForm>({
    user_id: props.presence.user_id,
    date: props.presence.date,
    heure_arrivee: props.presence.heure_arrivee,
    heure_depart: props.presence.heure_depart,
    minutes_retard: props.presence.minutes_retard,
    absent: props.presence.absent,
    en_retard: props.presence.en_retard,
});

// Logique métier pour le calcul du retard
watch(
    () => form.heure_arrivee,
    (newValue) => {
        const normalTime = new Date(`1970-01-01T08:00:00`);
        const arrivalTime = new Date(`1970-01-01T${newValue}:00`);
        const delay = (arrivalTime.getTime() - normalTime.getTime()) / (1000 * 60); // Convertir en minutes
        form.minutes_retard = delay > 0 ? delay : 0;
    },
);

// Soumission du formulaire
const submit = () => {
    form.put(route('presences.update', props.presence.id), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
    });
};

// Configuration UI
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Présences Sup_Admin', href: route('presences') },
    { title: 'Édition Présences : Sup_Admin', href: route('presences.edit', props.presence.id) },
];
</script>

<template>
    <Head title="Modifier la présence" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto max-w-7xl p-6">
            <Link
                :href="route('presences')"
                prefetch
                class="mb-6 inline-flex items-center gap-2 rounded-lg bg-muted px-4 py-2 text-foreground transition-colors hover:bg-muted/80"
            >
                Retour à la liste
            </Link>

            <div class="rounded-xl border border-border bg-card p-6 shadow-sm">
                <h2 class="mb-6 border-b border-border pb-4 text-2xl font-bold text-foreground">Modifier la fiche de présence</h2>

                <form @submit.prevent="submit" class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div class="space-y-4 rounded-lg bg-muted p-4 md:col-span-2">
                        <h3 class="font-medium text-foreground">Informations étudiant</h3>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-foreground">Étudiant</label>
                            <select
                                v-model="form.user_id"
                                class="w-full rounded-lg border border-input px-4 py-2 focus:ring-2 focus:ring-ring"
                                :class="{ 'border-destructive': form.errors.user_id }"
                            >
                                <option value="" disabled>Sélectionnez un étudiant</option>
                                <option v-for="user in props.users" :key="user.id" :value="user.id">{{ user.name }} ({{ user.email }})</option>
                            </select>
                            <p v-if="form.errors.user_id" class="text-sm text-destructive">{{ form.errors.user_id }}</p>
                        </div>
                    </div>

                    <div class="space-y-4 rounded-lg bg-muted p-4">
                        <h3 class="font-medium text-foreground">Horaires</h3>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-foreground">Date</label>
                            <input
                                v-model="form.date"
                                type="date"
                                class="w-full rounded-lg border border-input px-4 py-2 focus:ring-2 focus:ring-ring"
                                :class="{ 'border-destructive': form.errors.date }"
                            />
                            <p v-if="form.errors.date" class="text-sm text-destructive">{{ form.errors.date }}</p>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-foreground">Heure d'arrivée</label>
                            <input
                                v-model="form.heure_arrivee"
                                type="time"
                                class="w-full rounded-lg border border-input px-4 py-2 focus:ring-2 focus:ring-ring"
                                :class="{ 'border-destructive': form.errors.heure_arrivee }"
                            />
                            <p v-if="form.errors.heure_arrivee" class="text-sm text-destructive">{{ form.errors.heure_arrivee }}</p>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-foreground">Heure de départ</label>
                            <input
                                v-model="form.heure_depart"
                                type="time"
                                class="w-full rounded-lg border border-input px-4 py-2 focus:ring-2 focus:ring-ring"
                                :class="{ 'border-destructive': form.errors.heure_depart }"
                            />
                            <p v-if="form.errors.heure_depart" class="text-sm text-destructive">{{ form.errors.heure_depart }}</p>
                        </div>
                    </div>

                    <div class="space-y-4 rounded-lg bg-muted p-4">
                        <h3 class="font-medium text-foreground">Statut</h3>

                        <div>
                            <label class="mb-1 flex items-center gap-2 text-sm font-medium text-foreground">
                                <input v-model="form.absent" type="checkbox" class="rounded border-input text-primary focus:ring-ring" />
                                Absent(e)
                            </label>
                        </div>

                        <div>
                            <label class="mb-1 flex items-center gap-2 text-sm font-medium text-foreground">
                                <input v-model="form.en_retard" type="checkbox" class="rounded border-input text-primary focus:ring-ring" />
                                En retard
                            </label>
                        </div>

                        <div v-if="form.en_retard">
                            <label class="mb-1 block text-sm font-medium text-foreground">Minutes de retard</label>
                            <input
                                v-model.number="form.minutes_retard"
                                type="number"
                                min="0"
                                step="1"
                                class="w-full rounded-lg border border-input px-4 py-2 focus:ring-2 focus:ring-ring"
                                :class="{ 'border-destructive': form.errors.minutes_retard }"
                            />
                            <p v-if="form.errors.minutes_retard" class="text-sm text-destructive">{{ form.errors.minutes_retard }}</p>
                        </div>
                    </div>

                    <div class="flex justify-end gap-4 border-t border-border pt-4 md:col-span-2">
                        <Link
                            :href="route('presences')"
                            class="rounded-lg border border-input px-6 py-2 text-foreground transition-colors hover:bg-muted"
                        >
                            Annuler
                        </Link>
                        <button type="submit" class="rounded-lg bg-primary px-6 py-2 text-primary-foreground transition-colors hover:bg-primary/90">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>
