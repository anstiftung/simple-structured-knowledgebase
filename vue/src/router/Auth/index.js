const Logout = () => import('@/views/Auth/Logout.vue')

export default [
  {
    path: '/auth/logout',
    component: Logout,
    name: 'logout',
    meta: {
      title: 'Logout',
    },
  }
]
