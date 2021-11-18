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
      <h2>Which of these do you celebrate often?</h2>
      <form @submit.prevent="saveForm">
        <table class="goals">
          <tr>
            <th>Icon</th>
            <th>Item</th>
            <th>Often?</th>
          </tr>
          <tr
            v-for="(item, id) in this.viewing.itemsToday"
            :key="id"
            :item="item"
            class="goals hand"
          >
            <td class="icon">
              <img
                v-bind:src="
                  appDir.icons + item.celebration_set + '/' + item.image
                "
                class="icon"
              />
            </td>
            <td
              :id="item.id + 'R'"
              class="item"
              @click="showDefinition(item)"
              v-bind:class="{ selected: evaluateSelect(item.number) }"
            >
              {{ item.name }}
              <span :id="item.id" class="definition"></span>
            </td>
            <td :id="item.id + 'Often'" class="often">
              <input class="often" type="checkbox" v-model="item.quick" />
            </td>
          </tr>
        </table>

        <br />

        <button class="button green" id="update" @click="saveForm">
          Update
        </button>
      </form>
      <button class="button red" @click="addItem">Add Personal Item</button>
    </div>
  </div>
</template>

<script>
import AuthorService from '@/services/AuthorService.js'
import NavBar from '@/components/NavBar.vue'

import { mapState } from 'vuex'
import { integer } from 'vuelidate/lib/validators'
import { authorMixin } from '@/mixins/AuthorMixin.js'
export default {
  components: {
    NavBar
  },
  props: ['uid', 'tid'],
  computed: mapState(['user', 'viewing', 'appDir']),
  mixins: [authorMixin],
  data() {
    return {
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
    async addItem() {
      await this.saveForm()
      this.$router.push({
        name: 'myItem',
        params: {
          uid: this.$route.params.uid,
          tid: this.$route.params.tid
        }
      })
    },
    async updateItem(id) {
      await this.saveForm()
      this.$router.push({
        name: 'myItem',
        params: {
          uid: this.$route.params.uid,
          tid: this.$route.params.tid,
          id: id
        }
      })
    },
    async saveForm() {
      try {
        if (!this.saved) {
          this.disableButton('update')
          this.saved = true
          var params = {}
          var plan = []
          var now = {}
          var l = this.viewing.itemsToday.length
          for (var i = 0; i < l; i++) {
            now.id = this.viewing.itemsToday[i]['id']
            if (this.viewing.itemsToday[i]['quick']) {
              now.quick = 'Y'
            } else {
              now.quick = 'N'
            }
            plan.push(now)
            now = {}
          }
          params['plan'] = JSON.stringify(plan)
          params['uid'] = this.$route.params.uid
          params['tid'] = this.$route.params.tid
          params['year'] = new Date().getFullYear()
          console.log(params)
          await AuthorService.do('updateSettingsToday', params)
          this.$router.push({
            name: 'myToday',
            params: {
              uid: this.$route.params.uid,
              tid: this.$route.params.tid
            }
          })
        } else {
          console.log('second press')
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
        this.menu = await this.menuParams('My Settings Today', 'M')
        await this.checkItemsToday(this.$route.params)
        await this.checkMember(this.$route.params)
        await this.checkTeam(this.$route.params)
      } catch (error) {
        console.log('There was an error in Team.vue:', error) // Logs out the error
      }
    }
  }
}
</script>

<style scoped>
table.goals {
  width: 100%;
  border-collapse: collapse;
  background-color: var(--color-grey);
}

tr:nth-child(even) {
  background-color: var(--color-grey);
}

tr:hover {
  background-color: var(--color-grey-medium);
}
td,
th {
  border: 1px solid;
  padding: 8px;
}

th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: center;
  background-color: var(--color-green);
  color: white;
}
.goal {
  color: var(--color-green);
  line-height: 18px;
}
td.item {
  width: 80%;
}
.item {
  color: var(--color-green-dark);
}
.definition {
  color: var(--color-red);
  font-size: 14px;
}

td.often {
  width: 20%;
}
.selected {
  background-color: var(--color-yellow);
}
</style>
