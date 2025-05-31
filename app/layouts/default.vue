<template>
  <div class="font-normal font-[Inter_Variable] prose-neutral">
    <header
      class="sticky top-0 z-50 block max-h-10 min-w-full flex p-4 backdrop-blur-sm"
      :class="[$style.headerShadow]"
    >
      <nuxt-link href="/">
        <img src="~/public/USyd.jpg" class="max-h-6">
      </nuxt-link>
      <div class="w-full" />
      <div class="flex gap-4 whitespace-nowrap">
        <nuxt-link href="/static/introduction">
          Introduction
        </nuxt-link>
        <nuxt-link href="/api/php/page.php" target="_blank">
          Team members
        </nuxt-link>
        <nuxt-link href="/static/tos">
          Term of Service
        </nuxt-link> <nuxt-link href="/static/contact">
          Contact Us
        </nuxt-link>
      </div>
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
