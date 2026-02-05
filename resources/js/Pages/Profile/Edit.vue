<template>
  <Layout>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
          <div class="max-w-xl">
            <ProfileInformationForm :user="user" :must-verify-email="mustVerifyEmail" :status="status" />
          </div>
        </div>

        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
          <div class="max-w-xl">
            <UpdatePasswordForm />
          </div>
        </div>

        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
          <div class="max-w-xl">
            <DeleteUserForm />
          </div>
        </div>
      </div>
    </div>
  </Layout>
</template>

<script setup>
import Layout from '../../Layouts/Layout.vue'
import ProfileInformationForm from './Partials/ProfileInformationForm.vue'
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue'
import DeleteUserForm from './Partials/DeleteUserForm.vue'

const props = defineProps({
  user: Object,
  mustVerifyEmail: Boolean,
  status: String,
})
</script>
