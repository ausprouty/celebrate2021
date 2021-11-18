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
      <p class="time">{{ this.time }}</p>
      <div>
        <div class="bar">
          <img
            v-bind:src="
              appDir.icons + this.item.celebration_set + '/' + this.item.image
            "
            class="icon-small"
          />
          <span class="celebrate"> Let's Celebrate what God did</span>
        </div>
      </div>

      <div class="subheading">
        <form @submit.prevent="saveForm">
          <div class="app-link">
            <div class="shadow-card -shadow">
              <div class="entry">
                <BaseInput
                  v-bind:label="this.item.paraphrase"
                  v-model="today.entry"
                  type="number"
                  class="field"
                />
              </div>
              <div v-if="this.item.details">
                <BaseTextarea
                  v-bind:label="this.item.details"
                  v-model="today.comment"
                  type="textarea"
                  class="field paragraph"
                />
              </div>

              <BaseTextarea
                label="Praise or Prayer Request"
                type="textarea"
                v-model="today.prayer"
                class="field paragraph"
              />
              <div v-if="this.celebrations">
                <p>You recently celebrated:</p>
                <CelebrationList
                  v-for="celebration in celebrations"
                  :key="celebration.todayid"
                  :celebration="celebration"
                />
              </div>
              <div v-if="!this.celebrations">
                <p>
                  God is amazing. This is the first time you have celebrated
                  this.
                </p>
              </div>
            </div>
          </div>
        </form>
        <button class="button green" id="update" @click="saveForm">
          Record Celebration
        </button>
        &nbsp;&nbsp;&nbsp;&nbsp;
        <button class="button grey" id="delete" @click="deleteForm">
          Delete
        </button>
      </div>
    </div>
  </div>
</template>

<script>
import AuthorService from '@/services/AuthorService.js'
import NavBar from '@/components/NavBar.vue'
import CelebrationList from '@/components/CelebrationList.vue'

import { mapState } from 'vuex'

import { authorMixin } from '@/mixins/AuthorMixin.js'
export default {
  components: {
    NavBar,
    CelebrationList
  },

  props: ['uid', 'tid', 'todayid', 'month', 'year'],
  computed: mapState(['user', 'viewing', 'appDir', 'months']),
  mixins: [authorMixin],
  data() {
    return {
      item: {
        name: 'Bob',
        celebration_set: '',
        image: null
      },
      celebrations: [],
      show_definition: false,
      today: {
        month: 1,
        year: 2021,
        entry: null,
        comment: null,
        prayer: null
      },
      objective: null,
      time: null,
      authorized: false
    }
  },

  methods: {
    // see https://www.w3schools.com/howto/howto_js_collapsible.asp
    toggleDefinition() {
      if (this.show_definition) {
        this.show_definition = false
      } else {
        this.show_definition = true
      }
    },
    async saveForm() {
      if (!this.saved) {
        this.saved = true
        this.disableButton('update')
        this.disableButton('delete')
        var params = {}
        params['today'] = JSON.stringify(this.today)
        params['route'] = JSON.stringify(this.$route.params)
        console.log(params)
        await AuthorService.do('updateTodayEntry', params)
        this.$router.push({
          name: 'myToday',
          params: {
            uid: this.$route.params.uid,
            tid: this.$route.params.tid
          }
        })
      }
    },
    async deleteForm() {
      alert('I do not know what to do')
    }
  },
  beforeCreate: function() {
    document.body.className = 'user'
  },
  async created() {
    this.authorized = this.authorize(
      'personal',
      this.$route.params.uid,
      this.$route.params.tid
    )
    if (this.authorized) {
      try {
        this.menu = await this.menuParams('My Today Update', 'M')
        await this.checkItemsToday(this.$route.params)
        await this.checkMember(this.$route.params)
        await this.checkTeam(this.$route.params)
        for (var i = 0; i < this.viewing.itemsToday.length; i++) {
          if (this.viewing.itemsToday[i].id == this.$route.params.id) {
            this.item = this.viewing.itemsToday[i]
            let params = []
            params['route'] = JSON.stringify(this.$route.params)
            var res = await AuthorService.do(
              'getRecentMemberCelebrationsForItem',
              params
            )
            if (typeof res != 'undefined') {
              this.celebrations = res
            }
          }
        }

        this.time = this.months[this.today.month] + ',  ' + this.today.year
      } catch (error) {
        console.log('There was an error in myTodayUpdate.vue:', error) // Logs out the error
      }
    }
  }
}
</script>

<style scoped>
.icon-small {
  height: 32px;
  width: 32px;
}
p.time {
  text-align: right;
  size: 8pt;
  font-style: italic;
}
div.bar {
  background-color: yellow;
  height: 40px;
  padding: 10px;
  text-align: left;
}
.celebrate {
  color: green;
  font-weight: bold;
}
</style>
<style>
@media only screen and (max-width: 300px) {
  .celebrate {
    font-size: 10px;
  }
  label {
    font-size: 10px;
  }
}
</style>
