import AuthRoutes from './Auth/index'
import ArticleRoutes from './Article/index'
import CollectionRoutes from './Collection/index'
import PageRoutes from './Pages/index'

export default [
  ...AuthRoutes,
  ...PageRoutes,
  ...ArticleRoutes,
  ...CollectionRoutes,
]
