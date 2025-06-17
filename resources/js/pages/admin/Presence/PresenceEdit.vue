<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import type { BreadcrumbItem } from '@/types';

interface PresenceForm {
  user_id: number | null;
  date: string;
  heure_arrivee: string;
  heure_depart: string;
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
watch(() => form.heure_arrivee, (newValue) => {
  const normalTime = new Date(`1970-01-01T08:00:00`);
  const arrivalTime = new Date(`1970-01-01T${newValue}:00`);
  const delay = (arrivalTime.getTime() - normalTime.getTime()) / (1000 * 60); // Convertir en minutes
  form.minutes_retard = delay > 0 ? delay : 0;
});

// Soumission du formulaire
const submit = () => {
  form.put(route('presences.update', props.presence.id), {
    preserveScroll: true,
    onSuccess: () => form.reset(),
  });
};

// Configuration UI
const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Présences', href: route('presences') },
  { title: 'Édition', href: route('presences.edit', props.presence.id) },
];
</script>

<template>
  <Head title="Modifier la présence" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6 max-w-7xl mx-auto">
      <Link
        :href="route('presences')"
        class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg transition-colors mb-6"
      >
        Retour à la liste
      </Link>

      <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <h2 class="text-2xl font-bold mb-6 text-gray-900 border-b pb-4">
          Modifier la fiche de présence
        </h2>

        <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div class="md:col-span-2 space-y-4 p-4 bg-gray-50 rounded-lg">
            <h3 class="font-medium text-gray-700">Informations étudiant</h3>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Étudiant</label>
              <select
                v-model="form.user_id"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
              >
                <option value="" disabled>Sélectionnez un étudiant</option>
                <option 
                  v-for="user in props.users" 
                  :key="user.id" 
                  :value="user.id"
                >
                  {{ user.name }} ({{ user.email }})
                </option>
              </select>
            </div>
          </div>

          <div class="space-y-4 p-4 bg-gray-50 rounded-lg">
            <h3 class="font-medium text-gray-700">Horaires</h3>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
              <input
                v-model="form.date"
                type="date"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Heure d'arrivée</label>
              <input
                v-model="form.heure_arrivee"
                type="time"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Heure de départ</label>
              <input
                v-model="form.heure_depart"
                type="time"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
              />
            </div>
          </div>

          <div class="space-y-4 p-4 bg-gray-50 rounded-lg">
            <h3 class="font-medium text-gray-700">Statut</h3>

            <div>
              <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-1">
                <input
                  v-model="form.absent"
                  type="checkbox"
                  class="rounded border-gray-300 text-green-600 focus:ring-green-500"
                />
                Absent(e)
              </label>
            </div>

            <div>
              <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-1">
                <input
                  v-model="form.en_retard"
                  type="checkbox"
                  class="rounded border-gray-300 text-green-600 focus:ring-green-500"
                />
                En retard
              </label>
            </div>

            <div v-if="form.en_retard">
              <label class="block text-sm font-medium text-gray-700 mb-1">Minutes de retard</label>
              <input
                v-model.number="form.minutes_retard"
                type="number"
                min="0"
                step="1"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
              />
            </div>
          </div>

          <div class="md:col-span-2 flex justify-end gap-4 pt-4 border-t">
            <Link
              :href="route('presences')"
              class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
            >
              Annuler
            </Link>
            <button
              type="submit"
              class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors"
            >
              Enregistrer
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>