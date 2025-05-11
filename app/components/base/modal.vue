<script lang="ts">
import {
  DialogClose,
  DialogContent,
  DialogDescription,
  DialogOverlay,
  DialogPortal,
  DialogRoot,
  DialogTitle,
  DialogTrigger,
} from 'reka-ui'

export interface ModalProps {
  title?: string
  description?: string
}

export interface ModalSlots {
  default: () => any
  title: () => any
  description: () => any
  content: () => any
  close: () => any
}
</script>

<script setup lang="ts">
const props = defineProps<ModalProps>()
const slots = defineSlots<ModalSlots>()

const open = defineModel({ default: false })
</script>

<template>
  <dialog-root v-model:open="open">
    <dialog-trigger v-if="!!slots.default" as-child>
      <slot />
    </dialog-trigger>
    <dialog-portal>
      <dialog-overlay class="fixed inset-0 z-100 bg-black op-40" />
      <dialog-content class="fixed left-50% top-50% z-150 translate-x--50% translate-y--50% rounded-md bg-white p-4">
        <dialog-title v-if="props.title || !!slots.title">
          <slot name="title">
            {{ props.title }}
          </slot>
        </dialog-title>
        <dialog-description v-if="props.description || !!slots.description">
          <slot name="description">
            {{ props.description }}
          </slot>
        </dialog-description>
        <slot name="content" />
        <div v-if="!!slots.close" class="block w-full flex justify-end gap-2 pt-4">
          <dialog-close as-child>
            <slot name="close" />
          </dialog-close>
        </div>
      </dialog-content>
    </dialog-portal>
  </dialog-root>
</template>
