const Article = () => import('@/views/Article.vue')
const Articles = () => import('@/views/Articles.vue')
const ArticleEdit = () => import('@/views/ArticleEdit.vue')

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
    component: ArticleEdit,
    name: 'articleCreate',
    meta: {
      title: 'Neuer Beitrag',
      protected: true,
    },
  },
  {
    path: '/beitrag/:slug/bearbeiten',
    component: ArticleEdit,
    name: 'articleEdit',
    meta: {
      title: 'Beitrag bearbeiten',
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
