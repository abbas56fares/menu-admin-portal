<template>
  <AdminLayout>
    <div class="max-w-7xl mx-auto px-6 py-6">
      <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-white mb-6">Categories</h1>
        <Link href="/admin/categories/create" class="px-4 py-2 font-semibold text-white rounded">Add Category</Link>
      </div>

      <div class="bg-transparent border border-white/20 rounded">
        <table class="w-full text-left text-white">
          <thead class="bg-white/5">
            <tr>
              <th class="p-3">ID</th>
              <th class="p-3">Name</th>
              <th class="p-3">Logo</th>
              <th class="p-3">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="cat in categories.data" :key="cat.id" class="border-t border-white/10">
              <td class="p-3">{{ cat.id }}</td>
              <td class="p-3">{{ cat.name }}</td>
              <td class="p-3">
                <img v-if="cat.logo" :src="`/storage/${cat.logo}`" alt="" class="h-10" />
                <span v-else class="text-white/60">—</span>
              </td>
              <td class="p-3 flex gap-2">
                <Link :href="`/admin/categories/${cat.id}/edit`" class="px-3 py-1 bg-white/10 text-white rounded hover:bg-white/20">Edit</Link>
                <button @click="destroy(cat.id)" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <Pagination :links="categories.links" />
    </div>
  </AdminLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'
import AdminLayout from '../../../Layouts/AdminLayout.vue'
import Pagination from '../../../Components/Pagination.vue'

defineOptions({ layout: null })

const props = defineProps({
  categories: Object,
})

const destroy = (id) => {
  if (confirm('Delete this category?')) {
    router.delete(`/admin/categories/${id}`)
  }
}
</script>
