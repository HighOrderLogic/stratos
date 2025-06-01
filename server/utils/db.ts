import type { Connector, Database } from 'db0'
import { createDatabase } from 'db0'
import pgConnector from 'db0/connectors/postgresql'
import type { Client as PgClient } from 'pg'
import { prefixStorage } from 'unstorage'

export const storage = useStorage<any>('app')

export function usePropertiesDb() {
  return prefixStorage<Property>(storage, 'properties:')
}

export type PgDatabase = Database<Connector<PgClient>>

let pgInstance: PgDatabase

export function useUsersDb(): PgDatabase {
  if (!pgInstance) {
    // eslint-disable-next-line node/prefer-global/process
    const pgUrl = globalThis.process?.env?.POSTGRES_URL

    if (!pgUrl) {
      throw new Error('No Postgres url environment variable')
    }
    pgInstance = createDatabase(pgConnector({ connectionString: pgUrl.split('?', 1)[0], ssl: false }))
  }

  return pgInstance
}
