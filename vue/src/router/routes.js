import ArticleRoutes from '@/router/Article/index'
import AttachmentRoutes from '@/router/Attachment/index'
import AuthRoutes from '@/router/Auth/index'
import CollectionRoutes from '@/router/Collection/index'
import PageRoutes from '@/router/Pages/index'
import UserRoutes from '@/router/User/index'

export default [
  ...AuthRoutes,
  ...PageRoutes,
  ...ArticleRoutes,
  ...CollectionRoutes,
  ...AttachmentRoutes,
  ...UserRoutes,
]
