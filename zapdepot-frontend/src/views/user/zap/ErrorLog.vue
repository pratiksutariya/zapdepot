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
        <div class="row">
          <div class="col-md-12">
            <div class="card card-primary card-outline">
              <div class="card-header">
                <div class="row">
                  <div class="col-md-6">
                    <div class="mail_box"></div>
                    <div>
                      <div class="mr-2" role="group" aria-label="First group">
                        
                        <button
                          type="button"
                          class="btn btn-secondary ml-2"
                          title="Clear All Logs"
                          @click="clearErrorLogs()"
                        >
                          <i class="nav-icon fas fa-trash"></i>
                        </button>
                        
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6 text-right">
                    Refresh Logs In <span> 00 </span> : <span> {{ timerCount }}</span> Seconds
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
                          <th>Error</th>
                          <th style="width: 20%">Date & Time</th>
                          <th style="width: 5%">Zap ID</th>
                        </tr>
                      </thead>
                      <tbody v-if="errorLog.data && errorLog.data.length">
                        <tr v-for="(data, index) in errorLog.data" :key="index">
                          <td>{{ ++index }}</td>
                          <td>{{ data.integration_type }}</td>
                          <td>{{ data.error_log }}</td>
                          <td>{{ format_date(data.created_at) }}</td>
                          <td>{{ data.zap_id }}</td>
                        </tr>
                      </tbody>
                      <tbody v-else>
                        <tr>
                          <td colspan="6" class="text-center">No Data Found</td>
                        </tr>
                      </tbody>
                    </table>
                    <div
                      v-if="errorLog.data"
                      class="d-flex justify-content-center text-center"
                    >
                      <pagination
                        v-model="page"
                        :records="errorLog.total"
                        :per-page="errorLog.per_page"
                        @paginate="myCallback"
                      />
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
import moment from "moment";
export default defineComponent({
  setup() {
    const toast = useToast();
    return {
      toast,
    };
  },
  components: {},
  data() {
    return {
      page: 1,
      timerEnabled: true,
      timerCount: 20,
    };
  },
  watch: {
    timerEnabled(value) {
      if (value) {
        setTimeout(() => {
          this.timerCount--;
        }, 1000);
      } else {
        this.error_log({ parmas: `?page=${this.page}` }).then((resp) => {
          if (resp.data.status) {
            this.timerEnabled = true;
            this.timerCount = 20;
          }
        });
      }
    },

    timerCount: {
      handler(value) {
        if (value > 0 && this.timerEnabled) {
          setTimeout(() => {
            this.timerCount--;
          }, 1000);
        } else {
          this.timerEnabled = false;
        }
      },
      immediate: true, // This ensures the watcher is triggered upon creation
    },
  },
  computed: {
    ...mapGetters("", []),
    ...mapState("zap", ["errorLog"]),
  },
  mounted() {
    this.error_log({ parmas: `?page=${this.page}` });
  },
  created() {},
  methods: {
    ...mapActions({
      error_log: "zap/errorLog",
      clear_error_log: "zap/clearErrorLog",
    }),
    myCallback(page) {
      this.error_log({ parmas: `?page=${page}` });
    },
    format_date(value) {
      if (value) {
        return moment(String(value)).format("DD-MM-YYYY H:s a");
      }
    },
    clearErrorLogs() {
      this.clear_error_log({ parmas: `?page=1` }).then((resp) => {
        if (resp.data.msg == 200) {
          console.log(true)
        }
      });
    }
  },
});
</script>
