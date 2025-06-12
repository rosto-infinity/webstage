<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount, watch } from 'vue';
import { Chart, PieController, ArcElement, Tooltip, Legend, type ChartData } from 'chart.js';

Chart.register(PieController, ArcElement, Tooltip, Legend);

interface ChartProps {
  data: ChartData<'pie'>;
  options?: any;
}

const props = defineProps<ChartProps>();

const canvasRef = ref<HTMLCanvasElement | null>(null);
let chartInstance: Chart<'pie'> | null = null;

onMounted(() => {
  if (canvasRef.value && props.data.datasets) { // Vérification supplémentaire
    chartInstance = new Chart(canvasRef.value, {
      type: 'pie',
      data: props.data,
      options: props.options
    });
  }
});

onBeforeUnmount(() => {
  if (chartInstance) {
    chartInstance.destroy();
  }
});

watch(() => props.data, (newData) => {
  if (chartInstance) {
    chartInstance.data = newData;
    chartInstance.update();
  }
}, { deep: true });
</script>

<template>
  <canvas ref="canvasRef"></canvas>
</template>
