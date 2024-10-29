const Register = () => import('@/views/Auth/Register.vue')
const NotFound = () => import('@/views/NotFound.vue')

const Login = () => import('@/views/Auth/Login.vue')
const Logout = () => import('@/views/Auth/Logout.vue')
const LoginLocally = () => import('@/views/Auth/LoginLocally.vue')

const KEYCLOAK_ENABLED = import.meta.env.VITE_KEYCLOAK_ENABLED

export default [
  {
    path: '/auth/login',
    component: KEYCLOAK_ENABLED == 'true' ? Login : LoginLocally,
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
    path: '/auth/register',
    component: KEYCLOAK_ENABLED == 'true' ? NotFound : Register,
    name: 'register',
    meta: {
      title: 'Registrierung',
    },
  },
]
