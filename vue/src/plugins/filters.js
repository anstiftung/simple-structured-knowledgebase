import moment from 'moment'

const filters = {
  formatedDate(date) {
    return moment(date).format('DD.MM.YYYY')
  },
  formatedDateTime(date) {
    return moment(date).format('DD.MM.YYYY HH:mm') + ' Uhr'
  },
  fileNameToFileType(fileName) {
    const parts = fileName.split('.')
    return parts[parts.length - 1]
  },
  bytesToHumandReadableSize(size) {
    const i = size == 0 ? 0 : Math.floor(Math.log(size) / Math.log(1024))
    return (
      (size / Math.pow(1024, i)).toFixed(2) * 1 +
      ' ' +
      ['B', 'kB', 'MB', 'GB', 'TB'][i]
    )
  },
}
export default filters
