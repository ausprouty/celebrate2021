<template>
  <div class="white">
    <img src="/images/menu/black.png" />
    <h3>What wonderful things to celebrate. Isn't God amazing?</h3>
    <p><a href="/">What to celebrate some more?</a></p>
  </div>
</template>
<script>
import { mapState } from 'vuex'
export default {
  computed: mapState(['user', 'member', 'team']),
  methods: {
    async created() {
      this.user = null
      this.member = null
      this.member = null
    }
  }
}
</script>
