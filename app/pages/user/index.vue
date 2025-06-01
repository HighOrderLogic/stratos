<script setup lang="ts">
const userCookie = useUserCookie()

if (!userCookie.value) {
  await navigateTo('/login')
}

async function logout() {
  userCookie.value = undefined
  await navigateTo('/')
}
</script>

<template>
  <div>Hi {{ userCookie!.username }}</div>
  <div class="pt-4"><base-button label="Logout" @click="logout" /></div>
  <nuxt-link href="/user/setting" class="pt-4">
    Setting
  </nuxt-link>
</template>
