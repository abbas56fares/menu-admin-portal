<template>
  <section>
    <header>
      <h2 class="text-lg font-medium text-gray-900">Profile Information</h2>
      <p class="mt-1 text-sm text-gray-600">Update your account's profile information and email address.</p>
    </header>

    <form @submit.prevent="submit" class="mt-6 space-y-6">
      <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
        <input id="name" v-model="form.name" type="text" class="mt-1 block w-full border rounded px-3 py-2" required autofocus autocomplete="name" />
        <div v-if="form.errors.name" class="mt-2 text-sm text-red-600">{{ form.errors.name }}</div>
      </div>

      <div>
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input id="email" v-model="form.email" type="email" class="mt-1 block w-full border rounded px-3 py-2" required autocomplete="username" />
        <div v-if="form.errors.email" class="mt-2 text-sm text-red-600">{{ form.errors.email }}</div>

        <div v-if="mustVerifyEmail && !user.email_verified_at" class="mt-2">
          <p class="text-sm text-gray-800">
            Your email address is unverified.
            <button type="button" @click="sendVerification" class="underline text-sm text-gray-600 hover:text-gray-900">
              Click here to re-send the verification email.
            </button>
          </p>

          <p v-if="$page.props.flash.status === 'verification-link-sent'" class="mt-2 text-sm text-green-600">
            A new verification link has been sent to your email address.
          </p>
        </div>
      </div>

      <div class="flex items-center gap-4">
        <button :disabled="form.processing" class="px-4 py-2 bg-indigo-600 text-white rounded">Save</button>
        <p v-if="$page.props.flash.status === 'profile-updated'" class="text-sm text-gray-600">Saved.</p>
      </div>
    </form>
  </section>
</template>

<script setup>
import { useForm, router } from '@inertiajs/vue3'

const props = defineProps({
  user: Object,
  mustVerifyEmail: Boolean,
  status: String,
})

const form = useForm({
  name: props.user.name,
  email: props.user.email,
})

const submit = () => {
  form.patch('/profile')
}

const sendVerification = () => {
  router.post('/email/verification-notification')
}
</script>
