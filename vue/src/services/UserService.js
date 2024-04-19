import { makeApiRequest } from '@/plugins/api'

class UserService {
  getUsers(onlyEditors = false) {
    const config = {
      method: 'get',
      url: 'users',
      params: {
        onlyEditors: onlyEditors,
      },
    }
    return makeApiRequest(config)
  }
}

export default new UserService()
