import ToastPlugin from '@/plugins/toast.js'
import keycloakInstance from '@/plugins/keycloak.js'
import Router from '@/router'

/* refreshes the API token in two cases: before every API-Call, before every route-change

before API call:
this is needed if you are editing an article for too long and click on save

beforeRouteChange:
this is needed to avoid refreshing the token multiple times when changing the route to a page which has multiple API Calls.
If the token is refreshed on RouteChange, there will be no additionall refresh-calls during the API calls.

*/

const refreshToken = async () => {
  if (keycloakInstance.token) {
    await keycloakInstance
      .updateToken(5)
      .then(state => {})
      .catch(error => {
        ToastPlugin.error('Can`t update Authorization-Token.')
        Router.push({ name: 'login' })
      })
  }

  return true
}

export default refreshToken
