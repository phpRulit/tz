<template>
  <div class="container">
    <div class="row justify-content-md-center">
      <div class="col-6">
        <div class="card card-default">
          <div class="card-header">Регистрация</div>
          <div class="card-body">
            <div class="form-group">
              <label for="name">Имя</label>
              <input type="text" id="name" class="form-control" :class="{ 'is-invalid': errors.name }" placeholder="Введите Ваше имя..." v-model="details.name">
              <div class="invalid-feedback" v-if="errors.name"> {{ errors.name[0] }}</div>
            </div>
            <div class="form-group">
              <label for="email">E-mail</label>
              <input type="email" id="email" class="form-control" :class="{ 'is-invalid': errors.email }" placeholder="user@example.com" v-model="details.email">
              <div class="invalid-feedback" v-if="errors.email"> {{ errors.email[0] }}</div>
            </div>
            <div class="form-group">
              <label for="password">Пароль</label>
              <input type="password" id="password" class="form-control" :class="{ 'is-invalid': errors.password }" placeholder="Придумайте и введите пароль..." v-model="details.password">
              <div class="invalid-feedback" v-if="errors.password"> {{ errors.password[0] }}</div>
            </div>
            <div class="form-group">
              <label for="password_confirmation">Подтверждение пароля</label>
              <input type="password" id="password_confirmation" class="form-control" placeholder="Введите пароль ещё раз..." v-model="details.password_confirmation">
            </div>
            <button @click="register" class="btn btn-primary">Зарегистрироваться</button>
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
        name: null,
        email: null,
        password: null,
        password_confirmation: null
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
    ...mapActions("auth", ["sendRegisterRequest"]),
    register: function() {
      this.sendRegisterRequest(this.details).then(() => {
        if (this.$store.getters["messageSuccess"]) {
          this.$toastr.s(this.$store.getters["messageSuccess"]);
          this.$router.push({name: 'home'});
        }
      });
    }
  }
}
</script>