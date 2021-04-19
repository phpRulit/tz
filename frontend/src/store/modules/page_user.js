import axios from 'axios'
export default {
    namespaced: true,
    state: {
        user: null,
        books: null,
        book: null,
        voices: [],
        messageSuccess: null,
        messageError: null,
    },
    getters: {
        user: state => state.user,
        books: state => state.books,
        book: state => state.book,
        voices: state => state.voices,
        messageSuccess: state => state.messageSuccess,
        messageError: state => state.messageError
    },
    mutations: {
        setUserData (state, userData) {
            state.user = userData
        },
        setBooks (state, data) {
            state.books = data
        },
        setBook (state, data) {
            state.book = data
        },
        setVoices (state, data) {
            state.voices = data
        },
        setMessageSuccess(state, msg) {
            state.messageSuccess = msg;
        },
        setMessageError(state, msg) {
            state.messageError = msg;
        },
    },
    actions: {
        getUserAuth({commit}) {
            return axios
                .get('/get-user')
                .then(({data}) => {
                    commit('setUserData', data);
                })
        },
        getUserBooks({commit}) {
            return axios
                .get('/get-my-books')
                .then(({data}) => {
                    commit('setBooks', data ? data : []);
                })
        },
        getUserVoices({commit}) {
            return axios
                .get('/get-voices')
                .then(({data}) => {
                    commit('setVoices', data
                        ? (data.book_id ? JSON.parse(data.book_id) : [])
                        : []);
                })
        },
        getCreateOrEditItem ({commit}, credentials) {
            commit('setMessageError', null);
            commit('setMessageSuccess', null);
            const formData = new FormData();
            formData.append('title', credentials.title);
            formData.append('author', credentials.author);
            formData.append('description', credentials.description);
            return axios
                .post(credentials.url, formData)
                .then(({ data }) => {
                    if (data.errors) {
                        commit("setErrors", data.errors, {root: true});
                    } else if (data.messageError) {
                        commit("setMessageError", data.messageError);
                    } else if (data.message)  {
                        commit("setMessageSuccess", data.message);
                        commit('setBook', data.item);
                        commit("setErrors", [], {root: true});
                    }
                });
        },
        getDestroyBook({commit}, book_id) {
            return axios
                .delete('/destroy/' + book_id)
                .then(({data}) => {
                    if (data.messageError) {
                        commit("setMessageError", data.messageError);
                    } else if (data.message)  {
                        commit("setMessageSuccess", data.message);
                    }
                })
        },
        getToVote({commit}, credentials) {
            const formData = new FormData();
            formData.append('voice', credentials.voice);
            return axios
                .post('/to-vote/' + credentials.id, formData)
                .then(({data}) => {
                    if (data.messageError) {
                        commit("setMessageError", data.messageError);
                    } else if (data.message)  {
                        commit('setBook', data.item);
                        commit("setMessageSuccess", data.message);
                    }
                })
        }
    }
}