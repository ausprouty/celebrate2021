<template>
  <div id="line-chart" class="line-chart"></div>
</template>

<script>
import Chartist from 'chartist'
import '@/assets/css/chartist.css'
export default {
  name: 'line-chart',
  prop: ['series'],
  data() {
    return {
      canvasId: 'line-chart',
      chart:{}
    }
  },
  created() {
    this.chart.update(this.series)
  },
  // see https://stackoverflow.com/questions/53990677/how-to-make-chartist-update-on-vuejs
  mounted() {
    this.$onchart = new Chartist.Line(
      '#line-chart',
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
        series: []
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

    this.chart.on('draw', function(data) {
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
    //}
  },
  // IMPORTANT: Vue.js is Reactive framework.
  // Hence watch for prop changes here
  watch: {
    data(newData) {
      this.chart.update(newData)
    }
  }
}
</script>
