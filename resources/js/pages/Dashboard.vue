<script setup lang="ts">
import BarChart from '@/components/Charts/BarChart.vue';
import LineChart from '@/components/Charts/LineChart.vue';
import PieChart from '@/components/Charts/PieChart.vue';
import AppLayoutUser from '@/layouts/AppLayoutUser.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref } from 'vue';

import type { BreadcrumbItem } from '@/types';

const props = defineProps<{
    total?: number;
    present?: number;
    absent?: number;
    late?: number;
    lateMinutes?: number;
    weekStats?: Record<string, { present: number; absent: number }>;
    monthlyStats?: { month: string; rate: number }[];
    selectedDate?: string;
    isSuperAdmin?: boolean;
}>();

const { total = 0, present = 0, absent = 0, late = 0, lateMinutes = 0, weekStats = {}, monthlyStats = [], isSuperAdmin = false } = props;

const date = ref(props.selectedDate || new Date().toISOString().slice(0, 10));

const presenceRate = total > 0 ? Math.round((present / total) * 100) : 0;
const absenceRate = total > 0 ? Math.round((absent / total) * 100) : 0;

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

function filterByDate() {
    router.get(route('user.dashboard'), { date: date.value }, { preserveState: true, replace: true });
}
</script>

<template>
    <Head title="Tableau de bord Utilisateur" />

    <AppLayoutUser :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-4">
            <!-- Filtre par date -->
            <form @submit.prevent="filterByDate" class="mb-6 flex items-center gap-2">
                <label for="date" class="font-medium">Date :</label>
                <input id="date" type="date" v-model="date" class="input" />
                <button type="submit" class="btn btn-primary">Filtrer</button>
            </form>

            <!-- Cartes statistiques -->
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-xl border bg-card p-5 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="rounded-full bg-blue-100 p-3 text-xl text-primary">👤</div>
                        <div>
                            <p class="text-sm text-muted-foreground">Total présences</p>
                            <p class="text-2xl font-bold">{{ total }}</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-xl border bg-card p-5 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="rounded-full bg-green-100 p-3 text-xl text-primary">✅</div>
                        <div>
                            <p class="text-sm text-muted-foreground">Présences</p>
                            <p class="text-2xl font-bold">{{ present }}</p>
                            <p class="mt-1 text-xs text-primary">{{ presenceRate }}% de présence</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-xl border bg-card p-5 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="rounded-full bg-red-100 p-3 text-xl text-red-600">❌</div>
                        <div>
                            <p class="text-sm text-muted-foreground">Absences</p>
                            <p class="text-2xl font-bold">{{ absent }}</p>
                            <p class="mt-1 text-xs text-red-600">{{ absenceRate }}% d'absence</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-xl border bg-card p-5 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="rounded-full bg-orange-100 p-3 text-xl text-orange-600">⏰</div>
                        <div>
                            <p class="text-sm text-muted-foreground">Retards</p>
                            <p class="text-2xl font-bold">{{ late }}</p>
                            <p v-if="late > 0" class="mt-1 text-xs text-orange-600">{{ lateMinutes }} minutes total</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Graphiques -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- PieChart -->
                <div class="rounded-xl border bg-card p-6">
                    <h3 class="mb-4 text-lg font-semibold">Répartition des présences</h3>
                    <div class="h-80">
                        <PieChart
                            :data="{
                                labels: ['Présent', 'Absent', 'Retard'],
                                datasets: [
                                    {
                                        data: [present, absent, late],
                                        backgroundColor: ['#654bc3', '#EF4444', '#b6b2ff'],
                                    },
                                ],
                            }"
                        />
                    </div>
                </div>
                <!-- BarChart -->
                <div class="rounded-xl border bg-card p-6">
                    <h3 class="mb-4 text-lg font-semibold">Présence cette semaine</h3>
                    <div class="h-80">
                        <BarChart
                            v-if="Object.keys(weekStats).length > 0"
                            :data="{
                                labels: Object.keys(weekStats),
                                datasets: [
                                    {
                                        label: 'Présent',
                                        data: Object.values(weekStats).map((d) => d?.present || 0),
                                        backgroundColor: '#654bc3',
                                    },
                                    {
                                        label: 'Absent',
                                        data: Object.values(weekStats).map((d) => d?.absent || 0),
                                        backgroundColor: '#EF4444',
                                    },
                                ],
                            }"
                        />
                        <div v-else class="flex h-full items-center justify-center text-muted-foreground">Aucune donnée disponible cette semaine</div>
                    </div>
                </div>
            </div>
            <!-- LineChart -->
            <div class="rounded-xl border bg-card p-6">
                <h3 class="mb-4 text-lg font-semibold">Tendance mensuelle</h3>
                <div class="h-80">
                    <LineChart
                        v-if="monthlyStats.length > 0"
                        :data="{
                            labels: monthlyStats.map((m) => m.month),
                            datasets: [
                                {
                                    label: 'Taux de présence',
                                    data: monthlyStats.map((m) => m.rate),
                                    borderColor: '#654bc3',
                                    fill: true,
                                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                                },
                            ],
                        }"
                    />
                    <div v-else class="flex h-full items-center justify-center text-muted-foreground">Aucune donnée mensuelle disponible</div>
                </div>
            </div>

            <div class="mt-8">
                <Link v-if="isSuperAdmin" :href="route('dashboard.superadmin')" prefetch class="btn btn-primary text-red-700">
                    Accéder au dashboard Super Admin
                </Link>
            </div>
            <!-- <p>isSuperAdmin: {{ isSuperAdmin }}</p> -->
        </div>
    </AppLayoutUser>
</template>
