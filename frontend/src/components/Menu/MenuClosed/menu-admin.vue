<template>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav mr-auto">
        <li class="nav-item" v-for="(route, key) in routes.admin" v-bind:key="route.path">
          <router-link :to="{ name : route.path }" :key="key" class="nav-link">{{route.name}}</router-link>
        </li>
      </ul>
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="nav-link" href="#" @click="logout()">Logout</a>
        </li>
      </ul>
    </div>
  </nav>
</template>
<script>
export default {
  props: ['userAuth'],
  data() {
    return {
      routes: {
        admin: [
          { name: 'Админка', path: 'admin.page' }
        ]
      }
    }
  },
  methods: {
    logout () {
      this.$store.dispatch('auth/logout');
    }
  },
  mounted() {
    //
  }
}
</script>
<style>
.navbar {
  margin-bottom: 30px;
}
</style>