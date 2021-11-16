<template>
  <div class="white">
    <img src="/images/menu/black.png" />
    <h2>Login</h2>
    <form @submit.prevent="saveForm">
      <BaseInput
        v-model="email"
        label="Email"
        type="text"
        placeholder
        class="field"
        :class="{ error: $v.email.$error }"
        @blur="$v.email.$touch()"
      />
      <template v-if="$v.email.$error">
        <p v-if="!$v.email.required" class="errorMessage">Email is required.</p>
      </template>

      <BaseInput
        v-model="password"
        label="Password"
        type="password"
        placeholder
        class="field"
        :class="{ error: $v.password.$error }"
        @blur="$v.password.$touch()"
      />
      <template v-if="$v.password.$error">
        <p v-if="!$v.password.required" class="errorMessage">
          Password is required.
        </p>
      </template>
      <br />
      <br />
      <button class="button red" @click="saveForm">Login</button>

      <template v-if="wrong">
        <p class="errorMessage">Wrong email or password. Try again</p>
      </template>
    </form>
    <button class="button grey" @click="retrievePassword">
      Forgot Password
    </button>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import AuthorService from '@/services/AuthorService.js'
import { required } from 'vuelidate/lib/validators'

export default {
  data() {
    return {
      email: '',
      password: '',
      submitted: false,
      saved: false,
      wrong: null
    }
  },
  computed: mapState(['user', 'member', 'team']),
  validations: {
    email: { required },
    password: { required }
  },
  methods: {
    async retrievePassword() {
      var params = {}
      if (this.email) {
        params.email = this.email
        await AuthorService.do('retrievePassword', params)
        this.$router.push({
          name: 'myPasswordSent'
        })
      } else {
        alert('Please enter your email so we can retrieve your password')
      }
    },
    async saveForm() {
      try {
        if (!this.saved) {
          this.saved = true
          var params = {}
          params.email = this.email.toLowerCase()
          params.password = this.password
          let res = await AuthorService.login(params)
          console.log(res)
          if (res.data.content) {
            console.log(res.data.content)
            var person = {}
            person = res.data.content
            person.uid = res.data.content.uid
            person.token = res.data.token
            person.expires = res.data.content.expires * 1000
            var date = new Date()
            person.now = date.getTime()
            console.log(person)
            this.$store.dispatch('loginUser', [person])
            params['uid'] = person.uid
            var team = await AuthorService.do('getTeamFromUid', params)
            this.$store.dispatch('setTeam', [team])
            this.$router.push({
              name: 'myToday',
              params: {
                uid: person.uid,
                tid: this.team.tid
              }
            })
          } else {
            this.wrong = true
            console.log(
              'It seems you have forgotten your password.  Please conrtact Bob Prouty'
            )
          }
        }
      } catch (error) {
        console.log('Login There was an error ', error) //
      }
    }
  },
  beforeCreate: function() {
    document.body.className = 'user'
  },
  async created() {
    if (typeof this.user.expires != 'undefined') {
      if (this.user.expires > this.user.now) {
        this.$router.push({
          name: 'myToday',
          params: {
            uid: this.user.uid,
            tid: this.team.tid
          }
        })
      }
    }
    console.log('backend is ' + process.env.VUE_APP_STANDARD_BACKEND)
  }
}
</script>
