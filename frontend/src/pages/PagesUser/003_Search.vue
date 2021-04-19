<template>
  <div v-if="userAuth">
    <SearchBoth
        :userAuth="userAuth"
        :rootGetAllBooks="rootGetAllBooks"
    ></SearchBoth>
  </div>
</template>

<script>
import {mapActions} from "vuex";
import SearchBoth from "@/pages/ForAll/SearchBoth";
export default {
  components: {
    SearchBoth
  },
  data () {
    return {
      rootGetAllBooks: 'authSearchGetAllBooks',
      userAuth: null
    }
  },
  methods: {
    ...mapActions("pageUser", ["getUserAuth"]),
    setUserAuth () {
      this.getUserAuth()
          .then(() => {
            this.userAuth = this.$store.getters["pageUser/user"];
          })
    }
  },
  created() {
    this.setUserAuth ();
  },
  beforeDestroy() {
    this.$root.$off(this.rootGetAllBooks);
  }
}
</script>