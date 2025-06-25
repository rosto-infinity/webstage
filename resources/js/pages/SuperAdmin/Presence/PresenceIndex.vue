<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import Badge from '@/components/Badge.vue';
import SortIcon from '@/components/SortIcon.vue';
import { Head, Link, usePage, router } from '@inertiajs/vue3';
import { ref, computed ,watch} from 'vue';
import { Trash2 ,ChevronLeft, ChevronRight, Pen, Users, Calendar, Clock, Search, Download } from 'lucide-vue-next';
import type { BreadcrumbItem } from '@/types';

defineProps<{
  presenceCount:number
}>()


// Typage des messages flash
interface FlashMessages {
  success?: string;
  error?: string;
  warning?: string;
}

// Récupérer les messages flash
const flash = computed<FlashMessages>(() => usePage().props.flash as FlashMessages);
const showFlash = ref(false);
const flashMessage = ref('');
const flashType = ref<'success' | 'error' | 'warning'>('success');

// Surveillance réactive des messages flash
watch(flash, (newVal: FlashMessages) => {
  const hasMessage = newVal.success || newVal.error || newVal.warning;

  if (hasMessage) {
    showFlash.value = true;
    flashMessage.value = newVal.success || newVal.error || newVal.warning || '';
    flashType.value = newVal.success ? 'success' 
                     : newVal.error ? 'error' 
                     : 'warning';
    
    // Durée de 5s pour une meilleure expérience
    setTimeout(() => {
      showFlash.value = false;
    }, 5000);
  }
}, { 
  immediate: true,
});
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
const page = usePage(); // garde le type par défaut
const rawPresences = (page.props as any).presences as Omit<Presence, 'late_minutes'|'late'>[];

const data = ref<Presence[]>(
  rawPresences.map((r: Omit<Presence, 'late_minutes'|'late'>) => ({
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

// const presentCount = computed(() => data.value.filter(r => !r.absent && !r.late).length);
const presentCount = computed(() => data.value.filter(r => !r.absent ).length);
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
  { title: 'Présences : Sup_Admin', href: '/presences' },
];
</script>

<template>
  <Head title="Présences" />
  <AppLayout :breadcrumbs="breadcrumbs">
    
    <!-- Message flash -->
    <div v-if="showFlash" 
         :class="[
           'fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-md transition-all duration-300',
           flashType === 'success' 
             ? 'bg-success/10 border-success text-success-foreground' 
             : 'bg-destructive/10 border-destructive text-destructive-foreground'
         ]">
      <div class="flex items-start justify-between gap-4">
        <div class="flex-1">
          <h3 class="font-medium">
            {{ flashType === 'success' ? 'Succès' : 'Erreur' }}
          </h3>
          <p class="text-sm mt-1">{{ flashMessage }}</p>
        </div>
        <button @click="showFlash = false" class="text-muted-foreground hover:text-foreground">
          <X class="w-5 h-5" />
        </button>
      </div>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4 mb-8 p-2">
      <div class="bg-card p-5 rounded-xl border flex items-center gap-3">
        <Users class="text-primary w-6 h-6"/>
        <div><p class="text-sm text-muted-foreground">Total stagiaires</p><p class="text-2xl font-bold">{{ filteredAndSortedData.length }}</p></div>
      </div>
      <div class="bg-card p-5 rounded-xl border flex items-center gap-3">
        <Calendar class="text-success w-6 h-6"/>
        <div><p class="text-sm text-muted-foreground">Présents</p><p class="text-2xl font-bold">{{ presentCount }}</p></div>
      </div>
      <div class="bg-card p-5 rounded-xl border flex items-center gap-3">
        <Users class="text-destructive w-6 h-6"/>
        <div><p class="text-sm text-muted-foreground">Absents</p><p class="text-2xl font-bold">{{ absentCount }}</p></div>
      </div>
      <div class="bg-card p-5 rounded-xl border flex items-center gap-3">
        <Clock class="text-warning w-6 h-6"/>
        <div><p class="text-sm text-muted-foreground">En retard</p><p class="text-2xl font-bold">{{ lateCount }}</p></div>
      </div>
    </div>

    <div class="p-2">
      <!-- Actions -->
      <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-6">
        <div>
          <h1 class="text-3xl font-bold">Tableau de présence : {{ presenceCount }}</h1>
          <p class="text-muted-foreground">BTS 2 Génie Logiciel / DQP</p>
        </div>
        <div class="flex gap-2">
          <Link :href="route('presences.add')" class="btn btn-success">
            <Pen class="w-5 h-5"/>Ajouter
          </Link>
          <button class="btn btn-success">
            <Download class="w-5 h-5"/>Exporter
          </button>
        </div>
      </div>

      <!-- Recherche & filtre -->
      <div class="flex flex-col md:flex-row gap-4 mb-4">
        <div class="relative flex-1">
          <Search class="absolute left-3 top-3 text-muted-foreground w-5 h-5"/>
          <input 
            v-model="searchTerm" 
            @input="setCurrentPage(1)" 
            placeholder="Rechercher nom/email" 
            class="input pl-10"
          />
        </div>
        <select 
          v-model="filterStatus" 
          @change="setCurrentPage(1)" 
          class="input"
        >
          <option value="all">Tous</option>
          <option value="present">Présents</option>
          <option value="absent">Absents</option>
          <option value="late">Retard</option>
        </select>
      </div>

      <!-- Tableau -->
      <div class="overflow-x-auto">
        <table class="min-w-full table-auto text-left text-sm bg-card rounded-xl border">
          <thead class="bg-muted">
            <tr>
              <th @click="handleSort('date')" class="th-sort px-4 py-2">Date <SortIcon field="date" :sortField="sortField" :direction="sortDirection"/></th>
              <th class="px-4 py-2">Nom</th>
              <th class="px-4 py-2">E‑mail</th>
              <th @click="handleSort('arrival_time')" class="th-sort px-4 py-2">Arrivée <SortIcon field="arrival_time" :sortField="sortField" :direction="sortDirection"/></th>
              <th @click="handleSort('departure_time')" class="th-sort px-4 py-2">Départ <SortIcon field="departure_time" :sortField="sortField" :direction="sortDirection"/></th>
              <th @click="handleSort('late_minutes')" class="th-sort px-4 py-2">Retard <SortIcon field="late_minutes" :sortField="sortField" :direction="sortDirection"/></th>
              <th class="px-4 py-2">Absent</th>
              <th class="px-4 py-2">En retard</th>
              <th colspan="2" class="px-4 py-2 text-center">Actions sur la présence</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="r in paginatedData" :key="r.id" class="hover:bg-muted/50 border-t">
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
              <td class="px-4 py-2">
                <Badge :type="r.absent ? 'destructive' : 'success'">
                  {{ r.absent ? 'Oui' : 'Non' }}
                </Badge>
              </td>
              <td class="px-4 py-2">
                <Badge :type="r.late ? 'warning' : 'success'">
                  {{ r.late ? 'Oui' : 'Non' }}
                </Badge>
              </td>
              <td class="px-4 py-2">
                <Link :href="route('presences.edit', { id: r.id })" prefetch class="text-primary hover:underline">
                  <Pen class="w-4 h-4 inline"/> Editer
                </Link>
              </td>
              <td class="px-4 py-2">
                <button @click="deletePresence(r.id)" class="text-destructive hover:underline">
                  <Trash2 class="w-4 h-4 inline"/> Suppr
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="flex justify-between items-center py-4">
        <div>
          <span class="text-muted-foreground">Afficher</span>
          <select 
            v-model="itemsPerPage" 
            @change="setCurrentPage(1)" 
            class="input mx-2"
          >
            <option v-for="n of [5,10,20,50]" :key="n">{{ n }}</option>
          </select>
          <span class="text-muted-foreground">sur {{ filteredAndSortedData.length }}</span>
        </div>
        <div class="flex items-center gap-2">
          <button 
            :disabled="currentPage === 1" 
            @click="setCurrentPage(currentPage-1)" 
            class="btn btn-outline disabled:opacity-50"
          >
            <ChevronLeft class="w-4 h-4"/>
          </button>
          <span class="text-muted-foreground">Page {{ currentPage }} / {{ totalPages }}</span>
          <button 
            :disabled="currentPage === totalPages" 
            @click="setCurrentPage(currentPage+1)" 
            class="btn btn-outline disabled:opacity-50"
          >
            <ChevronRight class="w-4 h-4"/>
          </button>
        </div>
      </div>
    </div>
  </AppLayout>
</template>


<style scoped>
.th-sort { cursor: pointer; }
</style>
