<script setup lang="ts">
const name = ref('')
const owner = ref('')
const type = ref('house')
const address = ref('')

const showModal = ref(false)

const { data: properties, refresh: refreshProperties } = await useFetch('/js/properties', {
  method: 'get',
  transform: (data) => {
    if (!Array.isArray(data)) {
      return []
    }
    return data.sort((a, b) => {
      const dateA = new Date(a.dateCreated).getTime()
      const dateB = new Date(b.dateCreated).getTime()
      return dateB - dateA
    })
  },
})

async function addProperty() {
  const { error } = await useFetch('/js/properties', {
    method: 'POST',
    body: {
      name: name.value,
      owner: owner.value,
      type: type.value,
      address: address.value,
    },
  })

  if (error.value) {
    console.error('Error adding property:', error.value)
  } else {
    name.value = ''
    owner.value = ''
    type.value = 'house'
    address.value = ''
    showModal.value = false
    await refreshProperties()
  }
}
</script>

<template>
  <div v-if="properties && properties.length > 0" class="gap-4 divide-gray">
    <template v-for="property in properties" :key="property.id">
      <div class="my-4 flex items-center gap-2 border rd-md border-solid p-4">
        <nuxt-link
          class="m-2 aspect-square h-full w-auto flex items-center object-contain"
          :href="`/properties/${property.id}`"
        >
          <property-image :type="property.type" />
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
    <!-- Assuming base-modal can be controlled with v-model:modelValue or a similar prop -->
    <base-modal v-model:open="showModal" title="New property" description="Add a new property to the database">
      <base-button label="Add property" @click="showModal = true" />
      <template #content>
        <form
          id="new-property-form"
          class="grid grid-cols-[auto_1fr] gap-2 [&>label]:(font-medium font-normal)"
          @submit.prevent="addProperty"
        >
          <label for="name">Name</label><input id="name" v-model="name" name="name" required>
          <label for="owner">Owner</label><input id="owner" v-model="owner" name="owner" required>
          <label for="type">Type</label><select id="type" v-model="type" name="type">
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
          <label for="address">Address</label><input id="address" v-model="address" name="address" required>
          <base-button label="Submit" type="submit" class="col-span-2 mt-4 justify-self-start" />
        </form>
      </template>
      <template #close>
        <base-button label="Close" @click="showModal = false" />
      </template>
    </base-modal>
  </div>
</template>
