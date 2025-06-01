import * as z from 'zod'

const requestBodySchema = z.object({
  username: z.string().transform(s => s.trim()),
  password: z.string().transform(s => s.trim()),
})

export default defineEventHandler(async (event) => {
  const body = await readValidatedBody(event, (b) => {
    const res = requestBodySchema.safeParse(b)
    if (res.error) {
      return false
    }

    return res.data
  })

  const db = useUsersDb()

  const { rows = [] } = await db.sql`
    INSERT INTO users (username, password) 
    VALUES (${body.username}, ${body.password})
    ON CONFLICT (username) DO UPDATE
    SET password = excluded.password
    RETURNING id, username`

  return { id: rows[0]?.id as number, username: rows[0]?.username as string }
})
