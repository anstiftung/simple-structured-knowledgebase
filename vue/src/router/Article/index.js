const Article = () => import('@/views/Article.vue')
const Articles = () => import('@/views/Articles.vue')

export default [
  {
    path: '/artikel',
    component: Articles,
    name: 'articles',
    meta: {
      title: 'Alle Artikel',
    },
  },
  {
    path: '/artikel/:slug',
    component: Article,
    name: 'article',
    meta: {
      title: 'Artikel',
    },
  },
]
