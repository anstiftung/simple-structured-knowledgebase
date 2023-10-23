import axios from 'axios'
import { useToast } from 'vue-toastification'
const API_URL = import.meta.env.VITE_API_ROOT + 'api/'

const api = axios.create({
  baseURL: API_URL,
})

const toast = useToast()

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
        toast.error('data property does not exist in the response body')
      }
    })
    .catch(error => {
      if (error.response) {
        console.error(`Server error with code ${error.response.status}`)
        toast.error(`Server error with code ${error.response.status}`)
      } else if (error.request) {
        console.error(`Request error ${error.request}`)
        toast.error(`Request error ${error.request}`)
      } else {
        console.error(`Error: ${error.message}`)
        toast.error(`Error: ${error.message}`)
      }
      throw error
    })
}
