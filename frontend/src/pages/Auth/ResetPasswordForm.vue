<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-6">
        <div class="card card-default">
          <div class="card-header">Смена пароля</div>
          <div class="card-body">
            <div class="form-group">
              <label for="email">E-mail</label>
              <input type="email" id="email" class="form-control" :class="{ 'is-invalid': errors.email }" placeholder="user@example.com" v-model="details.email">
              <div class="invalid-feedback" v-if="errors.email"> {{ errors.email[0] }}</div>
            </div>
            <div class="form-group">
              <label for="password">Пароль</label>
              <input type="password" id="password" class="form-control" :class="{ 'is-invalid': errors.password }" placeholder="Придумайте и введите новый пароль..." v-model="details.password">
              <div class="invalid-feedback" v-if="errors.password"> {{ errors.password[0] }}</div>
            </div>
            <div class="form-group">
              <label for="password_confirmation">Подтверждение пароля</label>
              <input type="password" id="password_confirmation" class="form-control" placeholder="Введите пароль ещё раз..." v-model="details.password_confirmation">
            </div>
            <button @click="resetPassword" class="btn btn-primary">Сменить пароль</button>
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
        token: null,
        email: null,
        password: null,
        password_confirmation: null,
      },
    }
  },
  computed: {
    ...mapGetters(["errors"])
  },
  mounted() {
    this.$store.commit("setErrors", {});
  },
  methods: {
    ...mapActions("auth", ["getResetPassword"]),
    resetPassword: function() {
      this.details.token = this.$route.params.token;
      this.getResetPassword(this.details).then(() => {
        if (this.$store.getters["messageError"]) {
          this.$toastr.e(this.$store.getters["messageError"]);
        } else {
          this.$toastr.s(this.$store.getters["messageSuccess"]);
          this.$router.push({name: 'login'})
        }
      });
    }
  }
}
</script>