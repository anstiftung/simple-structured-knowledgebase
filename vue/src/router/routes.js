import ArticleRoutes from './Article/index'
import AttachmentRoutes from './Attachment/index'
import AuthRoutes from './Auth/index'
import CollectionRoutes from './Collection/index'
import PageRoutes from './Pages/index'

export default [
  ...AuthRoutes,
  ...PageRoutes,
  ...ArticleRoutes,
  ...CollectionRoutes,
  ...AttachmentRoutes,
]
