import { Node, mergeAttributes } from '@tiptap/core'

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

  renderHTML({ HTMLAttributes }) {
    return ['div', mergeAttributes(HTMLAttributes), 0]
  },

  addNodeView() {
    return ({ node }) => {
      // Markup is slightly different, than the output. Reason: tiptap requires two dom elements to be returned
      /*
        <div class="info-box">
        <svg use="/icons/warning.svg#warning"/>
          <div class="content"></div>
        </div>
      */

      const dom = document.createElement('div')

      dom.classList.add('info-box')
      dom.dataset.type = node.attrs['data-type']

      const iconSVG = document.createElementNS(
        'http://www.w3.org/2000/svg',
        'svg',
      )

      iconSVG.setAttribute('fill', 'currentColor')
      iconSVG.setAttribute('viewBox', '0 0 15 15')
      iconSVG.setAttribute('width', '15px')
      iconSVG.setAttribute('height', '15px')

      const useElement = document.createElementNS(null, 'use')
      useElement.setAttribute('href', `/icons/danger.svg#danger`)

      iconSVG.append(useElement)
      dom.append(iconSVG)

      const content = document.createElement('div')
      content.classList.add('content')

      dom.append(content)

      return {
        dom,
        contentDOM: content,
      }
    }
  },
})
