<template>
  <AdminLayout>
    <div class="max-w-7xl mx-auto px-6">
      <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-white mb-6">Items</h1>
        <Link href="/admin/items/create" class="px-4 py-2 bg-indigo-600 text-white rounded">Add Item</Link>
      </div>

      <div class="bg-transparent border border-white/20 rounded p-4 mb-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <input v-model="filtersLocal.q" placeholder="Search..." class="border border-white/20 bg-transparent text-white placeholder-white/60 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-white/30" />
          <select v-model="filtersLocal.category_id" class="border border-white/20 bg-transparent text-white rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-white/30">
            <option value="" class="text-black">All Categories</option>
            <option v-for="cat in categories" :key="cat.id" :value="cat.id" class="text-black">{{ cat.name }}</option>
          </select>
          <select v-model="filtersLocal.sort" class="border border-white/20 bg-transparent text-white rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-white/30">
            <option value="name" class="text-black">Name</option>
            <option value="price" class="text-black">Price</option>
            <option value="created_at" class="text-black">Created</option>
          </select>
          <select v-model="filtersLocal.dir" class="border border-white/20 bg-transparent text-white rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-white/30">
            <option value="asc" class="text-black">Asc</option>
            <option value="desc" class="text-black">Desc</option>
          </select>
        </div>
        <div class="mt-3">
          <button @click="applyFilters" class="px-4 py-2 bg-white/10 text-white rounded hover:bg-white/20">Apply</button>
        </div>
      </div>

      <div class="bg-transparent border border-white/20 rounded">
        <table class="w-full text-left text-white">
          <thead class="bg-white/5">
            <tr>
              <th class="p-3">ID</th>
              <th class="p-3">Image</th>
              <th class="p-3">Name</th>
              <th class="p-3">Category</th>
              <th class="p-3">Subcategory</th>
              <th class="p-3">Price</th>
              <th class="p-3">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in items.data" :key="item.id" class="border-t border-white/10">
              <td class="p-3">{{ item.id }}</td>
              <td class="p-3">
                <img v-if="item.image" :src="`/storage/${item.image}`" alt="" class="h-10 w-10 rounded object-cover" />
                <span v-else class="text-white/60">—</span>
              </td>
              <td class="p-3">{{ item.name }}</td>
              <td class="p-3">{{ item.category?.name }}</td>
              <td class="p-3">{{ item.subcategory?.name }}</td>
              <td class="p-3">{{ item.price }} {{ item.currency }}</td>
              <td class="p-3 flex gap-2">
                <Link :href="`/admin/items/${item.id}/edit`" class="px-3 py-1 bg-white/10 text-white rounded hover:bg-white/20">Edit</Link>
                <button @click="destroy(item.id)" class="px-3 py-1 bg-red-600 text-white rounded">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <Pagination :links="items.links" />
    </div>
  </AdminLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'
import AdminLayout from '../../../Layouts/AdminLayout.vue'
import Pagination from '../../../Components/Pagination.vue'
import { reactive } from 'vue'

defineOptions({ layout: null })

const props = defineProps({
  items: Object,
  categories: Array,
  filters: Object,
})

const filtersLocal = reactive({
  q: props.filters?.q || '',
  category_id: props.filters?.category_id || '',
  subcategory_id: props.filters?.subcategory_id || '',
  type_id: props.filters?.type_id || '',
  sort: props.filters?.sort || 'name',
  dir: props.filters?.dir || 'asc',
})

const applyFilters = () => {
  router.get('/admin/items', filtersLocal, { preserveState: true, replace: true })
}

const destroy = (id) => {
  if (confirm('Delete this item?')) {
    router.delete(`/admin/items/${id}`)
  }
}
</script>
