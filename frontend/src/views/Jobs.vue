<template>
  <Preloader v-if="loading"/>
  <div v-else>
    <div v-for="job in visibleJobs" :key="job.id">
      <MDBRow class="py-3 border" :class="job.collapsed ? 'bg-dark bg-opacity-10' : '' ">
        <MDBCol col="9">
          <MDBRow>
            <MDBCol col="8">
              <h6>
                {{ job.name }}<br>
                <span class="text-primary"><b>id:</b> {{ job.id }}</span>
              </h6>
            </MDBCol>
            <MDBCol col="4" class="d-flex justify-content-end">
              <MDBSpinner v-if="job.statusCode === 1" style="height: 22px; width: 22px; border-width: 2px" color="primary"/>
              <h6 v-else-if="job.statusCode === 2" class="bg-primary p-2 text-white shadow">{{ job.status }}</h6>
              <h6 v-else-if="job.statusCode === 0" class="bg-success p-2 text-white shadow">{{ job.status }}</h6>
              <h6 v-else class="bg-danger text-white p-2 shadow">{{ job.status }}</h6>
            </MDBCol>
          </MDBRow>


        </MDBCol>
        <MDBCol col="3" class="border-start">

          <MDBBtn
              class="btn btn-light w-50 m-0"
              @click="
                activeJob = job.id;
                this.visibleJobs = this.visibleJobs.map(item => {
                  if(item.id !== this.activeJob) item.collapsed = false;
                  return item;
                });
                job.collapsed = !job.collapsed;
              "
              :aria-controls="job.id.toString()"
              :aria-expanded="job.collapsed"
          >
            {{ job.collapsed ? 'Скрыть' : 'Подробнее' }}
          </MDBBtn>

          <div class="ms-2 d-inline">
            <MDBBtn @click="deleteJob(job.id)" class="btn btn-danger m-0">
              Удалить
            </MDBBtn>
          </div>

        </MDBCol>
      </MDBRow>
      <MDBRow>
        <MDBCollapse
            :id="job.id.toString()"
            v-model="job.collapsed"
            class="border"
        >
          <Job
              v-if="job.id === activeJob"
              :id="job.id"
          />
        </MDBCollapse>
      </MDBRow>
    </div>

    <hr class="bg-dark w-100">
    <div class="mb-5 ms-3">
      <MDBBtn
          v-for="btn in btnsCount"
          :key="btn"

          @click="nextPage(btn)"
          class="rounded-0 mx-0"
          :class="btn === activeBtn ? 'bg-primary text-white' : '' "
      >
        {{ btn }}
      </MDBBtn>
    </div>
  </div>
</template>

<script>
import {
  MDBCollapse,
  MDBBtn,
  MDBRow,
  MDBCol,
  MDBSpinner,
} from "mdb-vue-ui-kit";

import Job from "@/components/Job";
import Preloader from "@/components/Preloader";

const axios = require('axios');

const JOBS_ON_PAGE_COUNT = 8;

export default {
  name: "Jobs",
  components: {
    Preloader,
    Job,
    MDBBtn,
    MDBCollapse,
    MDBRow,
    MDBCol,
    MDBSpinner,
  },
  data() {
    return {
      jobs: [],
      visibleJobs: [],
      startIndex: 0,
      btnsCount: 0,
      activeBtn: 1,
      activeJob: -1,
      loading: true,
      active: true,
    }
  },
  methods: {
    loadJobs() {
      axios.get('/api/jobs').then(r => {
        r = r.data;

        if (this.jobs.filter(item => item.collapsed).length === 0) {
          this.activeJob = -1;
        }

        this.jobs = r.data.jobs;

        this.jobs = this.jobs.map(item => {
          item = {...item, collapsed: false};
          if (item.id === this.activeJob) {
            item.collapsed = true;
          }
          return item;
        })

        this.btnsCount = Math.floor(this.jobs.length / JOBS_ON_PAGE_COUNT);
        this.jobs.reverse();
        this.visibleJobs = [];

        for (let i = this.startIndex; i < Math.min(this.jobs.length, this.activeBtn * JOBS_ON_PAGE_COUNT); i++) {
          this.visibleJobs.push(this.jobs[i]);
        }

        if (this.loading) this.loading = false;
        if (this.active) setTimeout(this.loadJobs, 1500);
      });

    },
    deleteJob(id) {
      axios.delete(`/api/job?job=${id}`).then(() => {
        alert('Удалил');
      });
    },
    nextPage(btn) {
      this.startIndex = (btn - 1) * JOBS_ON_PAGE_COUNT;
      this.activeBtn = btn;
      this.activeJob = -1;
      this.visibleJobs = [];
      for (let i = this.startIndex; i < Math.min(this.jobs.length, this.startIndex + JOBS_ON_PAGE_COUNT); i++) {
        this.visibleJobs.push(this.jobs[i]);
      }
    },
    arrowClick(e) {
      console.log(e.key);
      if (e.key === 'ArrowRight') {
        this.nextPage(Math.min(this.activeBtn + 1, this.btnsCount));
      } else if (e.key === 'ArrowLeft') {
        this.nextPage(Math.max(this.activeBtn - 1, 1));
      }
    }
  },
  beforeMount() {
    this.loadJobs()
  },
  created: function () {
    window.addEventListener('keyup', this.arrowClick);
  },
  unmounted() {
    this.active = false;
    this.loading = true;
    window.removeEventListener('keyup', this.arrowClick);

  }
};
</script>