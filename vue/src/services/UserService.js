import { makeApiRequest } from '@/plugins/api'

class UserService {
  getUsers() {
    const config = {
      method: 'get',
      url: 'users',
    }
    return makeApiRequest(config)
  }
}

export default new UserService()
