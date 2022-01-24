<template>
  <form>
    <MDBRow class="w-100">
      <MDBCol col="4">
        <MDBInput
            type="text"
            label="Название настройки"
            v-model="setting.name"
            wrapper-class="mb-3"
            class="p-2"
        />
      </MDBCol>
      <MDBCol col="4">
        <MDBInput
            type="text"
            label="Значение"
            v-model="setting.value"
            wrapper-class="mb-3"
            class="p-2"
        />
      </MDBCol>
      <MDBCol col="4">
        <MDBInput
            type="text"
            label="Коллекция"
            v-model="setting.collection"
            wrapper-class="mb-3"
            class="p-2"
        />
      </MDBCol>
    </MDBRow>


    <MDBBtn @click="createSetting" color="primary mt-5" block>Добавить настройку</MDBBtn>
  </form>
</template>
<script>
import {
  MDBRow,
  MDBCol,
  MDBInput,
  MDBBtn,
} from "mdb-vue-ui-kit";
const axios = require('axios');

export default {
  components: {
    MDBRow,
    MDBCol,
    MDBInput,
    MDBBtn,
  },
  methods: {
    createSetting() {
      console.log(this.setting)
      axios.post('api/setting', {setting: this.setting}).then(r => {
        console.log(r.data)
      })
    }
  },
  data() {
    return {
      dropdown1: false,
      setting: {
        name: "",
        value: "",
        collection: ""
      }
    }
  },

};
</script>