<script setup lang="ts">
const route = useRoute()

const { data: page } = await useAsyncData(
  `static-${route.path}`,
  () => queryCollection('staticPage').path(route.path).first(),
)

useSeoMeta({
  title: page.value?.title,
  description: page.value?.description,
})
</script>

<template>
  <ContentRenderer v-if="page" :value="page" />
  <div v-else>
    Page not found.
  </div>
</template>
