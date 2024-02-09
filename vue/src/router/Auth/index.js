const Login = () => import('@/views/Auth/Login.vue')
const Logout = () => import('@/views/Auth/Logout.vue')

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
]
