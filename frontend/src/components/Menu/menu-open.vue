<template>
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <router-link :to="{name: 'home'}" class="navbar-brand">SPA (Laravel + JWT + Vue JS + Vuex + Docker + Elasticsearch + Amazon)</router-link>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item" v-for="(route, key) in routes.open" v-bind:key="route.path">
          <router-link :to="{ name : route.path }" :key="key" class="nav-link">{{route.name}}</router-link>
        </li>
      </ul>
    </div>
  </nav>
</template>
<script>
export default {
  data() {
    return {
      routes: {
        open: [
          { name: 'Библиотека', path: 'search'},
          { name: 'Регистрация', path: 'register' },
          { name: 'Вход', path: 'login'},
        ]
      }
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