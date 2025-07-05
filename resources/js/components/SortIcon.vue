<script setup lang="ts">
import { ChevronDown, ChevronUp } from 'lucide-vue-next';
import type { PropType } from 'vue';

const props = defineProps({
    field: {
        type: String,
        required: true,
    },
    sortField: {
        type: String,
        required: true,
    },
    direction: {
        type: String as PropType<'asc' | 'desc'>,
        required: true,
    },
});
</script>

<template>
    <span class="inline-flex items-center">
        <ChevronUp v-if="props.sortField === props.field && props.direction === 'asc'" class="h-4 w-4 text-gray-500" />
        <ChevronDown v-else-if="props.sortField === props.field && props.direction === 'desc'" class="h-4 w-4 text-gray-500" />
    </span>
</template>
