import Landing from '@/views/Landing.vue'
import Test from '@/views/Test.vue'

export const routes = [
  {
    path: '/',
    component: Landing,
    name: 'landing',
    meta: {
      title: 'Startseite',
    },
  },
  {
    path: '/test',
    component: Test,
    name: 'test',
    meta: {
      title: 'Test',
    },
  },
]
