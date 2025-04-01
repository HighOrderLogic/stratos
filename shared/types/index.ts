export interface Property {
  name: string
  owner: string
  dateCreated: Date
  type: 'house' | 'apartment' | 'villa'
  address: string
  sold: boolean
}
