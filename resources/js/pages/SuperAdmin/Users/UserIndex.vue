<script setup lang="ts">
import Pagination from '@/components/Pagination.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem, type PaginationLink } from '@/types';
import { Head, Link, router } from '@inertiajs/vue3';
import { UserPlus,Trash2, Pen, Users, Calendar, Clock } from 'lucide-vue-next';

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Users List : Sup_Admin',
    href: '/presences/users',
  },
];

defineProps<{
  users: {
    data: Array<{
      id: number | string
      name: string
      email: string
      status?: 'active' | 'inactive'
    }>
    links: PaginationLink[]
  }
  totalUsers: number
  totalPresentUsers?: number
  totalAbsentUsers?: number
  totalLateUsers?: number
}>();

function destroy(id: number | string) {
  if (confirm('Supprimer cet utilisateur ?')) {
    router.delete(route('users.destroy', id));
  }
}
</script>

<template>
  <Head title="Users list" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="p-5 rounded-xl shadow-sm border border-border">
          <div class="flex items-center gap-3">
            <div class="p-3 rounded-lg text-primary">
              <Users class="w-5 h-5" />
            </div>
            <div>
              <p class="text-sm text-muted-foreground">Total Stagiaires</p>
              <p class="text-2xl font-bold">{{ totalUsers }}</p>
            </div>
          </div>
        </div>

        <div class="p-5 rounded-xl shadow-sm border border-border">
          <div class="flex items-center gap-3">
            <div class="p-3 rounded-lg text-green-600 dark:text-green-400">
              <Calendar class="w-5 h-5" />
            </div>
            <div>
              <p class="text-sm text-muted-foreground">Présents</p>
              <p class="text-2xl font-bold">{{ totalPresentUsers ?? 0 }}</p>
            </div>
          </div>
        </div>

        <div class="p-5 rounded-xl shadow-sm border border-border">
          <div class="flex items-center gap-3">
            <div class="p-3 rounded-lg text-red-600 dark:text-red-400">
              <Users class="w-5 h-5" />
            </div>
            <div>
              <p class="text-sm text-muted-foreground">Absents</p>
              <p class="text-2xl font-bold">{{ totalAbsentUsers ?? 0 }}</p>
            </div>
          </div>
        </div>

        <div class="p-5 rounded-xl shadow-sm border border-border">
          <div class="flex items-center gap-3">
            <div class="p-3 rounded-lg text-orange-600 dark:text-orange-400">
              <Clock class="w-5 h-5" />
            </div>
            <div>
              <p class="text-sm text-muted-foreground">En Retard</p>
              <p class="text-2xl font-bold">{{ totalLateUsers ?? 0 }}</p>
            </div>
          </div>
        </div>
      </div>

      <div class="flex justify-between items-center">
        <h2 class="text-lg font-medium">
          Liste des utilisateurs
        </h2>
        <span class="flex pt-2 px-2 rounded-sm text-white gap-1 bg-primary">
          <UserPlus/>
          <Link class="btn btn-primary mb-4" :href="route('users.create')" prefetch>Add Users</Link>
        </span>
        <span class="text-sm text-muted-foreground">
          Total: {{ totalUsers }} utilisateur(s)
        </span>
      </div>

      <div class="overflow-x-auto rounded-lg border border-border shadow-sm">
        <table class="min-w-full divide-y divide-border">
          <thead>
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                ID
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                Nom
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                Email
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                Statut
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-muted-foreground uppercase tracking-wider">
                Actions
              </th>
            </tr>
          </thead>
          <tbody class="divide-y divide-border">
            <tr 
              v-for="user in users.data" 
              :key="user.id"
              class="hover:bg-muted/50 transition-colors duration-150"
            >
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                {{ user.id }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm">
                {{ user.name }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm">
                {{ user.email }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span 
                  class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                  :class="{
                    'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200': user.status === 'active',
                    'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200': user.status === 'inactive',
                    'bg-muted text-muted-foreground': !user.status
                  }"
                >
                  {{ user.status === 'active' ? 'Actif' : user.status === 'inactive' ? 'Inactif' : 'N/A' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <Link 
                  :href="route('users.edit', user.id)" 
                  class="inline-flex items-center px-3 py-1 gap-1 bg-primary text-primary-foreground rounded-md hover:bg-primary/90"
                >
                 <Pen class="w-4 h-4 inline"/> Editer
                </Link>
                <button 
                  @click="destroy(user.id)" 
                  class="inline-flex items-center gap-1 px-3 py-1 bg-destructive text-destructive-foreground rounded-md hover:bg-destructive/90 ml-2"
                >
                <Trash2 class="w-4 h-4 inline"/>
                  Suppr.
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <Pagination :links="users.links" class="mt-4" />
      
      <div class="mt-4">
        <Link 
          :href="route('presences')" 
          prefetch
          class="inline-flex items-center px-4 py-2 bg-muted text-foreground rounded-md hover:bg-muted/80"
        >
          Retour à la liste des présences
        </Link>
      </div>
    </div>
  </AppLayout>
</template>

