<template>
  <Preloader v-if="loading" color="warning"/>
  <MDBContainer v-else class="px-0 py-3">
    <MDBRow>
      <MDBCol col="6">
        <h4>Переданные данные</h4>
        <MDBListGroup class="border border-1 scrollable" flush>
          <MDBListGroupItem v-for="(datum, index) in job.externalData" :key="datum" action>
            <b>{{ index }}:</b> {{ datum }}
          </MDBListGroupItem>
        </MDBListGroup>
      </MDBCol>
      <MDBCol col="6" v-if="job.logs.length > 0">
        <h4>Логи</h4>
        <MDBListGroup class="border border-1 scrollable" flush>
          <MDBListGroupItem
              class="bg-scroll\"
              v-for="log in job.logs" :key="log.id"
              :class=" log.type === 'info' ? 'bg-primary text-white' : 'bg-danger' "
              action
          >
            {{ log.value }}
          </MDBListGroupItem>
        </MDBListGroup>
      </MDBCol>
      <MDBCol v-else col="6">
        <h4>Логов нет</h4>
        <p class="text-dark text-opacity-50">Логи пока пусты</p>
      </MDBCol>
    </MDBRow>

    <MDBRow class="p-2 border-top mt-3">
      <MDBCol class="col-4 shadow-4-soft p-3 border bg-light" style="display: inline-grid">
        <div v-if="job.contents.length > 0">
          <h4>Результат работы</h4>
          <MDBListGroup flush>
            <MDBListGroupItem v-for="content in job.contents" :key="content.id" class="border-bottom d-flex pe-0" action>
              {{ content.type }}
              <MDBBtn @click="uploadContent(content.id)" class="ms-auto me-0">Скачать</MDBBtn>
            </MDBListGroupItem>
          </MDBListGroup>
        </div>
        <MDBRow class="">
          <MDBCol class="p-0">
            <MDBBtn class="m-0 w-100 btn-primary border-0 rounded-0 align-self-end">Запустить</MDBBtn>
          </MDBCol>
        </MDBRow>
      </MDBCol>
      <MDBCol class="col-8 shadow-4-soft p-3 border-bottom">
        <h4>Лог файл</h4>
        <div>
          <p v-if="job.log_file.length > 0">
            {{ job.log_file }}
          </p>
          <p v-else>
            Лога пока то нет
          </p>
        </div>
      </MDBCol>
    </MDBRow>

  </MDBContainer>
</template>

<script>
import {
  MDBListGroup,
  MDBListGroupItem,
  MDBCol,
  MDBRow,
  MDBContainer,
  MDBBtn

}
  from "mdb-vue-ui-kit";
import Preloader from "@/components/Preloader";

const axios = require('axios');
export default {
  name: 'Job',
  props: ['id'],
  components: {
    Preloader,
    MDBListGroup,
    MDBListGroupItem,
    MDBCol,
    MDBRow,
    MDBContainer,
    MDBBtn
  },
  data() {
    return {
      job: {},
      active: true,
      loading: true,
    }
  },
  methods: {
    loadJob() {
      axios.get(`/api/job/info?job=${this.id}`).then(r => {

        let data = r.data.data;
        this.job = {
          contents: data.contents,
          logs: data.logs,
          log_file: data.log_file,
          externalData: data.externalData
        };

        console.log(this.job);

        if (this.loading) this.loading = false;
        if (this.active) setTimeout(this.loadJob, 1500);
      })
    },
    uploadContent(id) {
      window.location = `api/download/json/content?content=${id}&job=${this.id}`;
    },
  },
  mounted() {
    this.loadJob();
  },
  beforeUnmount() {
    this.active = false;
    this.loading = true;
  }

}
</script>

<style>
.scrollable {
  overflow-y: scroll;
  max-height: 390px;
}
</style>
