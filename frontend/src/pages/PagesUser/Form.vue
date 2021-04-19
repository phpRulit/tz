<template>
  <div>
    <transition name="modal">
      <div class="modal-mask">
        <div class="modal-wrapper fixed-overlay" style="z-index: 9995;">
          <div class="modal-dialog-w-90pcnt" role="document">
            <div class="modal-content class-w90pcnt">
              <div class="modal-header">
                <h5 class="modal-title">{{this.book ? 'Редактирование книги' : 'Новая книга'}}</h5>
                <button type="button" class="close" aria-label="Close">
                  <span aria-hidden="true" @click="closeModal">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="col-md-12 text-center mt-2 mb-2">
                <span v-if="book" class="spanHover" id="pick-avatar">
                  <img :src="book && book.img ? pathImg + book.img : noPhoto" style="width:150px; height: 200px; border: 1px solid" alt="">
                </span>
                </div>
                <div class="card mb-3">
                  <div class="card-body pb-2">
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="title" class="col-form-label-sm mb-0">Наименование</label>
                          <input id="title" name="title" :class="{ 'is-invalid': errors.title }"  autofocus="autofocus" placeholder="Введите наименование книги..." class="form-control" type="text" v-model="details.title">
                          <div class="invalid-feedback" v-if="errors.title"> {{ errors.title[0] }}</div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="author" class="col-form-label-sm mb-0">Автор</label>
                          <input id="author" name="author" :class="{ 'is-invalid': errors.author }"  autofocus="autofocus" placeholder="Введите наименование книги..." class="form-control" type="text" v-model="details.author">
                          <div class="invalid-feedback" v-if="errors.author"> {{ errors.author[0] }}</div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="description" class="col-form-label-sm mb-0 w-100">Описание</label>
                          <textarea id="description"  class="form-control area" v-model="details.description" :class="{ 'is-invalid': errors.description }" :placeholder="'Введите описание книги...'"></textarea>
                          <div class="invalid-feedback" v-if="errors.description"> {{ errors.description[0] }}</div>
                        </div>
                      </div>
                    </div>
                    <div class="form-group">
                      <button @click="addItem" class="btn btn-primary button-blue">Сохранить</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
    <avatar-cropper
        v-if="book"
        @uploading="handleUploading"
        @uploaded="handleUploaded"
        :upload-headers="{'Authorization': `Bearer ${token}`}"
        :withCredentials="true"
        :labels="labels"
        :cropper-options="cropperOptions"
        trigger="#pick-avatar"
        :upload-url="baseUrl + '/save-img/' + book.id" />
  </div>

</template>

<script>
import {mapActions, mapGetters} from "vuex";
import AvatarCropper from "vue-avatar-cropper"
export default {
  components: {AvatarCropper},
  props: ['book', 'doIt', 'rootBackMyBooks'],
  data () {
    return {
      customToolbar: this.$store.getters["cardCustomizes/editorToolbar"],
      details: {
        title: this.book ? this.book.title : '',
        author: this.book ? this.book.author : '',
        description: this.book ? (JSON.parse(this.book.description)).description : '',
        url: this.book ? '/edit/' + this.book.id : '/add-new'
      },
      pathImg: this.$store.getters['customizes/pathApiStorage'],
      noPhoto: this.$store.getters['customizes/pathNoPhoto'],
      labels: { submit: "Сохранить", cancel: "Закрыть"},
      cropperOptions: {
        aspectRatio: 1,
        autoCropArea: 1,
        viewMode: 1,
        movable: false,
        zoomable: false
      },
      token: localStorage.getItem('isLogged'),
      baseUrl: process.env.VUE_APP_API_URL.slice(0, -1),
    }
  },
  computed: {
    ...mapGetters(["errors"]),
  },
  methods: {
    closeModal () {
      this.$root.$emit(this.rootBackMyBooks, false);
    },
    ...mapActions("pageUser", ["getCreateOrEditItem"]),
    addItem () {
      this.getCreateOrEditItem(this.details)
        .then(() => {
          if (this.$store.getters["pageUser/messageError"]) {
            this.$toastr.e(this.$store.getters["pageUser/messageError"]);
          } else if (this.$store.getters["pageUser/messageSuccess"]) {
            this.$root.$emit(this.rootBackMyBooks, [this.doIt, this.$store.getters["pageUser/book"]]);
            this.$toastr.s(this.$store.getters["pageUser/messageSuccess"]);
          }
        });
    },
    handleUploading() {
      this.$root.$emit('appGetModalDataProcessing', true);
    },
    handleUploaded(response) {
      let notFirstUpload = false;
      if (this.book && this.book.img) {
        notFirstUpload = true;
      }
      if (response.message) {
        this.$root.$emit(this.rootBackMyBooks, ['edit', response.item]);
        this.$toastr.s(response.message);
      } else if (response.messageError) {
        this.$toastr.e(response.messageError);
      }
    }
  },
  mounted() {
    this.$store.commit("setErrors", {});
  }
}
</script>

<style scoped>
  .area {
    min-height: 300px;
  }
</style>
