import { makeApiRequest } from '@/plugins/api'

class LicenseService {
  getLicenses() {
    const config = {
      method: 'get',
      url: 'licenses',
    }
    return makeApiRequest(config)
  }
}

export default new LicenseService()
