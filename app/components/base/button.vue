<script lang="ts">
export interface ButtonProps {
  label?: string
  onClick?: MaybeArray<(event: MouseEvent) => void | Promise<void>>
}
</script>

<script setup lang="ts">
const props = defineProps<ButtonProps>()

async function onClickCallback(e: MouseEvent) {
  if (!props.onClick) {
    return
  }

  const callbacks = Array.isArray(props.onClick) ? props.onClick : [props.onClick]

  await Promise.all(callbacks.map(c => c(e)))
}
</script>

<template>
  <button
    class="rounded-md border-none border-none p-2 op-80 shadow-sm transition-300 hover:op-100"
    @click="onClickCallback"
  >
    <slot name="icon" />
    <slot>
      <span v-if="props.label" class="text-sm font-semibold">
        {{ props.label }}
      </span>
    </slot>
  </button>
</template>
