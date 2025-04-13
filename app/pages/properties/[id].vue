<script setup lang="ts">
const property = ref<Property | undefined>(undefined)
const id = useRoute().params.id

async function fetchProperty() {
  const { data } = await useFetch(`/api/properties/${id}`)

  if (data.value) {
    property.value = {
      ...data.value,
      dateCreated: new Date(data.value.dateCreated),
    }
  }
}

onMounted(async () => {
  await fetchProperty()
})
</script>

<template>
  <div>
    <h1>Property ID: {{ id }}</h1>
    <div v-if="property">
      <h2>Details</h2>
      <p>Name: {{ property.name }}</p>
      <p>Owner: {{ property.owner }}</p>
      <p>Type: {{ property.type }}</p>
      <p>Address: {{ property.address }}</p>
      <p>Date created: {{ property.dateCreated.toUTCString() }}</p>
      <p>Sold: {{ property.sold ? "Yes" : "No" }}</p>
    </div>
    <div v-else>
      No property with id {{ id }} found.
    </div>
  </div>
</template>
