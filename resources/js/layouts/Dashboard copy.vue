<script setup lang="ts">
import BarChart from '@/components/Charts/BarChart.vue';
import LineChart from '@/components/Charts/LineChart.vue';
import PieChart from '@/components/Charts/PieChart.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { AlertCircle, Calendar, Clock, Users } from 'lucide-vue-next';

const props = defineProps<{
    totalUsers: number;
    presenceCount: number;
    Countpresent: number;
    Countabsent: number;
    Countlate: number;
}>();

const stats = {
    total: props.totalUsers,
    present: props.Countpresent,
    absent: props.Countabsent,
    late: props.Countlate,
};
const dailyPresence = [
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
const breadcrumbs: BreadcrumbItem[] = [{ title: 'Dashboard', href: '/dashboard' }];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-2">
            rrr
            <!-- Cartes de stats -->
            <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                <div
                    v-for="(item, i) in [
                        { icon: Users, label: 'Total étudiants', value: stats.total, bg: 'bg-blue-50', text: 'text-blue-600' },
                        { icon: Calendar, label: 'Présents', value: stats.present, bg: 'bg-green-50', text: 'text-green-600' },
                        { icon: AlertCircle, label: 'Absents', value: stats.absent, bg: 'bg-red-50', text: 'text-red-600' },
                        { icon: Clock, label: 'Retards', value: stats.late, bg: 'bg-orange-50', text: 'text-orange-600' },
                    ]"
                    :key="i"
                    class="flex items-center gap-3 rounded-xl border bg-white p-5 shadow"
                >
                    <div :class="`${item.bg} rounded-lg p-3 ${item.text}`">
                        <component :is="item.icon" class="h-6 w-6" />
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ item.label }}</p>
                        <p class="text-2xl font-bold">{{ item.value }}</p>
                    </div>
                </div>
            </div>

            <!-- Graphiques -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Graphe camembert -->
                <div class="rounded-xl border bg-white p-6 shadow">
                    <h3 class="mb-4 text-lg font-semibold">Présence aujourd’hui</h3>
                    <div class="relative h-80">
                        <PieChart
                            :data="{
                                labels: ['Présents', 'Absents', 'Retards'],
                                datasets: [{ data: [stats.present, stats.absent, stats.late], backgroundColor: ['#10B981', '#EF4444', '#F59E0B'] }],
                            }"
                            :options="{ responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'right' } } }"
                            class="absolute inset-0"
                        />
                    </div>
                </div>

                <!-- Bar hebdo -->
                <div class="rounded-xl border bg-white p-6 shadow">
                    <h3 class="mb-4 text-lg font-semibold">Présence hebdomadaire</h3>
                    <div class="relative h-80">
                        <BarChart
                            :data="{
                                labels: dailyPresence.map((d) => d.day),
                                datasets: [
                                    { label: 'Présents', data: dailyPresence.map((d) => d.present), backgroundColor: '#10B981', borderRadius: 4 },
                                    { label: 'Absents', data: dailyPresence.map((d) => d.absent), backgroundColor: '#EF4444', borderRadius: 4 },
                                ],
                            }"
                            :options="{
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    x: { grid: { display: false } },
                                    y: { beginAtZero: true, max: stats.total },
                                },
                                plugins: {
                                    tooltip: {
                                        callbacks: {
                                            afterLabel: (ctx) => {
                                                const idx = ctx.dataIndex;
                                                const total = dailyPresence[idx].present + dailyPresence[idx].absent;
                                                return `Taux: ${Math.round(((ctx.raw as number) / total) * 100)}%`;
                                            },
                                        },
                                    },
                                },
                            }"
                            class="absolute inset-0"
                        />
                    </div>
                </div>

                <!-- Line mensuel -->
                <div class="rounded-xl border bg-white p-6 shadow">
                    <h3 class="mb-4 text-lg font-semibold">Tendance mensuelle</h3>
                    <div class="relative h-80">
                        <LineChart
                            :data="{
                                labels: monthlyTrend.map((m) => m.month),
                                datasets: [
                                    {
                                        label: 'Taux de présence',
                                        data: monthlyTrend.map((m) => m.rate),
                                        borderColor: '#3B82F6',
                                        backgroundColor: 'rgba(59,130,246,0.1)',
                                        fill: true,
                                        tension: 0.3,
                                    },
                                ],
                            }"
                            :options="{
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: { y: { min: 50, max: 100, ticks: { callback: (v) => v + '%' } } },
                                plugins: { tooltip: { callbacks: { label: (ctx) => ctx.parsed.y + '%' } } },
                            }"
                            class="absolute inset-0"
                        />
                    </div>
                </div>

                <!-- Bar motifs absence -->
                <div class="rounded-xl border bg-white p-6 shadow">
                    <h3 class="mb-4 text-lg font-semibold">Motifs d’absence (mois en cours)</h3>
                    <div class="relative h-80">
                        <BarChart
                            :data="{
                                labels: ['Maladie', 'Transport', 'Familial', 'Autre'],
                                datasets: [
                                    {
                                        label: 'Nombre',
                                        data: [12, 8, 5, 3],
                                        backgroundColor: ['#F59E0B', '#EF4444', '#8B5CF6', '#64748B'],
                                        borderRadius: 4,
                                    },
                                ],
                            }"
                            :options="{
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: { x: { grid: { display: false } }, y: { beginAtZero: true } },
                                plugins: { legend: { display: false } },
                            }"
                            class="absolute inset-0"
                        />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
/* Suppression de toute règle canvas globale forcée */
</style>
