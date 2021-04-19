<template>
  <div id="main">
    <header id="header">
      <menuClosed
          v-if="isLogged"
      ></menuClosed>
      <menuOpen v-else></menuOpen>
    </header>
    <div id="content">
      <router-view></router-view>
    </div>
  </div>
</template>
<script>
import menuOpen from './components/Menu/menu-open.vue'
import menuClosed from './components/Menu/MenuClosed/menu-closed.vue'
import {mapActions, mapGetters} from 'vuex'
export default {
  components: {
    menuOpen,
    menuClosed
  },
  computed: {
    isLogged () {
      return localStorage.getItem('isLogged') ? localStorage.getItem('isLogged') : null;
    }
  }
}
</script>

<style lang="scss">
@import "~admin-lte/dist/css/adminlte.css";
@import "~bootstrap/dist/css/bootstrap.min.css";
@import "~bootstrap-vue/dist/bootstrap-vue.css";
</style>