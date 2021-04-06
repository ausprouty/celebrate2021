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
      <div v-if="this.member_image">
        <img v-bind:src="this.member_image" class="member" />
      </div>
      <h2>Who are you discipling?</h2>
      <p>People I have met with in the past three months</p>
      <div v-for="(item, id) in items" :key="id" :item="item">
        <div v-if="discipleshipItem(item.id)">
          <p>
            <span class="item_name">{{ item.name }} :</span>
            {{ item.paraphrase }}
          </p>
        </div>
      </div>
      <form @submit.prevent="saveForm">
        <table class="goals">
          <tr>
            <th>Group</th>
            <th>Name</th>
            <th>Progress</th>
          </tr>
          <tr
            v-for="(disciple, discipleid) in disciples"
            :key="discipleid"
            :disciple="disciple"
            class="disciples"
          >
            <td class="group">
              <input class="group" type="text" v-model="disciple.group_name" />
            </td>
            <td class="firstname">
              <input class="firstname" type="text" v-model="disciple.firstname" />
            </td>
            <td class="progress">
              <v-select :options="progress_options" label="name" v-model="disciple.progress">
                <template slot="option" slot-scope="option" class="option_name">{{ option.name }}</template>
              </v-select>
            </td>
          </tr>
          <tr
            v-for="(new_disciple, discipleid) in new_disciples"
            :key="discipleid"
            :new_disciple="new_disciple"
            class="disciples"
          >
            <td class="group">
              <input class="group" type="text" v-model="new_disciple.group_name" />
            </td>
            <td class="firstname">
              <input class="firstname" type="text" v-model="new_disciple.firstname" />
            </td>
            <td class="progress">
              <v-select :options="progress_options" label="name" v-model="new_disciple.progress">
                <template slot="option" slot-scope="option" class="option_name">{{ option.name }}</template>
              </v-select>
            </td>
          </tr>
        </table>

        <br />
        <button class="button green" @click="saveForm">Update</button>
      </form>
    </div>
  </div>
</template>

<script>
import AuthorService from '@/services/AuthorService.js'
import NavBar from '@/components/NavBar.vue'
import vSelect from 'vue-select'
import '@/assets/css/vueSelect.css'
import { mapState } from 'vuex'
import { integer } from 'vuelidate/lib/validators'
import { authorMixin } from '@/mixins/AuthorMixin.js'
export default {
  components: {
    NavBar,
    'v-select': vSelect
  },
  props: ['uid', 'tid'],
  computed: mapState(['user', 'appDir']),
  mixins: [authorMixin],
  data() {
    return {
      disciples: [],
      saved: false,
      member: {},
      items: {},
      new_disciples: [
        {
          discipleid: '1',
          uid: null,
          tid: null,
          firstname: '',
          progress: '',
          group_name: ''
        },
        {
          discipleid: '2',
          uid: null,
          tid: null,
          firstname: '',
          progress: '',
          group_name: ''
        },
        {
          discipleid: '3',
          uid: null,
          tid: null,
          firstname: '',
          progress: '',
          group_name: ''
        },
        {
          discipleid: '4',
          uid: null,
          tid: null,
          firstname: '',
          progress: '',
          group_name: ''
        },
        {
          discipleid: '5',
          uid: null,
          tid: null,
          firstname: '',
          progress: '',
          group_name: ''
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
      member_image: null,
      options: ['Engaged', 'Training', 'Multiplying', 'Delete', ''],
      progress_options: [
        {
          name: 'Engaged',
          value: 'Engaged'
        },
        {
          name: 'Training',
          value: 'Training'
        },
        {
          name: 'Multiplying',
          value: 'Multiplying'
        },
        {
          name: 'Delete',
          value: 'Delete'
        }
      ]
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
          var res = await AuthorService.updateDisciples(params)
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
        var params = []
        console.log(this.progress_options)
        var route = {}
        params['uid'] = this.$route.params.uid
        this.member = await AuthorService.do('getUser', params)
        console.log(this.member)
        if (this.member.image) {
          this.member_image = '/images/members/' + this.member.image
        }
        route.uid = this.$route.params.uid
        route.tid = this.$route.params.tid
        route.year = new Date().getFullYear()
        params['route'] = JSON.stringify(route)
        console.log(params)
        this.disciples = await AuthorService.getDisciples(params)
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
  background-color: #f2f2f2;
}

tr:hover {
  background-color: #ddd;
}
td,
th {
  border: 1px solid #ddd;
  padding: 8px;
}

th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: center;
  background-color: #4caf50;
  color: white;
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
