<template>
  <div class="app-link" v-on:click="showPage(user)">
    <div class="shadow-card -shadow" v-bind:class="{ not_current: this.evaluateCurrent(user.current) }">
      <img v-bind:src="appDir.members + this.image" class="member" />

      <div class="card-names">
        <span class="card-name">{{ user.firstname }} {{ user.lastname }}</span>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
export default {
  props: {
    user: Object
  },

  data: function() {
    return {
      image: 'blank.png'
    }
  },
  computed: mapState(['appDir']),
  methods: {
    evaluateCurrent(current) {
      if (current == 'Y') {
        return false
      }
      return true
    },
    showPage: function(user) {
      console.log('user')
      console.log(user)
      this.$router.push({
        name: 'teamMemberReports',
        params: {
          uid: this.user.uid,
          tid: this.$route.params.tid
        }
      })
    }
  },
  created() {
    this.image = this.user.image
  }
}
</script>
<style scoped>
<style scoped > div.break {
  display: inline;
}


.not_current {
  background-color:#dee597
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
