import { helpers, maxLength, required, url } from '@vuelidate/validators'

export const required$ = helpers.withMessage(
  'Dieses Feld darf nicht leer sein.',
  required,
)

export const maxLength$ = max =>
  helpers.withMessage(
    ({ $params }) => `Es sind maximal ${$params.max} Zeichen erlaubt.`,
    maxLength(max),
  )

export const url$ = helpers.withMessage(
  'Diese Feld muss eine gültige URL sein (beginnend mit https…)',
  url,
)
