import { Extension } from '@tiptap/core'

export const ExitLinkOnEnter = Extension.create({
  name: 'exitLinkOnEnter',

  addKeyboardShortcuts() {
    return {
      Enter: ({ editor }) => {
        if (!editor.isActive('link')) {
          return false
        }

        editor.chain().focus().unsetLink().splitBlock().run()

        return true // prevent default Enter behavior
      },
    }
  },
})
