<script setup lang="ts">
const { data: properties } = await useFetch('/api/properties', { method: 'get' })
properties.value?.sort((a, b) => {
  const dateA = new Date(a.dateCreated).getTime()
  const dateB = new Date(b.dateCreated).getTime()
  return dateB - dateA
})
</script>

<template>
  <div v-if="properties && properties.length > 0" class="gap-4 divide-gray">
    <template v-for="property in properties" :key="property.id">
      <div class="my-4 flex items-center border rd-md border-solid p-4">
        <nuxt-link
          class="m-4 aspect-square h-full w-auto flex items-center object-contain"
          :href="`/properties/${property.id}`"
        >
          <div
            class="text-4xl"
            :class="[
              property.type === 'house' ? 'i-mdi-home'
              : property.type === 'apartment' ? 'i-mdi-office-building'
                : 'i-mdi-home-modern',
            ]"
          />
        </nuxt-link>
        <div>
          <div><span class="font-bold">Id:</span> {{ property.id }}</div>
          <div><span class="font-bold">Property name:</span> {{ property.name }}</div>
          <div><span class="font-bold">Date created:</span> {{ new Date(property.dateCreated).toUTCString() }}</div>
          <div><span class="font-bold">Property address:</span> {{ property.address }}</div>
        </div>
      </div>
    </template>
  </div>
  <div v-else>
    There is no property
  </div>
  <div class="block flex justify-end pt-4">
    <base-modal title="New property" description="Add a new property to the database">
      <base-button label="Add property" />
      <template #content>
        <form
          id="new-property-form"
          action="/api/properties"
          method="post"
          class="grid grid-cols-[auto_1fr] gap-2 [&>label]:(font-medium font-normal)"
        >
          <label for="name">Name</label><input id="name" name="name">
          <label for="owner">Owner</label><input id="owner" name="owner">
          <label for="type">Type</label><select id="type" name="type">
            <option value="house">
              House
            </option>
            <option value="apartment">
              Apartment
            </option>
            <option value="villa">
              Villa
            </option>
          </select>
          <label for="address">Address</label><input id="address" name="address">
        </form>
        <base-button label="Submit" type="submit" form="new-property-form" class="mt-4" />
      </template>
      <template #close>
        <base-button label="Close" />
      </template>
    </base-modal>
  </div>
</template>
