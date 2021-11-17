<template>
  <div v-on:click="changeCelebration(celebration.todayid)">
    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;{{ celebration.comment }}: {{ celebration.prayer }}
  </div>
</template>

<script>
export default {
  props: {
    celebration: Object
  },
  methods: {
    changeCelebration: function() {
      this.$router.push({
        name: 'teamProfile',
        params: {
          tid: this.celebration.todayid
        }
      })
    }
  }
}
</script>
<style></style>
