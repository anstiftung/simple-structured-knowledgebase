const Login  = () => import('@/views/Auth/Login.vue')
const Logout = () => import('@/views/Auth/Logout.vue')
const Renew = () => import('@/views/Auth/Renew.vue')

export default [
  {
    path: '/auth/login',
    component: Login,
    name: 'login',
    meta: {
      title: 'Login',
    },
  },
  {
    path: '/auth/logout',
    component: Logout,
    name: 'logout',
    meta: {
      title: 'Logout',
    },
  },
  {
    path: '/auth/renew',
    component: Renew,
    name: 'auth-renew',
    meta: {
      title: 'Renew authentication',
    },
  },
]
