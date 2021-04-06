<template>
  <div v-if="item.previous_prayer">
    <p>Last month</p>
    <p class="prayer">{{ item.previous_prayer }}</p>
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
