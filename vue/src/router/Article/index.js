const Article = () => import('@/views/Article.vue')
const Articles = () => import('@/views/Articles.vue')

export default [
  {
    path: '/beitraege',
    component: Articles,
    name: 'articles',
    meta: {
      title: 'Alle Beiträge',
    },
  },
  {
    path: '/beitrag/:slug',
    component: Article,
    name: 'article',
    meta: {
      title: 'Beitrag',
    },
  },
]
