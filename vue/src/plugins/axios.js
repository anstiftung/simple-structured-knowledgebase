import axios from 'axios'

axios.interceptors.response.use(
  function (response) {
    return response
  },
  function (error) {
    if (error.response.status === 401) {
      console.log('[401] Unauthorized Error')
    }
    return Promise.reject(error)
  },
)

export default axios
