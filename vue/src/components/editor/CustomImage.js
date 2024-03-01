import Image from '@tiptap/extension-image'

export default Image.extend({
  addAttributes() {
    return {
      ...this.parent?.(),
      class: {
        default: 'w-full',
      },
    }
  },
})
