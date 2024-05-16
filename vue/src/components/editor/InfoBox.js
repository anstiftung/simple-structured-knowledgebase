import { Node } from '@tiptap/core'

export default Node.create({
  name: 'infoBox',

  group: 'block',

  content: 'inline*',

  addAttributes() {
    return {
      class: {
        default: 'info-box',
      },
      'data-type': {
        default: 'warning', // ['warning', 'question', 'danger']
        parseHTML: element => element.getAttribute('data-type'),
      },
    }
  },

  parseHTML() {
    return [
      {
        tag: 'div',
        class: 'info-box',
      },
    ]
  },

  renderHTML({ node, HTMLAttributes }) {
    return [
      'div',
      HTMLAttributes,
      [
        'svg',
        {
          viewBox: '0 0 15 15',
          height: '15px',
          width: '15px',
        },
        [
          'use',
          {
            href: getInfoBoxIcon(node.attrs['data-type']),
          },
        ],
      ],
      ['span', 0],
    ]
  },

  addNodeView() {
    return ({ node }) => {
      const [dom, contentElement] = getMainDom(node)
      return {
        dom,
        contentDOM: contentElement,
      }
    }
  },
})

function getMainDom(node) {
  const dom = document.createElement('div')
  dom.classList.add('info-box')
  dom.dataset.type = node.attrs['data-type']

  const iconSVG = document.createElementNS('http://www.w3.org/2000/svg', 'svg')

  iconSVG.setAttribute('viewBox', '0 0 15 15')
  iconSVG.setAttribute('width', '15px')
  iconSVG.setAttribute('height', '15px')

  const useElement = document.createElementNS(
    'http://www.w3.org/2000/svg',
    'use',
  )
  useElement.setAttribute('href', getInfoBoxIcon(node.attrs['data-type']))

  iconSVG.append(useElement)
  dom.append(iconSVG)

  const contentElement = document.createElement('div')
  contentElement.classList.add('content')
  contentElement.innerText = node.textContent

  dom.append(contentElement)

  return [dom, contentElement]
}

function getInfoBoxIcon(type) {
  return `/icons/${type}.svg#${type}`
}
