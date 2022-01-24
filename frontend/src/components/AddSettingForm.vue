<template>
  <form>
    <MDBRow class="w-100">
      <MDBCol col="4">
        <MDBInput
            type="text"
            label="Название настройки"
            v-model="jobName"
            wrapper-class="mb-3"
            class="p-2"
        />
      </MDBCol>
      <MDBCol col="4">
        <MDBInput
            type="text"
            label="Значение"
            v-model="jobName"
            wrapper-class="mb-3"
            class="p-2"
        />
      </MDBCol>
      <MDBCol col="4">
        <MDBInput
            type="text"
            label="Коллекция"
            v-model="jobName"
            wrapper-class="mb-3"
            class="p-2"
        />
      </MDBCol>
    </MDBRow>


    <MDBBtn color="primary mt-5" block>Добавить настройку</MDBBtn>
  </form>
</template>
<script>
import {
  MDBRow,
  MDBCol,
  MDBInput,
  MDBBtn,
} from "mdb-vue-ui-kit";


export default {
  components: {
    MDBRow,
    MDBCol,
    MDBInput,
    MDBBtn,
  },
  data() {
    return {
      jobName: "",
      dropdown1: false,
      selectedOption: "",
      anotherData: {}
    }
  },

};
</script>