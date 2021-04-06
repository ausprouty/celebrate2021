<script>
export default {
  methods: {
    toFormData(obj) {
      this.content.edit_date = ''
      this.content.edit_uid = ''
      var form_data = new FormData()
      for (var key in obj) {
        form_data.append(key, obj[key])
      }
      this.content.text = ''
      console.log('form_data')
      // Display the key/value pairs
      for (var pair of form_data.entries()) {
        console.log(pair[0] + ', ' + pair[1])
      }
      return form_data
    }
  }
}
</script>
