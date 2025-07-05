<script setup lang="ts">
import BarChart from '@/components/Charts/BarChart.vue';
import LineChart from '@/components/Charts/LineChart.vue';
import PieChart from '@/components/Charts/PieChart.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';
import { router } from '@inertiajs/vue3';
import { AlertCircle, Calendar, Clock, Users } from 'lucide-vue-next';
import { computed, ref } from 'vue';

const props = defineProps<{
    totalUsers: number;
    presenceCount: number;
    Countpresent: number;
    Countabsent: number;
    Countlate: number;
    selectedDate?: string;
    selectedMonth?: string;
    selectedWeek?: string;
    selectedUser?: string;
    users: Array<string>;
    weeklyPresence: Array<{ day: string; present: number; absent: number }>;
    monthlyTrend: Array<{ day: string; rate: number }>;
    absenceReasons: Array<{ label: string; value: number; color: string }>;
    filterType: 'day' | 'week' | 'month';
}>();

const processing = ref(false);
const date = ref(props.selectedDate || new Date().toISOString().slice(0, 10));
const month = ref(props.selectedMonth || new Date().toISOString().slice(0, 7));
const week = ref(props.selectedWeek || new Date().toISOString().slice(0, 10));
const selectedUser = ref(props.selectedUser || '');
const filterType = ref(props.filterType || 'day');

// Calcul des statistiques
const stats = computed(() => ({
    total: props.totalUsers,
    present: props.Countpresent,
    absent: props.Countabsent,
    late: props.Countlate,
    percentage: props.filterType === 'day' && props.totalUsers > 0 ? Math.round((props.Countpresent / props.totalUsers) * 100) : null,
}));

// Formatage de la période pour les graphiques
const selectedMonthDisplay = computed(() => new Date(month.value).toLocaleString('fr-FR', { month: 'long', year: 'numeric' }));
const weekStart = computed(() => {
    const d = new Date(week.value);
    d.setDate(d.getDate() - d.getDay() + 1); // Lundi de la semaine
    return d.toLocaleDateString('fr-FR');
});
const weekEnd = computed(() => {
    const d = new Date(week.value);
    d.setDate(d.getDate() - d.getDay() + 7); // Dimanche de la semaine
    return d.toLocaleDateString('fr-FR');
});

// Vérification des données vides
const hasWeeklyData = computed(() => props.weeklyPresence.some((d) => d.present > 0 || d.absent > 0));
const hasMonthlyData = computed(() => props.monthlyTrend.some((m) => m.rate > 0));
const hasAbsenceReasons = computed(() => props.absenceReasons.some((r) => r.value > 0));

function filterData() {
    processing.value = true;
    router.get(
        route('dashboard'),
        {
            date: date.value,
            month: month.value,
            week: week.value,
            user: selectedUser.value,
            filterType: filterType.value,
        },
        {
            preserveState: true,
            replace: true,
            onFinish: () => (processing.value = false),
        },
    );
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard Sup_Admin',
        href: '/superadmin/dashboard',
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-2">
            <!-- Filtres -->
            <form @submit.prevent="filterData" class="mb-6 flex flex-col items-center gap-4 md:flex-row">
                <div class="flex items-center gap-2">
                    <label for="filterType" class="font-medium">Type de filtre :</label>
                    <select id="filterType" v-model="filterType" class="rounded-lg border border-input px-4 py-2 focus:ring-2 focus:ring-ring">
                        <option value="day">Jour</option>
                        <option value="week">Semaine</option>
                        <option value="month">Mois</option>
                    </select>
                </div>
                <div class="flex items-center gap-2" v-if="filterType === 'day'">
                    <label for="date" class="font-medium">Jour :</label>
                    <input
                        id="date"
                        type="date"
                        v-model="date"
                        class="rounded-lg border border-input px-4 py-2 focus:ring-2 focus:ring-ring"
                        :max="new Date().toISOString().split('T')[0]"
                    />
                </div>
                <div class="flex items-center gap-2" v-if="filterType === 'month'">
                    <label for="month" class="font-medium">Mois :</label>
                    <input
                        id="month"
                        type="month"
                        v-model="month"
                        class="rounded-lg border border-input px-4 py-2 focus:ring-2 focus:ring-ring"
                        :max="new Date().toISOString().slice(0, 7)"
                    />
                </div>
                <div class="flex items-center gap-2" v-if="filterType === 'week'">
                    <label for="week" class="font-medium">Semaine :</label>
                    <input
                        id="week"
                        type="date"
                        v-model="week"
                        class="rounded-lg border border-input px-4 py-2 focus:ring-2 focus:ring-ring"
                        :max="new Date().toISOString().split('T')[0]"
                    />
                </div>
                <div class="flex items-center gap-2">
                    <label for="user" class="font-medium">Utilisateur :</label>
                    <select id="user" v-model="selectedUser" class="rounded-lg border border-input px-4 py-2 focus:ring-2 focus:ring-ring">
                        <option value="">Tous</option>
                        <option v-for="user in props.users" :key="user" :value="user">{{ user }}</option>
                    </select>
                </div>
                <button
                    type="submit"
                    class="flex items-center gap-2 rounded-lg bg-primary px-4 py-2 text-primary-foreground hover:bg-primary/90"
                    :disabled="processing"
                >
                    <span v-if="!processing">Filtrer</span>
                    <svg v-else class="h-5 w-5 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path
                            class="opacity-75"
                            fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                        ></path>
                    </svg>
                </button>
            </form>

            <!-- Cartes de stats -->
            <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                <div
                    v-for="(item, i) in [
                        { icon: Users, label: 'Total étudiants', value: stats.total, text: 'text-primary' },
                        { icon: Calendar, label: 'Présents', value: stats.present, text: 'text-primary' },
                        { icon: AlertCircle, label: 'Absents', value: stats.absent, text: 'text-red-600' },
                        { icon: Clock, label: 'Retards', value: stats.late, text: 'text-orange-600' },
                    ]"
                    :key="i"
                    class="flex items-center gap-3 rounded-xl border border-border p-5 transition-all hover:shadow-md"
                >
                    <div :class="`rounded-lg p-3 ${item.text} bg-opacity-20`">
                        <component :is="item.icon" class="h-6 w-6" />
                    </div>
                    <div>
                        <p class="text-sm text-muted-foreground">{{ item.label }}</p>
                        <p class="text-2xl font-bold">{{ item.value }}</p>
                        <p v-if="i === 1 && stats.percentage !== null" class="text-xs text-green-500">Taux: {{ stats.percentage }}%</p>
                    </div>
                </div>
            </div>

            <!-- Graphiques -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Graphe camembert -->
                <div class="rounded-xl border border-border p-6 shadow-sm">
                    <h3 class="mb-4 text-lg font-semibold">
                        Présence
                        {{
                            filterType === 'day'
                                ? `le ${new Date(date).toLocaleDateString('fr-FR')}`
                                : filterType === 'week'
                                  ? `du ${weekStart} au ${weekEnd}`
                                  : `pour ${selectedMonthDisplay}`
                        }}
                    </h3>
                    <div v-if="stats.present + stats.absent + stats.late > 0" class="relative h-80">
                        <PieChart
                            :data="{
                                labels: ['Présents', 'Absents', 'Retards'],
                                datasets: [
                                    {
                                        data: [stats.present, stats.absent, stats.late],
                                        backgroundColor: ['#654bc3', '#EF4444', '#b6b2ff'],
                                        borderWidth: 1,
                                    },
                                ],
                            }"
                            :options="{
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'right',
                                        labels: {
                                            usePointStyle: true,
                                            padding: 20,
                                        },
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: (context) => {
                                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                                const value = context.raw as number;
                                                const percentage = Math.round((value / total) * 100);
                                                return `${context.label}: ${value} (${percentage}%)`;
                                            },
                                        },
                                    },
                                },
                            }"
                            class="absolute inset-0"
                        />
                    </div>
                    <div v-else class="flex h-80 items-center justify-center text-center text-muted-foreground">
                        Aucune donnée de présence pour cette période.
                    </div>
                </div>

                <!-- Bar hebdo -->
                <div class="rounded-xl border border-border p-6 shadow-sm">
                    <h3 class="mb-4 text-lg font-semibold">Présence hebdomadaire ({{ weekStart }} - {{ weekEnd }})</h3>
                    <div v-if="hasWeeklyData" class="relative h-80">
                        <BarChart
                            :data="{
                                labels: props.weeklyPresence.map((d) => d.day),
                                datasets: [
                                    {
                                        label: 'Présents',
                                        data: props.weeklyPresence.map((d) => d.present),
                                        backgroundColor: '#654bc3',
                                        borderRadius: 4,
                                        barPercentage: 0.6,
                                    },
                                    {
                                        label: 'Absents',
                                        data: props.weeklyPresence.map((d) => d.absent),
                                        backgroundColor: '#EF4444',
                                        borderRadius: 4,
                                        barPercentage: 0.6,
                                    },
                                ],
                            }"
                            :options="{
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    x: {
                                        grid: { display: false },
                                        stacked: true,
                                    },
                                    y: {
                                        beginAtZero: true,
                                        max: stats.total,
                                        stacked: true,
                                    },
                                },
                                plugins: {
                                    tooltip: {
                                        callbacks: {
                                            afterLabel: (context) => {
                                                const idx = context.dataIndex;
                                                const total = props.weeklyPresence[idx].present + props.weeklyPresence[idx].absent;
                                                return `Taux: ${Math.round(((context.raw as number) / total) * 100)}%`;
                                            },
                                        },
                                    },
                                },
                            }"
                            class="absolute inset-0"
                        />
                    </div>
                    <div v-else class="flex h-80 items-center justify-center text-center text-muted-foreground">
                        Aucune donnée de présence pour cette semaine.
                    </div>
                </div>

                <!-- Line mensuel -->
                <div class="rounded-xl border border-border p-6 shadow-sm">
                    <h3 class="mb-4 text-lg font-semibold">Tendance mensuelle pour {{ selectedMonthDisplay }}</h3>
                    <div v-if="hasMonthlyData" class="relative h-80">
                        <LineChart
                            :data="{
                                labels: props.monthlyTrend.map((m) => m.day),
                                datasets: [
                                    {
                                        label: 'Taux de présence',
                                        data: props.monthlyTrend.map((m) => m.rate),
                                        borderColor: '#654bc3',
                                        backgroundColor: 'rgba(101, 75, 195, 0.1)',
                                        fill: true,
                                        tension: 0.3,
                                        pointBackgroundColor: '#654bc3',
                                        pointRadius: 4,
                                    },
                                ],
                            }"
                            :options="{
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: {
                                        min: 0,
                                        max: 100,
                                        ticks: {
                                            callback: (v) => `${v}%`,
                                        },
                                    },
                                },
                                plugins: {
                                    tooltip: {
                                        callbacks: {
                                            label: (ctx) => `${ctx.parsed.y}%`,
                                        },
                                    },
                                },
                            }"
                            class="absolute inset-0"
                        />
                    </div>
                    <div v-else class="flex h-80 items-center justify-center text-center text-muted-foreground">
                        Aucune donnée de tendance disponible.
                    </div>
                </div>

                <!-- Bar motifs absence -->
                <div class="rounded-xl border border-border p-6 shadow-sm">
                    <h3 class="mb-4 text-lg font-semibold">Motifs d'absence pour {{ selectedMonthDisplay }}</h3>
                    <div v-if="hasAbsenceReasons" class="relative h-80">
                        <BarChart
                            :data="{
                                labels: props.absenceReasons.map((r) => r.label),
                                datasets: [
                                    {
                                        label: 'Nombre',
                                        data: props.absenceReasons.map((r) => r.value),
                                        backgroundColor: props.absenceReasons.map((r) => r.color),
                                        borderRadius: 4,
                                    },
                                ],
                            }"
                            :options="{
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    x: {
                                        grid: { display: false },
                                    },
                                    y: {
                                        beginAtZero: true,
                                    },
                                },
                                plugins: {
                                    legend: {
                                        display: false,
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: (context) => {
                                                const total = props.absenceReasons.reduce((sum, r) => sum + r.value, 0);
                                                const percentage = Math.round(((context.raw as number) / total) * 100);
                                                return `${context.label}: ${context.raw} (${percentage}%)`;
                                            },
                                        },
                                    },
                                },
                            }"
                            class="absolute inset-0"
                        />
                    </div>
                    <div v-else class="flex h-80 items-center justify-center text-center text-muted-foreground">
                        Aucune donnée sur les motifs d'absence pour ce mois.
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<style scoped>
.border-input {
    border-color: hsl(var(--input));
}
.bg-primary {
    background-color: hsl(var(--primary));
}
.text-primary-foreground {
    color: hsl(var(--primary-foreground));
}
.hover\:bg-primary\/90:hover {
    background-color: hsl(var(--primary) / 0.9);
}
</style>
