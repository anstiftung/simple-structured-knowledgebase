import ConfirmationToast from '@/components/atoms/ConfirmationToast.vue'
import { useToast } from 'vue-toastification'

const toast = useToast()

class ToastPlugin {
  confirm(message, callback) {
    toast.clear()
    const content = {
      component: ConfirmationToast,
      props: {
        message: message,
      },
      listeners: {
        granted: () => callback(),
      },
    }
    toast(content, {
      timeout: false,
      icon: false,
      closeButton: false,
    })
  }
  success(message) {
    toast.success(message)
  }
  error(message) {
    toast.error(message)
  }
  warning(message) {
    toast.warning(message)
  }
}

export default new ToastPlugin()
