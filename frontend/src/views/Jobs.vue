<template>
  <MDBAccordion flush>
    <MDBAccordionItem
        v-for="job in visibleJobs" :key="job.id"
        :headerTitle="`${job.name} (${job.status})`"
        :collapseId="job.id"
    >
      <Log :id="job.id" />
    </MDBAccordionItem>
  </MDBAccordion>
  <hr class="bg-dark w-100">
  <div class="mb-5 ms-3">
    <MDBBtn
        @click="nextPage(btn)"
        v-for="btn in btnsCount"
        :key="btn"
        class="rounded-0 mx-0"
        :class="btn === activeBtn ? 'bg-primary text-white' : '' "
    >
      {{ btn }}
    </MDBBtn>
  </div>
</template>

<script>
import {
  MDBAccordion,
  MDBAccordionItem,
  MDBBtn
}
  from
      "mdb-vue-ui-kit";
import Log from "@/components/Log";
const axios = require('axios');

const JOBS_ON_PAGE_COUNT = 12;

export default {
  name: "Jobs",
  components: {
    Log,
    MDBAccordion,
    MDBAccordionItem,
    MDBBtn,
  },
  data() {
    return {
      jobs: [],
      intervalId: null,
      visibleJobs: [],
      startIndex: -1,
      btnsCount: 0,
      activeBtn: 1,
    }
  },
  methods: {
    loadJobs() {
      axios.get('/api/jobs').then(r => {
        r = r.data;
        this.jobs = r.data.jobs;
        this.btnsCount = Math.floor(this.jobs.length / JOBS_ON_PAGE_COUNT);
        if(this.startIndex === -1) {
          this.startIndex = 0;
          for (let i = this.startIndex; i < Math.min(this.jobs.length, this.startIndex + JOBS_ON_PAGE_COUNT); i++) {
            this.visibleJobs.push(this.jobs[i]);
          }
        }
      });
    },
    nextPage(btn) {
      this.startIndex = (btn - 1) * JOBS_ON_PAGE_COUNT;
      this.activeBtn = btn;
      this.visibleJobs = [];
      for (let i = this.startIndex; i < Math.min(this.jobs.length, this.startIndex + JOBS_ON_PAGE_COUNT); i++) {
        this.visibleJobs.push(this.jobs[i]);
      }
    },
  },

  beforeMount() {
    this.loadJobs()
  },
  mounted() {
    this.intervalId = setInterval(() => {
      this.loadJobs()
    }, 1500);
  },
  beforeRouteLeave(){
    clearInterval(this.intervalId);
  }
};
</script>