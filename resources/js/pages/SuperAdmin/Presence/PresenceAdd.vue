<template>
  <Head title="Ajouter une présence" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6 max-w-7xl mx-auto">
      <!-- Bouton de retour -->
      <Link
        :href="route('presences')"
        class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-800 px-4 py-2 rounded-lg transition-colors mb-6"
      >
        <ArrowLeft class="w-4 h-4" />
        Retour à la liste
      </Link>

      <!-- Carte principale -->
      <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <h2 class="text-2xl font-bold mb-6 text-gray-900 border-b pb-4">
          Nouvelle fiche de présence
        </h2>

        <!-- Formulaire réorganisé horizontalement -->
        <form @submit.prevent="submit" class="space-y-6">
          <!-- Section Étudiant -->
          <div class="space-y-4 p-4 bg-gray-50 rounded-lg">
            <h3 class="font-medium text-gray-700">Informations étudiant</h3>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Étudiant</label>
              <select
                v-model="form.user_id"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                :class="{ 'border-red-500': form.errors.user_id }"
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
              <p v-if="form.errors.user_id" class="mt-1 text-sm text-red-600">
                {{ form.errors.user_id }}
              </p>
            </div>
          </div>

          <!-- Conteneur horizontal -->
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Section Horaires -->
            <div class="space-y-4 p-4 bg-gray-50 rounded-lg">
              <h3 class="font-medium text-gray-700">Horaires</h3>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
                <input
                  v-model="form.date"
                  type="date"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                  :class="{ 'border-red-500': form.errors.date }"
                />
                <p v-if="form.errors.date" class="mt-1 text-sm text-red-600">
                  {{ form.errors.date }}
                </p>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Arrivée</label>
                <input
                  v-model="form.heure_arrivee"
                  type="time"
                  :disabled="form.absent"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                  :class="{ 'border-red-500': form.errors.heure_arrivee, 'bg-gray-100': form.absent }"
                />
                <p v-if="form.errors.heure_arrivee" class="mt-1 text-sm text-red-600">
                  {{ form.errors.heure_arrivee }}
                </p>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Départ</label>
                <input
                  v-model="form.heure_depart"
                  type="time"
                  :disabled="form.absent"
                  :min="form.heure_arrivee"
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                  :class="{ 'border-red-500': form.errors.heure_depart, 'bg-gray-100': form.absent }"
                />
                <p v-if="form.errors.heure_depart" class="mt-1 text-sm text-red-600">
                  {{ form.errors.heure_depart }}
                </p>
              </div>
            </div>

            <!-- Section Statut -->
            <div class="space-y-4 p-4 bg-gray-50 rounded-lg">
              <h3 class="font-medium text-gray-700">Statut</h3>

              <div class="space-y-4">
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
                      :disabled="form.absent"
                      class="rounded border-gray-300 text-green-600 focus:ring-green-500"
                      :class="{ 'bg-gray-100': form.absent }"
                    />
                    En retard
                  </label>
                </div>
              </div>

              <!-- Affichage du retard calculé -->
              <div v-if="form.heure_arrivee && !form.absent" class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
                <div class="flex items-center gap-2 mb-2">
                  <Clock class="w-4 h-4 text-blue-600" />
                  <span class="text-sm font-medium text-blue-700">Calcul du retard</span>
                </div>
                <div class="text-sm text-blue-600 space-y-1">
                  <div>Normale: <strong>8h00</strong></div>
                  <div>Arrivée: <strong>{{ form.heure_arrivee }}</strong></div>
                  <div>Retard: <strong>{{ formatDelay(calculatedDelay) }}</strong></div>
                </div>
              </div>
            </div>

            <!-- Section Détails retard -->
            <div class="space-y-4 p-4 bg-gray-50 rounded-lg">
              <h3 class="font-medium text-gray-700">Détails</h3>

              <div v-if="form.en_retard && !form.absent">
                <label class="block text-sm font-medium text-gray-700 mb-1">Minutes de retard</label>
                <input
                  v-model.number="form.minutes_retard"
                  type="number"
                  readonly
                  class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed"
                />
                <p class="text-xs text-gray-500 mt-1">
                  Calculé automatiquement
                </p>
              </div>

              <!-- Espace supplémentaire pour équilibrer visuellement -->
              <div class="h-full flex items-start ">
                <div class="w-full p-3 bg-green-50 rounded-lg border border-green-200">
                  <p class="text-sm text-green-700">
                    Tous les champs marqués d'un astérisque (<span class="text-red-500">*</span>) sont obligatoires
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex gap-4 pt-4 border-t">
            <Link
              :href="route('presences')"
              class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
            >
              Annuler
            </Link>
            <button
              type="submit"
              :disabled="form.processing"
              class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:bg-green-400 transition-colors flex items-center gap-2"
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
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link, useForm, usePage } from '@inertiajs/vue3'
import { ArrowLeft, Clock } from 'lucide-vue-next'
import type { BreadcrumbItem } from '@/types'
import { watch, computed } from 'vue'

// Typage TypeScript
interface PresenceForm {
  user_id: number | null
  date: string
  heure_arrivee: string
  heure_depart: string
  minutes_retard: number | null
  absent: boolean
  en_retard: boolean
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
})

// Récupération des utilisateurs
const { props } = usePage<{
  users: Array<{ id: number; name: string; email: string }>
}>()

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
watch(() => form.heure_arrivee, (newArrivalTime) => {
  if (newArrivalTime && !form.absent) {
    const delay = calculateDelay(newArrivalTime);
    form.minutes_retard = delay;
    form.en_retard = delay > 0;
  } else {
    form.minutes_retard = null;
    form.en_retard = false;
  }
});

// Gestion des états liés
watch(() => form.absent, (newVal) => {
  if (newVal) {
    form.en_retard = false;
    form.minutes_retard = null;
    form.heure_arrivee = '';
    form.heure_depart = '';
  }
});

watch(() => form.en_retard, (newVal) => {
  if (!newVal && !form.absent) {
    form.minutes_retard = null;
  }
});

// Logique métier
const submit = () => {
  form.post(route('presences.store'), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset();
      // Optionnel : message de succès
    },
    onError: () => {
      // Optionnel : gestion des erreurs
    }
  })
}

// Configuration UI
const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Présences Sup_Admin', href: route('presences') },
  { title: 'Ajout Présences : Sup_Admin', href: route('presences.add') },
]
</script>

<style scoped>
/* Styles personnalisés si nécessaire */
.cursor-not-allowed {
  cursor: not-allowed;
}
</style>