<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-6">
        <div class="card card-default">
          <div class="card-header">Смена пароля</div>
          <div class="card-body">
            <form autocomplete="off" @submit.prevent="requestResetPassword" method="post">
              <div class="form-group">
                <label for="email">E-mail</label>
                <input id="email" class="form-control" :class="{ 'is-invalid': errors.email }" placeholder="user@example.com" v-model="details.email">
                <div class="invalid-feedback" v-if="errors.email"> {{ errors.email[0] }}</div>
              </div>
              <button @click="requestResetPassword" class="btn btn-primary">Получить письмо</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>
import { mapGetters, mapActions } from "vuex";
export default {
  data() {
    return {
      details: {
        email: null,
      }
    }
  },
  computed: {
    ...mapGetters(["errors"])
  },
  mounted() {
    this.$store.commit("setErrors", {});
  },
  methods: {
    ...mapActions("auth", ["sendMailResetPassword"]),
    requestResetPassword: function () {
      this.sendMailResetPassword(this.details).then(() => {
        if (this.$store.getters["messageError"]) {
          this.$toastr.e(this.$store.getters["messageError"]);
        } else {
          this.$toastr.s(this.$store.getters["messageSuccess"]);
          this.$router.push({name:'login'});
        }
      });
    }
  }
}
</script>