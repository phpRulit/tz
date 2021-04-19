<template>
  <div class="container" v-if="userAuth">
    <div class="card card-default">
      <div class="card-header">Dashboard</div>
        <div class="card-body">
          User Dashboard - {{userAuth.name}}
        </div>
    </div>
  </div>
</template>
<script>
import {mapActions} from "vuex";

export default {
  data() {
    return {
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
    this.setUserAuth();
  }
}
</script>