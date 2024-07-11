const UserProfile = () => import('@/views/User/Profile.vue')

export default [
  {
    path: '/profile/:id',
    component: UserProfile,
    name: 'profile',
    meta: {
      title: 'Benutzerprofil',
    },
  },
]
