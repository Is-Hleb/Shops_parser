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
          Полное имя класса задачи, например <strong>App\ECatalog</strong>
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
    <MDBTextarea
        label="Описание"
        class="mb-3"
        style="min-height: 100px"
        v-model="jobTemplate.description"
    />
    <MDBRow class="mb-4">
      <p>
        Создавать <b>множество</b> процессов, если переданно большое количество данных
        (иначе процесс будет запускаться с одним параметром в виде массива)
      </p>
      <div>
        <MDBCheckbox
            label="Создавать?"
            @change="jobTemplate.isArrayInput = !jobTemplate.isArrayInput"
        />
      </div>
      <p>
        Если "ДА", то будет создано большое количество задач вида <strong>имя1, имя2, имя3...</strong>
      </p>
    </MDBRow>

    <MDBBtn @click="createJobTemplate" class="w-100 btn btn-primary">Добавить шаблон</MDBBtn>
    <vue-basic-alert
        :duration="500"
        :closeIn="2500"
        ref="alert"
    />
  </MDBContainer>
</template>

<script>
import {
  MDBInput,
  MDBContainer,
  MDBRow,
  MDBBtn,
  MDBTextarea,
  MDBCheckbox
} from "mdb-vue-ui-kit"
import VueBasicAlert from "vue-basic-alert";

const axios = require('axios');

export default {
  data() {
    return {
      jobTemplate: {
        name: "",
        class: "",
        method: "",
        description: "",
        isArrayInput: true,
      }
    }
  },
  components: {
    MDBInput,
    MDBContainer,
    MDBRow,
    MDBBtn,
    MDBTextarea,
    MDBCheckbox,
    VueBasicAlert
  },
  methods: {
    createJobTemplate() {
      axios.post('/api/job/template', {jobTemplate: this.jobTemplate}).then(() => {
        this.$refs.alert.showAlert(
            'info', // There are 4 types of alert: success, info, warning, error
            `Шаблон ${this.jobTemplate.name} создан успешно`, // Message of the alert
            'Настройка создана', // Header of the alert
            {
              iconSize: 15, // Size of the icon (px)
              iconType: 'solid', // Icon styles: now only 2 styles 'solid' and 'regular'
              position: 'top right'
            }
        );
      }).then(() => {
        this.$router.go({name: 'job-create'})
      })
    }
  }
}
</script>