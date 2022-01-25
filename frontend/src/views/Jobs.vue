<template>
  <MDBAccordion flush>
    <MDBAccordionItem
        headerTitle="Job 1 ECatalog"
        collapseId="flush-collapseOne"
    >
      <MDBRow>
        <MDBCol col="6">
          <h4>Переданные данные</h4>
          <MDBListGroup class="border border-1" flush>
            <MDBListGroupItem action>Category url</MDBListGroupItem>
            <MDBListGroupItem action>Dapibus ac facilisis in</MDBListGroupItem>
            <MDBListGroupItem action>Vestibulum at eros</MDBListGroupItem>
          </MDBListGroup>
        </MDBCol>
        <MDBCol col="6">
          <h4>Ответные данные</h4>
          <MDBListGroup class="border border-1" flush>
            <MDBListGroupItem class="bg-success" action>Category url</MDBListGroupItem>
            <MDBListGroupItem action>Dapibus ac facilisis in</MDBListGroupItem>
            <MDBListGroupItem action>Vestibulum at eros</MDBListGroupItem>
          </MDBListGroup>
        </MDBCol>
      </MDBRow>
    </MDBAccordionItem>
  </MDBAccordion>
</template>

<script>
import {
  MDBAccordion,
  MDBAccordionItem,
  // MDBCard,
  MDBListGroup,
  MDBListGroupItem,
  MDBCol,
  MDBRow,
}
  from
      "mdb-vue-ui-kit";

export default {
  name: "Jobs",
  components: {
    MDBAccordion,
    MDBAccordionItem,
    // MDBCard,
    MDBListGroup,
    MDBListGroupItem,
    MDBCol,
    MDBRow
  },
};
</script>