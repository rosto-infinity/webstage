<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { PieChart, BarChart, LineChart } from 'vue-chartjs';
import { Users, Calendar, Clock, AlertCircle } from 'lucide-vue-next';
import type { BreadcrumbItem } from '@/types';
import { computed } from 'vue';

const props = defineProps<{
  stats: {
    total: number;
    present: number;
    absent: number;
    late: number;
  };
  dailyPresence: Array<{
    day: string;
    present: number;
    absent: number;
  }>;
  monthlyTrend: Array<{
    month: string;
    rate: number;
  }>;
  absenceReasons: Array<{
    reason: string;
    count: number;
  }>;
}>();

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Dashboard', href: '/dashboard' }];

// Calculs réactifs pour les graphiques
const pieData = computed(() => ({
  labels: ['Présents', 'Absents', 'Retards'],
  datasets: [{
    data: [props.stats.present, props.stats.absent, props.stats.late],
    backgroundColor: ['#10B981', '#EF4444', '#F59E0B']
  }]
}));

const barData = computed(() => ({
  labels: props.dailyPresence.map(d => d.day),
  datasets: [
    {
      label: 'Présents',
      data: props.dailyPresence.map(d => d.present),
      backgroundColor: '#10B981'
    },
    {
      label: 'Absents',
      data: props.dailyPresence.map(d => d.absent),
      backgroundColor: '#EF4444'
    }
  ]
}));

const lineData = computed(() => ({
  labels: props.monthlyTrend.map(m => m.month),
  datasets: [{
    label: 'Taux de présence',
    data: props.monthlyTrend.map(m => m.rate),
    borderColor: '#3B82F6',
    fill: true,
    tension: 0.3
  }]
}));

const reasonsData = computed(() => ({
  labels: props.absenceReasons.map(r => r.reason),
  datasets: [{
    data: props.absenceReasons.map(r => r.count),
    backgroundColor: ['#F59E0B', '#EF4444', '#8B5CF6', '#64748B']
  }]
}));
</script>

<template>
  <AppLayout :breadcrumbs="breadcrumbs">
    <div class="p-6 space-y-6">
      <!-- Cartes de statistiques -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <StatCard
          icon="Users"
          :value="stats.total"
          label="Total étudiants"
          color="blue"
        />
        <StatCard
          icon="Calendar"
          :value="stats.present"
          label="Présents aujourd'hui"
          color="green"
        />
        <StatCard
          icon="AlertCircle"
          :value="stats.absent"
          label="Absents aujourd'hui"
          color="red"
        />
        <StatCard
          icon="Clock"
          :value="stats.late"
          label="Retards aujourd'hui"
          color="orange"
        />
      </div>

      <!-- Graphiques -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Camembert des présences journalières -->
        <ChartContainer title="Répartition aujourd'hui">
          <PieChart
            :chart-data="pieData"
            :options="{
              responsive: true,
              plugins: {
                tooltip: {
                  callbacks: {
                    label: (ctx) => `${ctx.label}: ${ctx.raw} (${Math.round(ctx.raw * 100 / stats.total)}%)`
                  }
                }
              }
            }"
          />
        </ChartContainer>

        <!-- Barres des présences hebdomadaires -->
        <ChartContainer title="Présence hebdomadaire">
          <BarChart
            :chart-data="barData"
            :options="{
              scales: {
                y: { max: stats.total }
              },
              plugins: {
                tooltip: {
                  callbacks: {
                    afterLabel: (ctx) => {
                      const dayData = dailyPresence[ctx.dataIndex];
                      return `Taux: ${Math.round(dayData.present * 100 / (dayData.present + dayData.absent))}%`;
                    }
                  }
                }
              }
            }"
          />
        </ChartContainer>

        <!-- Courbe des tendances mensuelles -->
        <ChartContainer title="Tendance mensuelle">
          <LineChart
            :chart-data="lineData"
            :options="{
              scales: {
                y: {
                  min: 0,
                  max: 100,
                  ticks: {
                    callback: (value) => `${value}%`
                  }
                }
              }
            }"
          />
        </ChartContainer>

        <!-- Motifs d'absence -->
        <ChartContainer title="Motifs d'absence ce mois">
          <BarChart
            :chart-data="reasonsData"
            :options="{
              plugins: {
                legend: { display: false }
              }
            }"
          />
        </ChartContainer>
      </div>
    </div>
  </AppLayout>
</template>
