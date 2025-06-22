<script setup lang="ts">
import AppLayoutUser from '@/layouts/AppLayoutUser.vue';
import type { 
  BreadcrumbItem, 
  // Presence 
 } from '@/types';


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
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
      11111
    </div>

    <!-- Graphiques -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Graphe camembert -->
    222222222222

      <!-- Bar hebdo -->
      <div class="bg-white p-6 rounded-xl shadow border">
       3333333333333
      </div>

      <!-- Line mensuel -->
      <div class="bg-white p-6 rounded-xl shadow border">
       444444444444
      </div>

      <!-- Bar motifs absence -->
      <div class="bg-white p-6 rounded-xl shadow border">
        5555555555555555555555555555
      </div>
    </div>
 </div>

 


  </AppLayoutUser>
</template>

<style scoped>
/* Suppression de toute règle canvas globale forcée */
</style>
