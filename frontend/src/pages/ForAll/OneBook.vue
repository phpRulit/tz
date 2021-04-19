<template>
  <div>
    <div class="col-md-12 row">
      <div class="col-md-2 p-2 pl-4">
        <img :src="book.img ? pathImg + book.img : noPhoto" style="width:150px; height: 200px; border: 1px solid" alt="">
        <span class="font-weight-bold counterAtCol">
          <span class="mx-auto align-middle">{{Book.estimate ? Book.estimate.toFixed(1) : "0.0"}}</span>
        </span>
      </div>
      <div class="col-md-10">
        <div class="col-md-12 p-2 pr-0 font-weight-bold row">
          <div class="col-md-9 text-maroon">{{book.title}}</div>
          <div class="col-md-3 small text-right pr-0">
            <div v-if="userAuth && userAuth.id !== Book.user_id && ArrayVoices && !this.ArrayVoices.includes(Book.id)" class="rating-area">
              <input type="radio" :id="['star-5' + this.Book.id]" name="rating" value="5" v-model="rating">
              <label :for="['star-5'  + this.Book.id]" title="Оценка «5»"></label>
              <input type="radio" :id="['star-4' + this.Book.id]" name="rating" value="4" v-model="rating">
              <label :for="['star-4' + this.Book.id]" title="Оценка «4»"></label>
              <input type="radio" :id="['star-3' + this.Book.id]" name="rating" value="3" v-model="rating">
              <label :for="['star-3' + this.Book.id]" title="Оценка «3»"></label>
              <input type="radio" :id="['star-2' + this.Book.id]" name="rating" value="2" v-model="rating">
              <label :for="['star-2' + this.Book.id]" title="Оценка «2»"></label>
              <input type="radio" :id="['star-1' + this.Book.id]" name="rating" value="1" v-model="rating">
              <label :for="['star-1' + this.Book.id]" title="Оценка «1»"></label>
              </div>
            <span class="text-green" v-else-if="userAuth && userAuth.id !== Book.user_id && ArrayVoices && this.ArrayVoices.includes(Book.id)">Голос учтён</span>
            <span class="text-blue" v-else-if="userAuth && userAuth.id === Book.user_id">Ваша книга</span>
          </div>
        </div>
        <div class="col-md-12 p-2 text-gray">{{book.author}}</div>
        <div class="col-md-12 p-2 text-justify">{{DataDescription.description.length > 500 ? DataDescription.description.slice(0, 497) + '...' : DataDescription.description}}</div>
      </div>
    </div>
  </div>

</template>

<script>
import {mapActions} from "vuex";

export default {
  props: ['book', 'userAuth', 'rootGetAllBooks', 'ArrayVoices'],
  data () {
    return {
      Book: this.book,
      rating: 0,
      pathImg: this.$store.getters['customizes/pathApiStorage'],
      noPhoto: this.$store.getters['customizes/pathNoPhoto'],
    }
  },
  watch: {
    rating (newVal, oldVal) {
      if (newVal !== oldVal) {
        this.toVote();
      }
    }
  },
  computed: {
    DataDescription () {
      return JSON.parse(this.book.description)
    }
  },
  methods: {
    ...mapActions("pageUser", ["getToVote"]),
    toVote () {
      this.getToVote({id: this.Book.id, voice:this.rating})
        .then(() => {
          if (this.$toastr) {
            this.$toastr.removeByType("error");
            this.$toastr.removeByType("success");
          }
          if (this.$store.getters["pageUser/messageError"]) {
            this.$toastr.e(this.$store.getters["pageUser/messageError"]);
          } else if (this.$store.getters["pageUser/messageSuccess"]) {
            this.Book = this.$store.getters["pageUser/book"];
            this.$root.$emit(this.rootGetAllBooks, this.userAuth ? this.Book : null);
            this.$toastr.s(this.$store.getters["pageUser/messageSuccess"]);
          }
        })
    },
  }
}
</script>


<style scoped>
.rating-area {
  overflow: hidden;
  width: 265px;
  margin: 0 auto;
}
.rating-area:not(:checked) > input {
  display: none;
}
.rating-area:not(:checked) > label {
  float: right;
  width: 42px;
  padding: 0;
  cursor: pointer;
  font-size: 32px;
  line-height: 32px;
  color: lightgrey;
  text-shadow: 1px 1px #bbb;
}
.rating-area:not(:checked) > label:before {
  content: '★';
}
.rating-area > input:checked ~ label {
  color: gold;
  text-shadow: 1px 1px #c60;
}
.rating-area:not(:checked) > label:hover,
.rating-area:not(:checked) > label:hover ~ label {
  color: gold;
}
.rating-area > input:checked + label:hover,
.rating-area > input:checked + label:hover ~ label,
.rating-area > input:checked ~ label:hover,
.rating-area > input:checked ~ label:hover ~ label,
.rating-area > label:hover ~ input:checked ~ label {
  color: gold;
  text-shadow: 1px 1px goldenrod;
}
.rate-area > label:active {
  position: relative;
}
</style>