<template>
  <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
    <div>
      <Link href="/">
        <span class="text-4xl font-bold text-indigo-600">Chops & Sicilia</span>
      </Link>
    </div>

    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
      <div class="mb-4 text-sm text-gray-600">
        Thanks for signing up! Before getting started, please verify your email address by clicking on the link we just emailed to you.
      </div>

      <div v-if="$page.props.flash.status" class="mb-4 font-medium text-sm text-green-600">
        {{ $page.props.flash.status }}
      </div>

      <form @submit.prevent="submit">
        <div class="mt-4 flex items-center justify-between">
          <button
            :disabled="form.processing"
            type="submit"
            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50"
          >
            Resend Verification Email
          </button>

          <Link href="/logout" method="post" as="button" class="text-sm text-gray-600 hover:text-gray-900">
            Log Out
          </Link>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { Link, useForm } from '@inertiajs/vue3'

defineOptions({
  layout: null,
})

const form = useForm({})

const submit = () => {
  form.post('/email/verification-notification')
}
</script>
