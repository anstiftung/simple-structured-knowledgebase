import AuthRoutes from "./Auth/index"
import ArticleRoutes from "./Article/index"
import PageRoutes from "./Pages/index"

export default [
  ...AuthRoutes,
  ...PageRoutes,
  ...ArticleRoutes
]
