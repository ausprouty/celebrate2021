<template>
  <div>
    <div id="nav">
      <router-link to="/">
        <img class="nav-icon" alt="Home" src="/images/menu/ribbons/MyTopRibbon600.png" />
      </router-link>
    </div>

    <div v-if="authorized">
      <div class="container">
        <div class="back" @click="goBack()">
          <img class="icon" src="/images/icons/admin/back_48x48.png" />
        </div>
        <div class="my">
          <img v-bind:src="appDir.members + this.image" class="member" />
        </div>
        <div class="team">
          <img class="icon" src="/images/icons/admin/group_48x48.png" />
        </div>
        <div class="breadcrumb">{{ this.breadcrumb }}</div>
        <div class="time" v-if="this.time">{{ this.time }}</div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  computed: mapState(['user', 'my']),
  props: {
    breadcrumb: String,
    params: Object
  },

  async created() {
    this.authorized = this.authorize(
      'personal',
      this.params.uid,
      this.params.tid
    )
    this.authorized = true
    if (this.authorized) {
      if (typeof this.my.uid != 'undefined') {
        if (this.my.uid == this.params.uid) {
          this.image = this.my.picture
        }
      }
      if (this.image == 'blank') {
        var p = {}
        p['route'] = JSON.stringify(this.params)
        this.member = await AuthorService.do('getUser', p)
        this.$store.dispatch('seeingMember', [this.member])
        this.image = this.member.image
      }
    } else {
      alert('not authorized')
    }
  }
}
</script>
