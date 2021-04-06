<template>
  <div>
    <p>Already Entered:</p>
    <div v-for="(today, todayid) in this.item.entered" :key="todayid" :item="today" class="prayer">
     {{today.when}}:  {{ today.comment }} </br>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    item: Object
  },
 
}
</script>

<style scoped>
.prayer {
  color: purple;
}
</style>
