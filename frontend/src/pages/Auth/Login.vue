<template>
  <div class="container">
    <div class="row justify-content-md-center">
      <div class="col-6">
        <div class="card card-default" v-if="!EmailNotConfirmed">
          <div class="card-header">Вход</div>
          <div class="card-body">
              <div class="form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" class="form-control" :class="{ 'is-invalid': errors.email }" placeholder="user@example.com" v-model="details.email" required>
                <div class="invalid-feedback" v-if="errors.email">
                  {{ errors.email[0] }}
                </div>
              </div>
              <div class="form-group">
                <label for="password">Пароль</label>
                <input type="password" id="password" class="form-control" :class="{ 'is-invalid': errors.password }" placeholder="Введите пароль..." v-model="details.password" required>
                <div class="invalid-feedback" v-if="errors.password">
                  {{ errors.password[0] }}
                </div>
              </div>
              <button @click="login" class="btn btn-primary">Войти</button>
          </div>
          <div class="card-header p-0 pr-2">
            <router-link to="/reset-password" class="nav-link float-right">Забыли пароль ?</router-link>
          </div>
        </div>
        <div class="card card-default" v-else>
          <div class="card-header">Нажмите на кнопку, чтобы получить письмо для подтверждения регистрации...<br>Ваш email: {{EmailNotConfirmed}}</div>
          <button @click="resend" class="btn btn-primary">Отправить письмо</button>
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
        password: null
      },
      EmailNotConfirmed: null
    }
  },
  computed: {
    ...mapGetters(["errors"])
  },
  mounted() {
    this.$store.commit("setErrors", {});
  },
  methods: {
    ...mapActions("auth", ["sendLoginRequest"]),
    ...mapActions("auth", ["resendMailForVerify"]),
    login: function() {
      this.sendLoginRequest(this.details).then(() => {
        if (this.$store.getters["messageError"]) {
          this.$toastr.e(this.$store.getters["messageError"]);
          this.EmailNotConfirmed = this.$store.getters["auth/EmailNotConfirmed"];
        } else {
          let userAuth = this.$store.getters["auth/user"];
          if (userAuth) {
            if (Number.parseInt(userAuth.role) === 1) {
              localStorage.setItem('admin', 'true');
            }
            location.reload();
          }
        }
      });
    },
    resend: function() {
      this.resendMailForVerify(this.EmailNotConfirmed).then(() => {
        if (this.$store.getters["messageError"]) {
          this.$toastr.e(this.$store.getters["messageError"]);
        } else if (this.$store.getters["messageSuccess"]) {
          this.$toastr.s(this.$store.getters["messageSuccess"]);
          this.EmailNotConfirmed = null;
        }
      });
    }

  }
}
</script>
