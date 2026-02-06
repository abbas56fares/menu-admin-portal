<template>
  <AdminLayout>
    <div class="max-w-4xl mx-auto px-6">
      <h1 class="text-2xl font-bold mb-6 text-white">Edit Item</h1>
      <form @submit.prevent="submit" class="bg-white shadow rounded p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">Name</label>
            <input v-model="form.name" type="text" class="mt-1 w-full border rounded px-3 py-2" required />
            <div v-if="form.errors.name" class="text-sm text-red-600 mt-1">{{ form.errors.name }}</div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Price</label>
            <input v-model="form.price" type="number" step="0.01" class="mt-1 w-full border rounded px-3 py-2" />
            <div v-if="form.errors.price" class="text-sm text-red-600 mt-1">{{ form.errors.price }}</div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Currency</label>
            <input v-model="form.currency" type="text" class="mt-1 w-full border rounded px-3 py-2" required />
            <div v-if="form.errors.currency" class="text-sm text-red-600 mt-1">{{ form.errors.currency }}</div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Category</label>
            <select v-model="form.category_id" class="mt-1 w-full border rounded px-3 py-2" required>
              <option value="" disabled>Select category</option>
              <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
            </select>
            <div v-if="form.errors.category_id" class="text-sm text-red-600 mt-1">{{ form.errors.category_id }}</div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Subcategory</label>
            <select v-model="form.subcategory_id" class="mt-1 w-full border rounded px-3 py-2">
              <option value="">None</option>
              <option v-for="sub in availableSubcategories" :key="sub.id" :value="sub.id">{{ sub.name }}</option>
            </select>
            <div v-if="form.errors.subcategory_id" class="text-sm text-red-600 mt-1">{{ form.errors.subcategory_id }}</div>
          </div>

          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700">Description</label>
            <textarea v-model="form.description" rows="3" class="mt-1 w-full border rounded px-3 py-2"></textarea>
            <div v-if="form.errors.description" class="text-sm text-red-600 mt-1">{{ form.errors.description }}</div>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">Image</label>
            <input type="file" @change="onFileChange" class="mt-1 w-full" />
            <div v-if="form.errors.image" class="text-sm text-red-600 mt-1">{{ form.errors.image }}</div>
            <div v-if="props.item.image" class="mt-2">
              <img :src="`/storage/${props.item.image}`" alt="" class="h-16 w-16 rounded object-cover" />
            </div>
          </div>

          <div class="flex items-center mt-6">
            <input id="is_active" type="checkbox" v-model="form.is_active" class="mr-2" />
            <label for="is_active" class="text-sm text-gray-700">Active</label>
          </div>
        </div>

        <div class="mt-6 flex gap-2">
          <button :disabled="form.processing" class="px-4 py-2 bg-indigo-600 text-white rounded">Update</button>
          <Link href="/admin/items" class="px-4 py-2 bg-gray-200 rounded">Cancel</Link>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '../../../Layouts/AdminLayout.vue'
import { computed } from 'vue'

defineOptions({ layout: null })

const props = defineProps({
  item: Object,
  categories: Array,
})

const form = useForm({
  name: props.item.name || '',
  price: props.item.price || '',
  currency: props.item.currency || 'USD',
  description: props.item.description || '',
  category_id: props.item.category_id || '',
  subcategory_id: props.item.subcategory_id || '',
  image: null,
  is_active: !!props.item.is_active,
})

const availableSubcategories = computed(() => {
  const category = props.categories.find((c) => c.id === Number(form.category_id))
  return category?.subcategories || []
})

const onFileChange = (e) => {
  form.image = e.target.files[0]
}

const submit = () => {
  form.put(`/admin/items/${props.item.id}`, {
    forceFormData: true,
  })
}
</script>
