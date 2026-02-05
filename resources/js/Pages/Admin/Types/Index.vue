<template>
  <AdminLayout>
    <div class="max-w-7xl mx-auto px-6">
      <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-white mb-6">Types</h1>
        <Link href="/admin/types/create" class="px-4 py-2 bg-indigo-600 text-white rounded">Add Type</Link>
      </div>

      <div class="bg-transparent border border-white/20 rounded">
        <table class="w-full text-left text-white">
          <thead class="bg-white/5">
            <tr>
              <th class="p-3">ID</th>
              <th class="p-3">Name</th>
              <th class="p-3">Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="type in types.data" :key="type.id" class="border-t border-white/10">
              <td class="p-3">{{ type.id }}</td>
              <td class="p-3">{{ type.name }}</td>
              <td class="p-3 flex gap-2">
                <Link :href="`/admin/types/${type.id}/edit`" class="px-3 py-1 bg-white/10 text-white rounded hover:bg-white/20">Edit</Link>
                <button @click="destroy(type.id)" class="px-3 py-1 bg-red-600 text-white rounded">Delete</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <Pagination :links="types.links" />
    </div>
  </AdminLayout>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'
import AdminLayout from '../../../Layouts/AdminLayout.vue'
import Pagination from '../../../Components/Pagination.vue'

defineOptions({ layout: null })

const props = defineProps({
  types: Object,
})

const destroy = (id) => {
  if (confirm('Delete this type?')) {
    router.delete(`/admin/types/${id}`)
  }
}
</script>
