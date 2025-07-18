<template>
    <Head title="Ajouter une présence" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto max-w-7xl p-6">
            <!-- Bouton de retour -->
            <Link
                :href="route('presences')"
                prefetch
                class="mb-6 inline-flex items-center gap-2 rounded-lg bg-muted px-4 py-2 text-foreground transition-colors hover:bg-muted/80"
            >
                <ArrowLeft class="h-4 w-4" />
                Retour à la liste
            </Link>

            <!-- Carte principale -->
            <div class="rounded-xl border bg-card p-6 shadow-sm">
                <h2 class="mb-6 border-b pb-4 text-2xl font-bold text-foreground">Nouvelle fiche de présence</h2>

                <!-- Formulaire réorganisé horizontalement -->
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Section Étudiant -->
                    <div class="space-y-4 rounded-lg bg-muted p-4">
                        <h3 class="font-medium text-foreground">Informations étudiant</h3>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-foreground">Étudiant</label>
                            <select
                                v-model="form.user_id"
                                class="w-full rounded-lg border px-4 py-2 focus:border-primary focus:ring-2 focus:ring-primary"
                                :class="{ 'border-destructive': form.errors.user_id }"
                            >
                                <option value="" disabled>Sélectionnez un étudiant</option>
                                <option v-for="user in props.users" :key="user.id" :value="user.id">{{ user.name }} ({{ user.email }})</option>
                            </select>
                            <p v-if="form.errors.user_id" class="mt-1 text-sm text-destructive">
                                {{ form.errors.user_id }}
                            </p>
                        </div>
                    </div>

                    <!-- Conteneur horizontal -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <!-- Section Horaires -->
                        <div class="space-y-4 rounded-lg bg-muted p-4">
                            <h3 class="font-medium text-foreground">Horaires</h3>

                            <div>
                                <label class="mb-1 block text-sm font-medium text-foreground">Date</label>
                                <input
                                    v-model="form.date"
                                    type="date"
                                    class="w-full rounded-lg border px-4 py-2 focus:ring-2 focus:ring-primary"
                                    :class="{ 'border-destructive': form.errors.date }"
                                />
                                <p v-if="form.errors.date" class="mt-1 text-sm text-destructive">
                                    {{ form.errors.date }}
                                </p>
                            </div>

                            <div>
                                <label class="mb-1 block text-sm font-medium text-foreground">Arrivée</label>
                                <input
                                    v-model="form.heure_arrivee"
                                    type="time"
                                    :disabled="form.absent"
                                    class="w-full rounded-lg border px-4 py-2 focus:ring-2 focus:ring-primary"
                                    :class="{ 'border-destructive': form.errors.heure_arrivee, 'bg-muted/50': form.absent }"
                                />
                                <p v-if="form.errors.heure_arrivee" class="mt-1 text-sm text-destructive">
                                    {{ form.errors.heure_arrivee }}
                                </p>
                            </div>

                            <div>
                                <label class="mb-1 block text-sm font-medium text-foreground">Départ</label>
                                <input
                                    v-model="form.heure_depart"
                                    type="time"
                                    :disabled="form.absent"
                                    :min="form.heure_arrivee"
                                    class="w-full rounded-lg border px-4 py-2 focus:ring-2 focus:ring-primary"
                                    :class="{ 'border-destructive': form.errors.heure_depart, 'bg-muted/50': form.absent }"
                                />
                                <p v-if="form.errors.heure_depart" class="mt-1 text-sm text-destructive">
                                    {{ form.errors.heure_depart }}
                                </p>
                            </div>
                        </div>

                        <!-- Section Statut -->
                        <div class="space-y-4 rounded-lg bg-muted p-4">
                            <h3 class="font-medium text-foreground">Statut</h3>

                            <div class="space-y-4">
                                <div>
                                    <label class="mb-1 flex items-center gap-2 text-sm font-medium text-foreground">
                                        <input v-model="form.absent" type="checkbox" class="rounded border-primary text-primary focus:ring-primary" />
                                        Absent(e)
                                    </label>
                                </div>

                                <div v-if="form.absent">
                                    <label class="mb-1 block text-sm font-medium text-foreground">Motif d'absence</label>
                                    <select
                                        v-model="form.absence_reason_id"
                                        class="w-full rounded-lg border px-4 py-2 focus:ring-2 focus:ring-primary"
                                        :class="{ 'border-destructive': form.errors.absence_reason_id }"
                                    >
                                        <option value="" disabled>Sélectionnez un motif</option>
                                        <option v-for="reason in props.absenceReasons" :key="reason.id" :value="reason.id">
                                            {{ reason.name }}
                                        </option>
                                    </select>
                                    <p v-if="form.errors.absence_reason_id" class="mt-1 text-sm text-destructive">
                                        {{ form.errors.absence_reason_id }}
                                    </p>
                                </div>

                                <div>
                                    <label class="mb-1 flex items-center gap-2 text-sm font-medium text-foreground">
                                        <input
                                            v-model="form.en_retard"
                                            type="checkbox"
                                            :disabled="form.absent"
                                            class="rounded border-primary text-primary focus:ring-primary"
                                            :class="{ 'opacity-50': form.absent }"
                                        />
                                        En retard
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Section Détails retard -->
                        <div class="space-y-4 rounded-lg bg-muted p-4">
                            <h3 class="font-medium text-foreground">Détails</h3>

                            <div v-if="form.en_retard && !form.absent">
                                <label class="mb-1 block text-sm font-medium text-foreground">Minutes de retard</label>
                                <input
                                    v-model.number="form.minutes_retard"
                                    type="number"
                                    readonly
                                    class="w-full cursor-not-allowed rounded-lg border bg-muted/50 px-4 py-2 text-muted-foreground"
                                />
                                <p class="mt-1 text-xs text-muted-foreground">Calculé automatiquement</p>
                            </div>

                            <!-- Affichage du retard calculé -->
                            <div v-if="form.heure_arrivee && !form.absent" class="mt-4 rounded-lg border border-primary bg-primary/10 p-3">
                                <div class="mb-2 flex items-center gap-2">
                                    <Clock class="h-4 w-4 text-primary" />
                                    <span class="text-sm font-medium text-primary">Calcul du retard</span>
                                </div>
                                <div class="space-y-1 text-sm text-primary">
                                    <div>Normale: <strong>8h00</strong></div>
                                    <div>
                                        Arrivée: <strong>{{ form.heure_arrivee }}</strong>
                                    </div>
                                    <div>
                                        Retard: <strong>{{ formatDelay(calculatedDelay) }}</strong>
                                    </div>
                                </div>
                            </div>

                            <!-- Espace supplémentaire pour équilibrer visuellement -->
                            <div v-if="!form.en_retard || form.absent" class="flex h-full items-start">
                                <div class="bg-success/10 border-success w-full rounded-lg border p-3">
                                    <p class="text-success text-sm">
                                        Tous les champs marqués d'un astérisque (<span class="text-destructive">*</span>) sont obligatoires
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-4 border-t pt-4">
                        <Link :href="route('presences')" class="rounded-lg border px-6 py-2 text-foreground transition-colors hover:bg-muted">
                            Annuler
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="flex items-center gap-2 rounded-lg bg-primary px-6 py-2 text-primary-foreground transition-colors hover:bg-primary/90 disabled:bg-primary/60"
                        >
                            <span v-if="form.processing">Enregistrement...</span>
                            <span v-else>Enregistrer</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { ArrowLeft, Clock } from 'lucide-vue-next';
import { computed, watch } from 'vue';

// Typage TypeScript
interface PresenceForm {
    user_id: number | null;
    date: string;
    heure_arrivee: string;
    heure_depart: string;
    minutes_retard: number | null;
    absent: boolean;
    en_retard: boolean;
    absence_reason_id: number | null;
}

// Initialisation du formulaire
const form = useForm<PresenceForm>({
    user_id: null,
    date: new Date().toISOString().split('T')[0], // Date du jour par défaut
    heure_arrivee: '',
    heure_depart: '',
    minutes_retard: null,
    absent: false,
    en_retard: false,
    absence_reason_id: null,
});

// Récupération des utilisateurs et raisons d'absence
const { props } = usePage<{
    users: Array<{ id: number; name: string; email: string }>;
    absenceReasons: Array<{ id: number; name: string }>;
}>();

// Fonction pour calculer le retard
function calculateDelay(arrivalTime: string): number {
    if (!arrivalTime) return 0;

    const normalStartTime = '08:00'; // Heure normale de début

    const timeToMinutes = (time: string): number => {
        const [hours, minutes] = time.split(':').map(Number);
        return hours * 60 + minutes;
    };

    const normalStartMinutes = timeToMinutes(normalStartTime);
    const arrivalMinutes = timeToMinutes(arrivalTime);

    const delayMinutes = arrivalMinutes - normalStartMinutes;
    return delayMinutes > 0 ? delayMinutes : 0;
}

// Fonction pour formater l'affichage du retard
function formatDelay(minutes: number): string {
    if (minutes === 0) return '0 min';

    const hours = Math.floor(minutes / 60);
    const remainingMinutes = minutes % 60;

    if (hours > 0) {
        return `${hours}h ${remainingMinutes}min`;
    }
    return `${remainingMinutes}min`;
}

// Calcul du retard en temps réel
const calculatedDelay = computed(() => {
    return calculateDelay(form.heure_arrivee);
});

// Calcul automatique du retard quand l'heure d'arrivée change
watch(
    () => form.heure_arrivee,
    (newArrivalTime) => {
        if (newArrivalTime && !form.absent) {
            const delay = calculateDelay(newArrivalTime);
            form.minutes_retard = delay;
            form.en_retard = delay > 0;
        } else {
            form.minutes_retard = null;
            form.en_retard = false;
        }
    },
);

// Gestion des états liés
watch(
    () => form.absent,
    (newVal) => {
        if (newVal) {
            form.heure_arrivee = '';
            form.heure_depart = '';
            form.en_retard = false;
            form.minutes_retard = null;
        } else {
            form.absence_reason_id = null;
        }
    },
);

watch(
    () => form.en_retard,
    (newVal) => {
        if (!newVal && !form.absent) {
            form.minutes_retard = null;
        }
    },
);

// Logique métier
const submit = () => {
    form.post(route('presences.store'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
        },
        onError: () => {
            // Gestion des erreurs côté client si nécessaire
        },
    });
};

// Configuration UI
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Présences Sup_Admin', href: route('presences') },
    { title: 'Ajout Présences : Sup_Admin', href: route('presences.add') },
];
</script>

<style scoped>
/* Styles personnalisés si nécessaire */
.cursor-not-allowed {
    cursor: not-allowed;
}
</style>
