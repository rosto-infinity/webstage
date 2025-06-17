<script setup lang="ts">
import type { BreadcrumbItemType } from '@/types';
import AppSidebarLayoutUser from './app/AppSidebarLayoutUser.vue';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <AppSidebarLayoutUser :breadcrumbs="breadcrumbs">
        <slot />
    </AppSidebarLayoutUser>
</template>
