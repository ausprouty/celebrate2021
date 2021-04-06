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
      <h1 class="center">Teams</h1>
      <TeamList v-for="team in teams" :key="team.tid" :team="team" />
      <button class="button green" id="update" @click="newTeam">New Team</button>
    </div>
  </div>
</template>

<script>
import AuthorService from '@/services/AuthorService.js'
import TeamList from '@/components/TeamList.vue'
import { mapState } from 'vuex'
import { authorMixin } from '@/mixins/AuthorMixin.js'
import NavBar from '@/components/NavBar.vue'

export default {
  components: {
    TeamList,
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
        this.menu = await this.menuParams('Teams', 'M')
        var params = {}
        this.teams = await AuthorService.do('getTeams', params)
        console.log(this.teams)
      } catch (error) {
        console.log('There was an error in Team.vue:', error) // Logs out the error
      }
    }
  }
}
</script>
<style scoped></style>
