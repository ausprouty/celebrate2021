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
      <div v-if="showApple()" class="important center">
        <h2 class="center">Please install this app on your Apple Device</h2>
        <img class="ios" src="/images/installOnIOS.png" />
        <h2 class="center">This prompt will not be shown again</h2>
        <br />
      </div>

      <h2 class="center">What did the Holy Spirit enable you to do today?</h2>
      <div class="subheading">
        <form @submit.prevent="saveForm">
          <div
            v-for="(item, id) in this.items"
            :key="id"
            :item="item"
            class="progress"
          >
            <div class="app-link">
              <div class="shadow-card -shadow">
                <div class="wrapper">
                  <div class="icon-select" @click="openDetails(id)">
                    <img
                      v-bind:src="
                        appDir.icons + item.celebration_set + '/' + item.image
                      "
                      class="icon"
                    />
                    {{ item.name }}
                  </div>
                  <template v-if="pleaseOpen(id)">
                    <p></p>
                    <div class="entry" v-if="pleaseOpen(id)">
                      <BaseInput
                        label="#"
                        v-model="item.entry"
                        type="text"
                        class="field"

                      />
                    </div>

                    <!-- End of wrapper-->

                    <div v-if="item.details">
                      <BaseTextarea
                        v-bind:label="item.details"
                        @click="showDetails(item)"
                        v-model="item.comment"
                        type="textarea"
                        class="field paragraph"
                      />
                    </div>
                    <BaseTextarea
                      label="Praise or Prayer Request"
                      type="textarea"
                      @click="showPrayer(item)"
                      v-model="item.prayer"
                      class="field paragraph"
                    />
                    <div v-if="item.entered">
                      <TodayEntered :item="item"></TodayEntered>
                    </div>
                  </template>
                </div>
              </div>
            </div>
            <!-- End of applink-->
          </div>
          <!-- End of for loop-->
          <button class="button green" id="update" @click="saveForm">
            Update
          </button>
          <button class="button grey right" @click="updateSettings">
            Settings
          </button>
        </form>
      </div>

      <!-- End of Subheading-->
    </div>
    <!-- End of Authorized-->
  </div>
</template>

<script>
import AuthorService from '@/services/AuthorService.js'
import TodayEntered from '@/components/TodayEntered.vue'
import NavBar from '@/components/NavBar.vue'

import { mapState } from 'vuex'
import { integer } from 'vuelidate/lib/validators'
import { authorMixin } from '@/mixins/AuthorMixin.js'
export default {
  components: {
    NavBar,
    TodayEntered
  },
  props: ['uid', 'tid'],
  computed: mapState(['user', 'member', 'appDir']),
  mixins: [authorMixin],
  data() {
    return {
      items: [],
      please_open: null,
      member: {
        firstname: null,
        lastname: null,
        phone: null,
        scope: null,
        username: null,
        password: null,
        image: 'blank.png'
      },
      highlight: true,
      saved: false
    }
  },
  validations: {
    items: {
      numbers: { integer }
    }
  },
  methods: {
    openDetails(id) {
      if (this.please_open == null) {
        this.please_open = id
      } else {
        this.please_open = null
      }
    },
    pleaseOpen(id) {
      if (id == this.please_open) {
        return true
      } else {
        return false
      }
    },
    showApple() {
      let isApple = ['iPhone', 'iPad', 'iPod'].includes(navigator.platform)
      if (!isApple) {
        var lastSeenPrompt = localStorage.getItem('lastSeenPrompt')
        if (!lastSeenPrompt) {
          localStorage.setItem('lastSeenPrompt', Date.now())
          return true
        }
      }
      return false
    },
    showDetails() {
      console.log('what do I show')
    },
    showPrayer() {
      console.log('what prayer do I show')
    },
    showDefinition(item) {
      var present = document.getElementById(item.id).innerHTML

      if (present == '') {
        var message = '<br>(' + item.paraphrase + ')'
        if (item.uid == this.$route.params.uid) {
          var link =
            message +
            '<br> <a href= "/user/' +
            this.$route.params.uid +
            '/item/' +
            item.id +
            '"> Update Item </a>'
          message = link
        }
        document.getElementById(item.id).innerHTML = message
      } else {
        document.getElementById(item.id).innerHTML = null
      }
    },
    evaluateSelect(quantity) {
      if (quantity > 0) {
        return true
      }
      return false
    },
    async updateSettings() {
      this.saveForm()

      this.$router.push({
        name: 'myTodaySettings',
        params: {
          uid: this.$route.params.uid,
          tid: this.$route.params.tid
        }
      })
    },

    async saveForm() {
      try {
        if (!this.saved) {
          this.disableButton('update')
          this.saved = true
          var params = {}
          var today = []
          var now = {}
          var clean = 0
          var l = this.items.length
          for (var i = 0; i < l; i++) {
            now.id = this.items[i]['id']
            now.comment = this.items[i]['comment']
            now.prayer = this.items[i]['prayer']
            now.entry = 0
            clean = parseInt(this.items[i]['entry'], 10)
            if (typeof clean == 'number') {
              now.entry = clean
            }
            today.push(now)
            now = {}
          }
          params['items'] = JSON.stringify(today)
          var route = this.$route.params
          route.year = new Date().getFullYear()
          route.month = new Date().getMonth() + 1
          params['route'] = JSON.stringify(route)
          console.log(params)
          l = this.items.length
          for (i = 0; i < l; i++) {
            this.items[i]['entry'] = 0
            if (typeof this.items[i]['details'] != undefined) {
              this.items[i]['comment'] = null
            }
            if (typeof this.items[i]['prayer'] != undefined) {
              this.items[i]['prayer'] = null
            }
          }
          this.items = await AuthorService.do('getProgressToday', params)
        }
      } catch (error) {
        console.log('There was an error in saveForm ', error) //
      }
    },
    async addGoal() {
      console.log('add Goal')
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
        this.menu = await this.menuParams('My Today', 'M')
        var params = []
        var route = {}
        route.uid = this.$route.params.uid
        route.tid = this.$route.params.tid
        console.log('this.user')
        console.log(this.user)
        route.year = new Date().getFullYear()
        route.month = new Date().getMonth() + 1
        params['route'] = JSON.stringify(route)
        this.items = await AuthorService.do('getProgressToday', params)
        if (this.items.length < 1) {
          this.$router.push({
            name: 'myTodaySettings',
            params: {
              uid: this.$route.params.uid,
              tid: this.$route.params.tid
            }
          })
        }

        for (var i = 0; i < this.items.length; i++) {
          this.items[i].open = null
        }
        params['uid'] = this.$route.params.uid
        this.member = await AuthorService.do('getUser', params)
        console.log('this member')
        console.log(this.member)
      } catch (error) {
        console.log('There was an error in Team.vue:', error) // Logs out the error
      }
    }
  }
}
</script>

<style scoped>
white {
  background-color: white;
}

div.wrapper {
  display: block;
  width: 100%;
  overflow: hidden; /* will contain if #first is longer than #second */
}
div.icon {
  display: block; /* add this */
}
div.entry {
  display: block;
  overflow: hidden; /* if you don't want #second to wrap below #first */
}
table.time {
  display: block;
  background-color: white;
  padding: 10px;
  width: 97%;
  margin: auto;
  padding-bottom: 20px;
}
tr.time {
  width: 100%;
}
td.left {
  background-color: purple;
  color: white;
  padding-left: 10px;
  font-size: 10px;
  text-align: left;
  width: 20%;
}
td.right {
  width: 20%;
  color: white;
  font-size: 10px;
  text-align: right;
  background-color: purple;
  padding-right: 10px;
}
a.left,
a.right {
  color: white;
  text-decoration: none;
}
td.center {
  width: 60%;
  text-align: center;
  font-weight: 900;
}
div.inline {
  display: inline;
  float: left;
  text-align: center;
}

table.heading {
  display: block;
  background-color: rgb(243, 243, 148);
  padding: 10px;
  width: 97%;
  margin: auto;
}

div.subheading {
  display: block;
}
img.picture {
  width: 100%;
}
div.icon {
  display: inline;
}
img.icon {
  width: 48px;
  padding-right: 10px;
}

.important {
  background-color: rgb(243, 243, 148);
}
.ios {
  max-width: 600px;
  width: 90%;
  margin-bottom: 20px;
}

div.item_name {
  display: inline;
}

p.objective {
  padding-left: 10px;
  color: black;
  font-weight: 700;
  font-size: 16px;
  margin-top: -5px;
  margin-bottom: 0px;
}
ul.motto {
  margin-top: 0px;
  padding-inline-start: 20px;
}
li.motto {
  color: black;
  padding-left: 0px;
  font-size: 12px;
  font-style: italic;
}
div.left {
  display: inline;
}
div.right {
  float: right;
}
.collapsed {
  padding: 0 18px;
  display: none;
  overflow: hidden;
  background-color: #f1f1f1;
}

td.item {
  width: 80%;
}
.item_name {
  color: black;
  font-weight: bold;
}

td.goals {
  width: 20%;
}
.important {
  background-color: yellow;
}
</style>
