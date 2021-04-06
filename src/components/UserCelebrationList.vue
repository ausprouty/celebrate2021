<template>
  <div>
    <div class="celebration-names" v-on:click="showPage(celebration)">
      <span class="card-name">{{ celebration.name }}</span>
    </div>
    <div class="form">
      <BaseInput
        v-model="choose_language"
        label=celebration.name
        type="text"
        placeholder="Choose Language"
        class="field"
      />
    </div>
  </div>
</template>

<script>
export default {
  props: {
    celebration: Object
  },
  data: function() {
    return {}
  },

  methods: {
    showPage: function(celebration) {
      alert(celebration.paraphrase)
    }
  }
}
</script>
<style scoped>
<style scoped > div.break {
  display: inline;
}
</style>
