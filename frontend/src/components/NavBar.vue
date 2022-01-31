<template>
  <MDBContainer>
    <MDBRow>
      <MDBCol col="2" class="px-0">
        <MDBRow class="px-0">
          <MDBContainer class="content-container px-0">
            <!-- Tabs navs -->
            <MDBListGroup>
              <MDBListGroupItem class="rounded-0" action :active="currentRouteName === 'jobs'" style="cursor: pointer"
                                @click="$router.push({name: 'jobs'})">
                Задачи
              </MDBListGroupItem>
              <MDBListGroupItem class="rounded-0" action :active="currentRouteName === 'settings'"
                                style="cursor: pointer"
                                @click="$router.push({name: 'settings'})">
                Параметры
              </MDBListGroupItem>
              <hr>
              <MDBListGroupItem class="rounded-0" action :active="currentRouteName === 'job-create'"
                                style="cursor: pointer"
                                @click="$router.push({name: 'job-create'})">
                Добавить задачу
              </MDBListGroupItem>
              <MDBListGroupItem class="rounded-0" action :active="currentRouteName === 'job-template-create'"
                                style="cursor: pointer"
                                @click="$router.push({name: 'job-template-create'})">
                Добавить Шаблон для задачи
              </MDBListGroupItem>
              <MDBListGroupItem class="rounded-0" action :active="currentRouteName === 'setting-create'"
                                style="cursor: pointer"
                                @click="$router.push({name: 'setting-create'})">
                Добавить параметр
              </MDBListGroupItem>
            </MDBListGroup>
          </MDBContainer>
        </MDBRow>
      </MDBCol>

      <MDBCol col="10">
        <MDBContainer class="content-container">
          <router-view v-slot="{ Component }">
            <keep-alive>
              <component :is="Component"/>
            </keep-alive>
          </router-view>
        </MDBContainer>
      </MDBCol>
    </MDBRow>
  </MDBContainer>
</template>
<script>
import {
  MDBContainer,
  MDBListGroup,
  MDBListGroupItem,
  MDBRow,
  MDBCol
} from 'mdb-vue-ui-kit';


export default {
  components: {
    MDBContainer,
    MDBListGroup,
    MDBListGroupItem,
    MDBRow,
    MDBCol
  },
  computed: {
    currentRouteName() {
      return this.$route.name;
    }
  },
};
</script>

<style>
.content-container {
  margin-top: 40px;
}
</style>