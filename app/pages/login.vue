<script setup lang="ts">
const cookie = useUserCookie()
const hasLogin = computed(() => cookie.value !== undefined)

const username = ref<string>()
const password = ref<string>()

async function login() {
  const { status, data } = await useFetch(
    '/js/users/login',
    { method: 'POST', body: { username: username.value, password: password.value } },
  )

  if (status.value === 'success') {
    cookie.value = {
      id: data.value!.id,
      username: username.value as string,
      password: password.value as string,
    }
    await navigateTo('/user')
  }
}
</script>

<template>
  <div v-if="hasLogin">
    You have login succefully, redirecting...
  </div>
  <div v-else>
    <form
      id="login-form"
      class="grid grid-cols-[auto_1fr] gap-2 [&>label]:(font-medium font-normal)"
      @submit.prevent="login"
    >
      <label for="name">Name</label><input id="name" v-model="username" name="username" required>
      <label for="password">Password</label>
      <input id="password" v-model="password" name="password" type="password" required>
      <base-button label="Login" type="submit" class="col-span-2 mt-4 justify-self-start" />
    </form>
  </div>
</template>
