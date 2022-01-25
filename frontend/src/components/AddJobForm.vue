<template>
  <form>
    <MDBContainer>
      <MDBRow class="w-100">
        <MDBCol col="4" class="px-2">
          <MDBInput
              type="text"
              label="Название задачи"
              v-model="job.name"
              wrapper-class="mb-3"
              class="p-2"
              helper="Если поле пустое, название будет рандомным."
          />
        </MDBCol>
      </MDBRow>
    </MDBContainer>
    <MDBContainer>
      <MDBBtn @click="createJob" color="primary mt-5" block> Добавить задачу</MDBBtn>
    </MDBContainer>
  </form>
</template>
<script>
import {
  MDBRow,
  MDBCol,
  MDBInput,
  MDBContainer
  // MDBCheckbox,
  // MDBBtn,
  // MDBDropdown,
  // MDBDropdownToggle,
  // MDBDropdownMenu,
  // MDBDropdownItem
} from "mdb-vue-ui-kit";
import axios from "axios";


export default {
  components: {
    MDBRow,
    MDBCol,
    MDBInput,
    MDBContainer
    // MDBCheckbox,
    // MDBCheckbox,
    // MDBBtn,
    // MDBDropdown,
    // MDBDropdownToggle,
    // MDBDropdownMenu,
    // MDBDropdownItem

  },
  data() {
    return {
      job: {
        name: "",
        externalData: "",
        class: "",
        method: "",
      },
      settings: [],
      listActive: false,
    }
  },
  methods: {
    loadSettings() {
      axios.get('/api/settings').then(r => {
        let data = r.data.data.settings, output = [];

        for (let i = 0; i < data.length; i++) {
          output.push({
            name: data[i].name,
            value: data[i].value,
            collection: data[i].collection,
            id: data[i].id,
            key: data[i].id,
            selected: false,
            onEdit: false,
          });
        }

        this.settings = output;
      });
    },
    createJob() {

    },
  },
  beforeMount() {
    this.loadSettings();
  }
};
</script>