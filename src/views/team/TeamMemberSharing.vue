<template>
  <div class="white">
    <NavBar v-bind="menu"></NavBar>
    <div v-if="!this.authorized" class="not_authorized">
      <p>
        You have stumbled into a restricted page. Sorry I can not show it to you
        now.
      </p>
    </div>
    <div v-if="this.authorized">
      <div class="app-link">
        <div
          class="shadow-card -shadow"
          @click="editMember()"
          v-bind:class="{
            not_current: this.evaluateCurrent(this.member.current)
          }"
        >
          <div class="inline" v-if="this.member.image">
            <img
              v-bind:src="appDir.members + this.member.image"
              class="member"
            />
          </div>
          <div class="card-names">
            <span class="card-name"
              >{{ this.member.firstname }} {{ this.member.lastname }}</span
            >
          </div>
        </div>
      </div>
      <h1 v-if="missingMonths(this.missing)">
        Did you have anything to celebrate during these months?:
      </h1>
      <div v-for="missed in missing">
        <p class="months" @click="openProgress(missed)">{{ months[missed] }}</p>
      </div>
    </div>
  </div>
</template>

<script>
import AuthorService from '@/services/AuthorService.js'
import NavBar from '@/components/NavBar.vue'

import { mapState } from 'vuex'
import { authorMixin } from '@/mixins/AuthorMixin.js'

export default {
  components: {
    NavBar
  },

  props: ['uid', 'tid'],
  computed: mapState(['user', 'appDir', 'months']),
  mixins: [authorMixin],
  data() {
    return {
      member: {},
      missing: []
    }
  },
  methods: {
    evaluateCurrent(current) {
      if (current == 'Y') {
        return false
      }
      return true
    },
    missingMonths(missing) {
      if (missing.length == 0) {
        return false
      }
      return true
    },
    editMember() {
      this.$router.push({
        name: 'myToday',
        params: {
          uid: this.member.uid,
          tid: this.member.team
        }
      })
    },
    openProgress(month) {
      this.$router.push({
        name: 'myMonth',
        params: {
          uid: this.$route.params.uid,
          tid: this.$route.params.tid,
          year: this.$route.params.year,
          month: month
        }
      })
    },
    async loadForm() {
      this.authorized = this.authorize('team', null, this.$route.params.tid)
      if (this.authorized) {
        try {
          this.menu = await this.menuParams('Team Member Sharing', 'M')
          var params = []
          params['uid'] = this.$route.params.uid
          var d = new Date()
          if (typeof this.$route.params.year == 'undefined') {
            this.$route.params.year = d.getFullYear()
          }
          if (typeof this.$route.params.month == 'undefined') {
            //this will actually give you the previous month since it starts the array at 0
            this.$route.params.month = d.getMonth()
          }
          params['route'] = JSON.stringify(this.$route.params)
          this.member = await AuthorService.do('getUser', params)
          this.missing = await AuthorService.do(
            'getTeamMemberShowingMissingCelebrations',
            params
          )
          console.log('this.missing')
          console.log(this.missing)
        } catch (error) {
          console.log('There was an error in TeamMemberReports.vue:', error) // Logs out the error
        }
      }
    }
  },

  beforeCreate: function() {
    document.body.className = 'team'
    this.$route.params.time = null
  },
  async created() {
    this.loadForm()
  }
}
</script>
<style scoped>
img.icon {
  width: 48px;
}

.not_current {
  background-color: #dee597;
}
div.card-names {
  float: right;
  font-size: 18px;
  vertical-align: top;
  width: 70%;
  line-height: 60px;
}
.card-name {
  font-weight: bold;
  line-height: 20px;
}
.months {
  font-size: 18pt;
  padding-left: 20px;
}
div.inline {
  display: inline;
}
</style>
