<template>
  <div>
    <p class="definition">{{ item.paraphrase }}</p>
    <div v-if="item.edit_link" class="center">
      <a href v-bind:src="item.edit_link" class="center">Update Item</a>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    item: Object
  },
  data() {
    return {
      update_link: null
    }
  },
  created() {
    this.edit_link =
      '/user/' + this.$route.params.uid + '/item/' + this.item.id
  }
}
</script>

<style scoped>
table.progress_table {
  width: 60%;
  text-align: center;
}
td {
  border-bottom: 1px solid #ddd;
}
td.row_label {
  width: 80%;
  text-align: left;
}
td.row_value {
  width: 20%;
  text-align: right;
}

.definition {
  color: red;
  font-size: 14px;
}
</style>
