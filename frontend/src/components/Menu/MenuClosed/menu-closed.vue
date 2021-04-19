<template>
  <div id="main" v-if="userAuth">
    <header id="header">
      <menuUser v-if="(this.userAuth.role === '0')" :userAuth="userAuth"></menuUser>
      <menuAdmin v-else  :userAuth="userAuth"></menuAdmin>
    </header>
  </div>
</template>
<script>
import menuUser from '../../../components/Menu/MenuClosed/menu-user.vue'
import menuAdmin from '../../../components/Menu/MenuClosed/menu-admin.vue'
import axios from 'axios'
import {mapActions} from "vuex";
export default {
  components: {
    menuUser,
    menuAdmin,
  },
  data() {
    return {
      userAuth: null
    }
  },
  methods: {
    ...mapActions("pageUser", ["getUserAuth"]),
    setUserAuth () {
      this.getUserAuth()
        .then(() => {
          this.userAuth = this.$store.getters["pageUser/user"];
        })
    }
  },
  created() {
    this.setUserAuth();
  }
}
</script>