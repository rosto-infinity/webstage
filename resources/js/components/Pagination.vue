<script setup>
import {Link} from '@inertiajs/vue3';
  defineProps({
    links: {
        type: Object,
        required: true
    }
    
  });
</script>

<template>
  <div class="flex flex-wrap items-center justify-center space-x-1 mt-4">
    <Link
      v-for="(link, index) in links"
      :key="index"
      :href="link.url || '#'"
       v-html="link.label"
      class="px-3 py-1 rounded-md text-sm font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500"
      
      :class="[
        link.active ? 'bg-green-600 text-white font-semibold':
        'bg-whitepy-2 text-gray-700 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:text-gray-300 hover:dark:bg-gray-700',
        !link.url ? 'opacity-50 pointer-events-none' : 'hover:bg-gray-100 cursor-pointer text-gray-500 dark:hover:bg-gray-700 dark:text-gray-300 hover:dark:text-white',
      ]"
      />
  </div>

</template>