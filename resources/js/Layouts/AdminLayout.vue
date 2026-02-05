<template>
  <div class="min-h-screen">
    <nav class="bg-transparent border-b border-white/20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex items-center justify-between">
        <div class="flex items-center gap-2 text-white">
          <Link href="/admin/dashboard" class="hover:text-white/80">Dashboard</Link>
          <span class="text-white/60">›</span>
          <template v-for="(crumb, index) in breadcrumbs" :key="crumb.href + index">
            <Link v-if="crumb.href" :href="crumb.href" class="hover:text-white/80">
              {{ crumb.label }}
            </Link>
            <span v-else class="text-white/80">{{ crumb.label }}</span>
            <span v-if="index < breadcrumbs.length - 1" class="text-white/60">›</span>
          </template>
        </div>

        <div class="relative">
          <button
            class="px-4 py-2 bg-white/10 text-white rounded hover:bg-white/20 flex items-center gap-2"
            @click="toggleMenu"
          >
            Manage
            <span class="text-xs">▼</span>
          </button>
          <div
            v-if="menuOpen"
            class="absolute right-0 mt-2 w-56 bg-black/80 border border-white/20 rounded shadow-lg z-20"
          >
            <Link href="/admin/categories" class="block px-4 py-2 text-white hover:bg-white/10">Categories</Link>
            <Link href="/admin/types" class="block px-4 py-2 text-white hover:bg-white/10">Types</Link>
            <Link href="/admin/subcategories" class="block px-4 py-2 text-white hover:bg-white/10">Subcategories</Link>
            <Link href="/admin/items" class="block px-4 py-2 text-white hover:bg-white/10">Items</Link>
          </div>
        </div>
      </div>
    </nav>

    <main class="py-12">
      <slot />
    </main>
  </div>
</template>

<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { computed, ref } from 'vue'

const menuOpen = ref(false)

const toggleMenu = () => {
  menuOpen.value = !menuOpen.value
}

const page = usePage()

const breadcrumbs = computed(() => {
  const url = page.url || ''
  const parts = url.split('?')[0].split('/').filter(Boolean)

  const map = {
    categories: { label: 'Categories', href: '/admin/categories' },
    types: { label: 'Types', href: '/admin/types' },
    subcategories: { label: 'Subcategories', href: '/admin/subcategories' },
    items: { label: 'Items', href: '/admin/items' },
    dashboard: { label: 'Dashboard', href: '/admin/dashboard' },
    create: { label: 'Create', href: null },
    edit: { label: 'Edit', href: null },
  }

  if (!parts.includes('admin')) {
    return []
  }

  const adminIndex = parts.indexOf('admin')
  const section = parts[adminIndex + 1]
  const action = parts[adminIndex + 3] || parts[adminIndex + 2]

  const crumbs = []
  if (section && section !== 'dashboard' && map[section]) {
    crumbs.push(map[section])
  }
  if (action && map[action]) {
    crumbs.push(map[action])
  }

  return crumbs
})
</script>

<style scoped>
</style>
