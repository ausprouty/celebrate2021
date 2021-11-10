<template>
  <div class="white">
    <NavBar v-bind="menu"></NavBar>
    <div v-if="!this.authorized" class="not_authorized">
      <p>
        You have stumbled into a restricted page. Sorry I can not show it to you
        now.
      </p>
    </div>
    <div v-if="this.authorized">
      <h2>Update {{ member.firstname }} {{ member.lastname }}</h2>

      <form @submit.prevent="saveForm">
        <BaseInput
          v-model="$v.member.firstname.$model"
          label="First Name"
          type="text"
          placeholder
          class="field"
          :class="{ error: $v.member.firstname.$error }"
          @mousedown="$v.member.firstname.$touch()"
        />
        <template v-if="$v.member.firstname.$error">
          <p v-if="!$v.member.firstname.required" class="errorMessage">
            First Name is required
          </p>
        </template>

        <BaseInput
          v-model="$v.member.lastname.$model"
          label="Last Name"
          type="text"
          placeholder
          class="field"
          :class="{ error: $v.member.lastname.$error }"
          @mousedown="$v.member.lastname.$touch()"
        />
        <template v-if="$v.member.lastname.$error">
          <p v-if="!$v.member.lastname.required" class="errorMessage">
            Last Name is required
          </p>
        </template>

        <BaseInput
          v-model="$v.member.phone.$model"
          label="Phone"
          type="text"
          placeholder
          class="field"
        />


        <p @click="changeTodaySettings()" class="change">
          Change celebration settings
        </p>

        <p @click="changePassword()" class="change">Change Password or Email</p>

        <div v-if="this.change_password">
          <BaseInput
            v-model="$v.member.email.$model"
            label="Email"
            type="text"
            placeholder
            class="field"
          />

          <BaseInput
            v-model="$v.member.password.$model"
            label="Password"
            type="password"
            placeholder
            class="field"
          />
        </div>

        <br />
        <br />
        <button class="button green" id="update" click="saveForm">
          Update
        </button>
        <button class="button red" id="delete" @click="deleteForm">
          Delete
        </button>
      </form>
    </div>
  </div>
</template>

<script>
import AuthorService from '@/services/AuthorService.js'
import NavBar from '@/components/NavBar.vue'

import { mapState } from 'vuex'
import { required } from 'vuelidate/lib/validators'
import { authorMixin } from '@/mixins/AuthorMixin.js'

export default {
  components: {
    NavBar
  },
  props: ['uid'],
  mixins: [authorMixin],
  computed: mapState(['user', 'appDir']),
  data() {
    return {
      member: {
        firstname: null,
        lastname: null,
        email: null,
        phone: null,
        scope: null,
        password: null,
        image: 'blank.png'
      },
      change_password: false,
      member_image: null,
      submitted: false,
      wrong: null,
      registered: true
    }
  },
  validations: {
    member: {
      firstname: { required },
      lastname: { required },
      phone: {},
      email: { required },
      password: {}
    }
  },
  methods: {
    changeItems() {
      this.$router.push({
        name: 'myItem',
        params: {
          uid: this.$route.params.uid,
          tid: this.$route.params.tid
        }
      })
    },
    changeTodaySettings() {
      this.$router.push({
        name: 'myTodaySettings',
        params: {
          uid: this.$route.params.uid,
          tid: this.$route.params.tid
        }
      })
    },
    async saveForm() {
      try {
        if (!this.saved) {
          this.saved = true
          this.disableButton('update')
          this.disableButton('delete')
          var params = this.member
          params.member_uid = this.member.uid
          params.authorizer = this.user.uid
          await AuthorService.do('updateUserProfile', params)
          this.show()
        }
      } catch (error) {
        console.log('Update There was an error ', error) //
      }
    },

    async deleteForm() {
      try {
        var params = {}
        params.authorizer = this.user.uid
        params.member_uid = this.member.uid
        console.log('params from DeleteForm')
        console.log(params)
        let res = await AuthorService.deleteUser(params)
        console.log('res from Author Service')
        console.log(res)
        if (res.data.error) {
          this.registered = false
          this.error_message = res.data.message
        } else {
          this.registered = true
          this.$router.push({
            name: 'team',
            params: {
              uid: this.$route.params.uid,
              tid: this.$route.params.tid
            }
          })
        }
      } catch (error) {
        console.log('Delete There was an error ', error) //
      }
    },
    changePassword() {
      if (this.change_password === false) {
        this.change_password = true
      } else {
        this.change_password = false
      }
    },
    async show() {
      this.authorized = this.authorize(
        'profile',
        this.$route.params.uid,
        this.$route.params.tid
      )
      if (this.authorized) {
        try {
          this.menu = await this.menuParams('My Profile', 'M')
          var params = {}
          params.uid = this.$route.params.uid
          this.member = await AuthorService.do('getUser', params)
          this.member.password = null
          if (this.member.image) {
            this.member_image = '/images/members/' + this.member.image
          }
          console.log(this.member)
        } catch (error) {
          console.log('There was an error in MyProfile.vue:', error) // Logs out the error
        }
      }
    }
  },
  beforeCreate: function() {
    document.body.className = 'user'
  },
  async created() {
    this.show()
  }
}
</script>
<style scoped>
.change {
  color: green;
}
</style>
