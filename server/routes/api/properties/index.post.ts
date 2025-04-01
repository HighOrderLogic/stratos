import { v4 as uuidV4 } from 'uuid'

export default defineEventHandler(async (event) => {
  const body = await readValidatedBody(event, (_data) => {
    const data = _data as any

    if (!data.name || !data.owner || !data.type || !data.address) {
      return false
    }

    if (!['house', 'apartment', 'villa'].includes(data.type)) {
      return false
    }

    return data as Omit<Property, 'dateCreated' | 'sold'>
  })

  const newProperty: Property = { dateCreated: new Date(), sold: false, ...body }
  const newPropertyId = uuidV4()

  const propertiesDb = usePropertiesDb()

  propertiesDb.setItem(newPropertyId, newProperty)

  return { id: newPropertyId, ...newProperty }
})
