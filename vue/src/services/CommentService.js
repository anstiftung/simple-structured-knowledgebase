import { makeApiRequest } from '@/plugins/api'

class CommentService {
  createComment(comment) {
    const data = {
      ...comment,
    }
    const config = {
      method: 'post',
      url: 'comment',
      data: data,
    }
    return makeApiRequest(config)
  }

  deleteComment(comment) {
    const config = {
        method: 'delete',
        url: `comment/${comment.id}`
    }
    return makeApiRequest(config)
  }
}

export default new CommentService()
