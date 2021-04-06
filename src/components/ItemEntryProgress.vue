<template>
  <div>
    <p class="definition">{{ item.paraphrase }}</p>
    <table class="progress_table">
      <tr>
        <td class="row_label">Last month</td>
        <td class="row_value">{{ item.previous_entry }}</td>
      </tr>
    </table>

    <div v-if="item.cumulative == 'Y'">
      <table class="progress_table">
        <tr>
          <td class="row_label">Total for year</td>
          <td class="row_value">{{ item.calculated_entry }}</td>
        </tr>
      </table>
    </div>
    <div v-if="item.cumulative != 'Y'">
      <table class="progress_table">
        <tr>
          <td class="row_label">Average for year</td>
          <td class="row_value">{{ item.calculated_entry }}</td>
        </tr>
      </table>
    </div>
    <div v-if="item.goal_numbers">
      <table class="progress_table">
        <tr>
          <td class="row_label">Goal</td>
          <td class="row_value">{{ item.goal_numbers }}</td>
        </tr>
      </table>
    </div>
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
    this.update_link =
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
