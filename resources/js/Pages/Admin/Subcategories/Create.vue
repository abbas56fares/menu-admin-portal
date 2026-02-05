<template>
  <AdminLayout>
    <div class="max-w-3xl mx-auto px-6">
      <h1 class="text-2xl font-bold mb-6 text-white">Create Subcategory</h1>
      <form @submit.prevent="submit" class="bg-white shadow rounded p-6">
        <div>
          <label class="block text-sm font-medium text-gray-700">Name</label>
          <input v-model="form.name" type="text" class="mt-1 w-full border rounded px-3 py-2" required />
          <div v-if="form.errors.name" class="text-sm text-red-600 mt-1">{{ form.errors.name }}</div>
        </div>

        <div class="mt-4">
          <label class="block text-sm font-medium text-gray-700">Category</label>
          <select v-model="form.category_id" class="mt-1 w-full border rounded px-3 py-2" required>
            <option value="" disabled>Select category</option>
            <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
          </select>
          <div v-if="form.errors.category_id" class="text-sm text-red-600 mt-1">{{ form.errors.category_id }}</div>
        </div>

        <div class="mt-4">
          <label class="block text-sm font-medium text-gray-700">Type</label>
          <select v-model="form.type_id" class="mt-1 w-full border rounded px-3 py-2" required>
            <option value="" disabled>Select type</option>
            <option v-for="type in types" :key="type.id" :value="type.id">{{ type.name }}</option>
          </select>
          <div v-if="form.errors.type_id" class="text-sm text-red-600 mt-1">{{ form.errors.type_id }}</div>
        </div>

        <div class="mt-6 flex gap-2">
          <button :disabled="form.processing" class="px-4 py-2 bg-indigo-600 text-white rounded">Save</button>
          <Link href="/admin/subcategories" class="px-4 py-2 bg-gray-200 rounded">Cancel</Link>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '../../../Layouts/AdminLayout.vue'

defineOptions({ layout: null })

const props = defineProps({
  categories: Array,
  types: Array,
})

const form = useForm({
  name: '',
  category_id: '',
  type_id: '',
})

const submit = () => {
  form.post('/admin/subcategories')
}
</script>
