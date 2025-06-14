<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link, useForm, usePage } from '@inertiajs/vue3'
import { ArrowLeft } from 'lucide-vue-next'
import type { BreadcrumbItem } from '@/types'
import { watch } from 'vue'


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

// Logique métier
const submit = () => {
  form.post(route('presences.store'), {
    preserveScroll: true,
    onSuccess: () => form.reset(),
  })
}

// Gestion des états liés
watch(() => form.absent, (newVal) => {
  if (newVal) {
    form.en_retard = false
    form.minutes_retard = null
    form.heure_arrivee = ''
    form.heure_depart = ''
  }
})

watch(() => form.en_retard, (newVal) => {
  if (!newVal) form.minutes_retard = null
})

// Configuration UI
const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Présences', href: route('presences') },
  { title: 'Ajout', href: route('presences.add') },
]
</script>

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

        <!-- Formulaire -->
        <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Section Étudiant -->
          <div class="md:col-span-2 space-y-4 p-4 bg-gray-50 rounded-lg">
            <h3 class="font-medium text-gray-700">Informations étudiant</h3>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1 required">Étudiant</label>
              <select
                v-model="form.user_id"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                :class="{ 'border-red-500': form.errors.user_id }"
                required
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

          <!-- Section Horaires -->
          <div class="space-y-4 p-4 bg-gray-50 rounded-lg">
            <h3 class="font-medium text-gray-700">Horaires</h3>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1 required">Date</label>
              <input
                v-model="form.date"
                type="date"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                :class="{ 'border-red-500': form.errors.date }"
                required
              />
              <p v-if="form.errors.date" class="mt-1 text-sm text-red-600">
                {{ form.errors.date }}
              </p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Heure d'arrivée</label>
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
              <label class="block text-sm font-medium text-gray-700 mb-1">Heure de départ</label>
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

            <div>
              <label class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-1">
                <input
                  v-model="form.absent"
                  type="checkbox"
                  class="rounded border-gray-300 text-green-600 focus:ring-green-500"
                />
                Absent(e)
              </label>
              <p v-if="form.errors.absent" class="mt-1 text-sm text-red-600">
                {{ form.errors.absent }}
              </p>
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
              <p v-if="form.errors.en_retard" class="mt-1 text-sm text-red-600">
                {{ form.errors.en_retard }}
              </p>
            </div>

            <div v-if="form.en_retard && !form.absent">
              <label class="block text-sm font-medium text-gray-700 mb-1">Minutes de retard</label>
              <input
                v-model.number="form.minutes_retard"
                type="number"
                min="0"
                step="1"
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500"
                :class="{ 'border-red-500': form.errors.minutes_retard }"
              />
              <p v-if="form.errors.minutes_retard" class="mt-1 text-sm text-red-600">
                {{ form.errors.minutes_retard }}
              </p>
            </div>
          </div>

          <!-- Actions -->
          <div class="md:col-span-2 flex justify-end gap-4 pt-4 border-t">
            <Link
              :href="route('presences')"
              class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors"
            >
              Annuler
            </Link>
            <button
              type="submit"
              :disabled="form.processing"
              class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 disabled:bg-green-400 transition-colors"
            >
              Enregistrer
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>
