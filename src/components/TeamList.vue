<template>
  <div class="app-link hand" v-on:click="showTeam(team.tid)">
    <div class="shadow-card -shadow">
      <div class="card-name">{{ team.name }}</div>
      <div class="strategy">{{ team.strategy }} -- {{ team.focus }}</div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    team: Object
  },
  methods: {
    showTeam: function(tid) {
      this.$router.push({
        name: 'teamProfile',
        params: {
          tid: tid
        }
      })
    }
  }
}
</script>
<style scoped>
<style scoped > div.break {
  display: inline;
}

.not_current {
  background-color: #dee597;
}
div.card-names {
  float: right;
  font-size: 18px;
  vertical-align: top;
  width: 70%;
  line-height: 60px;
}
.card-name {
  font-weight: bold;
  line-height: 20px;
}
</style>
