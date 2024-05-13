import ToastPlugin from '@/plugins/toast.js'
import keycloakInstance from '@/plugins/keycloak.js'

const refreshToken = async () => {
  try {
    await keycloakInstance.updateToken(5).then(state => {
      if (state) console.info('api token renewed.')
    })
  } catch (error) {
    ToastPlugin.error('Can`t update Authorization-Token.')
  }
}

export default refreshToken
