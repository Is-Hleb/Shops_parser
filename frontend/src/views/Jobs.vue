<template>
  <MDBAccordion flush>
    <MDBAccordionItem
        v-for="job in jobs" :key="job.id"
        :headerTitle="`${job.name} (${job.status})`"
        :collapseId="job.id"
    >
      <Log :id="job.id" />
    </MDBAccordionItem>
  </MDBAccordion>
</template>

<script>
import {
  MDBAccordion,
  MDBAccordionItem

}
  from
      "mdb-vue-ui-kit";
import Log from "@/components/Log";
const axios = require('axios');

export default {
  name: "Jobs",
  components: {
    Log,
    MDBAccordion,
    MDBAccordionItem,
  },
  data() {
    return {
      jobs: []
    }
  },
  methods: {
    loadJobs() {
      axios.get('/api/jobs').then(r => {
        r = r.data;
        this.jobs = r.data.jobs;
      });
    }
  },
  beforeMount() {
    this.loadJobs()
  },
  mounted() {
    setInterval(() => {
      this.loadJobs()
    }, 1500);
  }
};
</script>