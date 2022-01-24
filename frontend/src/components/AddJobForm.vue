<template>
  <form>
    <MDBRow class="w-100">
      <MDBCol col="6">
        <MDBInput
            type="text"
            label="Название задачи"
            v-model="jobName"
            wrapper-class="mb-3"
            class="p-2"
            helper="Если поле пустое, название будет рандомным."
        />
      </MDBCol>
      <MDBCol col="6">
        <div class="form-outline">
          <MDBDropdown class="w-100" v-model="dropdown1">
            <MDBDropdownToggle class="w-100 m-0" @click="dropdown1 = !dropdown1">
              Входные данные или коллекция
            </MDBDropdownToggle>
            <MDBDropdownMenu class="w-100">
              <MDBDropdownItem class="w-100" tag="div">
                <MDBCheckbox
                    title="Title"
                    name="anotherData"
                    label="Label"
                    v-model="anotherData['anotherData']"
                />
              </MDBDropdownItem>
              <MDBDropdownItem tag="checkbox">
                <MDBCheckbox
                    title="Title"
                    name="anotherData1"
                    label="Label"
                    v-model="anotherData['anotherData1']"
                />
              </MDBDropdownItem>
              <MDBDropdownItem tag="checkbox">
                <MDBCheckbox
                    title="Title"
                    label="Label"
                    name="anotherData2"
                    v-model="anotherData['anotherData2']"
                />
              </MDBDropdownItem>
            </MDBDropdownMenu>
          </MDBDropdown>
          <div class="form-helper">Если входных данных больше, чем требует задача, будет создано несколько задач</div>
        </div>
      </MDBCol>
    </MDBRow>


    <MDBBtn color="primary mt-5" block> Добавить задачу</MDBBtn>
  </form>
</template>
<script>
import {
  MDBRow,
  MDBCol,
  MDBInput,
  MDBCheckbox,
  MDBBtn,
  MDBDropdown,
  MDBDropdownToggle,
  MDBDropdownMenu,
  MDBDropdownItem
} from "mdb-vue-ui-kit";


export default {
  components: {
    MDBRow,
    MDBCol,
    MDBInput,
    MDBCheckbox,
    // MDBCheckbox,
    MDBBtn,
    MDBDropdown,
    MDBDropdownToggle,
    MDBDropdownMenu,
    MDBDropdownItem

  },
  data() {
    return {
      jobName: "",
      dropdown1: false,
      selectedOption: "",
      anotherData: {}
    }
  },

};
</script>