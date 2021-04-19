import Vue from 'vue';
import App from './App.vue';
import router from './router';
import axios from 'axios';
import store from './store'
import BootstrapVue from 'bootstrap-vue';
import Toastr from 'vue-toastr';
import Moment from 'vue-moment';
import './bootstrap/bootstarp.scss';

Vue.router = router

Vue.use(Toastr, {
  defaultTimeout: 15000,
  defaultProgressBar: false,
  defaultProgressBarValue: 0,
  defaultPosition : "toast-bottom-full-width",
  defaultCloseOnHover: false,
  defaultClassNames: ["animated", "zoomInUp"]
});

Vue.use(Moment);
Vue.use(BootstrapVue);
Vue.config.productionTip = false;

axios.defaults.baseURL = process.env.VUE_APP_API_URL;

Vue.prototype.$http = axios;
const token = localStorage.getItem('isLogged');
if (token) {
  axios.defaults.headers.common.Authorization = `Bearer ${token}`
}

new Vue({
  router,
  store,
  created () {
    const userInfo = localStorage.getItem('isLogged') ? localStorage.getItem('isLogged') : null;
    if (userInfo) {
      this.$store.commit('auth/setToken', userInfo)
    }
    axios.interceptors.response.use(
        response => response,
        error => {
          if (error.response.status === 401) {
            this.$store.dispatch('auth/logout')
          } else if (error.response.status === 403) {
            if (this.$toastr) {
              this.$toastr.removeByType("error");
              this.$toastr.removeByType("success");
            }
            this.$toastr.e('У Вас нет прав доступа!!! Данные не получены!!!');
          }
          return Promise.reject(error)
        }
    )
  },
  render: h => h(App)
}).$mount('#app')
