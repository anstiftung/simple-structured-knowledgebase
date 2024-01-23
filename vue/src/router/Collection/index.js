const Collection = () => import('@/views/Article/Show.vue')
const Collections = () => import('@/views/Article/List.vue')
const CollectionEdit = () => import('@/views/Article/Edit.vue')

export default [
  {
    path: '/sammlungen',
    component: Collections,
    name: 'collections',
    meta: {
      title: 'Alle Sammlungen',
    },
  },
  {
    path: '/sammlung/neu',
    component: CollectionEdit,
    name: 'collectionCreate',
    meta: {
      title: 'Neue Sammlung',
      protected: true,
    },
  },
  {
    path: '/sammlung/:slug/bearbeiten',
    component: CollectionEdit,
    name: 'collectionEdit',
    meta: {
      title: 'Sammlung bearbeiten',
      protected: true,
    },
  },
  {
    path: '/sammlung/:slug',
    component: Collection,
    name: 'collection',
    meta: {
      title: 'Sammlung',
    },
  },
]
