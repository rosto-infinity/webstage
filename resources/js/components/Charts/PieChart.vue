<!-- src/components/Charts/PieChart.vue -->
<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount, watch } from 'vue';
import { Chart, PieController, ArcElement, Tooltip, Legend } from 'chart.js';
Chart.register(PieController, ArcElement, Tooltip, Legend);

interface Props { data: import('chart.js').ChartData<'pie'>; options?: import('chart.js').ChartOptions<'pie'>; }
const props = defineProps<Props>();
const canvasRef = ref<HTMLCanvasElement|null>(null);
let chart: Chart<'pie'>|null = null;

onMounted(() => {
  if (canvasRef.value) {
    chart = new Chart(canvasRef.value, { type: 'pie', data: props.data, options: props.options });
  }
});
onBeforeUnmount(() => chart?.destroy());
watch(() => props.data, (n) => { chart && (chart.data = n, chart.update()); }, { deep: true });
</script>
<template><canvas ref="canvasRef"></canvas></template>
