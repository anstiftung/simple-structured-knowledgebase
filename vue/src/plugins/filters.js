import moment from 'moment'

const filters = {
  formatedDate(date) {
    return moment(date).format('DD.MM.YYYY')
  },
  formatedDateTime(date) {
    return moment(date).format('DD.MM.YYYY HH:mm') + ' Uhr'
  },
}
export default filters
