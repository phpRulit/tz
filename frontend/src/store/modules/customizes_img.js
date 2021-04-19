import axios from "axios";

export default {
    namespaced: true,
    state: {
        pathApiStorage: "https://mylaravelapp.s3.eu-west-3.amazonaws.com/", //прописать свой url до images
        pathNoPhoto: require(`@/assets/No_foto.png`),
        pathLoadingWord: require(`@/assets/loadingWord.gif`),
    },
    getters: {
        pathApiStorage: state => state.pathApiStorage,
        pathNoPhoto: state => state.pathNoPhoto,
        pathLoadingWord: state => state.pathLoadingWord,
    },
    mutations: {
    },
    actions: {
    }
}
