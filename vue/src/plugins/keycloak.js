import Keycloak from 'keycloak-js'

const initOptions = {
  realm: 'verbund-offener-werkstaetten',
  clientId: 'cowiki',
  url: import.meta.env.VITE_KEYCLOAK_BASE_URL,
  //   resource: 'cowiki', //this is optional, depending on your keycloak config
  //   'public-client': true,
  //   'verify-token-audience': false,
  //   'ssl-required': 'external',
}

const keycloakInstance = new Keycloak(initOptions)
export default keycloakInstance
