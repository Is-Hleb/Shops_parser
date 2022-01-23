<template>
  <MDBContainer>
    <MDBTabs v-model="activeTabId4" vertical>
      <!-- Tabs navs -->
      <MDBTabNav class="shadow-2-soft" pills tabsClasses="mb-3 text-center">
        <MDBTabItem :wrap="false" tabId="ex4-1" href="/">Jobs</MDBTabItem>
        <MDBTabItem :wrap="false" tabId="ex4-2" href="ex4-2">Logs</MDBTabItem>
        <MDBTabItem :wrap="false" tabId="ex4-3" href="ex4-3">ActiveJobs</MDBTabItem>
      </MDBTabNav>
      <!-- Tabs navs -->
      <!-- Tabs content -->
      <MDBTabContent>
        <router-view class="px-3 shadow-2-soft h-100"></router-view>
      </MDBTabContent>
      <!-- Tabs content -->
    </MDBTabs>
  </MDBContainer>
</template>
<script>
import {
  MDBTabs,
  MDBTabNav,
  MDBTabContent,
  MDBTabItem,
  MDBContainer
} from 'mdb-vue-ui-kit';
import {ref} from 'vue';

export default {
  components: {
    MDBTabs,
    MDBTabNav,
    MDBTabContent,
    MDBTabItem,
  MDBContainer
  },
  setup() {
    const activeTabId4 = ref('ex4-1');
    return {
      activeTabId4,
    };
  },
};
</script>