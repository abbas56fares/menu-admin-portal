<template>
  <section>
    <header>
      <h2 class="text-lg font-medium text-gray-900">Update Password</h2>
      <p class="mt-1 text-sm text-gray-600">Ensure your account is using a long, random password to stay secure.</p>
    </header>

    <form @submit.prevent="submit" class="mt-6 space-y-6">
      <div>
        <label for="current_password" class="block text-sm font-medium text-gray-700">Current Password</label>
        <input id="current_password" v-model="form.current_password" type="password" class="mt-1 block w-full border rounded px-3 py-2" autocomplete="current-password" />
        <div v-if="form.errors.current_password" class="mt-2 text-sm text-red-600">{{ form.errors.current_password }}</div>
      </div>

      <div>
        <label for="password" class="block text-sm font-medium text-gray-700">New Password</label>
        <input id="password" v-model="form.password" type="password" class="mt-1 block w-full border rounded px-3 py-2" autocomplete="new-password" />
        <div v-if="form.errors.password" class="mt-2 text-sm text-red-600">{{ form.errors.password }}</div>
      </div>

      <div>
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
        <input id="password_confirmation" v-model="form.password_confirmation" type="password" class="mt-1 block w-full border rounded px-3 py-2" autocomplete="new-password" />
        <div v-if="form.errors.password_confirmation" class="mt-2 text-sm text-red-600">{{ form.errors.password_confirmation }}</div>
      </div>

      <div class="flex items-center gap-4">
        <button :disabled="form.processing" class="px-4 py-2 bg-indigo-600 text-white rounded">Save</button>
        <p v-if="$page.props.flash.status === 'password-updated'" class="text-sm text-gray-600">Saved.</p>
      </div>
    </form>
  </section>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  current_password: '',
  password: '',
  password_confirmation: '',
})

const submit = () => {
  form.put('/password', {
    onFinish: () => form.reset('current_password', 'password', 'password_confirmation'),
  })
}
</script>
