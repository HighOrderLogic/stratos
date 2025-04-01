export function usePropertiesDb() {
  return useStorage<Property>('properties')
}
