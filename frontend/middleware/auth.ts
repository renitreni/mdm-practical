export default defineNuxtRouteMiddleware((to, from) => {
    const auth = useCookie('tokenCookie')

    if (to.fullPath == '/' && auth.value != undefined) {
        return navigateTo('/portal')
    }

    if (to.fullPath !== '/' && auth.value === undefined) {
        return navigateTo('/')
    }
  })
