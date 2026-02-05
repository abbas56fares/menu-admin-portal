<template>
  <AdminLayout>
    <div class="max-w-3xl mx-auto px-6">
      <h1 class="text-2xl font-bold mb-6 text-white">Create Category</h1>
      <form @submit.prevent="submit" class="bg-white shadow rounded p-6">
        <div>
          <label class="block text-sm font-medium text-gray-700">Name</label>
          <input v-model="form.name" type="text" class="mt-1 w-full border rounded px-3 py-2" required />
          <div v-if="form.errors.name" class="text-sm text-red-600 mt-1">{{ form.errors.name }}</div>
        </div>

        <div class="mt-4">
          <label class="block text-sm font-medium text-gray-700">Logo</label>
          <input type="file" @change="onFileChange" class="mt-1 w-full" />
          <div v-if="form.errors.logo" class="text-sm text-red-600 mt-1">{{ form.errors.logo }}</div>
        </div>

        <div class="mt-6 flex gap-2">
          <button :disabled="form.processing" class="px-4 py-2 bg-indigo-600 text-white rounded">Save</button>
          <Link href="/admin/categories" class="px-4 py-2 bg-gray-200 rounded">Cancel</Link>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '../../../Layouts/AdminLayout.vue'

defineOptions({ layout: null })

const form = useForm({
  name: '',
  logo: null,
})

const onFileChange = (e) => {
  form.logo = e.target.files[0]
}

const submit = () => {
  form.post('/admin/categories', {
    forceFormData: true,
  })
}
</script>
