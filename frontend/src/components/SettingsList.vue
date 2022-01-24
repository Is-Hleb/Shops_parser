<template>
  <MDBListGroup>
    <MDBListGroupItem class="border-bottom border-2">
      <MDBRow>
        <MDBCol class="d-flex" col="3">
          <p class="align-self-center">Название</p>
        </MDBCol>
        <MDBCol class="d-flex border-4" col="3">
          <p class="align-self-center">Значение</p>
        </MDBCol>
        <MDBCol class="d-flex border-4" col="3">
          <p class="align-self-center">Коллекция</p>
        </MDBCol>
        <MDBCol class="d-flex border-4" col="3">
          <p class="align-self-center">Взаимодействие</p>
        </MDBCol>
      </MDBRow>
    </MDBListGroupItem>
    <MDBListGroupItem v-for="setting in settings" :key="setting.key">
      <MDBRow>
        <MDBCol class="d-flex" col="3">
          <p class="align-self-center">{{ setting.name }}</p>
        </MDBCol>
        <MDBCol class="d-flex" col="3">
          <p class="align-self-center">{{ setting.value }}</p>
        </MDBCol>
        <MDBCol class="d-flex" col="3">
          <p class="align-self-center">{{ setting.collection }}</p>
        </MDBCol>
        <MDBCol col="3">
          <MDBRow>
            <MDBBtn class="btn-warning">Редактировать</MDBBtn>
            <MDBBtn class="btn-danger">Удалить</MDBBtn>
          </MDBRow>
        </MDBCol>
      </MDBRow>
    </MDBListGroupItem>
  </MDBListGroup>
</template>
<script>
import {
  MDBListGroup,
  MDBListGroupItem,
  MDBCol,
  MDBBtn,
  MDBRow
} from "mdb-vue-ui-kit";

const conf = require('../axios.json');
const axios = require('axios');
console.log(conf)
export default {
  components: {
    MDBListGroup,
    MDBListGroupItem,
    MDBCol,
    MDBBtn,
    MDBRow
  },
  data() {
    return {
      settings: []
    }
  },
  beforeMount() {
    axios.get(conf.url + '/api/settings', {
      headers: {
        accept: 'application/json'
      }
    }).then(r => {
      let data = r.data.data.categories, output = [];

      for (let i = 0; i < data.length; i++) {
        output.push({
          name: "Категория",
          value: data[i],
          collection: 'Категории',
          key: i,
        });
      }
      this.settings = output;
    });
    this.settings = [];
  }
};
</script>