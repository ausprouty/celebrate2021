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
      <h2 v-if="team.name" class="center">Update {{ team.name }}</h2>
      <h2 v-if="!team.name" class="center">New Team</h2>
      <div v-if="this.team_image">
        <img v-bind:src="this.team_image" class="team" />
      </div>

      <form @submit.prevent="saveForm">
        <BaseInput
          v-model="$v.team.name.$model"
          label="Team Name"
          type="text"
          placeholder
          class="field"
        />

        <br />
        <br />
        <BaseSelect
          label="Strategy"
          :options="this.strategies"
          v-model="$v.team.strategy.$model"
          class="field"
        />
        <BaseSelect
          label="Focus"
          :options="this.focus_areas"
          v-model="$v.team.focus.$model"
          class="field"
        />
        <BaseSelect
          label="State"
          :options="this.states"
          v-model="$v.team.state.$model"
          class="field"
        />

        <br />
        <button class="button green" id="update" @click="saveForm">
          Update
        </button>
        <button class="button red" id="delete" @click="deleteForm">
          Delete
        </button>
      </form>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import AuthorService from '@/services/AuthorService.js'
import NavBar from '@/components/NavBar.vue'

import { authorMixin } from '@/mixins/AuthorMixin.js'

import { required } from 'vuelidate/lib/validators'

export default {
  components: {
    NavBar
  },
  props: ['uid'],
  mixins: [authorMixin],
  data() {
    return {
      team_image: null,
      submitted: false,
      wrong: null,
      registered: true
    }
  },
  computed: mapState(['focus_areas', 'states', 'team', 'strategies', 'user']),
  validations: {
    team: {
      name: { required },
      strategy: { required },
      focus: { required },
      state: {}
    }
  },
  methods: {
    async saveForm() {
      try {
        if (!this.saved) {
          this.saved = true
          this.disableButton('update')
          this.disableButton('delete')
          var params = this.team
          params.authorizer = this.user.uid
          console.log(params)
          if (this.team.tid) {
            await AuthorService.do('updateTeamProfile', params)
            this.$router.push({
              name: 'ourTeam',
              params: {
                tid: this.$route.params.tid
              }
            })
          } else {
            var resp = await AuthorService.do('createTeamProfile', params)
            console.log(resp)
            this.$router.push({
              name: 'ourTeam',
              params: {
                tid: resp.tid
              }
            })
          }
        }
      } catch (error) {
        console.log('Update There was an error ', error) //
      }
    },

    async deleteForm() {
      try {
        this.disableButton('update')
        this.disableButton('delete')
        var params = this.team
        params.authorizer = this.user.uid
        let res = await AuthorService.deleteTeam(params)
        if (res.data.error) {
          this.registered = false
          this.error_message = res.data.message
        } else {
          this.registered = true
          this.$router.push({
            name: 'adminTeams'
          })
        }
      } catch (error) {
        console.log('Delete There was an error ', error) //
      }
    },
    async show() {
      this.authorized = this.authorize('global', null, this.$route.params.tid)
      if (this.authorized) {
        try {
          this.menu = await this.menuParams('Team Profile', 'M')
          await this.checkTeam(this.$route.params)
          if (this.team.image) {
            this.team_image = this.team.image
          }
          console.log(this.team)
        } catch (error) {
          console.log('There was an error in TeamProfile.vue:', error) // Logs out the error
        }
      }
    }
  },
  beforeCreate: function() {
    document.body.className = 'team'
  },
  async created() {
    this.show()
  }
}
</script>
<style scoped></style>
