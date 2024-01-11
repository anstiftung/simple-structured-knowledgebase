const Article = () => import('@/views/Article.vue')
const Articles = () => import('@/views/Articles.vue')
const ArticleCreate = () => import('@/views/ArticleCreate.vue')

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
    path: '/beitrag/neu',
    component: ArticleCreate,
    name: 'articleCreate',
    meta: {
      title: 'Neuer Beitrag',
      protected: true,
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
