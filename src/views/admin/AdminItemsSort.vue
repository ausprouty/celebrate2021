<?php
<template>
  <div>
     <NavBar v-bind="menu"></NavBar>
    <div class="loading" v-if="loading">Loading...</div>
    <div class="error" v-if="error">There was an error... {{ this.error }}</div>
    <div class="content" v-if="loaded">
      <div v-if="this.authorized">
        <h1>Items to Celebrate</h1>
        <div>
          <draggable v-model="items">
            <transition-group>
              <div v-for="item in items" :key="item.id">
                <div class="shadow-card -shadow">
                  <img v-bind:src="appDir.icons + 'move2red.png'" class="sortable" />
                  <span class="card-name">{{ item.name }}</span>
                </div>
              </div>
            </transition-group>
          </draggable>
          <button class="button red" @click="saveForm">Save</button>
        </div>
      </div>
      <div v-if="!this.authorized">
        <p>
          You need to
          <a href="/login">login to make changes</a> here
        </p>
      </div>
    </div>
  </div>
</template>

<script>
import AuthorService from '@/services/AuthorService.js'
import { mapState } from 'vuex'
import { authorMixin } from '@/mixins/AuthorMixin.js'
import NavBar from '@/components/NavBar.vue'
import draggable from 'vuedraggable'
export default {
  components: {
    NavBar,
    draggable
  },
  mixins: [authorMixin],
  props: ['celebration_set'],
  computed: mapState(['appDir']),
  data() {
    return {
      authorized: false,
      loading: false,
      loaded: null,
      error: null,
      error_message: null,
      items: {}
    }
  },

  methods: {
    async saveForm() {
      try {
        var params = {}
        params.items = JSON.stringify(this.items)
        var response = await AuthorService.do('sortItems',params)
        this.$router.push({
          name: 'AdminCelebrationSets',
          params: {
            celebration_set: this.$route.params.celebration_set
          }
        })
      } catch (error) {
        console.log('There was an error in AdminItemsSort ', error)
        this.error = true
        this.loaded = false
        this.error_message = response.data.message
      }
    }
  },
  async created() {
    this.authorized = this.authorize('admin', null, null)
    if (this.authorized) {
      try {
        this.loading = true
        this.menu = await this.menuParams('Sort Items', 'M')
        var params = {}
        params.celebration_set = this.$route.params.celebration_set
        this.items = await AuthorService.getItemsCelebrationSet(params)
        console.log(this.items)
        this.loading = false
        this.loaded = true
      } catch (error) {
        console.log('There was an error in ItemsSort.vue:', error) // Logs out the error
      }
    }
  }
}
</script>
