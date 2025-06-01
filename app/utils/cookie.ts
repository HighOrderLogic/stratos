export function useUserCookie() {
  return useCookie<{ username: string, password: string } | undefined>('user', { default: () => undefined })
}
