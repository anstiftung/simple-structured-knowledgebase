const Collection = () => import('@/views/Collection/Show.vue')
const Collections = () => import('@/views/Collection/List.vue')
const CollectionEdit = () => import('@/views/Collection/Edit.vue')

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
