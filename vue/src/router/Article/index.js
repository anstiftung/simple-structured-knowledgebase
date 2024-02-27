const Article = () => import('@/views/Article/Show.vue')
const Articles = () => import('@/views/Article/List.vue')
const ArticleEdit = () => import('@/views/Article/Edit.vue')

export default [
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
