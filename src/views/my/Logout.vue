<template>
  <div class="white">
    <img src="/images/menu/black.png" />
    <h3>What wonderful things to celebrate. Isn't God amazing?</h3>
    <p><a href="/">What to celebrate some more?</a></p>
  </div>
</template>
<script>
import { mapState } from 'vuex'
import { authorMixin } from '@/mixins/AuthorMixin.js'
export default {
  computed: mapState(['user', 'viewing']),
  mixins: [authorMixin],
  methods: {
    async created() {
      this.clearView()
      this.logout()
    }
  }
}
</script>
