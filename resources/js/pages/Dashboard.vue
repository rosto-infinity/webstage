<script setup lang="ts">
import {Link } from '@inertiajs/vue3'
import AppLayoutUser from '@/layouts/AppLayoutUser.vue';
import type { 
  BreadcrumbItem, 
  // Presence 
 } from '@/types';
import PlaceholderPattern from '@/components/PlaceholderPattern.vue';


const breadcrumbs: BreadcrumbItem[] = [{ 
  title: 'Dashboard',
   href: '/dashboard' 
   }];
</script>

<template>
  <AppLayoutUser :breadcrumbs="breadcrumbs">
  
 <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div 
                   v-if="$page.props.auth.user"
                 class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <Link :href="route('dashboard.superadmin')" class="bg-green-600 text-white flex items-center px-6 py-2 rounded-lg hover:bg-green-700">
                      <Pen class="w-5 h-5"/>Gestion stage
                   </Link>
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-sidebar-border/70 dark:border-sidebar-border">
                    <PlaceholderPattern />
                </div>
            </div>
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-sidebar-border/70 dark:border-sidebar-border md:min-h-min">
                <PlaceholderPattern />
            </div>
</div>


  </AppLayoutUser>
</template>

<style scoped>
/* Suppression de toute règle canvas globale forcée */
</style>
