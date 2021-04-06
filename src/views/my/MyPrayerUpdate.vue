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
      <BackImage :image="appDir.members + this.member.image" :time="this.time"></BackImage>
      <div class="center">
        <h2>Update Prayer</h2>
      </div>
      <div class="subheading">
        <form @submit.prevent="saveForm">
          <div v-for="(item, id) in this.items" :key="id" :item="item" class="progress">
            <div class="app-link">
              <div
                class="shadow-card -shadow"
                v-bind:class="{ important: evaluateSelect(item.goal_numbers) }"
              >
                <div class="container" @click="showDefinition(item)">
                  <div class="icon">
                    <img
                      v-bind:src="
                        appDir.icons + item.celebration_set + '/' + item.image
                      "
                      class="icon"
                    />
                  </div>
                  <div
                    :id="item.id + 'R'"
                    class="item_name"
                    v-bind:class="{ selected: evaluateSelect(item.number) }"
                  >{{ item.name }}</div>
                  <div :id="item.id" class="collapsed">
                    <ItemEntryProgress :item="item"></ItemEntryProgress>
                  </div>
                </div>
                <hr />
                <div class="entry">
                  <BaseInput label="Number:" v-model="item.entry" type="number" class="field" />
                </div>
                <div v-if="item.details">
                  <BaseTextarea
                    v-bind:label="item.details"
                    @click="showDetails(item)"
                    v-model="item.comment"
                    type="textarea"
                    class="field paragraph"
                  />
                  <div :id="item.id + 'Details'" class="collapsed">
                    <ItemEntryDetails :item="item"></ItemEntryDetails>
                  </div>
                </div>
                <BaseTextarea
                  label="Praise or Prayer Request"
                  type="textarea"
                  @click="showPrayer(item)"
                  v-model="item.prayer"
                  class="field paragraph"
                />
                <div :id="item.id + 'Prayer'" class="collapsed">
                  <ItemEntryPrayer :item="item"></ItemEntryPrayer>
                </div>
              </div>
            </div>
          </div>
        </form>

        <button class="button green" id="update" @click="saveForm">Update</button>
      </div>
    </div>
  </div>
</template>

<script>
import AuthorService from '@/services/AuthorService.js'
import NavBar from '@/components/NavBar.vue'

import ItemEntryProgress from '@/components/ItemEntryProgress.vue'
import ItemEntryDetails from '@/components/ItemEntryDetails.vue'
import ItemEntryPrayer from '@/components/ItemEntryPrayer.vue'
import { mapState } from 'vuex'
import { integer } from 'vuelidate/lib/validators'
import { authorMixin } from '@/mixins/AuthorMixin.js'
export default {
  components: {
    NavBar,
    ItemEntryProgress,
    ItemEntryDetails,
    ItemEntryPrayer
  },

  props: ['uid', 'tid', 'pid'],
  computed: mapState(['user', 'appDir', 'months']),
  mixins: [authorMixin],
  data() {
    return {
      items: [],
      progress: [],
      highlight: true,
      member: {
        firstname: null,
        lastname: null,
        phone: null,
        scope: null,
        username: null,
        password: null,
        image: 'blank.png'
      },
      objective: null,
      time: null
    }
  },
  validations: {
    item: {
      numbers: { integer }
    }
  },
  methods: {
    // see https://www.w3schools.com/howto/howto_js_collapsible.asp
    showDefinition(item) {
      console.log('hit button')
      var content = document.getElementById(item.id)
      if (content.style.display === 'block') {
        content.style.display = 'none'
      } else {
        content.style.display = 'block'
      }
    },
    showDetails(item) {
      console.log('hit Show Details button')
      var content = document.getElementById(item.id + 'Details')
      if (content.style.display === 'block') {
        content.style.display = 'none'
      } else {
        content.style.display = 'block'
      }
    },
    showPrayer(item) {
      console.log('hit Show Prayerbutton')
      var content = document.getElementById(item.id + 'Prayer')
      if (content.style.display === 'block') {
        content.style.display = 'none'
      } else {
        content.style.display = 'block'
      }
    },

    evaluateSelect(quantity) {
      if (quantity > 0) {
        return true
      }
      return false
    },

    async saveForm() {
      if (!this.saved) {
        this.saved = true
        var params = {}
        this.disableButton('update')
        this.$route.params.year = this.items[0]['year']
        this.$route.params.month = this.items[0]['month']
        params['route'] = JSON.stringify(this.$route.params)
        params['items'] = JSON.stringify(this.items)
        await AuthorService.updateProgressPageEntry(params)
        this.$router.push({
          name: 'myPrayers',
          params: {
            uid: this.$route.params.uid,
            tid: this.$route.params.tid
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
          this.menu = await this.menuParams('My Prayer Update', 'M')
          var params = {}
          params['route'] = JSON.stringify(this.$route.params)
          this.items = await AuthorService.do('getPrayerUpdate', params)
          this.member = await AuthorService.do('getUser', params)
          this.objective = this.items[0]['objective']
          this.time =
            this.months[this.items[0]['month']] + ',  ' + this.items[0]['year']
        } catch (error) {
          console.log('There was an error in myMonth.vue:', error) // Logs out the error
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
