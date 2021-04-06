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
              <p class="objective">Prayer</p>
              <p class="verse">
                "Ask and it will be given to you; seek and you will find; knock
                and the door will be opened to you. For everyone who asks
                receives; the one who seeks finds; and to the one who knocks,
                the door will be opened."
              </p>
            </td>
          </tr>
        </table>

        <h2>What does the Holy Spirit want you to pray today?</h2>
      </div>
      <div class="subheading">
        <PrayerList v-for="item in items" :key="item.pid" :item="item" />
      </div>
    </div>
  </div>
</template>

<script>
import AuthorService from '@/services/AuthorService.js'
import PrayerList from '@/components/PrayerList.vue'
import NavBar from '@/components/NavBar.vue'
import { mapState } from 'vuex'
import { integer } from 'vuelidate/lib/validators'
import { authorMixin } from '@/mixins/AuthorMixin.js'
export default {
  components: {
    NavBar,
    PrayerList
  },

  props: ['uid', 'tid', 'year', 'month', 'page'],
  computed: mapState(['user', 'appDir', 'months']),
  mixins: [authorMixin],
  data() {
    return {
      items: [],
      member: {
        firstname: null,
        lastname: null,
        phone: null,
        scope: null,
        username: null,
        password: null,
        image: 'blank.png'
      },
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
    showPage: function(user) {
      console.log('user')
      console.log(user)
      this.$router.push({
        name: 'user',
        params: {
          uid: this.user.uid
        }
      })
    },
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

    async saveForm() {
      var params = {}
      params['route'] = JSON.stringify(this.$route.params)
      params['items'] = JSON.stringify(this.items)
      await AuthorService.updateProgressPageEntry(params)
      console.log('finished save')
    },
    async loadForm() {
      this.authorized = this.authorize(
        'personal',
        this.$route.params.uid,
        this.$route.params.tid
      )
      if (this.authorized) {
        try {
          this.menu = await this.menuParams('My Prayers', 'M')
          console.log(this.menu)
          var params = {}
          params['route'] = JSON.stringify(this.$route.params)
          this.picture = await AuthorService.do('getImagePage', params)
          this.items = await AuthorService.do('getPrayersTeam', params)
          this.member = await AuthorService.do('getUser', params)
          console.log(this.items)

          this.time =
            this.months[this.$route.params.month] +
            ',  ' +
            this.$route.params.year
        } catch (error) {
          console.log('There was an error in myMonth.vue:', error) // Logs out the error
        }
      }
    }
  },
  beforeCreate: function() {
    document.body.className = 'user'
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
  },
  async created() {
    this.loadForm()
  }
}
</script>

<style scoped>
table.heading {
  display: block;
  background-color: var(--color-yellow);
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

div.shadow-card {
  display: block;
}

.important {
  background-color: var(--color-yellow);
}

div.item_name {
  display: inline;
}

p.objective {
  padding-left: 10px;
  color: var(--color-black);
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
  color: var(--color-black);
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
  color: var(--color-black);
  font-weight: bold;
}

td.goals {
  width: 20%;
}
</style>
