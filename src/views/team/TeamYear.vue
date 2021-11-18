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
      <div v-if="this.viewing.team.image" class="center confetti">
        <img v-bind:src="this.viewing.team.image" class="team-small" />
      </div>

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
    'v-select': vSelect
  },

  props: ['tid', 'year', 'item'],
  computed: mapState([ 'view', 'user','appDir', 'months']),
  mixins: [authorMixin],
  data() {
    return {
      item_details: {},
      series: [],
      res: [],
      scope_options: [],
      scope: [],
      selected: {},
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
      this.authorized = this.authorize('team', null, this.$route.params.tid)
      if (this.authorized) {
        try {
          this.menu = await this.menuParams('Team Yearly Progress', 'M')
          var params = []
          params.route = JSON.stringify(this.$route.params)
          this.scope = await AuthorService.getItemsTeam(params)
          this.team = await AuthorService.do('getTeam', params)
          this.res = await AuthorService.do('getProgressTeamForYear', params)
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
  background-color: white;
}
div.traffic-chart {
  height: 300px;
}
.last_year {
  color: #d70206;
}
.this_year {
  color: #1edb1e;
  color: #21641c;
}

table.progress_table {
  width: 60%;
  margin: auto;
  text-align: center;
  background-color: #21641c;
}
td {
  border-bottom: 1px solid #ddd;
}
td.row_label {
  width: 80%;
  text-align: left;
  color: white;
}
td.row_value {
  width: 20%;
  text-align: right;
  color: white;
}
img.icon {
  width: 48px;
}

div.select {
  padding-bottom: 90px;
}

.team-small {
  width: 50%;
}
.definition {
  color: red;
  font-size: 14px;
}
</style>
