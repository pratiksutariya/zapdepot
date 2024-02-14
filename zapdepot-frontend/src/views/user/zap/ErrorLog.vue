<template>
  <div>
    <loader :active="loader" />
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Errors Logs</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">
                <a href="#">Home</a>
              </li>
              <li class="breadcrumb-item active">Error - Logs</li>
            </ol>
          </div>
        </div>
      </div>
    </section>
    <section class="content">
      <div class="container-fluid">
        <div class="row" >
          <div class="col-md-12">
            <div class="card card-primary card-outline">
              <div class="card-header">
                <div class="row">
                  <div class="col-md-6">
                    <div class="mail_box">
                    </div>
                    <div>
                      
                    </div>
                  </div>
                </div>
                <hr />
                <!-- /.card-header -->
                <div class="card-body py-0">
                  <div class="table-responsive">
                    <table class="table table-bordered">
                      <thead>
                        <tr>
                          <th style="width: 10px">#</th>
                          <th style="width: 20%">Integration Type</th>
                          <th >Error</th>
                          <th style="width: 20%">Date & Time</th>
                          <th style="width: 5%">Zap ID</th>
                        </tr>
                      </thead>
                      <!-- <tbody v-if="getZapLog.data && getZapLog.data.length">
                        <tr
                          v-for="(data, index) in getZapLog.data"
                          :key="index"
                        >
                          <td>{{ ++index }}</td>
                          <td>{{ data.firstname }}</td>
                          <td>{{ data.lastname }}</td>
                          <td>{{ data.email }}</td>
                          <td>{{ data.zap_name }}</td>
                          <td>{{ format_date(data.created_at) }}</td>
                        </tr>
                      </tbody>
                      <tbody v-else>
                        <tr>
                          <td colspan="6" class="text-center">No Data Found</td>
                        </tr>
                      </tbody> -->
                    </table>
                    <div class="d-flex justify-content-center text-center">
                      <!-- <pagination
                        v-model="page"
                        :records="getZapLog.total"
                        :per-page="getZapLog.per_page"
                        @paginate="myCallback"
                      /> -->
                    </div>
                  </div>
                </div>
                <!-- /.card-body -->
              </div>
              <!-- /.card -->
              <!-- /.card -->
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
<script>
import { defineComponent } from "vue";
import { mapGetters, mapActions, mapState } from "vuex";
import { useToast } from "vue-toastification";
// import moment from "moment";
export default defineComponent({
  setup() {
    const toast = useToast();
    return {
      toast,
    };
  },
  components: {
  },
  data() {
    return {
      page: 1,
    };
  },
  computed : {
    ...mapGetters("",[]),
    ...mapState("",[])
  },
  mounted() {

  },
  created() {

  },
  methods : {
    ...mapActions({})
  }
});
</script>
