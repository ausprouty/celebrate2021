<template>
  <div class="white">
    <NavBar v-bind="menu"></NavBar>
    <div v-if="!this.authorized" class="not_authorized">
      <p>
        You have stumbled into a restricted page. Sorry I can not show it to you
        now
      </p>
    </div>
    <div v-if="this.authorized">
      <div class="name" v-for="set in this.focus_areas" :key="set">
        <span class="link hand" @click="selectSet(set)">{{ set }}</span>
      </div>
      <br />
      <h1 class="center">What {{ this.$route.params.celebration_set }} celebrates</h1>
      <div v-for="(item, id) in this.items" :key="id" :item="item" class="goals">
        <div class="app-link">
          <div class="shadow-card -shadow" @click="editItem(item)">
            <div class="wrapper">
              <div class="icon">
                <img
                  v-bind:src="
                    appDir.icons + item.celebration_set + '/' + item.image
                  "
                  class="icon"
                />
              </div>
              <div class="name"><span class="green" >({{ item.objective }})</span><br> {{ item.name }} </div>
               
            </div>
            {{ item.paraphrase }}
          </div>
        </div>
      </div>
      <button class="button green" id="update" @click="newItem">New Item in this Set</button>
      <button class="button grey" id="update" @click="sort">Sort</button>
    </div>
  </div>
</template>

<script>
import AuthorService from '@/services/AuthorService.js'
import { mapState } from 'vuex'
import { authorMixin } from '@/mixins/AuthorMixin.js'
import NavBar from '@/components/NavBar.vue'

export default {
  components: {
    NavBar
  },

  mixins: [authorMixin],
  props: ['celebration_set'],
  computed: mapState(['appDir', 'focus_areas']),

  data() {
    return {
      authorized: false,
      items: {}
    }
  },
  methods: {
    selectSet(set) {
      this.$route.params.celebration_set = set
      this.show()
    },
    editItem(item) {
      this.$router.push({
        name: 'adminItem',
        params: {
          celebration_set: this.$route.params.celebration_set,
          id: item.id
        }
      })
    },
    newItem() {
      this.$router.push({
        name: 'adminItem',
        params: {
          celebration_set: this.$route.params.celebration_set
        }
      })
    },
    sort() {
      this.$router.push({
        name: 'adminItemsSort',
        params: {
          celebration_set: this.$route.params.celebration_set
        }
      })
    },
    async show() {
      var params = {}
      params.celebration_set = this.$route.params.celebration_set
      this.items = await AuthorService.getItemsCelebrationSet(params)
      console.log(this.items)
    }
  },
  beforeCreate: function() {
    document.body.className = 'admin'
  },
  async created() {
    this.authorized = this.authorize('admin', null, null)
    if (this.authorized) {
      try {
        this.menu = await this.menuParams('Celebration Sets', 'M')
        if (typeof this.$route.params.celebration_set == 'undefined') {
          this.$route.params.celebration_set = 'Cru'
        }
        this.show()
      } catch (error) {
        console.log('There was an error in Celebration Sets.vue:', error) // Logs out the error
      }
    }
  }
}
</script>
<style scoped>
.name {
  font-weight: 700;
}
div.name {
  float: left;
  padding-left: 20px;
}
.link {
  text-decoration: underline;
}
.green{
  color: green;
  font-weight: 200;
}
</style>
