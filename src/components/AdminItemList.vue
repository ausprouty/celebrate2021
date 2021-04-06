<template>
  <div>
    <div class="celebration-names" v-on:click="showPage(item)">
      <span class="card-name">{{ item.name }}</span>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    item: Object
  },
  data: function() {
    return {}
  },

  methods: {
    showPage: function(item) {
      alert(item.paraphrase)
    }
  }
}
</script>
<style scoped>
<style scoped > div.break {
  display: inline;
}
</style>
