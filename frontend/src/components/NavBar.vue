<template>
  <MDBContainer>
    <MDBTabs class="mt-5 shadow-2-soft" vertical>
      <!-- Tabs navs -->
      <MDBTabNav class="border border-right" tabsClasses="mb-3 text-center">
        <MDBNavbarItem :active="currentRouteName === 'jobs'" :to="{name: 'jobs'}">
          Задачи
        </MDBNavbarItem>
        <MDBNavbarItem :active="currentRouteName === 'settings'" :to="{name: 'settings'}">
          Параметры
        </MDBNavbarItem>
        <MDBNavbarItem :active="currentRouteName === 'job-create'" :to="{name: 'job-create'}">
          Добавить задачу
        </MDBNavbarItem>
        <MDBNavbarItem :active="currentRouteName === 'setting-create'" :to="{name: 'setting-create'}">
          Добавить параметр
        </MDBNavbarItem>
      </MDBTabNav>


      <MDBTabContent>
        <router-view/>
      </MDBTabContent>

    </MDBTabs>
  </MDBContainer>
</template>
<script>
import {
  MDBTabs,
  MDBTabNav,
  MDBTabContent,
  //MDBTabItem,
  MDBContainer,
  MDBNavbarItem
  // MDBTabPane
} from 'mdb-vue-ui-kit';

// import Jobs from "@/views/Jobs";
// import AddJobForm from "@/components/AddJobForm";

export default {
  components: {
    MDBTabs,
    MDBTabNav,
    MDBTabContent,
    //MDBTabItem,
    MDBContainer,
    MDBNavbarItem
    // MDBTabPane,
    // Jobs,
    // AddJobForm
  },
  computed: {
    currentRouteName() {
      return this.$route.name;
    }
  },

};
</script>