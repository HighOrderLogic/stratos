<template>
  <div class="font-normal font-[Inter_Variable] prose-neutral">
    <header
      class="sticky top-0 z-50 block min-w-full flex justify-center p-4 backdrop-blur-sm"
      :class="[$style.headerShadow]"
    >
      <nuxt-link href="/">
        <img src="~/public/USyd.jpg" />
      </nuxt-link>
    </header>
    <div class="mx-a pt-4 md:max-w-60ch lt-md:px-4">
      <slot />
    </div>
  </div>
</template>

<style lang="scss" module>
.headerShadow {
  box-shadow: rgba(0, 0, 0, 0.05) 0px 6px 24px 0px, rgba(0, 0, 0, 0.08) 0px 0px 0px 1px;
}
</style>
