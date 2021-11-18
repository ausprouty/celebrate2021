import Vue from 'vue'
import AuthorService from '@/services/AuthorService.js'
import Vuex from 'vuex'
import { mapState } from 'vuex'
Vue.use(Vuex)

export const authorMixin = {
  computed: mapState(['member', 'team', 'user', 'my']),
  data() {
    return {
      menu: {
        time: null,
        image: 'blank.png',
        breadcrumb: null
      }
    }
  },
  methods: {
    authorize(reason, uid, tid) {
      // if not logged in
      if (typeof this.user.expires == 'undefined') {
        // see if we have a user item in local starage
        if (localStorage.getItem('user')) {
          var user = JSON.parse(localStorage.getItem('user'))
          this.$store.dispatch('loginUser', [user])
        } else {
          this.$router.push({ name: 'login' })
        }
      }

      // if login has expired (see Author.php for length; right now 1 month)
      var date = new Date()
      var timestamp = date.getTime()

      if (this.user.expires < timestamp) {
        this.$router.push({ name: 'login' })
      }
      // is this your record for this team?
      if (this.user.uid == uid && this.user.team == tid && reason != 'admin') {
        console.log('this is your record')
        return true
      }
      // is this your own profile?
      if (reason == 'profile' && this.user.uid == uid) {
        return true
      }
      // is this your personal page?{
      if (reason == 'personal' && this.user.uid == uid) {
        console.log('this is your page')
        return true
      }
      // do you have global authority?
      var scope = ''
      if (typeof this.user.scope != 'undefined') {
        scope = this.user.scope
        console.log('Your scope is ' + scope)
      }
      console.log(scope)
      if (scope == 'global') {
        console.log('you have global authority')
        return true
      }
      // are you a team leader?
      if (reason == 'admin' && scope == 'admin') {
        console.log('you are administrator')
        return true
      }

      // are you a team leader?
      if (scope == 'team') {
        var ok = false
        for (var i = 0; i < this.user.teams.length; i++) {
          if (this.user.teams[i] == tid) {
            ok = true
          }
        }
        console.log('checked for team leader')
        return ok
      }
      if (reason == 'team') {
        var ok = false
        for (var i = 0; i < this.user.teams.length; i++) {
          if (this.user.teams[i] == tid) {
            ok = true
          }
        }
        console.log('checked for team')
        return ok
      }
      // are you a team member?{
      if (reason == 'team-member' && this.user.team == tid) {
        console.log('you are team member')
        return true
      }

      // is this something your team leader can modify?{
      if (
        reason == 'personal-or-teamleader' &&
        this.user.team == tid &&
        this.user.scope == 'team'
      ) {
        console.log('you are team leader')
        return true
      }
      return false
    },
    authorizeItemEdit(item) {
      if (item.celebration_set == 'Cru') {
        if (this.user.scope == 'global') {
          return true
        } else {
          return false
        }
      }
      if (item.celebration_set == 'personal') {
        if (item.uid == this.$route.params.uid) {
          return true
        } else {
          return false
        }
      }
      if (item.celebration_set == 'team') {
        if (item.tid == this.$route.params.tid) {
          if (this.user.scope == 'global') {
            return true
          }
          if (this.user.scope == 'team') {
            return true
          }
        } else {
          return false
        }
      }
      return false
    },
    disableButton(id) {
      document.getElementById(id).style.visibility = 'hidden'
    },
    enableButton(id) {
      document.getElementById(id).style.visibility = 'visible'
    },
    async menuParams(breadcrumb) {
      var params = this.$route.params

      params.image = 'blank.png'
      params.breadcrumb = breadcrumb
      console.log(params)
      if (typeof this.my.uid != 'undefined') {
        console.log('defined')
        if (this.my.uid == this.menu.uid) {
          console.log('not undefined')
          params.image = this.my.picture
        }
      }
      if (params.image == 'blank.png') {
        var p = {}
        // uid is blank in team
        if (typeof this.$route.params.uid != 'undefined') {
          p['route'] = JSON.stringify(this.$route.params)
          var member = await AuthorService.do('getUser', p)
          this.$store.dispatch('seeingMember', [member])
          params.image = member.image
        }
        params.time = 'Time'
      }
      return params
    },
    async checkItemsToday(route) {
      if (
        route.uid != this.member.uid ||
        typeof this.itemsToday == 'undefined'
      ) {
        var params = []
        params['uid'] = route.uid
        params['tid'] = route.tid
        var res = await AuthorService.do('getItemsToday', params)
        console.log(res)
        this.$store.dispatch('setItemsToday', res)
      }
      return
    },
    async checkMember(route) {
      if (route.uid != this.member.uid || route.tid != this.team.tid) {
        var params = []
        params['uid'] = route.uid
        params['tid'] = route.tid
        var res = await AuthorService.do('getMember', params)
        console.log('this is member')
        console.log(res)
        this.$store.dispatch('setMember', res)
      }
      return
    },
    async checkTeam(route) {
      if (route.tid != this.team.tid) {
        var params = []
        params['tid'] = route.tid
        var res = await AuthorService.do('getTeamFromTid', params)
        this.$store.dispatch('setTeam', res)
      }
      return
    },
    async checkTeams(route) {
      if (route.tid != this.team.tid || Object.keys(this.teams).length === 0) {
        var params = []
        params['uid'] = route.uid
        var res = await AuthorService.do('getTeamsForMember', params)
        this.$store.dispatch('setTeams', res)
      }
      return
    },
    async checkUser(route) {
      if (route.uid != this.user.uid) {
        var params = []
        params['uid'] = route.uid
        var res = await AuthorService.do('getUser', params)
        this.$store.dispatch('setUser', res)
      }
      return
    }
  }
}
