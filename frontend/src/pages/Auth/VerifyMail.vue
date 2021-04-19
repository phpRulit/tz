<template>
  <div class="verify">
    <div class="alert alert-danger" role="alert" v-if="error">
      {{ error }}
    </div>
    <p class="w-100 text-center" v-show="!error">Пожалуйста подождите...</p>
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";

export default {
  props: ["hash"],

  data() {
    return {
      error: null
    };
  },
  mounted() {
    this.sendVerifyMailRequest(this.hash)
      .then(() => {
        if (this.$store.getters["messageError"]) {
          this.$toastr.e(this.$store.getters["messageError"]);
          this.$router.push({name: "home"});
        } else {
          this.$toastr.s(this.$store.getters["messageSuccess"]);
          this.$router.push({name: "login"});
        }
      })
  },
  computed: {
    ...mapGetters("auth", ["user"])
  },
  methods: {
    ...mapActions("auth", ["sendVerifyMailRequest"])
  }
};
</script>
