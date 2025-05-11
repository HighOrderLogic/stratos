<script lang="ts">
export interface PropertyImageProps {
  type: 'house' | 'apartment' | 'villa'
}
</script>

<script setup lang="ts">
const props = defineProps<PropertyImageProps>()
</script>

<template>
  <img
    :src="`/${props.type}.png`"
    alt="Property Image"
    class="aspect-ratio-square h-auto max-w-20 rounded-lg object-cover shadow-lg"
  >
</template>
