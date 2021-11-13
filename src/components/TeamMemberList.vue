<template>
  <div class="app-link" v-on:click="showPage(member)">
    <div
      class="shadow-card -shadow"
      v-bind:class="{ not_current: this.evaluateCurrent(member.current) }"
    >
      <img v-bind:src="appDir.members + this.image" class="member" />

      <div class="card-names">
        <span class="card-name"
          >{{ member.firstname }} {{ member.lastname }}</span
        >
      </div>
    </div>
  </div>
</template>

<script>
import { mapState } from 'vuex'
export default {
  props: {
    member: Object
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
    showPage: function(member) {
      console.log('member')
      console.log(member)
      this.$router.push({
        name: 'teamMemberSharing',
        params: {
          uid: this.member.uid,
          tid: this.$route.params.tid
        }
      })
    }
  },
  created() {
    this.image = this.member.image
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
