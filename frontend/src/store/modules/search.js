import axios from 'axios'
export default {
    namespaced: true,
    state: {
        books: null,
        prompts: null,
    },
    getters: {
        books: state => state.books,
        prompts: state => state.prompts,
    },
    mutations: {
        setData (state, data) {
            state.books = data
        },
        setPrompts (state, data) {
            state.prompts = data
        }
    },
    actions: {
        getSearch({commit}, credentials) {
            return axios
                .get('/search', {params: {text: credentials.text, from: credentials.from, to: credentials.to} })
                .then(({data}) => {
                    commit('setData', data);
                })
        },
        getAllPrompts({commit}) {
            return axios
                .get('/get-prompts')
                .then(({data}) => {
                    commit('setPrompts', data);
                })
        }
    }
}