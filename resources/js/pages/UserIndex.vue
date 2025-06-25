<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import PlaceholderPattern from '../components/PlaceholderPattern.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: '/users',
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4 bg-background">
            <!-- Grille de cartes avec bordures harmonisées -->
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div class="relative aspect-video overflow-hidden rounded-xl border border-border/70 bg-card">
                    <PlaceholderPattern class="text-muted-foreground/20" />
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-border/70 bg-card">
                    <PlaceholderPattern class="text-muted-foreground/20" />
                </div>
                <div class="relative aspect-video overflow-hidden rounded-xl border border-border/70 bg-card">
                    <PlaceholderPattern class="text-muted-foreground/20" />
                </div>
            </div>

            <!-- Zone de contenu principal -->
            <div class="relative min-h-[100vh] flex-1 rounded-xl border border-border/70 bg-card p-6 text-foreground md:min-h-min">
                <div class="space-y-4">
                    <!-- Exemple d'éléments stylisés avec la nouvelle palette -->
                    <h2 class="text-xl font-semibold text-primary">Tableau de bord</h2>
                    
                    <div class="flex gap-4">
                        <div class="flex-1 rounded-lg bg-accent/10 p-4 text-accent-foreground border border-accent/20">
                            <h3 class="font-medium text-accent">Statistiques</h3>
                            <p class="text-muted-foreground">Données en temps réel</p>
                        </div>
                        
                        <div class="flex-1 rounded-lg bg-secondary/10 p-4 text-secondary-foreground border border-secondary/20">
                            <h3 class="font-medium text-secondary-foreground">Activité</h3>
                            <p class="text-muted-foreground">Historique récent</p>
                        </div>
                    </div>

                    <!-- Graphique exemple utilisant les couleurs chart-* -->
                    <div class="h-64 w-full rounded-lg border border-border bg-popover p-4">
                        <div class="flex h-full items-end gap-2">
                            <div 
                                v-for="(item, index) in 5" 
                                :key="index"
                                class="flex-1 rounded-t-sm"
                                :style="{
                                    height: `${20 + index * 15}%`,
                                    backgroundColor: `var(--chart-${index + 1})`
                                }"
                            />
                        </div>
                        <div class="mt-2 flex justify-between text-xs text-muted-foreground">
                            <span>Jan</span>
                            <span>Fév</span>
                            <span>Mar</span>
                            <span>Avr</span>
                            <span>Mai</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
