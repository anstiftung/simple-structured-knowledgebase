const Landing = () => import('@/views/Landing.vue')
const NotAuthorized = () => import('@/views/NotAuthorized.vue')
const Dashboard = () => import('@/views/Dashboard.vue')
const NotFound = () => import('@/views/NotFound.vue')
const Search = () => import('@/views/Search/List.vue')

export default [
  {
    path: '/',
    component: Landing,
    name: 'landing',
    meta: {
      title: 'Startseite',
    },
  },
  {
    path: '/not-authorized',
    component: NotAuthorized,
    name: 'not-authorized',
    meta: {
      title: 'Zugriff verweigert',
    },
  },
  {
    path: '/dashboard',
    component: Dashboard,
    name: 'dashboard',
    meta: {
      title: 'Dashboard',
      protected: true,
    },
  },
  {
    path: '/search',
    component: Search,
    name: 'search',
    meta: {
      title: 'Suche',
      protected: true,
    },
  },
  {
    path: '/not-found',
    component: NotFound,
    name: 'not-found',
    meta: {
      title: 'Fehler',
    },
  },
]
