const AttachedFile = () => import('@/views/Attachment/Show.vue')

export default [
  {
    path: '/anhang/:id',
    component: AttachedFile,
    name: 'attachedFile',
    meta: {
      title: 'Anhang',
    },
  },
]
