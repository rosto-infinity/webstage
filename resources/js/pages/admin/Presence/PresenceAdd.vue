<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue'
import { Head, Link, useForm, usePage } from '@inertiajs/vue3'
import { ArrowLeft } from 'lucide-vue-next'
import type { BreadcrumbItem } from '@/types'

// Définition du formulaire
interface PresenceForm {
  user_id: number | null
  date: string
  heure_arrivee: string
  heure_depart: string
  minutes_retard: number | null
  absent: boolean
  en_retard: boolean
  
}

const form = useForm<PresenceForm>({
  user_id: null,
  date: '',
  heure_arrivee: '',
  heure_depart: '',
  minutes_retard: null,
  absent: false,
  en_retard: false,
})

// Récupération de la liste des utilisateurs depuis les props via Inertia
const page = usePage<{
  props: { users: Array<{ id: number; name: string; email: string }> }
}>()

const users = page.props.users

function submit() {
  form.post(route('presences.store'))
}

// Fil d'ariane
const breadcrumbs: BreadcrumbItem[] = [
  {
       title: 'Add Presences',
        href: '/presences/add',
    },
]
</script>

<template>
  <Head title="Ajouter une présence" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6">
      <Link
        :href="route('presences')" prefetch
        class="flex items-center w-59 gap-2 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors"
      >
        <ArrowLeft class="w-4 h-4"/>
        <span>Retour aux présences</span>
      </Link>

      <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 my-6 max-w-2xl mx-auto">
        <h2 class="text-xl font-bold mb-4 text-gray-900">Ajouter une présence</h2>

        <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <!-- Sélection de l'utilisateur -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Étudiant</label>
            <select v-model="form.user_id"  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:green-500">
              <option value="" disabled>Choisissez un étudiant</option>
              <option v-for="u in users" :key="u.id" :value="u.id">
                {{ u.name }} — {{ u.email }}
              </option>
            </select>
            <span v-if="form.errors.user_id" class="text-red-600 text-sm">{{ form.errors.user_id }}</span>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
            <input v-model="form.date" type="date"  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:green-500" />
            <span v-if="form.errors.date" class="text-red-600 text-sm">{{ form.errors.date }}</span>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Heure d'arrivée</label>
            <input v-model="form.heure_arrivee" type="time" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:green-500" />
            <span v-if="form.errors.heure_arrivee" class="text-red-600 text-sm">{{ form.errors.heure_arrivee }}</span>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Heure de départ</label>
            <input v-model="form.heure_depart" type="time" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:green-500" />
            <span v-if="form.errors.heure_depart" class="text-red-600 text-sm">{{ form.errors.heure_depart }}</span>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Minutes de retard</label>
            <input v-model.number="form.minutes_retard" type="number" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:green-500" />
            <span v-if="form.errors.minutes_retard" class="text-red-600 text-sm">{{ form.errors.minutes_retard }}</span>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Absent</label>
            <select v-model="form.absent" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:green-500">
              <option :value="false">Non</option>
              <option :value="true">Oui</option>
            </select>
            <span v-if="form.errors.absent" class="text-red-600 text-sm">{{ form.errors.absent }}</span>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">En retard</label>
            <select v-model="form.en_retard" 
            class="w-full  px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:green-500">
              <option :value="false">Non</option>
              <option :value="true">Oui</option>
            </select>
            <span v-if="form.errors.en_retard" class="text-red-600 text-sm">{{ form.errors.en_retard }}</span>
          </div>

          <div class="md:col-span-2 flex justify-end">
            <button type="submit" :disabled="form.processing"
              class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors duration-200">
              Ajouter
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>
