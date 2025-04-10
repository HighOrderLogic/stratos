import { defineNuxtConfig } from 'nuxt/config'

const redisURL = 'redis://default:MZ8vgdY0RHh5vZFZijjQTrzeVkJWjuTe@redis-15004.c291.ap-southeast-2-1.ec2.redns.redis-cloud.com:15004'

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
        url: redisURL,
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
