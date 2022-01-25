<template>
  <form>
    <MDBContainer>
      <MDBRow class="w-100">
        <MDBCol col-8>
          <MDBRow>
            <MDBCol col="6" class="px-2">
              <h5>Название</h5>
              <MDBInput
                  type="text"
                  label="Название задачи"
                  v-model="job.name"
                  wrapper-class="mb-3"
                  class="p-2"
              />
              <p>Если поле пустое, значение будет рандомным</p>
            </MDBCol>
            <MDBCol col="6" class="px-2">
              <h5>Группа</h5>
              <MDBListGroup class="rounded-0 scrollable">
                <MDBListGroupItem
                    v-for="collection in collections"
                    :key="collection"
                    :active="collection === activeCollection"
                    class="hover-shadow btn shadow-0"
                    @click="changeActiveCollection(collection)"
                >
                  {{ collection }}
                </MDBListGroupItem>
              </MDBListGroup>
            </MDBCol>
          </MDBRow>
          <MDBRow class="scrollable" style="height: 320px">
            <MDBCol
                v-for="setting in settings.filter(item => item.selected)"
                :key="setting.selected"
                class="p-2 m-3 d-flex ms-2 rounded-2 shadow-5-soft"
                style="height: fit-content"
            >
              <div>
                {{ setting.value }}<br>
                <i @click="setting.selected = false" class="fas fa-backspace" style="cursor: pointer"></i>
              </div>
            </MDBCol>
          </MDBRow>
        </MDBCol>
        <MDBCol col="4" class="px-2">
          <h5>Параметр</h5>
          <MDBListGroup class="rounded-0 scrollable">
            <MDBListGroupItem
                v-for="setting in settingsToShow"
                :key="setting.id"
                :class="setting.selected ? 'setting-active' : ''"
                @click="setting.selected = !setting.selected"
                style="cursor: pointer; z-index: 1"
                class="table-hover"
            >
              <MDBRow>
                <MDBCol col="5" class="border-end">
                  {{ setting.name }}
                </MDBCol>
                <MDBCol col="7">
                  {{ setting.value }}
                </MDBCol>
              </MDBRow>
            </MDBListGroupItem>
          </MDBListGroup>
          <div class="d-inline-flex">
            <MDBBtn
                class="btn btn-primary shadow-0 rounded-0 m-0"
                @click="settingsToShow.forEach(item => item.selected = true)"
            >
              Выбрать все
            </MDBBtn>
            <MDBBtn
                class="btn btn-warning shadow-0 rounded-0 m-0"
                @click="settingsToShow.forEach(item => item.selected = false)"
            >
              Снять выделение
            </MDBBtn>
          </div>
        </MDBCol>
      </MDBRow>
    </MDBContainer>

    <MDBContainer style="min-height: 500px">
      <hr>
      <MDBRow>
        <h5>Шаблон задачи</h5>
        <MDBCol col="4">
          <MDBRow>
            <MDBListGroup class="rounded-0 p-0">
              <MDBListGroupItem
                  v-for="jobTemplate in jobTemplates"
                  :key="jobTemplate.id"
                  :active="jobTemplate.id === job.jobTemplate.id"
                  class="hover-shadow btn shadow-0 rounded-0 mx-0 "
                  @click="job.jobTemplate = jobTemplate"
              >
                {{ jobTemplate.name }}
              </MDBListGroupItem>
            </MDBListGroup>
          </MDBRow>
          <MDBRow>
            <p
                class="mt-5 scrollable"
                style="height: 150px !important;"
                v-if="!job.jobTemplate.isArrayInput && settings.filter(item => item.selected).length > 0"
            >
            Этот тип задачи не поддерживает такое количество аргументов, будут созданы задачи:<br>
              <span v-for="(setting, index) in settings.filter(item => item.selected)" :key="index">
                {{ job.name !== "" ? job.name : "РандомноеИмя" }}{{ setting.name }}{{index + 1}}<br>
              </span>
            </p>
          </MDBRow>
        </MDBCol>
        <MDBCol col="8" class="shadow-5-soft" style="height: fit-content">
          <MDBRow class="py-3">
            <MDBCol col="6" class="d-flex">
              <p class="align-self-center my-auto"><b>Название класса:</b> {{ job.jobTemplate.class }}</p>
            </MDBCol>
            <MDBCol col="6" class="d-flex">
              <p class="align-self-center my-auto"><b>Название метода:</b> {{ job.jobTemplate.method }}</p>
            </MDBCol>
          </MDBRow>
          <MDBRow>
            <MDBCol>
              <hr class="pt-0 mt-0">
              <b>Описание</b>
              <p>{{ job.jobTemplate.description }}</p>
            </MDBCol>
          </MDBRow>
        </MDBCol>
      </MDBRow>
      <MDBBtn @click="createJob" color="primary mt-5 mb-5" block> Добавить задачу</MDBBtn>
    </MDBContainer>
  </form>
</template>
<script>
import {
  MDBRow,
  MDBCol,
  MDBInput,
  MDBContainer,
  MDBListGroup,
  MDBListGroupItem,
  // MDBCheckbox,
  MDBBtn,
} from "mdb-vue-ui-kit";

const axios = require('axios');


export default {
  components: {
    MDBRow,
    MDBCol,
    MDBInput,
    MDBContainer,
    MDBListGroup,
    MDBListGroupItem,
    MDBBtn
  },
  data() {
    return {
      job: {
        name: "",
        externalData: [],
        class: "",
        method: "",
        jobTemplate: {},
      },
      jobTemplates: [],
      listActive: false,

      collections: [],
      activeCollection: null,

      settings: [],
      settingsToShow: [],
    }
  },
  methods: {
    loadTemplates() {
      axios.get('/api/job/templates').then(r => {
        let data = r.data.data.jobTemplates, output = [];

        for (let i = 0; i < data.length; i++) {
          output.push({
            id: data[i].id,
            name: data[i].name,
            class: data[i].class,
            isArrayInput: data[i].isArrayInput,
            description: data[i].description,
            method: data[i].method,
          });
        }

        this.jobTemplates = output;
        this.job.jobTemplate = output[0];
      });
    },
    loadExternalData() {
      axios.get('/api/settings').then(r => {
        let settings = r.data.data.settings, collections = {};
        for (let setting of settings) {
          collections[setting.collection] = setting.collection;
        }
        collections = Object.values(collections);

        this.collections = collections;
        this.settings = settings;
        this.changeActiveCollection(collections[0]);
      })
    },

    changeActiveCollection(collection) {
      this.activeCollection = collection;
      this.settingsToShow = this.settings.filter(item => item.collection === this.activeCollection);
    },

    createJob() {

    },
  },
  async beforeMount() {
    this.loadTemplates();
    this.loadExternalData();
  }
};
</script>

<style type="text/css">
* {
  transition-duration: 300ms;
}

.scrollable {
  overflow-y: scroll;
  max-height: 390px;
}

</style>