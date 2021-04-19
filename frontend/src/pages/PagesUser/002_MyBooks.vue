<template>
  <div>
    <div class="col-md-12" v-if="!loading">
      <div class="col-md-12 pt-3 p-2 border border-dashed border-blue-light" v-if="books.length > 0">
        <div class="form-row">
          <div class="col-11">
            <input v-model="title" type="text" class="form-control" placeholder="Введите название">
          </div>
          <div class="col-1 text-center">
            <button @click="clear" class="btn btn-secondary w-100 mb-2">Очитить</button>
          </div>
        </div>
      </div>
      <button @click="addBook" class="btn btn-success mt-3 mb-3">Добавить книгу</button>
      <div v-if="filteredItems.length > 0">
        <div class="oneBook" v-for="book in lists" :key="book.id">
          <hr>
          <OneBook
              :book="book"
              :userAuth="userAuth"
              :rootGetModal="rootGetModal"
              :rootGetModalAct="rootGetModalAct"
              :rootGetBook="rootGetBook + book.id"
          ></OneBook>
        </div>
        <div class="col-md-12 mt-3 overflow-auto" v-if="filteredItems.length > perPage">
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
      <Form
          v-if="showForm"
          :book="book"
          :doIt="doIt"
          :rootBackMyBooks="rootBackMyBooks"
      ></Form>
      <transition name="modal" v-if="showModalConfirm">
        <div class="modal-mask">
          <div class="modal-wrapper fixed-overlay">
            <div class="modal-dialog" role="document">
              <div class="modal-content class-w400px">
                <div class="modal-header p-0">
                  <p class="modal-title font-weight-bold mb-1 ml-3">Подтверждение действия</p>
                  <button type="button" class="close" aria-label="Close">
                    <span aria-hidden="true" @click="getCloseAct">&times;</span>
                  </button>
                </div>
                <div class="col-md-12 mt-2">
                  <p class="justify-content-around" style="hyphens: auto">
                    Вы уверены, что хотите удалить данную книгу - {{this.book.title}}? Если да, подтвердите действие...</p>
                </div>
                <div class="col-md-12 text-center mb-4">
                  <div class="row">
                    <div class="col-md-6">
                      <button @click="getAct" class="btn btn-primary w-100">Подтвердить</button>
                    </div>
                    <div class="col-md-6">
                      <button @click="getCloseAct" class="btn btn-secondary w-100">Отменить</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </transition>
    </div>
    <div class="text-center" v-else>
      <img :src="srcLoadingImg" class="mt-5" alt="">
    </div>
  </div>

</template>

<script>
import {mapActions} from "vuex";
import OneBook from "./OneBook";
import Form from "@/pages/PagesUser/Form";

export default {
  components: {
    OneBook,
    Form
  },
  data() {
    return {
      rootGetModal: 'modalGetEdit',
      rootBackMyBooks: 'modalCreateEdit',
      rootGetModalAct: 'authGetModalAct',
      rootGetBook: 'authGetBook',
      books: [],
      book: null,
      doIt: null,
      userAuth: null,
      title: '',
      perPage: 20,
      currentPage: 1,
      showForm: false,
      showModalConfirm: false,
      loading: true,
      srcLoadingImg: this.$store.getters['customizes/pathLoadingWord']
    }
  },
  computed: {
    filteredItems () {
      return this.books.length > 0 ? this.books
          .filter(item => {
            return this.title === '' || item.title.toLowerCase().indexOf(this.title.toLowerCase()) !== -1;
          }) : []
    },
    lists () {
      const items = this.filteredItems;
      // Return just page of items needed
      return items.slice(
          (this.currentPage - 1) * this.perPage,
          this.currentPage * this.perPage
      )
    },
    totalRows() {
      return this.filteredItems.length
    },
  },
  methods: {
    ...mapActions("pageUser", ["getUserAuth"]),
    setUserAuth () {
      this.getUserAuth()
        .then(() => {
          this.userAuth = this.$store.getters["pageUser/user"];
        })
    },
    ...mapActions("pageUser", ["getUserBooks"]),
    getBooks () {
      this.getUserBooks()
          .then(() => {
            this.books = this.$store.getters["pageUser/books"];
            this.loading = false;
          })
    },
    clear () {
      this.title = ''
    },
    addBook () {
      this.book = null;
      this.doIt = 'create';
      this.showForm = true;
    },
    getCloseAct () {
      this.showModalConfirm = false;
    },
    ...mapActions("pageUser", ["getDestroyBook"]),
    getAct () {
      this.getDestroyBook(this.book.id)
        .then(() => {
          if (this.$toastr) {
            this.$toastr.removeByType("error");
            this.$toastr.removeByType("success");
          }
          if (this.$store.getters["pageUser/messageError"]) {
            this.$toastr.e(this.$store.getters["pageUser/messageError"]);
          } else if (this.$store.getters["pageUser/messageSuccess"]) {
            this.books.splice(this.books[this.books.findIndex(x=>x.id===this.book.id)], 1);
            this.getCloseAct();
            this.$toastr.s(this.$store.getters["pageUser/messageSuccess"]);
          }
        })
    }
  },
  created() {
    this.setUserAuth();
    this.getBooks();
    this.$root.$on(this.rootBackMyBooks, (data) => {
      this.showForm = false;
      if(data) {{
        if (data[0] === 'edit') {
          this.books[this.books.findIndex(x=>x.id===data[1].id)] = data[1];
          this.$root.$emit(this.rootGetBook + data[1].id, data[1])
        } else {
          this.books.unshift(data[1]);
        }
      }}
    });
    this.$root.$on(this.rootGetModal, (data) => {
      this.showForm = true;
      this.doIt = 'edit';
      this.book = data;
    });
    this.$root.$on(this.rootGetModalAct, (book) => {
      this.showModalConfirm = true;
      this.book = book;
    })
  },
  beforeDestroy() {
    this.$root.$off(this.rootBackMyBooks);
    this.$root.$off(this.rootGetModal);
    this.$root.$off(this.rootGetModalAct);
    this.$root.$off(this.rootGetBook);
  }
}
</script>

<style>
.oneBook:last-child {
  margin-bottom: 100px;
}
</style>