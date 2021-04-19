<template>
  <div>
    <div class="col-md-12" v-if="!loading">
      <div class="col-md-12 pt-3 p-2 border border-dashed border-blue-light">
        <div class="form-row">
          <div class="col-6">
            <input v-model="details.text" id="title" v-on:keyup.enter="search" type="text" list="inputList" class="form-control" placeholder="Наименование книги или автора...">
            <dataList id="inputList">
              <option v-for="prompt in prompts" :key="prompt.index">{{prompt}}</option>
            </dataList>
          </div>
          <div class="col-2">
            <input v-model="details.from" type="number" v-on:keyup.enter="search" class="form-control mr-0 raz" placeholder="Оценка, от">
          </div>
          <div class="col-2">
            <input v-model="details.to" type="number" v-on:keyup.enter="search" class="form-control ml-0 raz" placeholder="Оценка, до">
          </div>
          <div class="col-1 text-center">
            <button @click="search" class="btn btn-primary w-100 mb-2">Поиск</button>
          </div>
          <div class="col-1 text-center">
            <button @click="clear" class="btn btn-secondary w-100 mb-2">Очитить</button>
          </div>
        </div>
      </div>

      <div v-if="books.length > 0">
        <div class="oneBook" v-for="book in lists" :key="book.id">
          <hr>
          <OneBook
              :book="book"
              :userAuth="userAuth"
              :rootGetAllBooks="rootGetAllBooks"
              :ArrayVoices="ArrayVoices"
          ></OneBook>
        </div>
        <div class="col-md-12 mt-3 overflow-auto" v-if="books.length > perPage">
          <b-pagination
              :total-rows="totalRows"
              v-model="currentPage"
              :per-page="perPage"
              align="center"
          ></b-pagination>
        </div>
      </div>
      <div class="text-center text-center mt-5" v-else>
        Книг не найдено...
      </div>
    </div>
    <div class="text-center" v-else>
      <img :src="srcLoadingImg" class="mt-5" alt="">
    </div>
  </div>

</template>

<script>
import OneBook from "@/pages/ForAll/OneBook";
import {mapActions} from "vuex";

export default {
  props: ['userAuth', 'rootGetAllBooks'],
  components: {
    OneBook
  },
  data() {
    return {
      books: [],
      prompts: [],
      details: {
        text: '',
        from: null,
        to: null,
      },
      perPage: 20,
      currentPage: 1,
      ArrayVoices: null,
      loading: true,
      srcLoadingImg: this.$store.getters['customizes/pathLoadingWord']
    }
  },
  computed: {
    lists () {
      const items = this.books;
      // Return just page of items needed
      return items.slice(
          (this.currentPage - 1) * this.perPage,
          this.currentPage * this.perPage
      )
    },
    totalRows() {
      return this.books.length
    },
  },
  methods: {
    ...mapActions("search", ["getSearch"]),
    search () {
      this.getSearch(this.details)
          .then(() => {
            this.books = this.$store.getters["search/books"];
          })
    },
    ...mapActions("search", ["getAllPrompts"]),
    getPrompts() {
      this.getAllPrompts()
        .then(() => {
          this.prompts = this.$store.getters["search/prompts"];
          if (!this.userAuth) {
            this.loading = false;
          }
        })
    },
    clear () {
      this.details.text = '';
      this.details.from = null;
      this.details.to = null;
      this.search ();
    },
    ...mapActions("pageUser", ["getUserVoices"]),
    setUserVoices () {
      this.getUserVoices()
          .then(() => {
            this.ArrayVoices = this.$store.getters["pageUser/voices"];
            this.loading = false;
          })
    }
  },
  created() {
    this.search();
    this.getPrompts();
    if (this.userAuth) {
      this.setUserVoices ();
    }
    this.$root.$on(this.rootGetAllBooks, (data) => {
      if (data) {
        this.ArrayVoices.push(data.id);
        this.books[this.books.findIndex(x=>x.id===data.id)] = data;
      }
    })
  }
}
</script>