import { makeApiRequest } from '@/plugins/api'

class AttachmentService {
  getAttachedFile(id, withArticles = false, errorFunction = null) {
    const config = {
      method: 'get',
      url: `attached-file/${id}`,
      params: {
        withArticles: withArticles,
      },
    }
    return makeApiRequest(config, errorFunction)
  }
  getAttachedUrl(id, withArticles = false, errorFunction = null) {
    const config = {
      method: 'get',
      url: `attached-url/${id}`,
      params: {
        withArticles: withArticles,
      },
    }
    return makeApiRequest(config, errorFunction)
  }

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
      url: 'attached-url',
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
      url: 'attached-file',
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
      method: 'patch',
      url: 'attached-url',
      data: data,
    }
    return makeApiRequest(config)
  }

  updateAttachmentFiles(files) {
    const data = {
      attached_files: files,
    }
    const config = {
      method: 'patch',
      url: 'attached-file',
      data: data,
    }
    return makeApiRequest(config)
  }

  combineAttachments(urls, files) {
    let attachments = urls.concat(files)
    attachments = attachments.sort((a, b) => a.created_at < b.created_at)
    return attachments
  }

  deleteAttachment(attachment, forceDelete = false) {
    let url =
      attachment.type == 'AttachedFile' ? 'attached-file' : 'attached-url'
    const config = {
      method: 'delete',
      url: `${url}/${attachment.id}`,
      params: {
        forceDelete: forceDelete,
      },
    }
    return makeApiRequest(config)
  }
}

export default new AttachmentService()
