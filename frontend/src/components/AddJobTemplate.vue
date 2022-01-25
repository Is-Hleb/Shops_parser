<template>
  <MDBContainer>
    <MDBRow>
      <div class="col-4">
        <MDBInput
            type="text"
            label="Название шаблона"
            v-model="jobTemplate.name"
            wrapper-class="px-2"
        />
        <p class="mt-2">
          Создавая задачу вы будете видеть указанное здесь имя шаблона
        </p>
      </div>
      <div class="col-4">
        <MDBInput
            type="text"
            label="Имя класса"
            v-model="jobTemplate.class"
            wrapper-class="px-2"
        />
        <p class="mt-2">
          Полное имя класс задача, например <strong>App\ECatalog</strong>
        </p>
      </div>
      <div class="col-4">
        <MDBInput
            type="text"
            label="Имя метода"
            v-model="jobTemplate.method"
            wrapper-class="px-2"
        />
        <p class="mt-2">
          Название метода класса, который необходимо выполнить в фоне
        </p>
      </div>
    </MDBRow>
    <MDBBtn @click="createJobTemplate" class="w-100 btn btn-primary">Добавить шаблон</MDBBtn>
  </MDBContainer>
</template>

<script>
import {
  MDBInput,
  MDBContainer,
  MDBRow,
  MDBBtn
} from "mdb-vue-ui-kit"

export default {
  data() {
    return {
      jobTemplate: {
        name: "",
        class: "",
        method: "",
      }
    }
  },
  components: {
    MDBInput,
    MDBContainer,
    MDBRow,
    MDBBtn
  },
  methods: {
    createJobTemplate() {
      console.log(this.jobTemplate)
    }
  }
}
</script>