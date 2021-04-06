<template>
  <div v-if="item.previous_comment">
    <p>Last month:</p>
    <p class="prayer">{{ item.previous_comment }}</p>
  </div>
</template>

<script>
export default {
  props: {
    item: Object
  }
}
</script>

<style scoped>
.prayer {
  color: purple;
}
</style>
