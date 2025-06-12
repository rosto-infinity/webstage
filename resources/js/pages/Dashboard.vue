<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
// Corrigez vos imports comme ceci :
import PieChart from '@/components/Charts/PieChart.vue';
import LineChart from '@/components/Charts/PieChart.vue'; // Supposant que ce fichier existe
import BarChart from '@/components/Charts/PieChart.vue'; // Supposant que ce fichier existe
import { Users, Calendar, Clock, AlertCircle } from 'lucide-vue-next';

// Données simulées (à remplacer par vos données réelles)
const stats = {
  total: 42,
  present: 32,
  absent: 5,
  late: 10
};

const dailyPresenceData = [
  { day: 'Lun', present: 38, absent: 4 },
  { day: 'Mar', present: 35, absent: 7 },
  { day: 'Mer', present: 40, absent: 2 },
  { day: 'Jeu', present: 32, absent: 10 },
  { day: 'Ven', present: 36, absent: 6 },
];

const monthlyTrend = [
  { month: 'Jan', rate: 82 },
  { month: 'Fév', rate: 85 },
  { month: 'Mar', rate: 78 },
  { month: 'Avr', rate: 88 },
  { month: 'Mai', rate: 90 },
  { month: 'Juin', rate: 92 },
];

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Dashboard',
    href: '/dashboard',
  },
];
</script>

<template>
  <Head title="Dashboard Présence" />

  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="flex h-full flex-1 flex-col gap-6 rounded-xl p-4">
      <!-- Cartes de statistiques -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
          <div class="flex items-center gap-3">
            <div class="p-3 rounded-lg bg-blue-50 text-blue-600">
              <Users class="w-5 h-5" />
            </div>
            <div>
              <p class="text-sm text-gray-500">Total Étudiants</p>
              <p class="text-2xl font-bold">{{ stats.total }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
          <div class="flex items-center gap-3">
            <div class="p-3 rounded-lg bg-green-50 text-green-600">
              <Calendar class="w-5 h-5" />
            </div>
            <div>
              <p class="text-sm text-gray-500">Présents (Aujourd'hui)</p>
              <p class="text-2xl font-bold">{{ stats.present }}</p>
              <p class="text-xs text-green-600 mt-1">
                {{ Math.round((stats.present / stats.total) * 100) }}% de présence
              </p>
            </div>
          </div>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
          <div class="flex items-center gap-3">
            <div class="p-3 rounded-lg bg-red-50 text-red-600">
              <AlertCircle class="w-5 h-5" />
            </div>
            <div>
              <p class="text-sm text-gray-500">Absents (Aujourd'hui)</p>
              <p class="text-2xl font-bold">{{ stats.absent }}</p>
              <p class="text-xs text-red-600 mt-1">
                {{ Math.round((stats.absent / stats.total) * 100) }}% d'absence
              </p>
            </div>
          </div>
        </div>

        <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-100">
          <div class="flex items-center gap-3">
            <div class="p-3 rounded-lg bg-orange-50 text-orange-600">
              <Clock class="w-5 h-5" />
            </div>
            <div>
              <p class="text-sm text-gray-500">Retards (Aujourd'hui)</p>
              <p class="text-2xl font-bold">{{ stats.late }}</p>
              <p class="text-xs text-orange-600 mt-1">
                Moyenne: 8 min
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Première ligne de graphiques -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Répartition des statuts -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Répartition des statuts (Aujourd'hui)</h3>
          <PieChart 
            :data="{
              labels: ['Présents', 'Absents', 'Retards'],
              datasets: [{
                data: [stats.present, stats.absent, stats.late],
                backgroundColor: ['#10B981', '#EF4444', '#F59E0B'],
                borderWidth: 0
              }]
            }" 
            :options="{
              responsive: true,
              plugins: {
                legend: {
                  position: 'right'
                },
                tooltip: {
                  callbacks: {
                    label: function(context) {
                      const label = context.label || '';
                      const value = context.raw || 0;
                      const percentage = Math.round((value / stats.total) * 100);
                      return `${label}: ${value} (${percentage}%)`;
                    }
                  }
                }
              }
            }"
            class="h-80"
          />
        </div>

        <!-- Présence par jour de la semaine -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Présence hebdomadaire</h3>
          <BarChart
            :data="{
              labels: dailyPresenceData.map(d => d.day),
              datasets: [
                {
                  label: 'Présents',
                  data: dailyPresenceData.map(d => d.present),
                  backgroundColor: '#10B981',
                  borderRadius: 4
                },
                {
                  label: 'Absents',
                  data: dailyPresenceData.map(d => d.absent),
                  backgroundColor: '#EF4444',
                  borderRadius: 4
                }
              ]
            }"
            :options="{
              responsive: true,
              scales: {
                x: {
                  grid: {
                    display: false
                  }
                },
                y: {
                  beginAtZero: true,
                  max: stats.total,
                  ticks: {
                    stepSize: 5
                  }
                }
              },
              plugins: {
                tooltip: {
                  callbacks: {
                    afterLabel: function(context) {
                      const datasetIndex = context.datasetIndex;
                      const value = context.raw;
                      const total = datasetIndex === 0 
                        ? value + dailyPresenceData[context.dataIndex].absent
                        : value + dailyPresenceData[context.dataIndex].present;
                      const percentage = Math.round((value / total) * 100);
                      return `Taux: ${percentage}%`;
                    }
                  }
                }
              }
            }"
            class="h-80"
          />
        </div>
      </div>

      <!-- Deuxième ligne de graphiques -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Tendance mensuelle -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Tendance mensuelle de présence</h3>
          <LineChart
            :data="{
              labels: monthlyTrend.map(m => m.month),
              datasets: [{
                label: 'Taux de présence',
                data: monthlyTrend.map(m => m.rate),
                borderColor: '#3B82F6',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                fill: true,
                tension: 0.3
              }]
            }"
            :options="{
              responsive: true,
              scales: {
                y: {
                  min: 50,
                  max: 100,
                  ticks: {
                    callback: function(value) {
                      return value + '%';
                    }
                  }
                }
              },
              plugins: {
                tooltip: {
                  callbacks: {
                    label: function(context) {
                      return context.parsed.y + '%';
                    }
                  }
                }
              }
            }"
            class="h-80"
          />
        </div>

        <!-- Raisons d'absence -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
          <h3 class="text-lg font-semibold text-gray-900 mb-4">Motifs d'absence (Mois en cours)</h3>
          <BarChart
            :data="{
              labels: ['Maladie', 'Problème transport', 'Familiale', 'Autre'],
              datasets: [{
                label: 'Nombre',
                data: [12, 8, 5, 3],
                backgroundColor: [
                  '#F59E0B',
                  '#EF4444',
                  '#8B5CF6',
                  '#64748B'
                ],
                borderRadius: 4
              }]
            }"
            :options="{
              responsive: true,
              scales: {
                x: {
                  grid: {
                    display: false
                  }
                },
                y: {
                  beginAtZero: true
                }
              },
              plugins: {
                legend: {
                  display: false
                }
              }
            }"
            class="h-80"
          />
        </div>
      </div>
    </div>
  </AppLayout>
</template>
