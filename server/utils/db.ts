import { prefixStorage } from 'unstorage'

export const storage = useStorage('app')

export function usePropertiesDb() {
  return prefixStorage<Property>(storage, 'properties:')
}
