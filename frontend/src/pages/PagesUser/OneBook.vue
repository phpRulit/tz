<template>
  <div>
    <div class="col-md-12 row">
      <div class="col-md-2 p-2 pl-4">
        <img :src="Book.img ? pathImg + Book.img : noPhoto" style="width:150px; height: 200px; border: 1px solid" alt="">
        <span class="font-weight-bold counterAtCol">
          <span class="mx-auto align-middle">{{Book.estimate ? Book.estimate.toFixed(1) : "0.0"}}</span>
        </span>
      </div>
      <div class="col-md-10">
        <div class="col-md-12 p-2 pr-0 font-weight-bold row">
          <div class="col-md-9 text-maroon">{{Book.title}}</div>
          <div class="col-md-3 small text-right pr-0">
            <span class="spanHover">
              <span @click="edit" class="text-blue mr-2">Редактировать</span>
              <span @click="destroy" class="text-red">Удалить</span>
            </span>
          </div>
        </div>
        <div class="col-md-12 p-2 text-gray">{{Book.author}}</div>
        <div class="col-md-12 p-2 text-justify">{{DataDescription.description.length > 500 ? DataDescription.description.slice(0, 497) + '...' : DataDescription.description}}</div>
      </div>
    </div>
  </div>

</template>

<script>
export default {
  props: ['book', 'userAuth', 'rootGetModal', 'rootGetModalAct', 'rootGetBook'],
  data () {
    return {
      Book: this.book,
      pathImg: this.$store.getters['customizes/pathApiStorage'],
      noPhoto: this.$store.getters['customizes/pathNoPhoto'],
    }
  },
  computed: {
    DataDescription () {
      return JSON.parse(this.Book.description);
    }
  },
  methods: {
    edit () {
      this.$root.$emit(this.rootGetModal, this.Book)
    },
    destroy () {
      this.$root.$emit(this.rootGetModalAct, this.Book)
    }
  },
  created() {
    this.$root.$on(this.rootGetBook, (data) => {
      this.Book = data;
    })
  }
}
</script>