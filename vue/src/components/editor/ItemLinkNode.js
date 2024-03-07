import { Node, mergeAttributes } from '@tiptap/core'

export default Node.create({
  name: 'itemLink',

  group: 'inline',

  inline: true,

  content: 'inline*',

  addAttributes() {
    return {
      'data-type': {
        default: null,
      },
      'data-id': {
        default: null,
      },
      href: {
        default: null,
      },
      target: {
        default: null,
      },
    }
  },

  parseHTML() {
    return [
      {
        tag: 'item-link',
      },
    ]
  },

  renderHTML({ HTMLAttributes }) {
    return ['item-link', mergeAttributes(HTMLAttributes), 0]
  },

  addNodeView() {
    return ({ node }) => {
      const dom = document.createElement('a')
      dom.dataset.type = node.attrs['data-type']
      dom.dataset.id = node.attrs['data-id']
      dom.setAttribute('href', node.attrs['href'])
      dom.setAttribute('target', node.attrs['target'])

      return {
        dom,
        contentDOM: dom,
      }
    }
  },
})
