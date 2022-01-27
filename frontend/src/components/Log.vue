<template>

    <MDBContainer>
      <MDBRow>
        <MDBCol col="6">
          <h4>Переданные данные</h4>
          <MDBListGroup class="border border-1" flush>
            <MDBListGroupItem action>Category url</MDBListGroupItem>
            <MDBListGroupItem action>Dapibus ac facilisis in</MDBListGroupItem>
            <MDBListGroupItem action>Vestibulum at eros</MDBListGroupItem>
            <MDBListGroupItem action>Category url</MDBListGroupItem>
            <MDBListGroupItem action>Dapibus ac facilisis in</MDBListGroupItem>
            <MDBListGroupItem action>Vestibulum at eros</MDBListGroupItem>
          </MDBListGroup>
        </MDBCol>
        <MDBCol col="6">
          <h4>Логи</h4>
          <MDBListGroup class="border border-1" flush>
            <MDBListGroupItem class="bg-primary text-white" action>info log</MDBListGroupItem>
            <MDBListGroupItem action>default log</MDBListGroupItem>
            <MDBListGroupItem class="bg-warning" action>Error log</MDBListGroupItem>
          </MDBListGroup>
        </MDBCol>
      </MDBRow>

      <MDBRow class="p-2 border-top mt-3">
        <MDBCol class="col-4 shadow-4-soft p-3 border bg-light" style="display: inline-grid">
          <h4>Результат работы</h4>
          <MDBListGroup flush>
            <MDBListGroupItem class="border-bottom" action>Category url</MDBListGroupItem>
            <MDBListGroupItem class="border-bottom" action>Dapibus ac facilisis in</MDBListGroupItem>
            <MDBListGroupItem class="border-bottom" action>Vestibulum at eros</MDBListGroupItem>
          </MDBListGroup>
          <MDBRow class="">
            <MDBCol class="p-0">
              <MDBBtn class="m-0 w-100 btn-primary border-0 rounded-0">Запустить</MDBBtn>
            </MDBCol>
            <MDBCol class="p-0">
              <MDBBtn class="m-0 w-100 btn-danger border-0 rounded-0">Удалить</MDBBtn>
            </MDBCol>
          </MDBRow>
        </MDBCol>
        <MDBCol class="col-8 shadow-4-soft p-3 border-bottom">
          <h4>Лог файл</h4>
          <div>
            <p>
              log file text log file text lg log file text log file text lg log file text log file text lg
              log file text log file text lg log file text log file text lg log file text log file text lg
              log file text log file text lg log file text log file text lg log file text log file text lg
              log file text log file text lg log file text log file text lg log file text log file text lg
              log file text log file text lg log file text log file text lg log file text log file text lg
              log file text log file text lg log file text log file text lg log file text log file text lg
              log file text log file text lg log file text log file text lg log file text log file text lg
              log file text log file text lg log file text log file text lg log file text log file text lg
              log file text log file text lg log file text log file text lg log file text log file text lg
              log file text log file text lg log file text log file text lg log file text log file text lg
              log file text log file text lg log file text log file text lg log file text log file text lg
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
from"mdb-vue-ui-kit";

export default {
  name: 'Log',
  components: {
    MDBListGroup,
    MDBListGroupItem,
    MDBCol,
    MDBRow,
    MDBContainer,
    MDBBtn
  },
  props: {
    id: null,
  },
}
</script>
