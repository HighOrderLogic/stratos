export default defineEventHandler(async (event) => {
  const userId = getRouterParam(event, 'id') as string

  const db = useUsersDb()

  const { rows = [] } = await db.sql`SELECT * FROM requests WHERE by_user = ${userId}`

  return rows.map(r => ({
    id: r.id,
    content: r.content,
    createdAt: new Date(r.created_at as string),
    authorId: r.by_user,
  }))
})
