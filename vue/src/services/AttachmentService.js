import { makeApiRequest } from '@/plugins/api'

class AttachmentService {
  getAttachmentUrls(page = 1, creatorId = null, invalid = false) {
    const config = {
      method: 'get',
      url: 'attached-urls',
      params: {
        page: page,
        creatorId: creatorId,
        invalid: invalid,
      },
    }
    return makeApiRequest(config)
  }

  getAttachmentFiles(page = 1, creatorId = null, invalid = false) {
    const config = {
      method: 'get',
      url: 'attached-files',
      params: {
        page: page,
        creatorId: creatorId,
        invalid: invalid,
      },
    }
    return makeApiRequest(config)
  }

  createAttachmentUrls(urls, article) {
    const data = {
      attached_urls: urls,
      ...(article && { article_id: article.id }),
    }
    const config = {
      method: 'post',
      url: 'attached-url/store',
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
      url: 'attached-file/store',
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
      url: 'attached-url/update',
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
      url: 'attached-file/update',
      data: data,
    }
    return makeApiRequest(config)
  }
}

export default new AttachmentService()
