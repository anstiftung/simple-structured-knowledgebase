import { makeApiRequest } from '@/plugins/api'

class SearchService {
  search(
    query,
    types = ['articles', 'collections', 'attachments'],
    onlyPublished = true,
    creator_id = 0,
    includingTrashed = false,
    sortBy = 'created_at',
    sortOrder = 'desc',
  ) {
    const config = {
      method: 'get',
      url: 'search',
      params: {
        query: query,
        types: types,
        onlyPublished: onlyPublished,
        created_by_id: creator_id,
        includingTrashed: includingTrashed,
        sortBy: sortBy,
        sortOrder: sortOrder,
      },
    }
    return makeApiRequest(config)
  }
}

export default new SearchService()
