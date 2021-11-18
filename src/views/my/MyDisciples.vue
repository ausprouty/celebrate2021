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
      <h2 class="center">Who are you discipling?</h2>
      <div class="definitions">
        <div v-for="(item, id) in items" :key="id" :item="item">
          <div v-if="discipleshipItem(item.id)">
            <p>
              <span class="item_name">{{ item.name }} :</span>
              {{ item.paraphrase }}
            </p>
          </div>
        </div>
      </div>
      <form @submit.prevent="saveForm">
        <table class="goals">
          <tr>
            <th>Name</th>
            <th>Progress</th>
          </tr>
          <tr
            v-for="(disciple, discipleid) in disciples"
            :key="discipleid"
            :disciple="disciple"
            class="disciples"
          >
            <td class="firstname">
              <input
                class="firstname"
                type="text"
                v-model="disciple.firstname"
              />
            </td>
            <td class="progress">
              <select v-model="disciple.progress">
                <option value></option>
                <option value="Engaged">Engaged</option>
                <option value="Training">Training</option>
                <option value="Multiplying">Multiplying</option>
                <option value="Delete">Delete</option>
              </select>
            </td>
          </tr>

          <tr
            v-for="(new_disciple, id) in new_disciples"
            :key="id"
            :new_disciple="new_disciple"
            class="disciples"
          >
            <td class="firstname">
              <input
                class="firstname"
                type="text"
                v-model="new_disciple.firstname"
              />
            </td>
            <td class="progress">
              <select v-model="new_disciple.progress">
                <option value></option>
                <option value="Engaged">Engaged</option>
                <option value="Training">Training</option>
                <option value="Multiplying">Multiplying</option>
                <option value="Delete">Delete</option>
              </select>
            </td>
          </tr>
        </table>

        <br />
        <button id="update" class="button green" @click="saveForm">
          Update
        </button>
      </form>
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
  computed: mapState(['user', 'member', 'appDir']),
  mixins: [authorMixin],
  data() {
    return {
      disciples: [],
      saved: false,

      items: {},
      new_disciples: [
        {
          id: 'A',
          firstname: '',
          progress: ''
        },
        {
          id: 'B',
          firstname: '',
          progress: ''
        },
        {
          id: 'C',
          firstname: '',
          progress: ''
        },
        {
          id: 'D',
          firstname: '',
          progress: ''
        },
        {
          id: 'E',
          firstname: '',
          progress: ''
        }
      ],
      disciple: {
        discipleid: null,
        uid: null,
        tid: null,
        firstname: '',
        progress: '',
        group_name: ''
      },
      member_image: null
    }
  },
  methods: {
    discipleshipItem(id) {
      if (id > 7 && id < 11) {
        return true
      } else {
        return false
      }
    },
    async saveForm() {
      try {
        if (!this.saved) {
          this.disableButton('update')
          this.saved = true
          var params = {}
          if (typeof this.disciples != 'undefined') {
            params['disciples'] = JSON.stringify(this.disciples)
          }
          params['new_disciples'] = JSON.stringify(this.new_disciples)
          params['uid'] = this.$route.params.uid
          params['tid'] = this.$route.params.tid
          params['year'] = new Date().getFullYear()
          console.log(params)
          var res = await AuthorService.do('updateDisciples', params)
          this.$router.push({
            name: 'myTodaySettings',
            params: {
              uid: this.$route.params.uid,
              tid: this.$route.params.tid
            }
          })
        }
      } catch (error) {
        console.log('There was an error in saveForm ', error) //
      }
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
        this.menu = await this.menuParams('My Disciples', 'M')
        await this.checkMember(this.$route.params)
        if (this.member.image) {
          this.member_image = '/images/members/' + this.member.image
        }
        var params = []
        params['route'] = JSON.stringify(this.$route.params)
        console.log(params)
        this.disciples = await AuthorService.do('getDisciplesForMember', params)
        this.items = await AuthorService.getItemsStandard(params)

        console.log(this.disciples)
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
  background-color: var(--color-grey-light);
}

tr:hover {
  background-color: var(--color-grey);
}
td,
th {
  border: 1px solid var(--color-grey);
  padding: 8px;
}

th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: center;
  background-color: var(--color-green);
  color: white;
}
.definitions {
  background-color: var(--color-yellow);
  padding: 10px;
}

td.group {
  width: 20%;
}
td.first_name {
  width: 50%;
}
td.progress {
  width: 30%;
}
.item_name {
  font-weight: 900;
}
</style>
