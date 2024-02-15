import ConfirmationToast from '@/components/atoms/ConfirmationToast.vue'
import { useToast } from 'vue-toastification'

const toast = useToast()

class ToastService {
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
}

export default new ToastService()
