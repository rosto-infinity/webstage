<script setup lang="ts">
import { BarController, BarElement, CategoryScale, Chart, Legend, LinearScale, Tooltip } from 'chart.js';
import { onBeforeUnmount, onMounted, ref, watch } from 'vue';
Chart.register(BarController, BarElement, CategoryScale, LinearScale, Tooltip, Legend);

interface Props {
    data: import('chart.js').ChartData<'bar'>;
    options?: import('chart.js').ChartOptions<'bar'>;
}
const props = defineProps<Props>();
const canvasRef = ref<HTMLCanvasElement | null>(null);
let chart: Chart<'bar'> | null = null;

onMounted(() => {
    if (canvasRef.value) {
        chart = new Chart(canvasRef.value, {
            type: 'bar',
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
    { deep: true },
);
</script>

<template>
    <canvas ref="canvasRef"></canvas>
</template>
