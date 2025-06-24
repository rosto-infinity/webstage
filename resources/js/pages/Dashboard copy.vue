<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import AppLayoutUser from '@/layouts/AppLayoutUser.vue';
import PieChart from '@/components/Charts/PieChart.vue';
import BarChart from '@/components/Charts/BarChart.vue';
import LineChart from '@/components/Charts/LineChart.vue';

import type { BreadcrumbItem } from '@/types';

// Définition des props avec valeurs par défaut
const props = defineProps<{
  total?: number;
  present?: number;
  absent?: number;
  late?: number;
  lateMinutes?: number;
  weekStats?: Record<string, {present: number, absent: number}>;
  monthlyStats?: {month: string, rate: number}[];
}>();

// Destructuration avec valeurs par défaut
const { 
  total = 0,
  present = 0, 
  absent = 0,
  late = 0,
  lateMinutes = 0,
  weekStats = {},
  monthlyStats = []
} = props;

// Calcul des pourcentages
const presenceRate = total > 0 ? Math.round((present / total) * 100) : 0;
const absenceRate = total > 0 ? Math.round((absent / total) * 100) : 0;

const breadcrumbs: BreadcrumbItem[] = [{ 
  title: 'Dashboard',
  href: '/dashboard' 
}];
</script>

<template>
  <Head title="Tableau de bord Utilisateur" />
  
  <AppLayoutUser :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-6 p-4">
      <!-- Section supérieure - Cartes statistiques -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Carte Présences totales -->
        <div class="bg-card dark:bg-card-dark rounded-xl border border-border dark:border-border-dark p-5 shadow-sm hover:shadow-md transition-shadow">
          <div class="flex items-center gap-4">
            <div class="p-3 rounded-full bg-blue-100 dark:bg-blue-900/20">
              <span class="text-blue-600 dark:text-blue-400 text-xl">👤</span>
            </div>
            <div>
              <p class="text-sm text-muted-foreground">Total présences</p>
              <p class="text-2xl font-bold">{{ total }}</p>
            </div>
          </div>
        </div>

        <!-- Carte Présences -->
        <div class="bg-card dark:bg-card-dark rounded-xl border border-border dark:border-border-dark p-5 shadow-sm hover:shadow-md transition-shadow">
          <div class="flex items-center gap-4">
            <div class="p-3 rounded-full bg-green-100 dark:bg-green-900/20">
              <span class="text-green-600 dark:text-green-400 text-xl">✅</span>
            </div>
            <div>
              <p class="text-sm text-muted-foreground">Présences</p>
              <p class="text-2xl font-bold">{{ present }}</p>
              <p class="text-xs text-green-600 dark:text-green-400 mt-1">
                {{ presenceRate }}% de présence
              </p>
            </div>
          </div>
        </div>

        <!-- Carte Absences -->
        <div class="bg-card dark:bg-card-dark rounded-xl border border-border dark:border-border-dark p-5 shadow-sm hover:shadow-md transition-shadow">
          <div class="flex items-center gap-4">
            <div class="p-3 rounded-full bg-red-100 dark:bg-red-900/20">
              <span class="text-red-600 dark:text-red-400 text-xl">❌</span>
            </div>
            <div>
              <p class="text-sm text-muted-foreground">Absences</p>
              <p class="text-2xl font-bold">{{ absent }}</p>
              <p class="text-xs text-red-600 dark:text-red-400 mt-1">
                {{ absenceRate }}% d'absence
              </p>
            </div>
          </div>
        </div>

        <!-- Carte Retards -->
        <div class="bg-card dark:bg-card-dark rounded-xl border border-border dark:border-border-dark p-5 shadow-sm hover:shadow-md transition-shadow">
          <div class="flex items-center gap-4">
            <div class="p-3 rounded-full bg-orange-100 dark:bg-orange-900/20">
              <span class="text-orange-600 dark:text-orange-400 text-xl">⏰</span>
            </div>
            <div>
              <p class="text-sm text-muted-foreground">Retards</p>
              <p class="text-2xl font-bold">{{ late }}</p>
              <p v-if="late > 0" class="text-xs text-orange-600 dark:text-orange-400 mt-1">
                {{ lateMinutes }} minutes total
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Section centrale - Graphiques -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Graphique Camembert -->
        <div class="bg-card dark:bg-card-dark rounded-xl border border-border dark:border-border-dark p-6">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold">Répartition des présences</h3>
            <div class="flex gap-2 text-xs">
              <span class="flex items-center"><span class="w-2 h-2 mr-1 bg-green-500 rounded-full"></span> Présent</span>
              <span class="flex items-center"><span class="w-2 h-2 mr-1 bg-red-500 rounded-full"></span> Absent</span>
              <span class="flex items-center"><span class="w-2 h-2 mr-1 bg-orange-500 rounded-full"></span> Retard</span>
            </div>
          </div>
          <div class="h-80">
            <PieChart
              :data="{
                labels: ['Présent', 'Absent', 'Retard'],
                datasets: [{ 
                  data: [present, absent, late],
                  backgroundColor: ['#10B981', '#EF4444', '#F59E0B']
                }]
              }"
            />
          </div>
        </div>

        <!-- Graphique Barres Hebdomadaire -->
        <div class="bg-card dark:bg-card-dark rounded-xl border border-border dark:border-border-dark p-6">
          <h3 class="text-lg font-semibold mb-4">Présence cette semaine</h3>
          <div class="h-80">
            <BarChart
              v-if="Object.keys(weekStats).length > 0"
              :data="{
                labels: Object.keys(weekStats),
                datasets: [
                  {
                    label: 'Présent',
                    data: Object.values(weekStats).map(d => d?.present || 0),
                    backgroundColor: '#10B981'
                  },
                  {
                    label: 'Absent',
                    data: Object.values(weekStats).map(d => d?.absent || 0),
                    backgroundColor: '#EF4444'
                  }
                ]
              }"
            />
            <div v-else class="h-full flex items-center justify-center text-muted-foreground">
              Aucune donnée disponible cette semaine
            </div>
          </div>
        </div>
      </div>

      <!-- Graphique Ligne Mensuel -->
      <div class="bg-card dark:bg-card-dark rounded-xl border border-border dark:border-border-dark p-6">
        <h3 class="text-lg font-semibold mb-4">Tendance mensuelle</h3>
        <div class="h-80">
          <LineChart
            v-if="monthlyStats.length > 0"
            :data="{
              labels: monthlyStats.map(m => m.month),
              datasets: [{
                label: 'Taux de présence',
                data: monthlyStats.map(m => m.rate),
                borderColor: '#3B82F6',
                fill: true,
                backgroundColor: 'rgba(59, 130, 246, 0.1)'
              }]
            }"
          />
          <div v-else class="h-full flex items-center justify-center text-muted-foreground">
            Aucune donnée mensuelle disponible
          </div>
        </div>
      </div>

      <!-- Section inférieure - Liens rapides -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-card dark:bg-card-dark rounded-xl border border-border dark:border-border-dark p-4">
          <Link 
            v-if="$page.props.auth.user"
            :href="route('dashboard.superadmin')" 
            class="flex items-center gap-2 p-3 rounded-lg bg-primary text-primary-foreground hover:bg-primary/90 transition-colors"
          >
            <span>Gestion stage</span>
          </Link>
        </div>
        <div class="bg-card dark:bg-card-dark rounded-xl border border-border dark:border-border-dark p-4">
         
        </div>
        <div class="bg-card dark:bg-card-dark rounded-xl border border-border dark:border-border-dark p-4">
         
        </div>
      </div>
    </div>
  </AppLayoutUser>
</template>

<style scoped>
/* Styles spécifiques si nécessaire */
</style>
