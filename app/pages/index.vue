<script setup lang="ts">
const { data: properties } = await useFetch('/api/properties', { method: 'get' })
</script>

<template>
  <div v-if="properties && properties.length > 0" class="gap-4 divide-gray">
    <div>{{ properties }}</div>
    <template v-for="property in properties" :key="property.id">
      <div>
        <span>{{ property.id }}</span>
        <span>{{ property.name }}</span>
        <span>{{ property.dateCreated }}</span>
        <span>{{ property.address }}</span>
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
        <base-button label="Submit" type="submit" form="new-property-form" />
      </template>
      <template #close>
        <base-button label="Close" />
      </template>
    </base-modal>
  </div>
</template>
