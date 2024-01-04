import { makeApiRequest } from '@/plugins/api'

class ArticleService {
  getArticle(slug) {
    const config = {
      method: 'get',
      url: `article/${slug}`,
    }
    return makeApiRequest(config)
  }
  getArticles(page) {
    const config = {
      method: 'get',
      url: 'articles',
      params: {
        page: page ?? 1,
      },
    }
    return makeApiRequest(config)
  }
}

export default new ArticleService()
