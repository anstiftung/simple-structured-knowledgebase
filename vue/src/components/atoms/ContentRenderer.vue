<script>
import { compile, h, render } from 'vue'
/* this component dynamicly renders the props.content string as a vue component */
export default {
  props: {
    content: {
      type: String,
      default: '',
    },
  },
  setup(props) {
    return () =>
      h({
        render: compile(props.content),
      })
  },
}
</script>
