<script setup lang="ts">
import {
    ArcElement,
    BarController,
    BarElement,
    CategoryScale,
    Chart,
    Legend,
    LinearScale,
    LineController,
    LineElement,
    PieController,
    PointElement,
    TimeScale,
    Tooltip,
} from 'chart.js';
import 'chartjs-adapter-date-fns';
import { onBeforeUnmount, onMounted, ref, watch } from 'vue';

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
    Legend,
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
            options: props.options,
        });
    }
});
onBeforeUnmount(() => chart?.destroy());
watch(
    () => props.data,
    (n) => {
        if (chart) {
            chart.data = n;
            chart.update();
        }
    },
    { deep: true },
);
</script>

<template>
    <canvas ref="canvasRef" class="h-80 w-full"></canvas>
</template>
