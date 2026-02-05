<template>
  <section class="space-y-6">
    <header>
      <h2 class="text-lg font-medium text-gray-900">Delete Account</h2>
      <p class="mt-1 text-sm text-gray-600">
        Once your account is deleted, all of its resources and data will be permanently deleted.
      </p>
    </header>

    <button @click="show = true" class="px-4 py-2 bg-red-600 text-white rounded">Delete Account</button>

    <div v-if="show" class="fixed inset-0 bg-black/50 flex items-center justify-center">
      <div class="bg-white rounded p-6 w-full max-w-md">
        <h2 class="text-lg font-medium text-gray-900">Are you sure?</h2>
        <p class="mt-2 text-sm text-gray-600">
          Please enter your password to confirm you would like to permanently delete your account.
        </p>

        <div class="mt-4">
          <label class="block text-sm font-medium text-gray-700">Password</label>
          <input v-model="form.password" type="password" class="mt-1 w-full border rounded px-3 py-2" />
          <div v-if="form.errors.password" class="mt-2 text-sm text-red-600">{{ form.errors.password }}</div>
        </div>

        <div class="mt-6 flex justify-end gap-2">
          <button @click="close" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">Cancel</button>
          <button @click="submit" :disabled="form.processing" class="px-4 py-2 bg-red-600 text-white rounded">Delete</button>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'

const show = ref(false)

const form = useForm({
  password: '',
})

const submit = () => {
  form.delete('/profile', {
    onSuccess: () => close(),
  })
}

const close = () => {
  show.value = false
  form.reset()
}
</script>
