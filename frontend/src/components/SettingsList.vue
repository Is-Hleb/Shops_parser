<template>
  <MDBListGroup class="rounded-0">
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
    <MDBListGroupItem v-for="setting in settingsToShow" :key="setting.key">
      <MDBRow>
        <MDBCol class="d-flex" col="3">
          <p @click="editSetting(setting.id)" v-if="!setting.onEdit" class="align-self-center">{{ setting.name }}</p>
          <MDBInput
              v-else
              title="Название"
              v-model="setting.name"
              label="Название"
              type="text"
          />
        </MDBCol>
        <MDBCol class="d-flex" col="3">
          <p @click="editSetting(setting.id)" v-if="!setting.onEdit" class="align-self-center">{{ setting.value }}</p>
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
          <p @click="editSetting(setting.id)" v-if="!setting.onEdit" class="align-self-center">{{ setting.collection }}</p>
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
            <MDBBtn @click="editSetting(setting.id)" class="btn-warning border-0 rounded-0">Редактировать</MDBBtn>
            <MDBBtn @click="dropSetting(setting.id)" class="btn-danger border-0 rounded-0">Удалить</MDBBtn>
          </MDBRow>
        </MDBCol>
      </MDBRow>
    </MDBListGroupItem>
  </MDBListGroup>
  <div class="mb-5">
    <MDBBtn class="rounded-0 ms-0 me-2 shadow-0 border border-warning mt-3" v-for="btn in btnCount" :key="btn" :class="btn === activeBtn ? 'btn-warning' : ''"
            @click="showSettings(btn)">{{ btn }}
    </MDBBtn>
  </div>
</template>
<script>
import {
  MDBListGroup,
  MDBListGroupItem,
  MDBCol,
  MDBBtn,
  MDBRow,
  MDBInput,

} from "mdb-vue-ui-kit";


const axios = require('axios');

export default {
  components: {
    MDBListGroup,
    MDBListGroupItem,
    MDBCol,
    MDBBtn,
    MDBRow,
    MDBInput,

  },
  data() {
    return {
      settings: [],
      onEditKey: -1,
      btnCount: 0,
      settingsToShow: [],
      activeBtn: 1,
    }
  },
  methods: {
    loadSettings() {
      this.settings = [];
      this.settingsToShow = [];
      axios.get('/api/settings').then(r => {
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
        this.btnCount = Math.floor((output.length - 1) / 7);

        this.showSettings(this.activeBtn);
      });
    },
    showSettings(btn) {
      let startIndex = btn * 7;

      this.activeBtn = btn;
      this.settingsToShow = [];
      for (let i = startIndex; i < Math.min(startIndex + 7, this.settings.length); i++) {
        this.settingsToShow.push(this.settings[i]);
      }
    },
    dropSetting(id) {
      let sure = +confirm("Точно удалить?");
      if(!sure) return;

      axios.post('/api/setting/delete', {setting: {id: id}}).then(r => {
        r = r.data;
        if (r.code === 'success') {
          this.settings = this.settings.filter(item => item.id !== id);
          this.settingsToShow = this.settingsToShow.filter(item => item.id !== id);

          if (this.settingsToShow.length === 0) {
            this.btnCount = Math.max(0, this.btnCount - 1);
            this.activeBtn -= 1;
            this.showSettings(this.activeBtn)
          }

        }
      }).catch(e => {
        alert(e.message);
      })
    },
    editSetting(id) {
      let setting = this.settingsToShow.filter(item => item.id === id)[0];
      setting.onEdit = !setting.onEdit;
      if (!setting.onEdit) { // Edit is ended
        axios.put(`/api/setting/edit?setting=${setting.id}`, {setting: setting}).then(r => {
          r = r.data;
          console.log(r);
        })
      }
    },
  },
  beforeMount() {
    this.loadSettings();
  }
};
</script>