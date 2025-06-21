<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import type { PaginationLink } from '@/types'

defineProps<{
  links: PaginationLink[]
}>()

// Fonction pour décoder les entités HTML (comme &laquo;)
const decodeHtmlEntities = (text: string) => {
  const textarea = document.createElement('textarea')
  textarea.innerHTML = text
  return textarea.value
}
</script>

<template>
  <nav 
    aria-label="Pagination"
    class="flex items-center justify-between mt-6"
  >
    <div class="flex-1 flex justify-center">
      <div class="flex space-x-1 rounded-md shadow-sm">
        <Link
          v-for="(link, index) in links"
          :key="index"
          :href="link.url || '#'"
          :aria-current="link.active ? 'page' : undefined"
          :aria-disabled="!link.url"
          class="relative inline-flex items-center px-4 py-2 text-sm font-medium transition-colors duration-150 focus:z-10 focus:outline-none focus:ring-2 focus:ring-green-500"
          :class="{
            'z-10 bg-green-400 text-white rounded-sm': link.active,
            'bg-white text-gray-700 hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700': !link.active && link.url,
            'opacity-50 cursor-not-allowed': !link.url,
            'rounded-l-md': index === 0,
            'rounded-r-md': index === links.length - 1
          }"
        >
          {{ decodeHtmlEntities(link.label) }}
        </Link>
      </div>
    </div>
  </nav>
</template>
