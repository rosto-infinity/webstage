<script setup lang="ts">
import AppLayoutUser from '@/layouts/AppLayoutUser.vue';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/admin/dashboard',
    },
];
</script>

<template>
    <AppLayoutUser :breadcrumbs="breadcrumbs">
        <div class="p-2">
            <!-- Cartes de stats -->
            <div class="mb-6 grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">11111</div>

            <!-- Graphiques -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <!-- Graphe camembert -->
                222222222222

                <!-- Bar hebdo -->
                <div class="rounded-xl border bg-white p-6 shadow">3333333333333</div>

                <!-- Line mensuel -->
                <div class="rounded-xl border bg-white p-6 shadow">444444444444</div>

                <!-- Bar motifs absence -->
                <div class="rounded-xl border bg-white p-6 shadow">5555555555555555555555555555</div>
            </div>
        </div>
    </AppLayoutUser>
</template>

<style scoped>
/* Suppression de toute règle canvas globale forcée */
</style>
