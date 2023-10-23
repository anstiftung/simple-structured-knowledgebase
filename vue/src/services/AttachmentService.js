import { makeApiRequest } from '@/plugins/api'

class AttachmentService {
  createAttachmentUrls(urls, recipe) {
    const data = {
      recipe_id: recipe.id,
      attached_urls: urls,
    }
    const config = {
      method: 'post',
      url: 'attachedUrl/store',
      data: data,
    }
    return makeApiRequest(config)
  }
}

export default new AttachmentService()
