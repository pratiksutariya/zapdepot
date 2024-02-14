<template>
  <div>
    <loader :active="loader" />
    <div class="card-body py-3">
      <div class="table-responsive">
        <table class="table table-bordered">
          <thead>
            <tr>
              <th style="width: 50px" class="text-center">#</th>
              <th>Log Details</th>
              <th style="width: 300px" class="text-center">Date Time</th>
            </tr>
          </thead>
          <tbody v-if="zapLog && zapLog.data && zapLog.data.length">
            <tr v-for="(item , index) in zapLog.data" :key="index">
              <td class="text-center">{{item.id}}</td>
              <td>{{item.detail}}</td>
              <td class="text-center">{{moment(item.created_at).format("YYYY-MM-DD , HH:MM a")}}</td>
            </tr>
          </tbody>
          <tbody v-else>
            <tr>
              <td colspan="3" class="text-center"> No Logs Found </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>
<script>
import { defineComponent } from "vue";
import { mapActions , mapState } from "vuex";
import moment from 'moment'
import useVuelidate from "@vuelidate/core";
// import { required } from "@vuelidate/validators";
import { useToast } from "vue-toastification";
// import Model from "@/components/model.vue";
// import LaravelVuePagination from 'laravel-vue-pagination';
export default defineComponent({
  setup() {
    const toast = useToast();
    return { toast, v$: useVuelidate() };
  },
  props : ['id'],
  data() {
    return {
      loader: false,
      moment : moment,
      timer : null
    };
  },
  components: {},
  mounted() {
    this.fetchZapLogs(this.id)
    
  },
  computed: {
    ...mapState('zap' , ['zapLog'])
  },
  methods: {
    ...mapActions('zap' , ['fetchZapLogs']),
  },
  created() {
    this.moment = moment
  },
});
</script>

