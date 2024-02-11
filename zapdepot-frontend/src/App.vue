<template>
    <!-- <master-layout v-if="isAuthenticated">
        <router-view/>
    </master-layout>
    <router-view v-else/> -->
    <router-view/>
</template>

<script>
import { defineComponent } from 'vue';
import { mapGetters } from "vuex";
// import MasterLayout from '@/components/layout/master.vue'
export default defineComponent({
  components:{
    // MasterLayout
  },
  name: 'App',
  computed: {
        ...mapGetters({
            isAuthenticated:"auth/authenticated"
        }),
    },
  created() {

  },
});
</script>
