interface RequestBody {
  username: string
  password: string
}

export default defineEventHandler(async (event) => {
  const body = await readValidatedBody(event, (_data) => {
    const data = _data as any
    if (!data.username || !data.password) {
      return false
    }

    return data as RequestBody
  })

  const db = useUsersDb()

  await db.sql`INSERT INTO users (username, password) 
    VALUE (${body.username}, ${body.password})
    ON CONFLICT (username) DO UPDATE
    SET password = excluded.password`

  return sendNoContent(event)
})
