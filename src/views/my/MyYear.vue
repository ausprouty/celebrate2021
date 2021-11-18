<template>
  <div class="white">
    <NavBar v-bind="menu"></NavBar>
    <div v-if="!this.authorized" class="not_authorized">
      <p>
        You have stumbled into a restricted page. Sorry I can not show it to you
        now.
      </p>
    </div>
    <div v-if="this.authorized" class="chart-area">
      <div>
        <h1 class="center">{{ this.item_details.name }}</h1>
      </div>
      <div id="traffic-chart" class="traffic-chart"></div>

      <div class="center">
        <table class="progress_table">
          <tr>
            <td class="row_label">Goal</td>
            <td class="row_value">{{ this.res.goal }}</td>
          </tr>
        </table>

        <div v-if="item_details.cumulative == 'Y'">
          <table class="progress_table">
            <tr>
              <td class="row_label">Total for year</td>
              <td class="row_value">{{ this.res.this_year_total }}</td>
            </tr>
          </table>
        </div>
        <div v-if="item_details.cumulative != 'Y'">
          <table class="progress_table">
            <tr>
              <td class="row_label">Average for year</td>
              <td class="row_value">{{ this.res.this_year_total }}</td>
            </tr>
          </table>
        </div>
        <div class="center">
          Key:
          <span class="last_year">{{ this.res.last_year }}</span>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          <span class="this_year">{{ this.res.this_year }}</span>
        </div>
      </div>
      <div class="select">
        <form @submit.prevent="saveForm">
          Show:
          <v-select
            :options="scope"
            label="name"
            @input="updateData"
            v-model="selected"
          >
            <template slot="option" slot-scope="option">
              <img
                :src="
                  '/images/icons/' + option.celebration_set + '/' + option.image
                "
                class="icon"
              />
              {{ option.name }}
            </template>
          </v-select>
        </form>
      </div>
    </div>
  </div>
</template>

<script>
import AuthorService from '@/services/AuthorService.js'
import NavBar from '@/components/NavBar.vue'

import Chartist from 'chartist'
import vSelect from 'vue-select'
import { mapState } from 'vuex'
import { authorMixin } from '@/mixins/AuthorMixin.js'
import '@/assets/css/chartist.css'
export default {
  components: {
    NavBar,
    Chartist,
    'v-select': vSelect
  },

  props: ['uid', 'tid', 'year', 'item'],
  computed: mapState(['user', 'viewing', 'appDir', 'months']),
  mixins: [authorMixin],
  data() {
    return {
      item_details: {},
      series: [],
      res: [],
      member: {
        firstname: null,
        lastname: null,
        phone: null,
        scope: null,
        username: null,
        password: null,
        image: 'blank.png'
      },
      scope_options: [],
      scope: [],
      selected: {
        celebration_set: 'Cru',
        code: 'conversations',
        cumulative: 'Y',
        definition:
          'Number of people with whom a gospel conversation was initiated, whether face to face or online.',
        details: null,
        id: '1',
        image: 'conversationbubbles_48x48.png',
        name: 'Gospel Conversations Started',
        numbers: 'Y',
        objective: 'Win Individuals',
        page: '1',
        paraphrase: 'How many people did you start to share the Gospel with?',
        sequence: '1',
        tid: null,
        uid: null
      },

      canvasId: 'traffic-chart'
    }
  },
  methods: {
    updateData() {
      console.log('this.selected')
      console.log(this.selected)
      this.$route.params.item = this.selected.id
      this.loadForm()
    },
    async loadForm() {
      this.authorized = this.authorize(
        'personal',
        this.$route.params.uid,
        this.$route.params.tid
      )
      if (this.authorized) {
        try {
          this.menu = await this.menuParams('My Year', 'M')
          var params = []
          params.route = JSON.stringify(this.$route.params)
          this.member = await AuthorService.do('getUser', params)
          this.scope = await AuthorService.getItemsMember(params)

          this.res = await AuthorService.do('getProgressPersonForYear', params)
          var temp = JSON.parse(this.res.item)
          this.item_details = temp[0]

          this.series = this.res.progress
          var chart = new Chartist.Line(
            '#traffic-chart',
            {
              labels: [
                'Jan',
                'Feb',
                'Mar',
                'Apr',
                'Jun',
                'Jul',
                'Aug',
                'Sep',
                'Oct',
                'Nov',
                'Dec'
              ],
              series: this.series
            },
            {
              low: 0,
              onlyInteger: true,
              showArea: false,
              showLine: true,
              showPoint: true,
              fullWidth: true,
              axisX: {
                showGrid: true,
                onlyInteger: true
              },
              axisY: {
                onlyInteger: true
              }
            }
          )
          chart.on('draw', function(data) {
            if (data.type === 'line' || data.type === 'area') {
              data.element.animate({
                d: {
                  begin: 2000 * data.index,
                  dur: 2000,
                  from: data.path
                    .clone()
                    .scale(1, 0)
                    .translate(0, data.chartRect.height())
                    .stringify(),
                  to: data.path.clone().stringify(),
                  easing: Chartist.Svg.Easing.easeOutQuint
                }
              })
            }
          })
        } catch (error) {
          console.log('There was an error in myMonth.vue:', error) // Logs out the error
        }
      }
    }
  },
  mounted() {
    //}
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
div.chart-area {
  background-color: var(--color-white);
}
div.traffic-chart {
  height: 300px;
}
div.select {
  padding-bottom: 90px;
}
.last_year {
  color: var(--color-red);
}
.this_year {
  color: var(--color-green);
}

table.progress_table {
  width: 60%;
  margin: auto;
  text-align: center;
  background-color: var(--color-green);
}
td {
  border-bottom: 1px solid var(--color-grey);
}
td.row_label {
  width: 80%;
  text-align: left;
  color: var(--color-white);
}
td.row_value {
  width: 20%;
  text-align: right;
  color: var(--color-white);
}
img.icon {
  width: 48px;
}

.definition {
  color: var(--color-red);
  font-size: 14px;
}
</style>
