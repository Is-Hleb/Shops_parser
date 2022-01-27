<template>
  <MDBAccordion flush>
    <MDBAccordionItem
        headerTitle="Job 1 ECatalog"
        collapseId="1"
    >
      <Log id="1" />
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

export default {
  name: "Jobs",
  components: {
    Log,
    MDBAccordion,
    MDBAccordionItem,

  },
};
</script>