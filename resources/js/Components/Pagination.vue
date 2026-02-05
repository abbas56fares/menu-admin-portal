<template>
  <nav v-if="links && links.length" class="flex flex-wrap gap-2 mt-6">
    <button
      v-for="(link, index) in links"
      :key="index"
      :disabled="!link.url"
      @click="visit(link.url)"
      class="px-3 py-1 rounded border text-sm"
      :class="link.active ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50'"
      v-html="link.label"
    />
  </nav>
</template>

<script setup>
import { router } from '@inertiajs/vue3'

defineProps({
  links: Array,
})

const visit = (url) => {
  if (!url) return
  router.visit(url, { preserveScroll: true })
}
</script>
