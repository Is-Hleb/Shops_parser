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
          <p v-if="!setting.onEdit" class="align-self-center">{{ setting.name }}</p>
          <MDBInput
            v-else
            title="Название"
            v-model="setting.name"
            label="Название"
            type="text"
          />
        </MDBCol>
        <MDBCol class="d-flex" col="3">
          <p v-if="!setting.onEdit" class="align-self-center">{{ setting.value }}</p>
          <MDBInput
              v-else
              class="d-block h-50 align-self-center"
              title="Название"
              v-model="setting.value"
              label="Название"
              type="text"
          />
        </MDBCol>
        <MDBCol class="d-flex" col="3">
          <p v-if="!setting.onEdit" class="align-self-center">{{ setting.collection }}</p>
          <MDBInput
              v-else
              class="d-block h-50 align-self-center"
              title="Название"
              v-model="setting.collection"
              label="Название"
              type="text"
          />
        </MDBCol>
        <MDBCol col="3">
          <MDBRow>
            <MDBBtn @click="setEdit(setting.key)" class="btn-warning border-0 rounded-0">Редактировать</MDBBtn>
            <MDBBtn class="btn-danger border-0 rounded-0">Удалить</MDBBtn>
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
  MDBRow,
  MDBInput
} from "mdb-vue-ui-kit";


const axios = require('axios');

export default {
  components: {
    MDBListGroup,
    MDBListGroupItem,
    MDBCol,
    MDBBtn,
    MDBRow,
    MDBInput
  },
  data() {
    return {
      settings: [],
      onEditKey: -1,
    }
  },
  methods: {
    setEdit(key) {
      this.settings[key].onEdit = !this.settings[key].onEdit;

    }
  },
  beforeMount() {
    axios.get('/api/settings', {
      headers: {
        accept: 'application/json'
      }
    }).then(r => {
      let data = r.data.data.settings, output = [];

      for (let i = 0; i < data.length; i++) {
        output.push({
          name: data[i].name,
          value: data[i].value,
          collection: data[i].collection,
          id: data[i].id,
          key: i,
          onEdit: false,
        });
      }
      this.settings = output;
    });
    this.settings = [];
  }
};
</script>