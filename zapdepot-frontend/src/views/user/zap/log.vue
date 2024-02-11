<template>
  <div>
    <loader :active="loader" />
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>Logs</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">
                <a href="#">Home</a>
              </li>
              <li class="breadcrumb-item active">Logs</li>
            </ol>
          </div>
        </div>
      </div>
      <!-- /.container-fluid -->
    </section>
    <section class="content">
      <div class="container-fluid">
        <div class="row" v-if="getZapLog.data">
          <div class="col-md-12">
            <div class="card card-primary card-outline">
              <div class="card-header">
                <div class="row">
                  <div class="col-md-6">
                    <div class="mail_box">
                      <!-- <div class="mail_icon"></div> -->
                    </div>
                    <div>
                      <zapSearch
                        @filterzap="getfilter"
                        :zapdata="ZapAllData"
                      ></zapSearch>
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
                          <th>First Name</th>
                          <th>Last Name</th>
                          <th>Email</th>
                          <th>Zap</th>
                          <th>Date & Time</th>
                        </tr>
                      </thead>
                      <tbody v-if="getZapLog.data && getZapLog.data.length">
                        <tr
                          v-for="(data, index) in getZapLog.data"
                          :key="index"
                        >
                          <td>{{ ++index }}</td>
                          <td>{{ data.firstname }}</td>
                          <td>{{ data.lastname }}</td>
                          <!-- <td>{{ data.id }}</td> -->
                          <td>{{ data.email }}</td>
                          <td>{{data.zap_name}}</td>
                          <td>{{format_date(data.created_at)}}</td>
                        </tr>
                      </tbody>
                      <tbody v-else>
                         <tr>
                           <td colspan="6" class="text-center">No Data Found</td>
                         </tr>
                      </tbody>
                    </table>
                    <div class="d-flex justify-content-center text-center">
                      <pagination
                        v-model="page"
                        :records="getZapLog.total"
                        :per-page="getZapLog.per_page"
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
import zapSearch from "@/components/search/ZapSearch.vue";
import moment from 'moment';
export default defineComponent({
  setup() {
    const toast = useToast();
    return {
      toast,
    };
  },
  components: {
    zapSearch,
  },
  data() {
    return {
      page: 1,
      search: null,
      ZapAllData: "",
    };
  },
  computed: {
    ...mapGetters({}),
    ...mapState("zap", ["getZapLog"]),
  },
  methods: {
    ...mapActions({
      getAll: "zap/logsGet",
      getAllZapAccounts: "zap/getAllZapAccounts",
    }),
    format_date(value){
        if (value) {
        return moment(String(value)).format('DD-MM-YYYY H:s a')
        }
    },
    getfilter(data_) {
      this.search = data_;

      //  this.getAllZapAccounts(data)
      this.getAll({ parmas: `?page=${this.page}&search=${data_}` });
    },

    myCallback(page) {
      this.getAll({ parmas: `?page=${page}&search=${this.search}` });
    },
  },
  created() {
    this.getAllZapAccounts(this.page, this.search).then((resp) => {
      if (resp.data.msg == 200) {
        this.ZapAllData = resp.data.data.data;
      }
    });
  },
  mounted() {
    this.getAll({ parmas: `?page=${this.page}` });
  },
});
</script>
