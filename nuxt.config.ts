import process from 'node:process'

import { defineNuxtConfig } from 'nuxt/config'

export default defineNuxtConfig({
  modules: ['@unocss/nuxt'],
  future: {
    compatibilityVersion: 4,
  },
  ssr: false,
  imports: {
    dirs: ['types'],
  },
  css: ['@fontsource-variable/inter'],
  nitro: {
    storage: {
      app: {
        driver: 'redis',
        url: process.env.REDIS_URL,
      },
    },
    devStorage: {
      app: {
        driver: 'fs',
        base: '.data',
      },
    },
  },
})
