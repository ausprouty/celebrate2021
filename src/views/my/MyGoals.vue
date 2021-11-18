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
      <h2>When do you want to throw a party?</h2>
      <p>Pick two or more of these and enter a goal.</p>
      <form @submit.prevent="saveForm">
        <table class="goals">
          <tr>
            <th>Icon</th>
            <th>Item</th>
            <th>Goal</th>
          </tr>
          <tr v-for="(item, id) in this.items" :key="id" :item="item" class="goals">
            <td class="icon top">
              <img
                v-bind:src="
                  appDir.icons + item.celebration_set + '/' + item.image
                "
                class="icon"
              />
            </td>
            <td
              :id="item.id + 'R'"
              class="item top"
              @click="showDefinition(item)"
              v-bind:class="{ selected: evaluateSelect(item.number) }"
            >
              {{ item.name }}
              <div :id="item.id + 'C'" class="hidden">
                <span :id="item.id" class="definition"></span>
                <div
                  v-if="authorizeItemEdit(item)"
                  class="hand update"
                  @click="updateItem(item.id)"
                >Update Item</div>
              </div>
            </td>
            <td :id="item.id + 'R'" class="goal top">
              <input class="goal" type="text" v-model="item.number" />
              {{ totalOrAverage(item.cumulative) }}
            </td>
          </tr>
        </table>

        <br />

        <button class="button green" id="update" @click="saveForm">Update</button>
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
      highlight: true
    }
  },
  validations: {
    items: {
      numbers: { integer }
    }
  },
  methods: {
    showDefinition(item) {
      var id = item.id + 'C'
      var present = document.getElementById(item.id).innerHTML
      if (present == '') {
        var message = '<br>(' + item.paraphrase + ')'
        document.getElementById(item.id).innerHTML = message
        document.getElementById(id).className = 'definition'
      } else {
        document.getElementById(item.id).innerHTML = null
        document.getElementById(id).className = 'hidden'
      }
    },
    totalOrAverage(input) {
      if (input == 'Y') {
        return 'total'
      } else {
        return 'average'
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
          tid: this.$route.params.tid,
          uid: this.$route.params.uid
        }
      })
    },
    async updateItem(id) {
      await this.saveForm()
      this.$router.push({
        name: 'myItem',
        params: {
          tid: this.$route.params.tid,
          uid: this.$route.params.uid,
          id: id
        }
      })
    },
    async saveForm() {
      try {
        if (!this.saved) {
          this.saved = true
          this.disableButton('update')
          var params = {}
          var plan = []
          var now = {}
          var clean = 0
          var l = this.items.length
          for (var i = 0; i < l; i++) {
            now.id = this.items[i]['id']
            now.number = 0
            clean = parseInt(this.items[i]['number'], 10)
            if (typeof clean == 'number') {
              now.number = clean
            }
            plan.push(now)
            now = {}
          }
          params['goals'] = JSON.stringify(plan)
          params['uid'] = this.$route.params.uid
          params['tid'] = this.$route.params.tid
          params['year'] = new Date().getFullYear()
          var res = await AuthorService.do('updateGoals', params)
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
        this.menu = await this.menuParams('My Goals', 'M')
        var params = []
        var route = {}
        route.uid = this.$route.params.uid
        route.tid = this.$route.params.tid
        route.year = new Date().getFullYear()
        params['route'] = JSON.stringify(route)
        this.member = await AuthorService.do('getUser', params)
        this.items = await AuthorService.do('getGoals', params)
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
  background-color: white;
}

tr:nth-child(even) {
  background-color: var(--color-grey-lightest);
}

tr:hover {
  background-color: var(--color-grey-light);
}
td,
th {
  border: 1px solid var(--color-grey-light);
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
  color: var(--color-green-dark);
  line-height: 18px;
  width: 60px;
}
td.top {
  vertical-align: top;
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

.selected {
  background-color: var(--color-yellos);
}
</style>
