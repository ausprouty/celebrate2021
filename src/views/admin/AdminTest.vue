<?php
<template>
  <div class="white">
    <NavBar v-bind="menu"></NavBar>
    <div v-if="!this.test_authorized" class="not_authorized">
      <p>
        You have stumbled into a restricted page. Sorry I can not show it to you
        now.
      </p>
    </div>
    <div v-if="this.test_authorized">
      <h1>Select Test</h1>
      <BaseSelect
        label="Test"
        :options="test_options"
        v-model="test"
        v-on:change="runTest(test)"
        class="field"
      />
      {{ this.result }}
    </div>
  </div>
</template>
<script>
import AuthorService from '@/services/AuthorService.js'
import { authorMixin } from '@/mixins/AuthorMixin.js'
import LogService from '@/services/LogService.js'
import { mapState } from 'vuex'
import NavBar from '@/components/NavBar.vue'

export default {
   computed: mapState(['focus_areas', 'user']),
   components: { NavBar },
   mixins: [authorMixin],
  data() {
    return {
      test: '',
      result: '',
      test_authorized: false,
      authorized: false,
      test_options: [
        'testCheckMember',
         'testCheckTeam',
         'testCheckUser',
        'getSettingsToday',
        'getTeamFromUid',
        'getUser'
      ]
    }
  },

  methods: {
    async runTest(test) {
      var response = await this[test]()
      this.result = response
      LogService.consoleLog( ' Response from ' + test, response)
    },
    setupRoute(){
        var route = {}
        route.uid = 1
        route.tid= 2
        return  route
    },
    setupParams(){
        var params = {}
        params.my_uid = this.user.uid
        return params
    },
     async testCheckMember() {
       var route = this.setupRoute()
      var response = await this.checkMember(route)
      return response
    },
     async testCheckTeam() {
      var route = this.setupRoute()
      var response = await this.checkTeam(route)
      return response
    },
     async testCheckUser() {
      var route = this.setupRoute()
      var response = await this.checkUser(route)
      return response
    },
   async getTeamFromUid() {
      var params = this.setupParams()
      params.uid = 1
      var response = await AuthorService.do('getTeamFromUid',params)
      return response
    },
    async getSettingsToday() {
      var params = []
      var route = {}
      route.uid = 1
      route.tid = 1
      route.year = new Date().getFullYear()
      params['route'] = JSON.stringify(route)
      console.log(params)
      var response = await AuthorService.do('getSettingsToday', params)
      return response
    },
    async getUser(){
        var params = this.setupParams()
        params['uid'] = this.$route.params.uid
        var response = await AuthorService.do('getUser', params)
        return response
    }

  },
  created() {

    this.test_authorized = this.authorize(
      'admin',
      this.user.uid,
      999
    )
   console.log (this.test_authorized)
  }
}
</script>
