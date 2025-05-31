export default defineEventHandler(async () => {
  const propertiesDb = usePropertiesDb()

  const properties: Record<string, Property> = {}

  for (const key of await propertiesDb.getKeys()) {
    const property = await propertiesDb.getItem(key)

    if (property) {
      properties[key] = property
    }
  }

  return Object.entries(properties).map(([key, property]) => ({ id: key, ...property }))
})
