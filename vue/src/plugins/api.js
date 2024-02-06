import axios from 'axios'
import keycloakInstance from '@/plugins/keycloak.js'
import { useToast } from 'vue-toastification'
const API_URL = import.meta.env.VITE_API_ROOT + 'api/'

const toast = useToast()

const api = axios.create({
  baseURL: API_URL,
})

// adds interceptor to injext auth token in every api request
api.interceptors.request.use(function (config) {
  const token = keycloakInstance.token
  if (token) {
    config.headers.Authorization = 'Bearer ' + token
  }

  return config
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
        toast.error('data property does not exist in the response body')
        throw new Error('data property does not exist in the response body')
      }
    })
    .catch(error => {
      if (error.response) {
        let errorString = `Server error with code ${error.response.status}`
        if (error.response.data.message) {
          errorString = `Server error: ${error.response.data.message}`
        }
        console.error(errorString)
        toast.error(errorString)
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