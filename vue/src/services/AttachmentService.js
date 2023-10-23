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

  createAttachmentFiles(files, recipe) {
    const data = {
      recipe_id: recipe.id,
      attached_files: files,
    }
    const config = {
      method: 'post',
      url: 'attachedFile/store',
      headers: {
        'Content-Type': 'multipart/form-data',
      },
      data: data,
    }
    return makeApiRequest(config)
  }

  updateAttachmentUrls(urls) {
    const data = {
      attached_urls: urls,
    }
    const config = {
      method: 'post',
      url: 'attachedUrl/update',
      data: data,
    }
    return makeApiRequest(config)
  }

  updateAttachmentFiles(files) {
    const data = {
      attached_files: files,
    }
    const config = {
      method: 'post',
      url: 'attachedFile/update',
      data: data,
    }
    return makeApiRequest(config)
  }
}

export default new AttachmentService()
