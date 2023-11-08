import moment from 'moment'

const filters = {
  formatedDate(date) {
    return moment(date).format('DD.MM.YYYY')
  },
  formatedDateTime(date) {
    return moment(date).format('DD.MM.YYYY HH:mm') + ' Uhr'
  },
  mimeTypeToFileType(mimeType) {
    switch (mimeType) {
      case 'image/png':
        return 'png'
        break
      case 'image/jpeg':
        return 'jpg'
        break
      default:
        return '?'
        break
    }
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
