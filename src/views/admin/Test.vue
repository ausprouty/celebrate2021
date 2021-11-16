<template>
  <div>
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
</template>
<script>
import AuthorService from '@/services/AuthorService.js'
import LogService from '@/services/AuthorService.js'

import { mapState } from 'vuex'

export default {
  data() {
    return {
      test: '',
      result: '',
      test_options: ['getTeamFromUid']
    }
  },
  computed: mapState(['user']),
  methods: {
    setupParams() {
      var params = {}
      //  params.my_uid = this.user.uid
      params.my_uid = 1
      return params
    },
    async runTest(test) {
      var response = await this[test]()
      this.result = response
      LogService.consoleLog(test, response)
    },
    async getTeamFromUid() {
      var params = this.setupParams()
      params.uid = 1
      var response = await AuthorService.getTeamFromUid(params)
      return response
    }
  }
}
</script>
