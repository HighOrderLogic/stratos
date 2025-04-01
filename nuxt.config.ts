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
      db: {
        driver: 'vercel-kv',
      },
    },
    devStorage: {
      db: {
        driver: 'fs',
        base: './.data/db',
      },
    },
  },

  compatibilityDate: '2025-03-31',
})