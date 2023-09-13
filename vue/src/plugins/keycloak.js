import Keycloak from 'keycloak-js'

const initOptions = {
  realm: 'verbund-offener-werkstaetten',
  clientId: 'cowiki',
  url: 'https://keytest.offene-werkstaetten.org/',
  //   resource: 'cowiki', //this is optional, depending on your keycloak config
  //   'public-client': true,
  //   'verify-token-audience': false,
  //   'ssl-required': 'external',
}

const keycloakInstance = new Keycloak(initOptions)

/**
 * Initializes Keycloak instance and calls the provided callback function if successfully authenticated.
 *
 * @param onAuthenticatedCallback
 */
const Login = callback => {
  keycloakInstance
    .init({ onLoad: 'login-required', checkLoginIframe: false })
    .then(function (authenticated) {
      callback()
    })
    .catch(e => {
      console.dir(e)
      console.log(`keycloak init exception: ${e}`)
    })

  console.log(keycloakInstance.idTokenParsed.email)
}

const RefreshToken = async callback => {
  try {
    await keycloakInstance.updateToken(30)
    console.error('token updated')
  } catch (error) {
    console.error('Failed to refresh token:', error)
  }
}

const KeyCloakService = {
  CallLogin: Login,
  CallRefreshToken: RefreshToken,
}

export default KeyCloakService
