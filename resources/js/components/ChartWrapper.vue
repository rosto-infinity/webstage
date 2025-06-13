<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount, watch } from 'vue';
import {
  Chart,
  BarController,
  LineController,
  PieController,
  CategoryScale,
  LinearScale,
  TimeScale,
  ArcElement,
  BarElement,
  LineElement,
  PointElement,
  Tooltip,
  Legend
} from 'chart.js';
import 'chartjs-adapter-date-fns';

Chart.register(
  BarController,
  LineController,
  PieController,
  CategoryScale,
  LinearScale,
  TimeScale,
  ArcElement,
  BarElement,
  LineElement,
  PointElement,
  Tooltip,
  Legend
);

interface Props<T extends ChartType> {
  type: T;
  data: import('chart.js').ChartData<T>;
  options?: import('chart.js').ChartOptions<T>;
}
const props = defineProps<Props<any>>();

const canvasRef = ref<HTMLCanvasElement | null>(null);
let chart: Chart | null = null;

onMounted(() => {
  if (canvasRef.value) {
    chart = new Chart(canvasRef.value, {
      type: props.type,
      data: props.data,
      options: props.options
    });
  }
});
onBeforeUnmount(() => chart?.destroy());
watch(() => props.data, (n) => {
  if (chart) {
    chart.data = n;
    chart.update();
  }
}, { deep: true });
</script>

<template>
  <canvas ref="canvasRef" class="w-full h-80"></canvas>
</template>
