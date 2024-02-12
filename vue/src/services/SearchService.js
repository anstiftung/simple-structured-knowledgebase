import { makeApiRequest } from '@/plugins/api'

class SearchService {
  search(query, types = ['articles', 'collections', 'attachments']) {
    const config = {
      method: 'get',
      url: 'search',
      params: {
        query: query,
        types: types,
      },
    }
    return makeApiRequest(config)
  }
}

export default new SearchService()
