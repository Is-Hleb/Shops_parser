<template>
  <NavBar v-if="!this.loading">
  </NavBar>
<!--  <div v-else style="width: 100%; height: 100vh; display: flex; justify-content: center">-->
<!--    <MDBSpinner style="margin: auto 0" color="primary"/>-->
<!--  </div>-->
</template>

<script>

import NavBar from "@/components/NavBar";



export default {
  name: 'App',
  data() {
    return {
      loading: false,
    }
  },
  components: {
    NavBar
  },
}
</script>

<style>

</style>
