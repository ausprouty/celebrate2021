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
      <div style="width:100%">
        <img v-bind:src="appDir.members + this.user.image" class="member" />
      </div>
      <div>
        <table class="time">
          <tr>
            <td class="left" @click="previousMonth()">< Previous Month</td>
            <td class="center">{{ this.time }}</td>
            <td class="right" @click="nextMonth()">Next Month ></td>
          </tr>
        </table>
      </div>

      <div class="center">
        <table class="heading">
          <tr>
            <td class="picture">
              <img
                v-bind:src="
                  appDir.page_images +
                    this.$route.params.page +
                    '/' +
                    this.picture
                "
                class="picture"
              />
            </td>
            <td class="objective">
              <p class="objective">{{ this.objective }}</p>
              <ul class="motto">
                <li class="motto">Encounter Jesus today.</li>
                <li class="motto">Impact Australia tomorrow.</li>
                <li class="motto">Reach the nations for eternity.</li>
              </ul>
            </td>
          </tr>
        </table>

        <h2>Who has the Holy Spirit transformed?</h2>
      </div>
      <div class="subheading">
        <form @submit.prevent="saveForm">
          <div
            v-for="(item, id) in this.items"
            :key="id"
            :item="item"
            class="progress"
          >
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
                  >
                    {{ item.name }}
                  </div>
                  <div :id="item.id" class="collapsed">
                    <ItemEntryProgress :item="item"></ItemEntryProgress>
                  </div>
                </div>
                <hr />
                <div class="entry">
                  <BaseInput
                    label="Number:"
                    v-model="item.entry"
                    type="number"
                    class="field"
                  />
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
        <div v-if="this.$route.params.page > 0" class="left">
          <button class="button green left" @click="previousForm"><</button>
        </div>
        <div v-if="this.$route.params.page < 5" class="right">
          <button class="button green right" @click="nextForm">></button>
        </div>
        <div v-if="this.$route.params.page == 5" class="right">
          <button class="button green right" @click="finishForm">
            Finished
          </button>
        </div>
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

  props: ['uid', 'tid', 'year', 'month', 'page'],
  computed: mapState(['user', 'appDir', 'months']),
  mixins: [authorMixin],
  data() {
    return {
      items: [],
      progress: [],
      highlight: true,
      picture: 'IMG_6282.JPG',
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
    nextMonth() {
      this.saveForm()
      var next = this.$route.params.month + 1
      if (next > 12) {
        this.$route.params.month = 1
        var year = this.$route.params.year + 1
        this.$route.params.year = year
      } else {
        this.$route.params.month = next
      }
      this.loadForm()
    },
    previousMonth() {
      this.saveForm()
      var prev = this.$route.params.month - 1
      if (prev < 1) {
        this.$route.params.month = 12
        var year = this.$route.params.year - 1
        this.$route.params.year = year
      } else {
        this.$route.params.month = prev
      }
      this.loadForm()
    },
    evaluateSelect(quantity) {
      if (quantity > 0) {
        return true
      }
      return false
    },
    async previousForm() {
      var prev = parseInt(this.$route.params.page, 10) - 1
      this.$route.params.page = prev
      this.loadForm()
    },
    async nextForm() {
      this.saveForm()
      var next = parseInt(this.$route.params.page, 10) + 1
      this.$route.params.page = next
      this.loadForm()
    },
    async finishForm() {
      this.saveForm()
      var params = []
      params['route'] = JSON.stringify(this.$route.params)
      await AuthorService.do('updateReportedTeam',params)
      this.$router.push({
        name: 'ourTeam',
        params: {
          tid: this.$route.params.tid
        }
      })
    },

    async saveForm() {
      var params = {}
      params['route'] = JSON.stringify(this.$route.params)
      params['items'] = JSON.stringify(this.items)
      await AuthorService.updateProgressPageEntry(params)
      console.log('finished save')
    },
    async loadForm() {
      this.authorized = this.authorize(
        'team',
        this.$route.params.uid,
        this.$route.params.tid
      )
      if (this.authorized) {
        try {
          this.menu = await this.menuParams('Team Monthly Progress', 'M')
          var params = {}
          var d = new Date()
          if (typeof this.$route.params.year == 'undefined') {
            this.$route.params.year = d.getFullYear()
          }
          if (typeof this.$route.params.month == 'undefined') {
            //this will actually give you the previous month since it starts the array at 0
            this.$route.params.month = d.getMonth()
          }
          if (typeof this.$route.params.page == 'undefined') {
            this.$route.params.page = 0
          }
          console.log(this.$route.params)
          params['route'] = JSON.stringify(this.$route.params)
          this.picture = await AuthorService.do('getImagePage', params)
          this.items = await AuthorService.do('getProgressPageEntry',params)
          this.objective = this.items[0]['objective']
          this.time =
            this.months[this.$route.params.month] +
            ',  ' +
            this.$route.params.year
          console.log(this.items)
        } catch (error) {
          console.log('There was an error in myMonth.vue:', error) // Logs out the error
        }
      }
    }
  },
  beforeCreate: function() {
    document.body.className = 'team'
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
