<script setup lang="ts">
const userCookie = useUserCookie()

const requestContent = ref<string>()
const showModal = ref(false)

if (!userCookie.value) {
  await navigateTo('/login')
}

const { data: repairRequests, refresh: refreshRequests } = await useFetch<any[]>(
  `/js/users/requests/${userCookie.value!.id}`,
  {
    method: 'GET',
    default: () => [],
  },
)

async function logout() {
  userCookie.value = undefined
  await navigateTo('/')
}

async function newRequest() {
  const { error } = await useFetch('/js/users/new-request', {
    method: 'POST',
    body: {
      userId: userCookie.value!.id,
      content: requestContent.value,
    },
  })

  if (error.value) {
    console.error('Failed to add new request')
  } else {
    requestContent.value = undefined
    showModal.value = false
    await refreshRequests()
  }
}
</script>

<template>
  <div>Hi {{ userCookie!.username }}</div>
  <div class="pt-4">
    <base-button label="Logout" @click="logout" />
  </div>
  <nuxt-link href="/user/setting" class="pt-4">
    Setting
  </nuxt-link>
  <div class="pt-4">
    <div v-if="repairRequests.length === 0">
      There is no request
    </div>
    <template v-else>
      <div v-for="req in repairRequests" :key="req.content" class="pt-2">
        <div>Created at {{ new Date(req.createdAt).toUTCString() }}</div>
        <div>Content: {{ req.content }}</div>
      </div>
    </template>
  </div>
  <div class="pt-4">
    <base-modal
      v-model:open="showModal" title="New repair request" description="Add a new repair request to the database"
    >
      <base-button label="Add repair request" @click="showModal = true" />
      <template #content>
        <form
          id="new-property-form"
          class="grid grid-cols-[auto_1fr] gap-2 [&>label]:(font-medium font-normal)"
          @submit.prevent="newRequest"
        >
          <label for="content">Content</label><input id="content" v-model="requestContent" name="name" required>
          <base-button label="Submit" type="submit" class="col-span-2 mt-4 justify-self-start" />
        </form>
      </template>
      <template #close>
        <base-button label="Close" @click="showModal = false" />
      </template>
    </base-modal>
  </div>
</template>
