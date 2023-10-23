import { makeApiRequest } from '@/plugins/api'

class AttachmentService {
  createAttachmentFiles(attachmentFile) {
    const config = {
      method: 'post',
      url: 'attachmentFile',
      attachmentFile,
    }
    return makeApiRequest(config)
  }
}

export default new AttachmentService()
