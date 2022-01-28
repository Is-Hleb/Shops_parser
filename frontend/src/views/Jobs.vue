<template>
  <MDBAccordion flush>
    <MDBAccordionItem
        v-for="job in jobs" :key="job.id"
        :headerTitle="job.name"
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
  beforeMount() {
    axios.get('/api/jobs').then(r => {
      r = r.data;
      this.jobs = r.data.jobs;
    });
  }
};
</script>