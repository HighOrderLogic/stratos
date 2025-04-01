export default defineEventHandler(async (event) => {
  const propertiesDb = usePropertiesDb()

  const propertyId = getRouterParam(event, 'id')!

  const property = await propertiesDb.get(propertyId)

  if (property) {
    return property
  } else {
    return sendError(event, createError({ statusCode: 404, message: 'property not found' }))
  }
})
