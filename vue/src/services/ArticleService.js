import { makeApiRequest } from '@/plugins/api'

class ArticleService {
  getArticle(slug) {
    const config = {
      method: 'get',
      url: `article/${slug}`,
    }
    return makeApiRequest(config)
  }
  getArticles(
    page = 1,
    creatorId = null,
    withoutCollection = false,
    withoutPagination = false,
  ) {
    const config = {
      method: 'get',
      url: 'articles',
      params: {
        page: page,
        creatorId: creatorId,
        withoutCollection: withoutCollection,
        withoutPagination: withoutPagination,
      },
    }
    return makeApiRequest(config)
  }

  createArticle(article) {
    const data = {
      ...article,
    }
    const config = {
      method: 'post',
      url: 'article',
      data: data,
    }
    return makeApiRequest(config)
  }

  updateArticle(article) {
    const data = {
      ...article,
    }
    const config = {
      method: 'patch',
      url: `/article/${article.slug}`,
      data: data,
    }
    return makeApiRequest(config)
  }

  clapArticle(slug) {
    const config = {
      method: 'patch',
      url: `article/${slug}/clap`,
    }
    return makeApiRequest(config)
  }
}

export default new ArticleService()
