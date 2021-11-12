<?php
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

import { mapState } from 'vuex'


export default {
  data() {
    return {
      test: '',
      result: '',
      test_options: [

        'verifyRoute'
      ]
    }
  },
  computed: mapState([ 'user']),
  methods: {
    async runTest(test) {
      var response = await this[test]()
      this.result = response
      LogService.consoleLog(test, response)
    },
    setupParams(){
        var params = {}
        params.my_uid = this.user.uid
        return params
    },
    async testBibleABSUpdate() {
      var params = this.setupParams()
      var response = await AuthorService.bibleUpdateABS(params)
      return response
    },
    async verifyRoute() {
      var params = this.setupParams()
      var response = await AuthorService.bibleUpdateABS(params)
      return response
    },

  }
}
</script>
