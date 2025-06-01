<script setup lang="ts">
const userCookie = useUserCookie()

if (!userCookie.value) {
  await navigateTo('/login')
}

const username = ref<string>()

async function changeUsername() {
  const { data, status } = await useFetch('/js/users/change-username', {
    method: 'POST',
    body: {
      id: userCookie.value!.id,
      newUsername: username.value,
    },
  })

  if (status.value === 'success') {
    userCookie.value!.username = data.value!.username
    await navigateTo('/user')
  }
}
</script>

<template>
  <div>User setting</div>
  <div class="pt-4">
    <form
      id="login-form"
      class="grid grid-cols-[auto_1fr] gap-2 [&>label]:(font-medium font-normal)"
      @submit.prevent="changeUsername"
    >
      <label for="name">New username</label><input id="name" v-model="username" name="username" required>
      <base-button label="Change" type="submit" class="col-span-2 mt-4 justify-self-start" />
    </form>
  </div>
</template>
