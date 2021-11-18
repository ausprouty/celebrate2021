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
      <h2 v-if="member.firstname">Update {{ member.firstname }} {{ member.lastname }}</h2>
      <h2 v-if="!member.firstname">Enter new member(s) for {{ team.name }}</h2>
      <div v-if="this.single_entry">
        <button class="button grey" id="update" @click="manyMembers">Enter Many People</button>
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
            <p v-if="!$v.member.firstname.required" class="errorMessage">First Name is required</p>
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
            <p v-if="!$v.member.lastname.required" class="errorMessage">Last Name is required</p>
          </template>

          <BaseInput
            v-model="$v.member.phone.$model"
            label="Phone"
            type="text"
            placeholder
            class="field"
          />
          <p v-if="!team.name" @click="changePassword()" class="change">Change Password or Email</p>

          <div v-if="this.change_password">
            <BaseInput
              v-model="$v.member.email.$model"
              label="Email"
              type="text"
              placeholder
              class="field"
              @click="checkUnique()"
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
          <button class="button green" id="update" click="saveForm">Update</button>
          <button class="button red" id="delete" @click="deleteForm">Delete</button>
        </form>
      </div>
      <div v-if="!this.single_entry">
        <button class="button grey" id="update" @click="oneMember">Enter Only One Person</button>
        <BaseTextarea
          v-model="$v.people.names.$model"
          label="Enter Multiple People here:"
          type="text"
          class="field"
        />
        <p>(Tab Delimited) Training Name (not used), First Name, Last Name, Email</p>
        <button class="button green" id="create" @click="createTeamMembers()">Record These Members</button>
      </div>
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
  props: ['uid', 'tid'],
  mixins: [authorMixin],
  computed: mapState(['view', 'user','appDir']),
  data() {
    return {
      member: {
        scope: null,
        firstname: null,
        lastname: null,
        email: null,
        phone: null,
        image: 'blank.png',
        password: null
      },
      team: {
        name: null
      },
      change_password: false,
      member_image: null,
      original_email: null,
      submitted: false,
      wrong: null,
      registered: true,
      single_entry: true,
      people: {
        names: null
      }
    }
  },
  validations: {
    member: {
      scope: { required },
      firstname: { required },
      lastname: { required },
      email: { required },
      phone: {},
      image: {},
      username: {},
      password: {}
    },
    people: {
      names: {}
    }
  },
  methods: {
    oneMember() {
      this.single_entry = true
    },
    manyMembers() {
      this.single_entry = false
    },
    async checkUnique() {
      if (this.original_email != this.$v.member.email.$model) {
        var params = {}
        params.email = this.$v.member.email.$model
        var res = await AuthorService.do('checkEmailIsUnique', params)
        if (res != 1) {
          alert('This email is already registered')
        }
        return res
      } else {
        return 1
      }
    },

    async createTeamMembers() {
      console.log(this.$v.people.names.$model)
      try {
        if (!this.saved) {
          this.saved = true
          this.disableButton('create')
          var params = {}
          params.tid = this.$route.params.tid
          params.authorizer = this.user.uid
          params.members = this.$v.people.names.$model
          console.log('params for SaveMembers')
          console.log(params)
          await AuthorService.do('createTeamMembers', params)
          this.show()
        }
      } catch (error) {
        console.log('Update There was an error ', error) //
      }
    },
    async saveForm() {
      try {
        if (!this.saved) {
          this.saved = true
          this.disableButton('update')
          this.disableButton('delete')
          var res = await this.checkUnique()
          console.log(res)
          if (res == 1) {
            var params = {}
            params = this.member
            params.tid = this.$route.params.tid
            console.log('Save Form')
            console.log(this.member)
            params.member_uid = this.member.uid
            params.authorizer = this.user.uid
            console.log('params for SaveForm')
            console.log(params)
            await AuthorService.do('updateUserProfile', params)
            console.log('I finished saving user')
            this.$router.push({
              name: 'ourTeam',
              params: {
                tid: this.$route.params.tid
              }
            })
          } else {
            throw new Error('This email is not unique')
          }
        }
      } catch (error) {
        console.log('Update There was an error ', error)
        this.enableButton('update')
        this.enableButton('delete')
        this.saved = false
      }
    },

    async deleteForm() {
      try {
        var params = {}
        params.authorizer = this.user.uid
        params.member_uid = this.member.uid
        params.member_username = this.member.username
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
            name: 'adminTeams'
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
        'team',
        this.$route.params.uid,
        this.$route.params.tid
      )
      if (this.authorized) {
        try {
          var params = {}
          this.menu = await this.menuParams('Team Member Profile', 'M')
          if (typeof this.$route.params.uid != 'undefined') {
            params.uid = this.$route.params.uid
            this.member = await AuthorService.do('getUser', params)
            this.member.password = null
            this.original_email = this.member.email
            if (this.member.image) {
              this.member_image = '/images/members/' + this.member.image
            }
            console.log(this.member)
          } else {
            params.tid = this.$route.params.tid
            this.team = await AuthorService.do('getTeam', params)
            this.change_password = true
            this.single_entry = false
            this.single_entry = true
            console.log(this.team)
          }
        } catch (error) {
          console.log('There was an error in TeamMemberProfile.vue:', error) // Logs out the error
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
