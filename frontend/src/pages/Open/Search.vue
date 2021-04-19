<template>
  <div>
    <SearchBoth
    :userAuth="null"
    :rootGetModal="null"
    :rootGetAllBooks="rootGetAllBooks"
    :ArrayVoices="null"
    ></SearchBoth>
  </div>
</template>

<script>
import SearchBoth from "@/pages/ForAll/SearchBoth";
export default {
  components: {
    SearchBoth
  },
  data () {
    return {
      rootGetAllBooks: 'openGetAllBooks',
    }
  },
  beforeDestroy() {
    this.$root.$off(this.rootGetAllBooks)
  }
}
</script>