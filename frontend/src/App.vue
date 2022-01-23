<template>
  <NavBar></NavBar>
<!--  <router-view/>-->
</template>

<script>

import NavBar from "@/components/NavBar";

export default {
  name: 'App',
  components: {
    NavBar
  }
}
</script>

<style>
#app {
  font-family: Roboto, Helvetica, Arial, sans-serif;
}
</style>
