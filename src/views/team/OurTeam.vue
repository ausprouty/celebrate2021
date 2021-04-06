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
      <h1 class="center">{{ this.team.name }}</h1>
      <UserList v-for="user in users" :key="user.uid" :user="user" />
       <button class="button grey" id="update" @click="newMember">Add Members</button>
    </div>
  </div>
</template>

<script>
import AuthorService from '@/services/AuthorService.js'
import UserList from '@/components/UserList.vue'
import { authorMixin } from '@/mixins/AuthorMixin.js'
import NavBar from '@/components/NavBar.vue'

export default {
  props: ['tid'],
  components: {
    UserList,
    NavBar
  },

  mixins: [authorMixin],
  data() {
    return {
      authorized: false,
      team: [
        {
          tid: null,
          code: null,
          name: null
        }
      ],
      users: [
        {
          firstname: null,
          lastname: null,
          uid: null,
          image: 'blank.png'
        }
      ]
    }
  },
  methods:{
    newMember(){
      this.$router.push({
        name: 'teamMemberProfile',
        params: {
          tid: this.$route.params.tid
        }
      })
    }

  },
  beforeCreate: function() {
    document.body.className = 'team'
  },
  async created() {
    this.authorized = this.authorize(
      'team',
      this.$route.params.uid,
      this.$route.params.tid
    )
    if (this.authorized) {
      try {
         this.menu = await this.menuParams('Our Team', 'M')
        var params = {}
        params.tid = this.$route.params.tid
        this.team = await AuthorService.do('getTeam',params)
        var route = []
        route['route'] = JSON.stringify(this.$route.params)
        this.users = await AuthorService.do('getTeamMembersReported',route)
        console.log(this.users)
      } catch (error) {
        console.log('There was an error in Team.vue:', error) // Logs out the error
      }
    }
  }
}
</script>
<style scoped>

</style>
