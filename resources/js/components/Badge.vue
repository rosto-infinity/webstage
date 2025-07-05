<script setup lang="ts">
import type { PropType } from 'vue';

const props = defineProps({
    type: {
        type: String as PropType<'success' | 'danger' | 'warning' | 'info'>,
        default: 'success',
    },
});
</script>

<template>
    <span
        :class="[
            'inline-flex items-center rounded px-2 py-0.5 text-xs font-medium',
            {
                'bg-green-100 text-green-800': props.type === 'success',
                'bg-red-100 text-red-800': props.type === 'danger',
                'bg-amber-100 text-amber-800': props.type === 'warning',
                'bg-blue-100 text-blue-800': props.type === 'info',
            },
        ]"
    >
        <slot />
    </span>
</template>
