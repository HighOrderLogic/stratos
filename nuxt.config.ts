import { defineNuxtConfig } from 'nuxt/config'

export default defineNuxtConfig({
  modules: ['@unocss/nuxt', '@nuxt/content'],
  future: {
    compatibilityVersion: 4,
  },
  ssr: false,
  imports: {
    dirs: ['types'],
  },
  css: ['@fontsource-variable/inter'],
  nitro: {
    sourceMap: 'inline',
    storage: {
      app: {
        driver: 'upstash',
      },
    },
    devStorage: {
      app: {
        driver: 'fs',
        base: '.data',
      },
    },
  },
  vite: { server: { fs: { strict: false } } },
  compatibilityDate: 'latest',
})
