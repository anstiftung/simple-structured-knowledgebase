import { makeApiRequest } from '@/plugins/api'

class CollectionService {
  getCollection(slug) {
    const config = {
      method: 'get',
      url: `collection/${slug}`,
    }
    return makeApiRequest(config)
  }
  getCollections(page = 1, parameters = {}) {
    const config = {
      method: 'get',
      url: 'collections',
      params: {
        page: page,
        ...parameters,
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
      url: `/collection/${collection.slug}`,
      data: data,
    }
    return makeApiRequest(config)
  }

  reorderFeaturedCollections(collections) {
    const config = {
      method: 'patch',
      url: `/collections/featured/reorder`,
      data: collections,
    }

    return makeApiRequest(config)
  }
}

export default new CollectionService()
