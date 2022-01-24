<template>
  <MDBAccordion flush>
    <MDBAccordionItem
        headerTitle="Job 1 ECatalog"
        collapseId="flush-collapseOne"
    >
      <MDBCol col="4">
        <MDBCard>
          <MDBListGroup flush>
            <MDBListGroupItem action>Category url</MDBListGroupItem>
            <MDBListGroupItem action>Dapibus ac facilisis in</MDBListGroupItem>
            <MDBListGroupItem action>Vestibulum at eros</MDBListGroupItem>
          </MDBListGroup>
        </MDBCard>
      </MDBCol>
    </MDBAccordionItem>
  </MDBAccordion>
</template>

<script>
import {MDBAccordion, MDBAccordionItem, MDBCard, MDBListGroup, MDBListGroupItem, MDBCol} from "mdb-vue-ui-kit";

export default {
  name: "Jobs",
  components: {
    MDBAccordion,
    MDBAccordionItem,
    MDBCard,
    MDBListGroup,
    MDBListGroupItem,
    MDBCol
  },
};
</script>