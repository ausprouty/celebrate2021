<template>
  <div class="white">
    <NavBar v-bind="menu"></NavBar>
    <div v-if="!this.authorized" class="not_authorized">
      <p>
        You have stumbled into a restricted page. Sorry I can not show it to you
        now
      </p>
    </div>
    <div v-if="this.authorized">
      <h1 class="center">Post</h1>
      <p>This allows you to post stats to someone</p>

    </div>
  </div>
</template>

<script>
import AuthorService from '@/services/AuthorService.js'
import { mapState } from 'vuex'
import { authorMixin } from '@/mixins/AuthorMixin.js'
import NavBar from '@/components/NavBar.vue'

export default {
  components: {

    NavBar
  },

  mixins: [authorMixin],
  data() {
    return {
      authorized: false,
      teams: {}
    }
  },
  computed: mapState(['team']),
  methods: {
    newTeam() {
      this.$router.push({
        name: 'teamProfile',
        params: {
          tid: null
        }
      })
    }
  },
  beforeCreate: function() {
    document.body.className = 'team'
  },
  async created() {
    this.authorized = this.authorize('admin', null, null)
    if (this.authorized) {
      try {

      } catch (error) {
        console.log('There was an error in AdminPost.vue:', error) // Logs out the error
      }
    }
  }
}
</script>
<style scoped></style>
