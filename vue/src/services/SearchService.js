import { makeApiRequest } from '@/plugins/api'

class SearchService {
  search(query) {
    const config = {
      method: 'get',
      url: 'search',
      params: {
        query: query,
      },
    }
    return makeApiRequest(config)
  }
}

export default new SearchService()
