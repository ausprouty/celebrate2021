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
      {{ this.time }}
      <div class="center">
        <h2>Update Today Entry</h2>
      </div>
      <div class="subheading">
        <form @submit.prevent="saveForm">
          <div class="app-link">
            <div class="shadow-card -shadow">
              <div class="container" @click="showDefinition(item)">
                <div class="icon">
                  <img
                    v-bind:src="
                      appDir.icons + item.celebration_set + '/' + item.image
                    "
                    class="icon"
                  />
                </div>
              </div>
              <div class="entry">
                <BaseInput
                  v-bind:label="item.name + ': '"
                  v-model="today.entry"
                  type="number"
                  class="field"
                />
              </div>

              <BaseTextarea
                label="Comment"
                v-model="today.comment"
                type="textarea"
                class="field paragraph"
              />

              <BaseTextarea
                label="Praise or Prayer Request"
                type="textarea"
                v-model="today.prayer"
                class="field paragraph"
              />
            </div>
          </div>
        </form>
        <button class="button green" id="update" @click="saveForm">
          Update
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

import { mapState } from 'vuex'

import { authorMixin } from '@/mixins/AuthorMixin.js'
export default {
  components: {
    NavBar
  },

  props: ['uid', 'tid', 'todayid', 'month', 'year'],
  computed: mapState(['user', 'member', 'appDir', 'months']),
  mixins: [authorMixin],
  data() {
    return {
      item: {},
      member: {
        firstname: null,
        lastname: null,
        phone: null,
        scope: null,
        username: null,
        password: null,
        image: 'blank.png'
      },
      today: {},
      objective: null,
      time: null
    }
  },

  methods: {
    // see https://www.w3schools.com/howto/howto_js_collapsible.asp
    showDefinition(today) {
      console.log('hit button')
      var content = document.getElementById(today.id)
      if (content.style.display === 'block') {
        content.style.display = 'none'
      } else {
        content.style.display = 'block'
      }
    },

    async saveForm() {
      if (!this.saved) {
        this.saved = true
        this.disableButton('update')
        this.disableButton('delete')
        var params = {}
        params['today'] = JSON.stringify(this.today)
        console.log(params)
        await AuthorService.do('updateTodayEntry', params)
        this.$router.push({
          name: 'myMonth',
          params: {
            uid: this.$route.params.uid,
            tid: this.$route.params.tid,
            page: this.$route.params.page,
            month: this.$route.params.month,
            year: this.$route.params.year
          }
        })
      }
    },

    async loadForm() {
      this.authorized = this.authorize(
        'personal',
        this.$route.params.uid,
        this.$route.params.tid
      )
      if (this.authorized) {
        try {
          this.menu = await this.menuParams('My Today Update', 'M')
          var params = {}
          params['route'] = JSON.stringify(this.$route.params)
          this.today = await AuthorService.do('getTodayEntry',params)
          console.log(this.today)
          params['id'] = this.today.item
          this.item = await AuthorService.do('getItem', params)
          console.log(this.item)
          this.time = this.months[this.today.month] + ',  ' + this.today.year
          console.log(this.item)
        } catch (error) {
          console.log('There was an error in myTodayUpdate.vue:', error) // Logs out the error
        }
      }
    }
  },
  beforeCreate: function() {
    document.body.className = 'user'
  },
  async created() {
    this.loadForm()
  }
}
</script>

<style scoped>
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
  text-align: center;
}

table.heading {
  display: block;
  background-color: rgb(243, 243, 148);
  padding: 10px;
  width: 97%;
  margin: auto;
}
td.picture {
  width: 50%;
}
td.objective {
  width: 45%;
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
</style>
