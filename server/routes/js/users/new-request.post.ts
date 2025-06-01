import * as z from 'zod'

const requestBodySchema = z.object({
  userId: z.number(),
  content: z.string().transform(s => s.trim()),
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
    INSERT INTO requests (by_user, content) 
    VALUES (${body.userId}, ${body.content}) 
    RETURNING id`

  return { id: rows[0]!.id }
})
