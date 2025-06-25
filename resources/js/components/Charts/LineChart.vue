<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount, watch } from 'vue';
import { Chart, LineController, LineElement, CategoryScale, LinearScale, PointElement, Tooltip, Legend } from 'chart.js';
Chart.register(LineController, LineElement, CategoryScale, LinearScale, PointElement, Tooltip, Legend);

interface Props {
  data: import('chart.js').ChartData<'line'>;
  options?: import('chart.js').ChartOptions<'line'>;
}
const props = defineProps<Props>();
const canvasRef = ref<HTMLCanvasElement | null>(null);
let chart: Chart<'line'> | null = null;

onMounted(() => {
  if (canvasRef.value) {
    chart = new Chart(canvasRef.value, {
      type: 'line',
      data: props.data,
      options: props.options,
    });
  }
});

onBeforeUnmount(() => {
  if (chart) {
    chart.destroy();
  }
});

watch(
  () => props.data,
  (newData) => {
    if (chart) {
      chart.data = newData;
      chart.update();
    }
  },
  { deep: true }
);
</script>

<template>
  <canvas ref="canvasRef"></canvas>
</template>