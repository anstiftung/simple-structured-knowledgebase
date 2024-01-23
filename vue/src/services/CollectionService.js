import { makeApiRequest } from '@/plugins/api'

class CollectionService {
  getCollection(slug) {
    const config = {
      method: 'get',
      url: `collection/${slug}`,
    }
    return makeApiRequest(config)
  }
  getCollections(page = 1, creatorId = null) {
    const config = {
      method: 'get',
      url: 'collections',
      params: {
        page: page,
        creatorId: creatorId,
      },
    }
    return makeApiRequest(config)
  }

  createCollection(collection) {
    const data = {
      ...collection,
    }
    const config = {
      method: 'post',
      url: 'collection',
      data: data,
    }
    return makeApiRequest(config)
  }

  updateCollection(collection) {
    const data = {
      ...collection,
    }
    const config = {
      method: 'patch',
      url: `/collection/${Collection.slug}`,
      data: data,
    }
    return makeApiRequest(config)
  }
}

export default new CollectionService()
