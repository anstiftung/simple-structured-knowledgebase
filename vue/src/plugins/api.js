import axios from 'axios'

const API_URL = import.meta.env.VITE_API_ROOT + 'api/'

const api = axios.create({
  baseURL: API_URL,
})

export const makeApiRequest = config => {
  return api(config)
    .then(response => {
      if (response.data.meta) {
        // paginated response -> return data and meta attribute
        return response.data
      }
      // single response -> return data property directly
      else if (response.data.data) {
        //
        return response.data.data
      } else {
        throw new Error('data property does not exist in the response body')
      }
    })
    .catch(error => {
      if (error.response) {
        console.error(`Server error with code ${error.response.status}`)
      } else if (error.request) {
        console.error(`Request error ${error.request}`)
      } else {
        console.error(`Error: ${error.message}`)
      }
      throw error
    })
}
