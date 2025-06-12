<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link  } from '@inertiajs/vue3';

import { ref, computed } from 'vue';
import { ChevronLeft, ChevronRight, Download, Filter, Search, Users, Calendar, Clock, Pen } from 'lucide-vue-next';

// Calcul des statistiques
const presentCount = computed(() =>
  data.value.filter(record => !record.isAbsent && !record.isLate).length
);

const absentCount = computed(() =>
  data.value.filter(record => record.isAbsent).length
);

const lateCount = computed(() =>
  data.value.filter(record => record.isLate).length
);

//tableau
const data = ref<AttendanceRecord[]>([
  { id: 1, date: '2025-06-12', fullName: 'Alice Dupont', email: 'alice.dupont@email.com', arrivalTime: '08:05', departureTime: '17:00', lateMinutes: 5, isAbsent: false, isLate: true },
  { id: 2, date: '2025-06-12', fullName: 'Benoît Martin', email: 'benoit.martin@email.com', arrivalTime: '08:00', departureTime: '17:00', lateMinutes: 0, isAbsent: false, isLate: false },
  { id: 3, date: '2025-06-12', fullName: 'Carla Moreau', email: 'carla.moreau@email.com', arrivalTime: '08:12', departureTime: '17:00', lateMinutes: 12, isAbsent: false, isLate: true },
  { id: 4, date: '2025-06-12', fullName: 'David Leroy', email: 'david.leroy@email.com', arrivalTime: '', departureTime: '', lateMinutes: 0, isAbsent: true, isLate: false },
  { id: 5, date: '2025-06-12', fullName: 'Emma Petit', email: 'emma.petit@email.com', arrivalTime: '08:03', departureTime: '17:00', lateMinutes: 3, isAbsent: false, isLate: true },
  { id: 6, date: '2025-06-12', fullName: 'Félix Bernard', email: 'felix.bernard@email.com', arrivalTime: '08:00', departureTime: '17:00', lateMinutes: 0, isAbsent: false, isLate: false },
  { id: 7, date: '2025-06-12', fullName: 'Gabrielle Lefevre', email: 'gabrielle.lefevre@email.com', arrivalTime: '08:10', departureTime: '17:00', lateMinutes: 10, isAbsent: false, isLate: true },
  { id: 8, date: '2025-06-12', fullName: 'Hugo Girard', email: 'hugo.girard@email.com', arrivalTime: '', departureTime: '', lateMinutes: 0, isAbsent: true, isLate: false },
  { id: 9, date: '2025-06-12', fullName: 'Inès Dubois', email: 'ines.dubois@email.com', arrivalTime: '08:00', departureTime: '17:00', lateMinutes: 0, isAbsent: false, isLate: false },
  { id: 10, date: '2025-06-12', fullName: 'Jules Fontaine', email: 'jules.fontaine@email.com', arrivalTime: '08:07', departureTime: '17:00', lateMinutes: 7, isAbsent: false, isLate: true },
  { id: 11, date: '2025-06-12', fullName: 'Kenza Laurent', email: 'kenza.laurent@email.com', arrivalTime: '08:00', departureTime: '17:00', lateMinutes: 0, isAbsent: false, isLate: false },
  { id: 12, date: '2025-06-12', fullName: 'Léo Marchand', email: 'leo.marchand@email.com', arrivalTime: '08:15', departureTime: '17:00', lateMinutes: 15, isAbsent: false, isLate: true },
  { id: 13, date: '2025-06-12', fullName: 'Manon Roux', email: 'manon.roux@email.com', arrivalTime: '08:00', departureTime: '17:00', lateMinutes: 0, isAbsent: false, isLate: false },
  { id: 14, date: '2025-06-12', fullName: 'Nicolas Gauthier', email: 'nicolas.gauthier@email.com', arrivalTime: '', departureTime: '', lateMinutes: 0, isAbsent: true, isLate: false },
  { id: 15, date: '2025-06-12', fullName: 'Océane Barbier', email: 'oceane.barbier@email.com', arrivalTime: '08:02', departureTime: '17:00', lateMinutes: 2, isAbsent: false, isLate: true },
  { id: 16, date: '2025-06-12', fullName: 'Pauline Masson', email: 'pauline.masson@email.com', arrivalTime: '08:00', departureTime: '17:00', lateMinutes: 0, isAbsent: false, isLate: false },
  { id: 17, date: '2025-06-12', fullName: 'Quentin Robin', email: 'quentin.robin@email.com', arrivalTime: '08:09', departureTime: '17:00', lateMinutes: 9, isAbsent: false, isLate: true },
  { id: 18, date: '2025-06-12', fullName: 'Rania Perrot', email: 'rania.perrot@email.com', arrivalTime: '08:00', departureTime: '17:00', lateMinutes: 0, isAbsent: false, isLate: false },
  { id: 19, date: '2025-06-12', fullName: 'Sami Lefort', email: 'sami.lefort@email.com', arrivalTime: '', departureTime: '', lateMinutes: 0, isAbsent: true, isLate: false },
  { id: 20, date: '2025-06-12', fullName: 'Théo Blanchard', email: 'theo.blanchard@email.com', arrivalTime: '08:04', departureTime: '17:00', lateMinutes: 4, isAbsent: false, isLate: true },
  { id: 21, date: '2025-07-12', fullName: 'Théo Blanchard', email: 'theo.blanchard@email.com', arrivalTime: '08:04', departureTime: '17:00', lateMinutes: 4, isAbsent: false, isLate: true },
  { id: 22, date: '2025-08-12', fullName: 'Théo Blanchard', email: 'theo.blanchard@email.com', arrivalTime: '08:04', departureTime: '17:00', lateMinutes: 4, isAbsent: false, isLate: true },
  { id: 23, date: '2025-09-12', fullName: 'Théo Blanchard', email: 'theo.blanchard@email.com', arrivalTime: '08:04', departureTime: '17:00', lateMinutes: 4, isAbsent: false, isLate: true },
]);

interface AttendanceRecord {
  id: number;
  date: string;
  fullName: string;
  email: string;
  arrivalTime: string;
  departureTime: string;
  lateMinutes: number;
  isAbsent: boolean;
  isLate: boolean;
}

const searchTerm = ref('');
const filterStatus = ref<'all' | 'present' | 'absent' | 'late'>('all');
const currentPage = ref(1);
const itemsPerPage = ref(10);
const sortField = ref<keyof AttendanceRecord>('date');
const sortDirection = ref<'asc' | 'desc'>('desc');

const filteredAndSortedData = computed(() => {
  let filtered = data.value.filter(record => {
    const matchesSearch = record.fullName.toLowerCase().includes(searchTerm.value.toLowerCase()) ||
      record.email.toLowerCase().includes(searchTerm.value.toLowerCase());

    const matchesFilter = filterStatus.value === 'all' ||
      (filterStatus.value === 'present' && !record.isAbsent && !record.isLate) ||
      (filterStatus.value === 'absent' && record.isAbsent) ||
      (filterStatus.value === 'late' && record.isLate);

    return matchesSearch && matchesFilter;
  });

  filtered.sort((a, b) => {
    let aValue = a[sortField.value];
    let bValue = b[sortField.value];

    if (typeof aValue === 'string') {
      aValue = aValue.toLowerCase();
      bValue = (bValue as string).toLowerCase();
    }

    return sortDirection.value === 'asc' ? (aValue < bValue ? -1 : 1) : (aValue > bValue ? -1 : 1);
  });

  return filtered;
});

const totalPages = computed(() => Math.ceil(filteredAndSortedData.value.length / itemsPerPage.value));
const paginatedData = computed(() => filteredAndSortedData.value.slice((currentPage.value - 1) * itemsPerPage.value, currentPage.value * itemsPerPage.value));

const handleSort = (field: keyof AttendanceRecord) => {
  if (sortField.value === field) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortField.value = field;
    sortDirection.value = 'asc';
  }
  currentPage.value = 1;
};

const setCurrentPage = (page: number) => {
  currentPage.value = page;
};

const pageNumbers = computed(() => {
  const pages = [];
  for (let i = 1; i <= totalPages.value; i++) {
    pages.push(i);
  }
  return pages;
});


const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Presences',
    href: '/presences',
  },
];
</script>

<template>

  <Head title="Presences" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
 <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
          <div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Tableau de Présence</h1>
            <p class="text-gray-600">BTS 2 Génie Logiciel / Développement d'Application DQP</p>
          </div>

          <div class="flex items-center gap-4">
         <Link 
            :href="route('presences.add')"
         
         class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">
            <Pen class="w-4 h-4" />
            Ajouter présence
          </Link>
          <button class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors duration-200">
            <Download class="w-4 h-4" />
            Exporter
          </button>
         </div>

        </div>
      </div>

       <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
          <div class="flex items-center gap-3">
            <div class="p-3 rounded-lg bg-blue-50 text-blue-600">
              <Users class="w-5 h-5" />
            </div>
            <div>
              <p class="text-sm text-gray-500">Total Stagiaires</p>
              <p class="text-2xl font-bold">{{ filteredAndSortedData.length }}</p>
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
              <p class="text-2xl font-bold">{{ presentCount }}</p>
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
              <p class="text-2xl font-bold">{{ absentCount }}</p>
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
              <p class="text-2xl font-bold">{{ lateCount }}</p>
            </div>
          </div>
        </div>
      </div>
<!-- ghdh -->
      <div
        class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 md:min-h-min dark:border-sidebar-border">
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 mb-6">
          <div class="flex flex-col lg:flex-row gap-4">
            <div class="flex-1">
              <div class="relative">
                <Search class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-4 h-4" />
                <input type="text" placeholder="Rechercher par nom ou email..." v-model="searchTerm"
                  @input="setCurrentPage(1)"
                  class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200" />
              </div>
            </div>

            <div class="flex items-center gap-2">
              <Filter class="w-4 h-4 text-gray-400" />
              <select v-model="filterStatus" @change="setCurrentPage(1)"
                class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200">
                <option value="all">Tous les statuts</option>
                <option value="present">Présents</option>
                <option value="absent">Absents</option>
                <option value="late">En retard</option>
              </select>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                  <th @click="handleSort('date')"
                    class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors duration-200">
                    <div class="flex items-center gap-1">Date
                      <SortIcon field="date" />
                    </div>
                  </th>
                  <th @click="handleSort('fullName')"
                    class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors duration-200">
                    <div class="flex items-center gap-1">Nom et Prénom
                      <SortIcon field="fullName" />
                    </div>
                  </th>
                  <th @click="handleSort('email')"
                    class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors duration-200">
                    <div class="flex items-center gap-1">E-mail
                      <SortIcon field="email" />
                    </div>
                  </th>
                  <th @click="handleSort('arrivalTime')"
                    class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors duration-200">
                    <div class="flex items-center gap-1">Arrivée
                      <SortIcon field="arrivalTime" />
                    </div>
                  </th>
                  <th @click="handleSort('departureTime')"
                    class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors duration-200">
                    <div class="flex items-center gap-1">Départ
                      <SortIcon field="departureTime" />
                    </div>
                  </th>
                  <th @click="handleSort('lateMinutes')"
                    class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors duration-200">
                    <div class="flex items-center gap-1">Retard (min)
                      <SortIcon field="lateMinutes" />
                    </div>
                  </th>
                  <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Absence
                  </th>
                  <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Retard</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="record in paginatedData" :key="record.id"
                  class="hover:bg-gray-50 transition-colors duration-200">
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">{{ new
                    Date(record.date).toLocaleDateString('fr-FR') }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ record.fullName }}</div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ record.email }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ record.arrivalTime || '-' }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ record.departureTime || '-' }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <span
                      :class="{ 'text-orange-600 font-medium': record.lateMinutes > 0, 'text-gray-900': record.lateMinutes <= 0 }">
                      {{ record.lateMinutes > 0 ? record.lateMinutes : 0 }} min
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <Badge :type="record.isAbsent ? 'danger' : 'success'">{{ record.isAbsent ? 'Oui' : 'Non' }}</Badge>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <Badge :type="record.isLate ? 'warning' : 'success'">{{ record.isLate ? 'Oui' : 'Non' }}</Badge>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <div class="px-6 py-4 border-t border-gray-200">
            <div class="flex flex-col lg:flex-row items-center justify-between gap-4">
              <div class="flex items-center gap-2 text-sm text-gray-700">
                <span>Afficher</span>
                <select v-model="itemsPerPage" @change="setCurrentPage(1)"
                  class="px-2 py-1 border border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                  <option value="5">5</option>
                  <option value="10">10</option>
                  <option value="20">20</option>
                  <option value="50">50</option>
                </select>
                <span>sur {{ filteredAndSortedData.length }} résultats</span>
              </div>

              <div class="flex items-center gap-2">
                <button @click="setCurrentPage(Math.max(1, currentPage - 1))" :disabled="currentPage === 1"
                  class="p-2 rounded-lg border border-gray-300 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200">
                  <ChevronLeft class="w-4 h-4" />
                </button>

                <div class="flex items-center gap-1">
                  <button v-for="pageNum in pageNumbers" :key="pageNum" @click="setCurrentPage(pageNum)"
                    :class="['w-8 h-8 rounded-lg text-sm font-medium transition-all duration-200', currentPage === pageNum ? 'bg-blue-600 text-white' : 'border border-gray-300 hover:bg-gray-50']">{{
                    pageNum }}</button>
                </div>

                <button @click="setCurrentPage(Math.min(totalPages, currentPage + 1))"
                  :disabled="currentPage === totalPages"
                  class="p-2 rounded-lg border border-gray-300 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200">
                  <ChevronRight class="w-4 h-4" />
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>
</template>
