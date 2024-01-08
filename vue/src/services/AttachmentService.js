import { makeApiRequest } from '@/plugins/api'

class AttachmentService {
  createAttachmentUrls(urls, article) {
    const data = {
      attached_urls: urls,
      ...(article && { article_id: article.id }),
    }
    const config = {
      method: 'post',
      url: 'attachedUrl/store',
      data: data,
    }
    return makeApiRequest(config)
  }

  createAttachmentFiles(files, article, progressCallback) {
    const data = {
      attached_files: files,
      ...(article && { article_id: article.id }),
    }
    const config = {
      method: 'post',
      url: 'attachedFile/store',
      headers: {
        'Content-Type': 'multipart/form-data',
      },
      onUploadProgress: progressEvent => {
        const { loaded, total } = progressEvent
        let percentage = Math.floor((loaded * 100) / total)
        progressCallback(percentage)
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
