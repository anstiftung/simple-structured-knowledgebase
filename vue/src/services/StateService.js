import { makeApiRequest } from '@/plugins/api'

class StateService {
  getStates() {
    const config = {
      method: 'get',
      url: 'states',
    }
    return makeApiRequest(config)
  }
}

export default new StateService()
