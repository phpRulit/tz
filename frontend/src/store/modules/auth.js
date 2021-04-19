import axios from 'axios'
export default {
    namespaced: true,
    state: {
        user: null,
        isLogged: localStorage.getItem('isLogged') || null,
        admin: localStorage.getItem('admin') || null,
        emailNotConfirmed: null
    },
    getters: {
        user: state => state.user,
        isLogged: state => state.isLogged,
        EmailNotConfirmed: state => state.emailNotConfirmed,
    },
    mutations: {
        setToken (state, token) {
            state.isLogged = token;
            localStorage.setItem('isLogged', token);
        },
        setIsLogged (state, userData) {
            state.user = userData.user;
            state.isLogged = userData.token;
            localStorage.setItem('isLogged', userData.token);
        },
        clearUserData () {
            localStorage.removeItem('isLogged')
            localStorage.removeItem('admin')
            location.reload()
        }
    },
    actions: {
        sendLoginRequest ({ commit }, credentials) {
            commit('setMessageError', null, {root: true});
            return axios
                .post('/auth/login', credentials)
                .then(({ data }) => {
                    if (data.errors) {
                        commit("setErrors", data.errors, {root: true});
                    } else if (data.messageError) {
                        commit('setMessageError', data.messageError, {root: true});
                    } else {
                        commit('setIsLogged', data);
                        localStorage.removeItem('errors')
                    }
                })
        },
        sendRegisterRequest({ commit }, credentials) {
            commit('setMessageSuccess', null, {root: true});
            return axios
                .post('/auth/register', credentials)
                .then(({ data }) => {
                    if (data.errors) {
                        commit("setErrors", data.errors, {root: true});
                    } else {
                        commit('setMessageSuccess', data.message, {root: true});
                    }
                });
        },
        logout ({ commit }) {
            commit('clearUserData')
        },
        sendVerifyMailRequest({ commit }, hash) {
            commit('setMessageSuccess', null, {root: true});
            commit('setMessageError', null, {root: true});
            return axios
                .get('/auth/verify/' + hash)
                .then(({ data }) => {
                    if (data.message) {
                        commit('setMessageSuccess', data.message, {root: true});
                    } else if (data.messageError) {
                        commit('setMessageError', data.messageError, {root: true});
                    }
                });
        },
        resendMailForVerify({ commit }, email) {
            commit('setMessageSuccess', null, {root: true});
            commit('setMessageError', null, {root: true});
            return axios
                .get('/auth/resend/' + email)
                .then(({ data }) => {
                    if (data.message) {
                        commit('setMessageSuccess', data.message, {root: true});
                    } else if (data.messageError) {
                        commit('setMessageError', data.messageError, {root: true});
                    }
                });
        },
        sendMailResetPassword ({ commit }, credentials) {
            commit('setMessageSuccess', null, {root: true});
            commit('setMessageError', null, {root: true});
            return axios
                .post('/auth/reset-password', credentials)
                .then(({ data }) => {
                    if (data.errors) {
                        commit("setErrors", data.errors, {root: true});
                    } else if (data.message) {
                        commit('setMessageSuccess', data.message, {root: true});
                    } else if (data.messageError) {
                        commit('setMessageError', data.messageError, {root: true});
                    }
                });
        },
        getResetPassword ({ commit }, credentials) {
            commit('setMessageSuccess', null, {root: true});
            commit('setMessageError', null, {root: true});
            return axios
                .post('/auth/reset/password', credentials)
                .then(({ data }) => {
                    if (data.errors) {
                        commit("setErrors", data.errors, {root: true});
                    } else if (data.message) {
                        commit('setMessageSuccess', data.message, {root: true});
                    } else if (data.messageError) {
                        commit('setMessageError', data.messageError, {root: true});
                    }
                });
        }
    }
}