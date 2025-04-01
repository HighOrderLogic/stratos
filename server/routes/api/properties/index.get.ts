export default defineEventHandler(async () => {
  const propertiesDb = usePropertiesDb()

  const properties = await propertiesDb.getItems(await propertiesDb.getKeys())

  return properties.map(({ key, value }) => ({ id: key, ...value }))
})
