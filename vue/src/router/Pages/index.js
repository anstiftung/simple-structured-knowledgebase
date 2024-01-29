const Landing = () => import('@/views/Landing.vue')
const NotAuthorized = () => import('@/views/NotAuthorized.vue')
const SearchResults = () => import('@/views/SearchResults.vue')
const Dashboard = () => import('@/views/Dashboard.vue')

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
    path: '/suche/:query?',
    component: SearchResults,
    name: 'search',
    meta: {
      title: 'Suche',
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
]
