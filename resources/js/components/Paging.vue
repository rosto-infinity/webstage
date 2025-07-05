<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

// Définition de l'interface pour les liens
interface LinkItem {
    url: string | null;
    label: string;
    active: boolean;
}

// Définition des props avec TypeScript
const props = defineProps<{
    links: LinkItem[];
}>();
</script>

<template>
    <!-- Affiche la navigation uniquement s'il y a plus d'un lien -->
    <nav v-if="props.links.length > 1" class="my-4 flex justify-center">
        <!-- Boucle sur chaque lien -->
        <template v-for="(link, idx) in props.links" :key="idx">
            <!-- Lien cliquable si une URL est disponible -->
            <Link
                v-if="link.url"
                :href="link.url"
                preserve-scroll
                class="mx-1 rounded border px-3 py-1 text-sm transition-colors"
                :class="{
                    'border-gray-300 bg-gray-200 text-gray-800': link.active,
                    'text-gray-500 hover:bg-gray-100': !link.active,
                }"
            >
                {{ link.label }}
            </Link>
            <!-- Texte statique si aucune URL n'est disponible -->
            <span
                v-else
                class="mx-1 rounded border border-gray-200 px-3 py-1 text-sm text-gray-400"
            >
                {{ link.label }}
            </span>
        </template>
    </nav>
</template>
