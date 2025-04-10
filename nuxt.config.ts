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
    sourceMap: 'inline',
    storage: {
      app: {
        driver: 'redis',
        // eslint-disable-next-line node/prefer-global/process
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
