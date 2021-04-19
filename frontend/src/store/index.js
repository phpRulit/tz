import Vue from 'vue'
import Vuex from 'vuex'


import auth from "./modules/auth";
import pageUser from "./modules/page_user";
import search from "./modules/search";
import customizes from "./modules/customizes_img";

Vue.use(Vuex)

//Здесь валидация полей форм + модули vuex
export default new Vuex.Store({
  state: {
    errors: [],
    messageSuccess: null,
    messageError: null,
  },
  getters: {
    errors: state => state.errors,
    messageSuccess: state => state.messageSuccess,
    messageError: state => state.messageError
  },
  mutations: {
    setErrors(state, errors) {
      state.errors = errors;
    },
    setMessageSuccess(state, msg) {
      state.messageSuccess = msg;
    },
    setMessageError(state, msg) {
      state.messageError = msg;
    },
  },
  actions: {
  },
  modules: {
    auth: auth,
    pageUser: pageUser,
    search: search,
    customizes: customizes
  }
})
