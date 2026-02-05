<template>
  <div class="min-h-screen">
    <nav class="bg-transparent border-b border-white/20">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
        <Link href="/" class="text-xl font-bold text-white">
          Chops & Sicilia
        </Link>
        <div class="flex gap-4">
          <Link href="/menu" class="text-white hover:text-white/80">Menu</Link>
          <template v-if="$page.props.auth.user">
            <Link href="/admin/dashboard" class="text-white hover:text-white/80">Admin Dashboard</Link>
            <Link href="/logout" method="post" as="button" class="text-white hover:text-white/80">Logout</Link>
            <span class="text-yellow-500 font-semibold">{{ $page.props.auth.user.name }}</span>
          </template> 
          <template v-else>
            <Link href="/login" class="text-white hover:text-white/80">Login</Link>
          </template>
        </div>
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