<template>
  <div>
    <loader :active="loader" />
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <!-- <h1>Category </h1> -->
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item">
                <a href="https://zapdepot.io">Home</a>
              </li>
              <li class="breadcrumb-item active">Zaps</li>
            </ol>
          </div>
        </div>
      </div>
      <!-- /.container-fluid -->
    </section>
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-primary card-outline">
              <div class="card-header">
                <div class="row">
                  <div class="col-md-6">
                    <div class="mail_box">
                      <!-- <div class="mail_icon"></div> -->
                    </div>
                    <div
                      style="
                        display: inline-block;
                        position: absolute;
                        margin-left: 2%;
                        top: 8%;
                      "
                    >
                      <h2 style="margin: 0">My Zaps</h2>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="float-right mr-3">
                      <a
                        type="button"
                        href="javascript:void(0)"
                        @click="showzapmodal()"
                        class="btn btn-block btn-outline-primary"
                        ><i class="fas fa-plus pr-2"></i>Add Zap</a
                      >
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
                          <th style="width: 15px">Status</th>
                          <th>Name</th>
                          <th style="width: 160px"></th>
                          <th style="width: 15px">Connection</th>
                          <th style="width: 100px">Action</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr
                          class=""
                          v-for="(account, index) in zapAccounts"
                          :key="account.id"
                          style="width: 100%"
                        >
                          <td >Zap_{{ account.id }}</td>
                          <td class="d-none">{{index+1}}</td>
                          <td>
                            <label class="switch">
                              <input
                                type="checkbox"
                                v-model="account.status"
                                :disabled="
                                  account.receiver_id && account.sender_id
                                    ? false
                                    : true
                                "
                                @click="
                                  updatestatus(account.status, account.id)
                                "
                              />
                              <span class="slider round"></span>
                            </label>
                          </td>
                          <td style="border-right:0px">
                            <router-link
                              :to="{
                                name: 'ZapsDetail',
                                params: { id: account.id },
                              }"
                            >
                              {{ account.name }}
                            </router-link>

                          </td>
                          <td class="ring-container" style="border-left:0px">
                            <div v-if="account.data_transfer_status == 1">
                                <div style="display: inline-block;">
                                    <div class="ringring"></div>
                                    <div class="circle"></div>
                                    <p style="font-size: 12px; margin: 0 0 0 10px;">We are processing data...</p>
                                </div>
                            </div>
                            <!-- <div v-else>
                              <div class=""></div>
                              <div class="circle--2"></div>
                            </div> -->
                          </td>

                          <td style="text-align:center">
                            <img
                              v-if="account.sender_name == 'gohighlevel'"
                              class="img-set mr-1"
                              width="23"
                              height="23"
                              src="@/assets/images/ready-funnels-small.png"
                            />
                            <img
                            v-else-if="account.sender_name == 'aweber'"
                              class="img-set mr-1"
                              width="23"
                              height="23"
                              src="@/assets/images/aweber_2.png"
                            />
                            <img
                              v-else-if="
                                account.sender_name == 'active_campaign'
                              "
                              class="img-set mr-1"
                              width="23"
                              height="23"
                              src="@/assets/images/activec.png"
                            />
                            <img
                              v-else-if="
                                account.sender_name == 'gohighlevel_single'
                              "
                              class="img-set mr-1"
                              width="23"
                              height="23"
                              src="@/assets/images/gohighlevel.png"
                            />
                            <img
                              v-else-if="
                                account.sender_name == 'webinar_account'
                              "
                              class="img-set mr-1"
                              width="23"
                              height="23"
                              src="@/assets/images/webinar.png"
                            />
                            <img
                              v-else-if="account.sender_name == 'google_sheet'"
                              class="img-set mr-1"
                              width="23"
                              height="23"
                              src="@/assets/images/Sheets.png"
                            />
                            <img
                              v-if="account.receiver_name == 'gohighlevel'"
                              class="img-set ml-1"
                              width="23"
                              height="23"
                              src="@/assets/images/ready-funnels-small.png"
                            />
                            <img
                            v-else-if="account.receiver_name == 'aweber'"
                              class="img-set mr-1"
                              width="23"
                              height="23"
                              src="@/assets/images/aweber_2.png"
                            />
                            <img
                              v-else-if="
                                account.receiver_name == 'active_campaign'
                              "
                              class="img-set ml-1"
                              width="23"
                              height="23"
                              src="@/assets/images/activec.png"
                            />
                            <img
                              v-else-if="
                                account.receiver_name == 'gohighlevel_single'
                              "
                              class="img-set ml-1"
                              width="23"
                              height="23"
                              src="@/assets/images/gohighlevel.png"
                            />
                            <img
                              v-else-if="
                                account.receiver_name == 'webinar_account'
                              "
                              class="img-set ml-1"
                              width="23"
                              height="23"
                              src="@/assets/images/webinar.png"
                            />
                            <img
                              v-else-if="
                                account.receiver_name == 'google_sheet'
                              "
                              class="img-set ml-1"
                              width="23"
                              height="23"
                              src="@/assets/images/Sheets.png"
                            />
                            <img
                              v-else-if="
                                account.receiver_name == 'web_hook'
                              "
                              class="img-set ml-1"
                              width="23"
                              height="23"
                              src="@/assets/images/Sheets.png"
                            />
                          </td>
                          <td>
                            <button
                              class="btn btn-danger delete-btn"
                              @click="deleteCheck(account.id)"
                              style="padding: 0px 4px 0px 4px !important"
                            >
                              <i class="fas fa-trash-alt"></i>
                            </button>
                            <button
                              class="btn btn-info ml-2"
                              @click="logsPage(account.id)"
                              style="padding: 0px 4px 0px 4px !important"
                            >
                              <i class="fa-solid fa-clock-rotate-left"></i>
                            </button>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <!-- {{zapAccounts.length }} -->
                    <!-- {{ "sdfdf=[======="+ $store.state.zap.getZapAll }} -->

                    <!-- <v-pagination
                        :show-disabled="true"
                        size="small"
                        :pages="this.getZapAccounts.last_page"
                        :range-size="this.getZapAccounts.per_page"
                        active-color="#DCEDFF"
                        @click="getResults"
                    /> -->
                     <pagination
                        v-if="getZapAccounts"
                        v-model="page"
                        :records="parseInt(getZapAccounts.total)"
                        :per-page="parseInt(getZapAccounts.per_page)"
                        @paginate="getResults"
                      />
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
    <Model :modaltitle="modaltitle" @close="CloseModel()" v-if="showmodal">
      <template #body>
        <form @submit.prevent="addzap()">
          <div class="modal-body">
            <div class="form-group">
              <label for="prodcut_name">Zap Name</label>
              <input
                class="form-control"
                placeholder="Enter Zap Name"
                :class="{
                  'is-invalid': v$.zap.name.$errors.length,
                }"
                v-model="v$.zap.name.$model"
              />
              <span
                class="invalid-feedback"
                role="alert"
                v-for="(error, index) of v$.zap.name.$errors"
                :key="index"
              >
                <strong>{{ error.$message }}</strong>
              </span>
            </div>
          </div>
          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Save</button>
          </div>
        </form>
      </template>
    </Model>

  </div>
</template>
<script>
import { defineComponent } from "vue";
import { mapGetters, mapActions } from "vuex";
import useVuelidate from "@vuelidate/core";
import { required } from "@vuelidate/validators";
import { useToast } from "vue-toastification";
import Model from "@/components/model.vue";

export default defineComponent({
  setup() {
    const toast = useToast();
    return { toast, v$: useVuelidate() };
  },
  data() {
    return {
      current_page: 1,
      page:1,
      loader: false,
      modaltitle: "Add Zap",
      showmodal: false,
      zap: {
        name: "",
      },
    };
  },
  validations() {
    return {
      zap: {
        name: { required },
      },
    };
  },
  components: {
    Model,
  },
  computed: {
    ...mapGetters({
      getZapAccounts: "zap/getZapAccounts",
    }),
    zapAccounts() {
      if (this.getZapAccounts && this.getZapAccounts.data) {

        return this.getZapAccounts.data.map((data) => ({
            ...data,
          status: !!data.status,
        }));
      } else {
        return [];
      }
    },
  },
  methods: {
    ...mapActions({
      getAllZapAccounts: "zap/getAllZapAccounts",
      addZap: "zap/addZap",
      deleteZap: "zap/deleteZap",
      updateZapStatus: "zap/updateZapStatus",
    }),
    status_convert() {
      return (value) => {
        if (value == 0) {
          return false;
        } else {
          return true;
        }
      };
    },
    myCallback(page) {
       console.log(this.getZapAccounts);
       console.log(page);
    //   this.getAll({ parmas: `?page=${page}&search=${this.search}` });
    },
    onChangePage(){
           console.log("onChangePage",this.getZapAccounts);
        // this.setCurrentPage("https://api.zapdepot.io/api/get-all-zaps?page="+(direction + 1));
        // if (direction === -1 && this.currentPage > 1) {
        //     this.currentPage -= 1
        // } else if (direction === 1 && this.current_page < this.getZapAccounts.length / this.perPage) {
        //     this.currentPage += 1
        // }
    },
    CloseModel() {
      this.showmodal = false;
    },
    getResults(page = 1) {
      console.log(this.getZapAccounts);
      this.loader = true;
      this.current_page = page;
    //   this.last_page = page;
    //   this.total = page;
      console.log("total"+this.getZapAccounts);
      this.getAllZapAccounts(page)
        .then(() => {
          this.loader = false;
        })
        .catch(() => {
          this.loader = false;
        });
    },
    showzapmodal() {
      (this.zap = {
        name: "",
      }),
        (this.showmodal = true);
      this.v$.zap.$reset();
    },
    addzap() {
      this.v$.zap.$touch();
      if (this.v$.zap.$invalid) {
        return;
      }
      this.loader = true;
      this.addZap(this.zap)
        .then((resp) => {
          this.showmodal = false;
          this.loader = false;
          this.getResults(this.current_page);
          this.toast.success("Zap added successfully.");
          this.$router.push({ name: 'ZapsDetail' ,params: { id: resp.data.data.id }})
        })
        .catch((error) => {
          this.loader = false;
          this.toast.error(error.response.data.message);
        });
    },
    logsPage(id) {
      this.$router.push({
        name: "LogLists",
        params: { id: id },
      });
    },
    deleteCheck(id) {
      // this.$swal('Hello Vue world!!!');
      this.$swal
        .fire({
          title: "Are you sure?",
          text: "You won't be able to revert this!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Yes, delete it!",
        })
        .then((result) => {
          if (result.isConfirmed) {
            this.deleteZap(id)
              .then((response) => {
                if (response.data.status == true) {
                  this.$swal.fire(
                    "Deleted!",
                    "Zap has been deleted.",
                    "success"
                  );
                  this.getResults(this.current_page);
                }
              })
              .catch((error) => {
                this.toast.error(error.response.data.message);
              });
          }
        });
    },
    async updatestatus(status, id) {
      let newstatus = !status;
      let data = {
        id: id,
        status: newstatus == true ? 1 : 0,
      };
      await this.updateZapStatus(data)
        .then((response) => {
          if (response.data.status == true) {
            this.toast.success("Status updated");
            this.getResults(this.current_page);

          }
        })
        .catch((error) => {
          this.toast.error(error.response.data.message);
        });
    },
  },
  created() {
    this.getResults();
  },
//   mounted() {
//     this.getAll({ parmas: `?page=${this.page}` });
//   },
});
</script>
<style scoped>
input:checked + .slider{
  background-color: #f7513b !important;
}
.card-primary.card-outline{
  border-top: 3px solid #f7513b !important;
}
td a{
  color: black !important;
}
.btn-info{
  border-color: #fff !important;
}

:deep(.page-item .page-link){
  color: #000
   !important;
}
:deep(.VuePagination__pagination-item-prev-page .page-link), :deep(.VuePagination__pagination-item-next-page .page-link){
  color: #f7513b !important;
}
:deep(.page-item.active .page-link){
  color: #fff !important;
  background-color: #f7513b !important;
  border-color: #f7513b !important;
}
</style>
<style>
.switch {
  position: relative;
  display: inline-block;
  width: 50px;
  height: 28px;
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: 0.4s;
  transition: 0.4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 16px;
  width: 16px;
  left: 4px;
  bottom: 6px;
  background-color: white;
  transition: 0.4s;
}

input:checked + .slider {
  background-color: #2196f3;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196f3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(26px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}

.ring-container {
    position: relative;
}

.circle {
    width: 10px;
    height: 10px;
    background-color: #37c466;
    border-radius: 50%;
    position: absolute;
    top: 20px;
    left: 5px;
}
tbody tr td{
    padding-bottom: 3px !important;
}
.circle--2 {
    width: 20px;
    height: 20px;
    background-color: #eb391a;
    border-radius: 50%;
    position: absolute;
    top: 20px;
    left: 20px;
}

.ringring {
    border: 3px solid #37c466;
    -webkit-border-radius: 30px;
    height: 20px;
    width: 20px;
    position: absolute;
    left: 0px;
    top: 15px;
    -webkit-animation: pulsate 1s ease-out;
    -webkit-animation-iteration-count: infinite;
    opacity: 0.0;
}
@-webkit-keyframes pulsate {
    0% {-webkit-transform: scale(0.1, 0.1); opacity: 0.0;}
    50% {opacity: 1.0;}
    100% {-webkit-transform: scale(1.2, 1.2); opacity: 0.0;}
}
</style>
