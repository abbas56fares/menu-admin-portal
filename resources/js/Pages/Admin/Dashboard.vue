<template>
  <AdminLayout>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class=" overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 ">
            <h1 class=" text-3xl font-bold text-white mb-6">Admin Dashboard</h1>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
              <div class="bg-transparent border border-white/20 rounded-lg p-3">
                <div class="text-white text-s font-medium">Total Categories</div>
                <div class="text-2xl  font-bold text-white mt-1">{{ stats.categories }}</div>
              </div>
              <div class="bg-transparent border border-white/20 rounded-lg p-3">
                <div class="text-white text-s font-medium">Total Types</div>
                <div class="text-2xl  font-bold text-white mt-1">{{ stats.types }}</div>
              </div>
              <div class="bg-transparent border border-white/20 rounded-lg p-3">
                <div class="text-white text-s font-medium">Total Items</div>
                <div class="text-2xl  font-bold text-white mt-1">{{ stats.items }}</div>
              </div>
              <div class="bg-transparent border border-white/20 rounded-lg p-3">
                <div class="text-white text-s font-medium">Inactive Items</div>
                <div class=" text-2xl  font-bold text-white mt-1">{{ stats.inactive }}</div>
              </div>
            </div>

            <div class="mt-8">
              <h2 class="text-2xl font-bold text-white mb-4">Quick Links</h2>
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <Link
                  href="/admin/categories"
                  class="inline-flex items-center justify-center px-4 py-2 border border-white/20 bg-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-white/10"
                >
                  Manage Categories
                </Link>
                <Link
                  href="/admin/types"
                  class="inline-flex items-center justify-center px-4 py-2 border border-white/20 bg-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-white/10"
                >
                  Manage Types
                </Link>
                <Link
                  href="/admin/subcategories"
                  class="inline-flex items-center justify-center px-4 py-2 border border-white/20 bg-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-white/10"
                >
                  Manage Subcategories
                </Link>
                <Link
                  href="/admin/items"
                  class="inline-flex items-center justify-center px-4 py-2 border border-white/20 bg-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-white/10"
                >
                  Manage Items
                </Link>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import AdminLayout from '../../Layouts/AdminLayout.vue'

defineProps({
  stats: Object,
})
</script>

