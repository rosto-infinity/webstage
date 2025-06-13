<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

interface LinkItem {
  url: string | null;
  label: string;
  active: boolean;
}

const props = defineProps<{
  links: LinkItem[];
}>();
</script>

<template>
  <nav v-if="props.links.length > 1" class="flex justify-center my-4">
    <template v-for="(link, idx) in props.links" :key="idx">
      <Link
        v-if="link.url"
        :href="link.url"
        preserve-scroll
        v-html="link.label"
        class="mx-1 px-3 py-1 text-sm rounded border transition-colors"
        :class="link.active
          ? 'bg-gray-200 text-gray-800 border-gray-300'
          : 'text-gray-500 hover:bg-gray-100'"
      />
      <span
        v-else
        v-html="link.label"
        class="mx-1 px-3 py-1 text-sm rounded text-gray-400 border border-gray-200"
      />
    </template>
  </nav>
</template>
