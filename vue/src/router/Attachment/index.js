const AttachmentShow = () => import('@/views/Attachment/Show.vue')

export default [
  {
    path: '/anhang/:id',
    component: AttachmentShow,
    name: 'attachedFile',
    meta: {
      title: 'Anhang',
    },
  },
  {
    path: '/url/:id',
    component: AttachmentShow,
    name: 'attachedUrl',
    meta: {
      title: 'Anhang',
    },
  },
]
