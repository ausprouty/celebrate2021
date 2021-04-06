import BibleService from '@/services/BibleService.js'
export const bibleMixin = {
  methods: {
    async getBibleVersions(language_iso, testament = 'FU') {
      try {
        var versions = []
        var params = {
          language_iso: language_iso,
          testament: testament
        }
        if (language_iso.length > 2) {
          var response = await BibleService.getBibleVersions(params)
          console.log('response from Bible versions')
          console.log(response)
          if (response.data.content !== false) {
            versions = response.data.content
            console.log('versions')
            console.log(versions)
          }
          return versions
        }
      } catch (error) {
        console.log(
          'BIBLE MIXIN -- There was an error finding Bible Versions:',
          error
        ) // Logs out the error
        this.error = error.toString() + 'BIBLE MIXIN -- getBibleVersions'
        return null
      }
    }
  }
}
