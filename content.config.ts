import { defineCollection, defineContentConfig } from '@nuxt/content'

export default defineContentConfig({
  collections: {
    staticPage: defineCollection({
      type: 'page',
      source: 'static/**/*.md',
    }),
  },
})
