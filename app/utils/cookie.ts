export function useUserCookie() {
  return useCookie<{ id: number, username: string, password: string } | undefined>('user', { default: () => undefined })
}
