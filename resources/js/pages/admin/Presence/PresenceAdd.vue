<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link, useForm } from '@inertiajs/vue3';
import {ArrowLeft } from 'lucide-vue-next';

interface PresenceForm {
  nom_prenom: string;
  email: string;
  date: string;
  heure_arrivee: string;
  heure_depart: string;
  minutes_retard: number | null;
  absent: boolean;
  en_retard: boolean;
}

const form = useForm<PresenceForm>({
  nom_prenom: '',
  email: '',
  date: '',
  heure_arrivee: '',
  heure_depart: '',
  minutes_retard: null,
  absent: false,
  en_retard: false,
});

function submit() {
  form.post(route('presence.store'));
}
const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Add Presences ',
    href: '/presences/add',
  },
];
</script>

<template>

  <Head title="Add presence" />

  <AppLayout :breadcrumbs="breadcrumbs">
          <!-- Design du formulaire d'ajout d'étudiant -->
<div class="p-6">

   <Link  :href="route('presences')"
   
      class="flex items-center w-48 gap-2  bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors duration-200">
        <ArrowLeft  class="w-4 h-4" />
            <button class="cursor-pointer">Retour présences </button>
    </Link>
    

    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 my-6 ">
     
    <h2 class="text-xl font-bold mb-4 text-gray-900">Ajouter un étudiant</h2>
    <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Nom et prénom</label>
        <input v-model="form.nom_prenom" type="text" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" />
        <span v-if="form.errors.nom_prenom" class="text-red-600 text-sm">{{ form.errors.nom_prenom }}</span>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">E-mail</label>
        <input v-model="form.email" type="email" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" />
        <span v-if="form.errors.email" class="text-red-600 text-sm">{{ form.errors.email }}</span>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Date</label>
        <input v-model="form.date" type="date" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" />
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Heure d'arrivée</label>
        <input v-model="form.heure_arrivee" type="time" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" />
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Heure de départ</label>
        <input v-model="form.heure_depart" type="time" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" />
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Minutes de retard</label>
        <input v-model.number="form.minutes_retard" type="number" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" />
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Absent</label>
        <select v-model="form.absent" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
          <option :value="false">Non</option>
          <option :value="true">Oui</option>
        </select>
      </div>
      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">En retard</label>
        <select v-model="form.en_retard" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
          <option :value="false">Non</option>
          <option :value="true">Oui</option>
        </select>
      </div>
      <div class="md:col-span-2 flex justify-end">
        <button type="submit" :disabled="form.processing" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">
          Ajouter
        </button>
      </div>
    </form>

      
    </div>
</div>

  </AppLayout>
</template>
