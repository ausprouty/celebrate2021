<template>
  <div class="white">
    <img src="/images/menu/black.png" />
    <h2>Start Your Celebrating Now</h2>
    <p>We are glad you want to celebrate what God is doing.</p>
    <p>
      We need to collect a few facts so that we can keep your information
      private.
    </p>
    <form @submit.prevent="saveForm">
      <BaseInput
        v-model="teamkey"
        label="Team Key"
        type="text"
        placeholder
        class="field"
        :class="{ error: $v.teamkey.$error }"
        @blur="$v.teamkey.$touch()"
      />
      <template v-if="$v.teamkey.$error">
        <p v-if="!$v.teamkey.required" class="errorMessage">
          Team Key is required. Ask your trainer for this.
        </p>
      </template>
      <BaseInput
        v-model="firstname"
        label="First Name"
        type="text"
        placeholder
        class="field"
        :class="{ error: $v.firstname.$error }"
        @blur="$v.firstname.$touch()"
      />
      <template v-if="$v.firstname.$error">
        <p v-if="!$v.firstname.required" class="errorMessage">
          First Name is required.
        </p>
      </template>
      <BaseInput
        v-model="lastname"
        label="Family Name"
        type="text"
        placeholder
        class="field"
      />

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
      <button class="button red" @click="saveForm">
        Next: Design your Celebration Experience
      </button>
    </form>
  </div>
</template>

<script>
import { mapState } from 'vuex'
import AuthorService from '@/services/AuthorService.js'
import { required } from 'vuelidate/lib/validators'

export default {
  props: ['team_key'],
  data() {
    return {
      teamkey: 'MYFWS',
      firstname: 'fred',
      lastname: 'fromo',
      email: 'from@somethe.ong',
      password: 'somegarbage',
      submitted: false,
      saved: false
    }
  },
  computed: mapState(['user']),
  validations: {
    teamkey: { required },
    firstname: { required },
    lastname: {},
    email: { required },
    password: { required }
  },
  methods: {
    async saveForm() {
      try {
        if (!this.saved) {
          this.saved = true
          var params = {}
          params.teamkey = this.teamkey
          params.firstname = this.firstname
          params.lastname = this.lastname
          params.email = this.email.toLowerCase()
          params.password = this.password
          let res = await AuthorService.register(params)
          console.log(res)
          if (res.data.content) {
            this.user = res.data.content
            this.user.token = res.data.token
            this.user.expires = res.data.content.expires * 1000
            var date = new Date()
            this.user.now = date.getTime()
            console.log(this.user)
            this.$store.dispatch('loginUser', [this.user])
            this.team = await AuthorService.getTeam(this.user.uid)
            this.$store.dispatch('setTeam', [this.viewing.team.tid])
            this.$router.push({
              name: 'myToday',
              params: {
                uid: this.user.uid,
                tid: this.viewing.team.tid
              }
            })
          } else {
            this.wrong = true
            console.log(
              'It seems your Team Key is not working. Please contact your Generations Coach'
            )
          }
        }
      } catch (error) {
        console.log(
          'There was an error in the processing your request.  Tell Bob Prouty:  ',
          error
        ) //
      }
    }
  },
  beforeCreate: function() {
    document.body.className = 'user'
  },
  created() {
    this.teamkey = this.$route.params.team_key
  }
}
</script>
