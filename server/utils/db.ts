import { prefixStorage } from 'unstorage'

export const storage = useStorage<Property>('app')

export function usePropertiesDb() {
  return prefixStorage(storage, 'properties:')
}
