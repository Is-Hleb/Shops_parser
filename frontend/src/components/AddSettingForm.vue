<template>
  <form>
    <MDBRow class="w-100">
      <MDBCol col="4">
        <MDBInput
            type="text"
            label="Название настройки"
            v-model="setting.name"
            wrapper-class="mb-3"
        />
      </MDBCol>
      <MDBCol col="4">
        <MDBInput
            type="text"
            label="Значение"
            v-model="setting.value"
            wrapper-class="mb-3"
        />
      </MDBCol>
      <MDBCol col="4">
        <MDBRow>
          <MDBCol col="7" class="me-0 pe-0">
            <MDBInput
                type="text"
                label="Коллекция"
                v-model="setting.collection"
                wrapper-class="mb-3"
            />
          </MDBCol>
          <MDBCol col="5" class="ms-0 p-0">
            <MDBDropdown class="w-100" v-model="dropdown1">
              <MDBDropdownToggle class="rounded-0 w-100" @click="dropdown1 = !dropdown1">
                УЖЕ ЕСТЬ
              </MDBDropdownToggle>
              <MDBDropdownMenu class="w-100" aria-labelledby="dropdownMenuButton">
                <MDBDropdownItem v-for="collection in this.collections" :key="collection">
                  <div class="w-100 px-3 py-2 hover-shadow"
                       @click="this.setting.collection = collection; dropdown1 = false">{{ collection }}
                  </div>
                </MDBDropdownItem>
              </MDBDropdownMenu>
            </MDBDropdown>
          </MDBCol>
        </MDBRow>
      </MDBCol>
    </MDBRow>
    <MDBBtn @click="createSetting" color="primary mt-5" block>Добавить настройку</MDBBtn>
    <vue-basic-alert
        :duration="500"
        :closeIn="2500"
        ref="alert"
    />
  </form>
</template>
<script>
import {
  MDBRow,
  MDBCol,
  MDBInput,
  MDBBtn,
  MDBDropdown,
  MDBDropdownToggle,
  MDBDropdownMenu,
  MDBDropdownItem
} from "mdb-vue-ui-kit";

import VueBasicAlert from "vue-basic-alert";

const axios = require('axios');

export default {
  components: {
    MDBRow,
    MDBCol,
    MDBInput,
    MDBBtn,
    MDBDropdown,
    MDBDropdownToggle,
    MDBDropdownMenu,
    MDBDropdownItem,
    VueBasicAlert
  },
  methods: {
    createSetting() {
      axios.post('api/setting', {setting: this.setting}).then(() => {
        this.$refs.alert.showAlert(
            'info', // There are 4 types of alert: success, info, warning, error
            `Настройка ${this.setting.name} создана со значением: ${this.setting.value}`, // Message of the alert
            'Настройка создана', // Header of the alert
            {
              iconSize: 15, // Size of the icon (px)
              iconType: 'solid', // Icon styles: now only 2 styles 'solid' and 'regular'
              position: 'top right'
            }
        );

        this.setting.value = "";
        this.setting.name = "";
      })
    }
  },
  data() {
    return {
      dropdown1: false,
      setting: {
        name: "",
        value: "",
        collection: "",
        collections: []
      }
    }
  },
  beforeMount() {
    axios.get('/api/settings/collections').then(r => {
      this.collections = r.data.data.collections;
      console.log(this.collections);
    })
  }
};
</script>