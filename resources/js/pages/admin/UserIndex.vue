<script setup lang="ts">
import Pagination from '@/components/Pagination.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { Users, Calendar, Clock, Pen } from 'lucide-vue-next';
const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Users',
    href: '/users',
  },
];

defineProps({
  users: {
    type: Object,
    required: true
  },
  totalUsers: {
    type: Number,
    required: true
  }
});
</script>

<template>
  <Head title="Users list" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
      
       <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
          <div class="flex items-center gap-3">
            <div class="p-3 rounded-lg bg-blue-50 text-blue-600">
              <Users class="w-5 h-5" />
            </div>
            <div>
              <p class="text-sm text-gray-500">Total Stagiaires</p>
              <p class="text-2xl font-bold">{{ totalUsers }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
          <div class="flex items-center gap-3">
            <div class="p-3 rounded-lg bg-green-50 text-green-600">
              <Calendar class="w-5 h-5" />
            </div>
            <div>
              <p class="text-sm text-gray-500">Présents</p>
              <p class="text-2xl font-bold">{{  }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
          <div class="flex items-center gap-3">
            <div class="p-3 rounded-lg bg-red-50 text-red-600">
              <Users class="w-5 h-5" />
            </div>
            <div>
              <p class="text-sm text-gray-500">Absents</p>
              <p class="text-2xl font-bold">{{  }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
          <div class="flex items-center gap-3">
            <div class="p-3 rounded-lg bg-orange-50 text-orange-600">
              <Clock class="w-5 h-5" />
            </div>
            <div>
              <p class="text-sm text-gray-500">En Retard</p>
              <p class="text-2xl font-bold">{{ }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Ajout du compteur d'utilisateurs -->
      <div class="flex justify-between items-center">
        <h2 class="text-lg font-medium text-gray-900 dark:text-white">
          Liste des utilisateurs
        </h2>
        <span class="text-sm text-gray-500 dark:text-gray-400">
          Total: {{ totalUsers }} utilisateur(s)
        </span>
      </div>

      <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700 shadow-sm">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
          <!-- [Le reste de votre table reste inchangé] -->
          <thead class="bg-gray-50 dark:bg-gray-800">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                ID
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                Nom
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                Email
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                Statut
              </th>
            </tr>
          </thead>
          <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
            <tr v-for="user in users.data" class="hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors duration-150">
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                {{ user.id }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                {{ user.name }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                {{ user.email }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                  Actif
                </span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <Pagination :links="users.links" class="mt-4" />
      
      <div class="mt-4">
        <Link href="/presences" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
          Retour à la liste des présences
        </Link>
      </div>
    </div>
  </AppLayout>
</template>
