<template>
  <AdminLayout>
    <div class="max-w-3xl mx-auto px-6">
      <h1 class="text-2xl font-bold mb-6 text-white">Edit Type</h1>
      <form @submit.prevent="submit" class="bg-white shadow rounded p-6">
        <div>
          <label class="block text-sm font-medium text-gray-700">Name</label>
          <input v-model="form.name" type="text" class="mt-1 w-full border rounded px-3 py-2" required />
          <div v-if="form.errors.name" class="text-sm text-red-600 mt-1">{{ form.errors.name }}</div>
        </div>

        <div class="mt-6 flex gap-2">
          <button :disabled="form.processing" class="px-4 py-2 bg-indigo-600 text-white rounded">Update</button>
          <Link href="/admin/types" class="px-4 py-2 bg-gray-200 rounded">Cancel</Link>
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
  type: Object,
})

const form = useForm({
  name: props.type.name || '',
})

const submit = () => {
  form.put(`/admin/types/${props.type.id}`)
}
</script>
