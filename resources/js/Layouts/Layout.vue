<template>
  <div class="min-h-screen">
    <nav class="bg-transparent border-b border-white/20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
        <Link href="/" class="text-xl font-bold text-white">
          Chops & Sicilia
        </Link>
      </div>
    </nav>  

    <main>
      <slot />
    </main>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<style scoped>
</style>