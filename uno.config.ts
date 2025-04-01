import {
  defineConfig,
  presetIcons,
  presetTypography,
  presetWind3,
  transformerDirectives,
  transformerVariantGroup,
} from 'unocss'

export default defineConfig({
  presets: [
    presetIcons(),
    presetTypography(),
    presetWind3(),
  ],
  transformers: [
    transformerDirectives(),
    transformerVariantGroup(),
  ],
})
