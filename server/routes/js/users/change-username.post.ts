import * as z from 'zod'

const requestBodySchema = z.object({
  id: z.number(),
  newUsername: z.string().transform(s => s.trim()),
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
    UPDATE users 
    SET username = ${body.newUsername} 
    WHERE id = ${body.id} 
    RETURNING username`

  return { username: rows[0]?.username as string }
})
