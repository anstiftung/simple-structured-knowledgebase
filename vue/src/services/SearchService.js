import { makeApiRequest } from '@/plugins/api'

class SearchService {
  search(
    query,
    types = ['articles', 'collections', 'attachments'],
    onlyPublished = true,
  ) {
    const config = {
      method: 'get',
      url: 'search',
      params: {
        query: query,
        types: types,
        onlyPublished: onlyPublished,
      },
    }
    return makeApiRequest(config)
  }
}

export default new SearchService()
