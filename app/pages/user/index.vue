<script setup lang="ts">
const userCookie = useUserCookie()

if (!userCookie.value) {
  await navigateTo('/login')
}

function logout() {
  userCookie.value = undefined
}
</script>

<template>
  <div>Hi {{ userCookie!.username }}</div>
  <div><base-button label="Logout" @click="logout" /></div>
  <nuxt-link href="/user/setting">
    Setting
  </nuxt-link>
</template>
