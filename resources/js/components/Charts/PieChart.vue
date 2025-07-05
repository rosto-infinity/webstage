<!-- src/components/Charts/PieChart.vue -->
<script setup lang="ts">
import { ArcElement, Chart, Legend, PieController, Tooltip } from 'chart.js';
import { onBeforeUnmount, onMounted, ref, watch } from 'vue';

// Enregistrement des composants nécessaires de Chart.js
Chart.register(PieController, ArcElement, Tooltip, Legend);

// Définition des props avec TypeScript
interface Props {
    data: import('chart.js').ChartData<'pie'>;
    options?: import('chart.js').ChartOptions<'pie'>;
}
const props = defineProps<Props>();

// Référence pour le canvas et instance du graphique
const canvasRef = ref<HTMLCanvasElement | null>(null);
let chart: Chart<'pie'> | null = null;

// Initialisation du graphique après le montage du composant
onMounted(() => {
    if (canvasRef.value) {
        chart = new Chart(canvasRef.value, {
            type: 'pie',
            data: props.data,
            options: props.options,
        });
    }
});

// Destruction du graphique avant la suppression du composant
onBeforeUnmount(() => {
    if (chart) {
        chart.destroy();
    }
});

// Mise à jour du graphique lorsque les données changent
watch(
    () => props.data,
    (newData) => {
        if (chart) {
            chart.data = newData;
            chart.update();
        }
    },
    { deep: true },
);
</script>

<template>
    <canvas ref="canvasRef"></canvas>
</template>
