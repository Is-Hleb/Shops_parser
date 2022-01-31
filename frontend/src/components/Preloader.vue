<template>
  <div style="width: 100%; height: 400px; display: flex; justify-content: center">
    <MDBSpinner style="margin: auto 0" :color="color"/>
  </div>
</template>

<script>

import {MDBSpinner} from "mdb-vue-ui-kit";

export default {
  name: 'Preloader',
  props: {
    color: {
      default: 'primary',
      type: String
    }
  },
  components: {
    MDBSpinner
  },
}

</script>