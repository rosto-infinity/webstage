<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import Badge from '@/components/Badge.vue';
import SortIcon from '@/components/SortIcon.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { ChevronLeft, ChevronRight, Pen, Users, Calendar, Clock, Search, Download } from 'lucide-vue-next';
import type { BreadcrumbItem } from '@/types';
import type { PageProps } from '@/types';
import { Trash2 } from 'lucide-vue-next';
import { router } from '@inertiajs/vue3';

// Ajouter cette fonction
function deletePresence(id: number) {
  if (confirm('Êtes-vous sûr de vouloir supprimer cette présence ?')) {
    router.delete(route('presences.destroy', { presence: id }), {
      onSuccess: () => {
        // Suppression locale pour réactivité immédiate
        data.value = data.value.filter(p => p.id !== id);
      }
    });
  }
}
interface Presence {
  id: number;
  date: string;
  arrival_time: string | null;
  departure_time: string | null;
  late_minutes: number;
  absent: boolean;
  late: boolean;
  user: { name: string; email: string };
}

// Fonction utilitaire calcul du retard
function calculerMinutesRetard(arrivee: string | null, normale = '08:00'): number {
  if (!arrivee) return 0;
  const [hArr, mArr] = arrivee.split(':').map(Number);
  const [hNorm, mNorm] = normale.split(':').map(Number);

  const dateArr = new Date(0, 0, 0, hArr, mArr);
  const dateNorm = new Date(0, 0, 0, hNorm, mNorm);

  const diffMin = Math.floor((+dateArr - +dateNorm) / 60000);
  return diffMin > 0 ? diffMin : 0;
}

const page = usePage<PageProps<{ presences: Omit<Presence, 'late_minutes'|'late'>[] }>>();
const data = ref<Presence[]>(
  page.props.presences.map(r => ({
    ...r,
    late_minutes: calculerMinutesRetard(r.arrival_time),
    late: calculerMinutesRetard(r.arrival_time) > 0,
  }))
);

const searchTerm = ref('');
const filterStatus = ref<'all' | 'present' | 'absent' | 'late'>('all');
const currentPage = ref(1);
const itemsPerPage = ref(10);
const sortField = ref<keyof Presence>('date');
const sortDirection = ref<'asc' | 'desc'>('desc');

const filteredAndSortedData = computed(() => {
  return [...data.value]
    .filter(r => {
      const term = searchTerm.value.toLowerCase();
      const match = r.user.name.toLowerCase().includes(term) || r.user.email.toLowerCase().includes(term);
      return match && (
        filterStatus.value === 'all' ||
        (filterStatus.value === 'present' && !r.absent && !r.late) ||
        (filterStatus.value === 'absent' && r.absent) ||
        (filterStatus.value === 'late' && r.late)
      );
    })
    .sort((a, b) => {
      const aVal: any = a[sortField.value];
      const bVal: any = b[sortField.value];
      if (aVal < bVal) return sortDirection.value === 'asc' ? -1 : 1;
      if (aVal > bVal) return sortDirection.value === 'asc' ? 1 : -1;
      return 0;
    });
});

const totalPages = computed(() => Math.ceil(filteredAndSortedData.value.length / itemsPerPage.value));
const paginatedData = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage.value;
  return filteredAndSortedData.value.slice(start, start + itemsPerPage.value);
});

const presentCount = computed(() => data.value.filter(r => !r.absent && !r.late).length);
const absentCount = computed(() => data.value.filter(r => r.absent).length);
const lateCount = computed(() => data.value.filter(r => r.late).length);

function handleSort(field: keyof Presence) {
  if (sortField.value === field) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortField.value = field;
    sortDirection.value = 'asc';
  }
  currentPage.value = 1;
}

function setCurrentPage(n: number) {
  currentPage.value = n;
}

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Présences', href: '/presences' },
];
</script>

<template>
  <Head title="Présences" />
  <AppLayout :breadcrumbs="breadcrumbs">
    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-8 p-2">
      <div class="bg-white p-5 rounded-xl shadow border flex items-center gap-3">
        <Users class="text-blue-600 w-6 h-6"/>
        <div><p class="text-sm text-gray-500">Total stagiaires</p><p class="text-2xl font-bold">{{ filteredAndSortedData.length }}</p></div>
      </div>
      <div class="bg-white p-5 rounded-xl shadow border flex items-center gap-3">
        <Calendar class="text-green-600 w-6 h-6"/>
        <div><p class="text-sm text-gray-500">Présents</p><p class="text-2xl font-bold">{{ presentCount }}</p></div>
      </div>
      <div class="bg-white p-5 rounded-xl shadow border flex items-center gap-3">
        <Users class="text-red-600 w-6 h-6"/>
        <div><p class="text-sm text-gray-500">Absents</p><p class="text-2xl font-bold">{{ absentCount }}</p></div>
      </div>
      <div class="bg-white p-5 rounded-xl shadow border flex items-center gap-3">
        <Clock class="text-orange-600 w-6 h-6"/>
        <div><p class="text-sm text-gray-500">En retard</p><p class="text-2xl font-bold">{{ lateCount }}</p></div>
      </div>
    </div>

    <div class="p-2">
      <!-- Actions -->
      <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
        <div><h1 class="text-3xl font-bold">Tableau de présence</h1><p class="text-gray-600">BTS 2 Génie Logiciel / DQP</p></div>
        <div class="flex gap-2">
          <Link :href="route('presences.add')" class="bg-green-600 text-white flex items-center px-6 py-2 rounded-lg hover:bg-green-700">
            <Pen class="w-5 h-5"/>Ajouter
          </Link>
          <button class="bg-green-600 text-white flex items-center px-6 py-2 rounded-lg hover:bg-green-700">
            <Download class="w-5 h-5"/>Exporter
          </button>
        </div>
      </div>

      <!-- Recherche & filtre -->
      <div class="flex flex-col md:flex-row gap-4 mb-4">
        <div class="relative flex-1">
          <Search class="absolute left-3 top-3 text-gray-400 w-5 h-5"/>
          <input v-model="searchTerm" @input="setCurrentPage(1)" placeholder="Rechercher nom/email" class="pl-10 pr-4 py-2 rounded-lg border focus:ring focus:outline-none"/>
        </div>
        <select v-model="filterStatus" @change="setCurrentPage(1)" class="rounded-lg border focus:ring focus:outline-none px-2 py-2">
          <option value="all">Tous</option>
          <option value="present">Présents</option>
          <option value="absent">Absents</option>
          <option value="late">Retard</option>
        </select>
      </div>

      <!-- Tableau -->
      <div class="overflow-x-auto">
        <table class="min-w-full table-auto text-left text-sm bg-white rounded-xl shadow border">
          <thead class="bg-gray-50">
            <tr>
              <th @click="handleSort('date')" class="th-sort px-4 py-2">Date <SortIcon field="date" :sortField="sortField" :direction="sortDirection"/></th>
              <th class="px-4 py-2">Nom</th>
              <th class="px-4 py-2">E‑mail</th>
              <th @click="handleSort('arrival_time')" class="th-sort px-4 py-2">Arrivée <SortIcon field="arrival_time" :sortField="sortField" :direction="sortDirection"/></th>
              <th @click="handleSort('departure_time')" class="th-sort px-4 py-2">Départ <SortIcon field="departure_time" :sortField="sortField" :direction="sortDirection"/></th>
              <th @click="handleSort('late_minutes')" class="th-sort px-4 py-2">Retard <SortIcon field="late_minutes" :sortField="sortField" :direction="sortDirection"/></th>
              <th class="px-4 py-2">Absent</th>
              <th class="px-4 py-2">En retard</th>
              <th class="px-4 py-2">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="r in paginatedData" :key="r.id" class="hover:bg-gray-100">
              <td class="px-4 py-2">{{ new Date(r.date).toLocaleDateString('fr-FR') }}</td>
              <td class="px-4 py-2">{{ r.user.name }}</td>
              <td class="px-4 py-2">{{ r.user.email }}</td>
              <td class="px-4 py-2">{{ r.arrival_time ?? '-' }}</td>
              <td class="px-4 py-2">{{ r.departure_time ?? '-' }}</td>
              <td class="px-4 py-2">
                <Badge :type="r.late_minutes > 0 ? 'warning' : 'success'">
                  {{ r.late_minutes }} min
                </Badge>
              </td>
              <td class="px-4 py-2"><Badge :type="r.absent ? 'danger' : 'success'">{{ r.absent ? 'Oui' : 'Non' }}</Badge></td>
              <td class="px-4 py-2"><Badge :type="r.late ? 'warning' : 'success'">{{ r.late ? 'Oui' : 'Non' }}</Badge></td>
              <td class="px-4 py-2">
  <Link :href="route('presences.edit', { id: r.id })" class="text-blue-600 hover:underline">
    <Pen class="w-4 h-4 inline"/> Modifier
  </Link>
</td>
<td class="px-4 py-2 flex gap-2">
  
    <button @click="deletePresence(r.id)" class="text-red-600 hover:underline">
      <Trash2 class="w-4 h-4 inline"/> Supprimer
    </button>
  </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="flex justify-between items-center py-4">
        <div><span>Afficher</span><select v-model="itemsPerPage" @change="setCurrentPage(1)" class="border rounded p-1 mx-2"><option v-for="n of [5,10,20,50]" :key="n">{{ n }}</option></select><span>sur {{ filteredAndSortedData.length }}</span></div>
        <div class="flex items-center gap-2">
          <button :disabled="currentPage === 1" @click="setCurrentPage(currentPage-1)" class="p-2 border rounded hover:bg-gray-200 disabled:opacity-50"><ChevronLeft class="w-4 h-4"/></button>
          <span>Page {{ currentPage }} / {{ totalPages }}</span>
          <button :disabled="currentPage === totalPages" @click="setCurrentPage(currentPage+1)" class="p-2 border rounded hover:bg-gray-200 disabled:opacity-50"><ChevronRight class="w-4 h-4"/></button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<style scoped>
.th-sort { cursor: pointer; }
</style>
