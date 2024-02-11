<template>
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
            <li class="breadcrumb-item active">Integration</li>
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
          <div
            class="card card-primary card-outline"
            style="border-color: #00d2f2"
          >
            <div class="card-header">
              <div class="row">
                <div class="col-md-6">
                  <div class="mail_box">
                    <div class="mail_icon"></div>
                  </div>
                  <div
                    style="
                      display: flex;
                      position: absolute;
                      margin-left: 2%;
                      top: 8%;
                    "
                  >
                    <img  style="width: 35px;margin-right: 10px; object-fit: contain;" src="../../../assets/images/ready-funnels-small.png" alt="">
                    <h2 style="margin: 0">Ready Funnels Pro</h2>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="float-right mr-3">
                    <a
                      type="button"
                      href="javascript:void(0)"
                      @click="showModal"
                      class="btn btn-block btn-outline-primary"
                      ><i class="fas fa-plus pr-2"></i>Add Account</a
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
                        <th>Account Label</th>
                        <!-- <th>Location Id</th> -->
                        <th>API Key</th>
                        <th style="width: 100px">Delete</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr
                        class=""
                        v-for="(account, index) in getallGAccounts"
                        :key="account.id"
                        style="width: 100%"
                      >
                        <td>{{ ++index }}</td>
                        <td>{{ account.label }}</td>
                        <!-- <td>{{account.location_id}}</td> -->
                        <td style="width: 55%; word-break: break-all">
                          {{ api_key_encrypt(account.api_key) }}
                        </td>
                        <td>
                          <button
                            class="btn btn-primary mr-1"
                            @click="editgohighlevel(account)"
                            style="padding: 8px !important"
                          >
                            <i class="fas fa-edit"></i></button
                          ><button
                            class="btn btn-danger delete-btn"
                            @click="deletgohighlevel(account.id, 'agency')"
                            style="padding: 8px !important"
                          >
                            <i class="fas fa-trash-alt"></i>
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
            <!-- /.card -->
          </div>
        </div>
      </div>
      <div class="row mt-5">
        <div class="col-md-12">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <div class="row">
                <div class="col-md-6">
                  <div class="mail_box">
                    <div class="active_icon"></div>
                  </div>
                  <div
                    style="
                      display: flex;
                      position: absolute;
                      margin-left: 2%;
                      top: 8%;
                    "
                  >
                  <img  style="width: 35px;margin-right: 10px; object-fit: contain;" src="../../../assets/images/activec.png" alt="">
                    <h2 style="margin: 0">Active Campaign</h2>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="float-right mr-3">
                    <a
                      type="button"
                      href="javascript:void(0)"
                      @click="showModala()"
                      class="btn btn-block btn-outline-primary"
                      ><i class="fas fa-plus pr-2"></i>Add Account</a
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
                        <th>Account Label</th>
                        <th>API Url</th>
                        <th>API Key</th>
                        <th style="width: 100px">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr
                        class=""
                        v-for="(account, index) in getActiveCamAccounts"
                        :key="account.id"
                        style="width: 100%"
                      >
                        <td>{{ ++index }}</td>
                        <td>{{ account.label }}</td>
                        <!-- <td>{{account.location_id}}</td> -->
                        <td>
                          {{ account.api_url }}
                        </td>
                        <td>
                          {{ api_key_encrypt(account.api_key) }}
                        </td>
                        <td>
                          <button
                            class="btn btn-primary mr-1"
                            @click="editActivelevel(account)"
                            style="padding: 8px !important"
                          >
                            <i class="fas fa-edit"></i></button
                          ><button
                            class="btn btn-danger delete-btn"
                            @click="deleteActiveCampaign(account.id)"
                            style="padding: 8px !important"
                          >
                            <i class="fas fa-trash-alt"></i>
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
            <!-- /.card -->
          </div>
        </div>
      </div>
      <div class="row mt-5">
        <div class="col-md-12">
          <div
            class="card card-primary card-outline"
            style="border-color: #4acf27"
          >
            <div class="card-header">
              <div class="row">
                <div class="col-md-6">
                  <div class="mail_box">
                    <div class="active_icon"></div>
                  </div>
                  <div
                    style="
                      display: flex;
                      position: absolute;
                      margin-left: 2%;
                      top: 8%;
                    "
                  >
                    <img  style="width: 35px;margin-right: 10px; object-fit: contain;" src="../../../assets/images/gohighlevel.png" alt="">
                    <h2 style="margin: 0">Gohighlevel</h2>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="float-right mr-3">
                    <a
                      type="button"
                      href="javascript:void(0)"
                      @click="showModalg()"
                      class="btn btn-block btn-outline-primary"
                      ><i class="fas fa-plus pr-2"></i>Add Account</a
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
                        <th>Account Label</th>
                        <th>Access Token</th>
                        <th>Refresh Token</th>
                        <th>Location Id</th>
                        <th style="width: 50px">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr
                        class=""
                        v-for="(account, index) in getallGAccountsSingle"
                        :key="account.id"
                        style="width: 100%"
                      >
                        <td>{{ ++index }}</td>
                        <td>{{ account.label }}</td>
                        <!-- <td>{{account.location_id}}</td> -->
                        <td>
                          {{ api_key_encrypt(account.access_token) }}
                        </td>
                        <td>
                          {{ api_key_encrypt(account.refresh_token) }}
                        </td>
                        <td>
                          {{ account.location_id }}
                        </td>
                        <td>
                          <button
                            class="btn btn-danger delete-btn"
                            @click="deletgohighlevel(account.id, 'single')"
                            style="padding: 8px !important"
                          >
                            <i class="fas fa-trash-alt"></i>
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
            <!-- /.card -->
          </div>
        </div>
      </div>
      <div class="row mt-5">
        <div class="col-md-12">
          <div
            class="card card-primary card-outline"
            style="border-color: #ffe900"
          >
            <div class="card-header">
              <div class="row">
                <div class="col-md-6">
                  <div class="mail_box">
                    <div class="active_icon"></div>
                  </div>
                  <div
                    style="
                      display: flex;
                      position: absolute;
                      margin-left: 2%;
                      top: 8%;
                    "
                  >
                  <img  style="width: 35px;margin-right: 10px; object-fit: contain;" src="../../../assets/images/webinar.png" alt="">
                    <h2 style="margin: 0">Go To Webinar</h2>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="float-right mr-3">
                    <a
                      type="button"
                      href="javascript:void(0)"
                      @click="redirectToGotowebinar()"
                      class="btn btn-block btn-outline-primary"
                      ><i class="fas fa-plus pr-2"></i>Add Account</a
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
                        <th>First name</th>
                        <th>Last name</th>
                        <th>Email</th>
                        <th>Account Key</th>
                        <th>Organizer Key</th>
                        <th style="width: 50px">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr  v-for="(get,index) in getWebinarAccounts" :key="get.id">
                        <td>{{++index}}</td>
                        <td>{{get.first_name}}</td>
                        <td>{{get.last_name}}</td>
                        <td>{{get.email}}</td>
                        <td>{{get.account_key}}</td>
                        <td>{{get.organizer_key}}</td>
                        <td>
                          <button
                            class="btn btn-danger delete-btn"
                            @click="deletwebinar(get.id, 'single')"
                            style="padding: 8px !important"
                          >
                            <i class="fas fa-trash-alt"></i>
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
            <!-- /.card -->
          </div>
        </div>
      </div>
      <div class="row mt-5">
        <div class="col-md-12">
          <div
            class="card card-primary card-outline"
            style="border-color: #E75F4D"
          >
            <div class="card-header">
              <div class="row">
                <div class="col-md-6">
                  <div class="mail_box">
                    <div class="active_icon"></div>
                  </div>
                  <div
                    style="
                      display: flex;
                      position: absolute;
                      margin-left: 2%;
                      top: 8%;
                    "
                  >
                    <img  style="width: 35px;margin-right: 10px; object-fit: contain;" src="../../../assets/images/Sheets.png" alt="">
                    <h2 style="margin: 0">Google Account</h2>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="float-right mr-3">
                    <a
                      type="button"
                      href="javascript:void(0)"
                      @click="googleModel()"
                      class="btn btn-block btn-outline-primary"
                      ><i class="fas fa-plus pr-2"></i>Add Account</a
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
                        <th>Account Name</th>
                        <th>Access Token</th>
                        <th>Refresh Token</th>
                        <th style="width: 50px">Action</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr  v-for="(get,index) in allGoogleAccounts" :key="index">
                        <td>{{++index}}</td>
                        <td>{{get.label}}</td>
                        <td>{{get.access_token.substring(0, 50) + '***********'}}</td>
                        <td>{{get.refresh_token.substring(0, 50) + '***********'}}</td>
                        <td>
                          <button
                            class="btn btn-danger delete-btn"
                            @click="deleteGotogoogle(get)"
                            style="padding: 8px !important"
                          >
                            <i class="fas fa-trash-alt"></i>
                          </button>
                        </td>
                      </tr>
                    </tbody>
                  </table>
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
  <Model
    :modaltitle="modaltitle"
    @close="closeGoghilevel()"
    v-if="showgohilevel"
  >
    <template #modal-logo>
      <div class="image-box row">
        <div class="col-12 custom-box">
          <img
            src="@/assets/images/readyfunnels.png"
            style="max-width: 217px"
          />
        </div>
      </div>
    </template>
    <template #body>
      <form @submit.prevent="addgohilevel">
        <div class="modal-body">
          <label
            >To get the API key. Login into your account go to Settings >>
            Business Info.
            <span class="question-box"
              ><a
                href="/img/ready-detail.png"
                style="color: #fff"
                target="_blank"
                >?</a
              ></span
            ></label
          >
          <div class="form-group">
            <label for="prodcut_name">Account Label</label>
            <input
              class="form-control"
              placeholder="Enter Label"
              :class="{
                'is-invalid': v$.gohighlevel.label.$errors.length,
              }"
              v-model="v$.gohighlevel.label.$model"
            />
            <span
              class="invalid-feedback"
              role="alert"
              v-for="(error, index) of v$.gohighlevel.label.$errors"
              :key="index"
            >
              <strong>{{ error.$message }}</strong>
            </span>
          </div>
          <div class="form-group">
            <label for="prodcut_name">API Key</label>
            <input
              class="form-control"
              placeholder="Enter API key"
              :class="{
                'is-invalid': v$.gohighlevel.api_key.$errors.length,
              }"
              v-model="v$.gohighlevel.api_key.$model"
            />
            <span
              class="invalid-feedback"
              role="alert"
              v-for="(error, index) of v$.gohighlevel.api_key.$errors"
              :key="index"
            >
              <strong>{{ error.$message }}</strong>
            </span>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Add</button>
        </div>
      </form>
    </template>
  </Model>
  <Model :modaltitle="modaltitle" @close="closeActiveCam()" v-if="showactive">
    <template #modal-logo>
      <div class="image-box row">
        <div class="col-12 custom-box">
          <img
            src="@/assets/images/active-campaign.png"
            style="max-width: 100px"
          />
        </div>
      </div>
    </template>
    <template #body>
      <form @submit.prevent="addactive">
        <div class="modal-body">
          <label
            >To get API credentials. Login into account to go settings and
            developer. You will find URL and Key.
            <span class="question-box"
              ><a
                href="/img/active-detail.png"
                style="color: #fff"
                target="_blank"
                >?</a
              ></span
            ></label
          >
          <div class="form-group">
            <label for="prodcut_name">Enter Account Label</label>
            <input
              class="form-control"
              placeholder="Enter Label"
              :class="{
                'is-invalid': v$.activeCam.label.$errors.length,
              }"
              v-model="v$.activeCam.label.$model"
            />
            <span
              class="invalid-feedback"
              role="alert"
              v-for="(error, index) of v$.activeCam.label.$errors"
              :key="index"
            >
              <strong>{{ error.$message }}</strong>
            </span>
          </div>
          <div class="form-group">
            <label for="prodcut_name">Enter API Key</label>
            <input
              class="form-control"
              placeholder="Enter API key"
              :class="{
                'is-invalid': v$.activeCam.api_key.$errors.length,
              }"
              v-model="v$.activeCam.api_key.$model"
            />
            <span
              class="invalid-feedback"
              role="alert"
              v-for="(error, index) of v$.activeCam.api_key.$errors"
              :key="index"
            >
              <strong>{{ error.$message }}</strong>
            </span>
          </div>
          <div class="form-group">
            <label for="prodcut_name">API Url</label>
            <input
              class="form-control"
              placeholder="Enter API Url"
              :class="{
                'is-invalid': v$.activeCam.api_url.$errors.length,
              }"
              v-model="v$.activeCam.api_url.$model"
            />
            <span
              class="invalid-feedback"
              role="alert"
              v-for="(error, index) of v$.activeCam.api_url.$errors"
              :key="index"
            >
              <strong>{{ error.$message }}</strong>
            </span>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Add Account</button>
        </div>
      </form>
    </template>
  </Model>
  <Model
    :modaltitle="modaltitle"
    @close="closeGoSingle()"
    v-if="showgohilevelSig"
  >
    <template #modal-logo>
      <div class="image-box row">
        <div class="col-12 custom-box">
          <img
            src="@/assets/images/gohighleveln.jpg"
            style="max-width: 250px"
          />
        </div>
      </div>
    </template>
    <template #body>
      <form @submit.prevent="addgohilevelsingle">
        <div class="modal-body">
          <div class="form-group">
            <label for="prodcut_name">Enter Account Label</label>
            <input
              class="form-control"
              placeholder="Enter Label"
              :class="{
                'is-invalid': v$.gohighlevelSingle.label.$errors.length,
              }"
              v-model="v$.gohighlevelSingle.label.$model"
            />
            <span
              class="invalid-feedback"
              role="alert"
              v-for="(error, index) of v$.gohighlevelSingle.label.$errors"
              :key="index"
            >
              <strong>{{ error.$message }}</strong>
            </span>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Connect Account</button>
        </div>
      </form>
    </template>
  </Model>
  <Model
    :modaltitle="modaltitle"
    @close="gooleModelCLose()"
    v-if="googlePopUP"
  >
    <template #modal-logo>
      <div class="image-box row">
        <div class="col-12 custom-box">
          <img
            src="@/assets/images/shhets.png"
            style="max-width: 250px"
          />
        </div>
      </div>
    </template>
    <template #body>
      <form @submit.prevent="redirectToGoogleAccount">
        <div class="modal-body">
          <div class="form-group">
            <label for="prodcut_name">Enter Account Label</label>
            <input
              class="form-control"
              placeholder="Enter Label"
              :class="{
                'is-invalid': v$.google.label.$errors.length,
              }"
              v-model="v$.google.label.$model"
            />
            <span
              class="invalid-feedback"
              role="alert"
              v-for="(error, index) of v$.google.label.$errors"
              :key="index"
            >
              <strong>{{ error.$message }}</strong>
            </span>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Connect Account</button>
        </div>
      </form>
    </template>
  </Model>
</template>

<script>
import Model from "@/components/model.vue";
import useVuelidate from "@vuelidate/core";
import {
    required,
    url
} from "@vuelidate/validators";
import {
    mapActions,
    mapGetters,
} from "vuex";
import {
    useToast
} from "vue-toastification";
export default {
    setup() {
        const toast = useToast();
        return {
            toast,
            v$: useVuelidate()
        };
    },
    data() {
        return {
            showgohilevel: false,
            showactive: false,
            googlePopUP: false,
            showgohilevelSig: false,
            gohighlevel: {
                label: "",
                // location_id: "",
                api_key: "",
            },
            gohighlevelSingle: {
                label: "",
            },
            google : {
              label: ""
            },
            activeCam: {
                label: "",
                api_key: "",
                api_url: "",
            },
            loader: false,
            modaltitle: "",
        };
    },
    validations() {
        return {
            gohighlevel: {
                label: {
                    required
                },
                // location_id: { required },
                api_key: {
                    required
                },
            },
            activeCam: {
                label: {
                    required
                },
                api_key: {
                    required
                },
                api_url: {
                    required,
                    url
                },
            },
            gohighlevelSingle: {
                label: {
                    required
                },
            },
            google : {
              label: {
                    required
                },
            }
        };
    },
    components: {
        Model,
    },
    computed: {
        ...mapGetters({
            getallGAccounts: "integration/getGohighLevelAccounts",
            getallGAccountsSingle: "integration/getGohighLevelAccountsSingle",
            getActiveCamAccounts: "integration/getActiveCamAccounts",
            getWebinarAccounts: "integration/getWebinarAccounts",
            allGoogleAccounts: "integration/getGoogleAccounts",
        }),
        api_key_encrypt() {
            return (value) => {
                if (value.length > 50) {
                    return value.substr(0, 50) + "*****";
                } else {
                    return value;
                }
            };
        },
    },
    methods: {
        ...mapActions({
            addAccountGo: "integration/addGohighlevelAccount",
            connectToGoogleAccount: "integration/connectToGoogleAccount",
            setAllGohighlevel: "integration/getAllGohilevelAccounts",
            getAllGoogleDATA: "integration/getAllGoogleDATA",
            setAllGohighlevelSingle: "integration/setAllGohilevelAccountsSingle",
            deleteGohighaccount: "integration/deleteGohighaccount",
            addActiveCamAccount: "integration/addActiveCamAccount",
            setAllActiveCamAccounts: "integration/getAllActiveCamAccounts",
            deleteActiveAccount: "integration/deleteActiveAccount",
            getAllWebinarData: "integration/getAllWebinarData",
            deleteWebinarSingle: "integration/deleteWebinarSingle",
            deleteGoogleAccount: "integration/deleteGoogleAccount",
        }),
        showModala() {
            (this.activeCam = {
                id: "",
                label: "",
                api_url: "",
                api_key: "",
            }),
            (this.showactive = true);
            this.modaltitle = "Allow ZapDepot to access your ActiveCampaign Account?";
            this.v$.activeCam.$reset();
        },
        showModalg() {
            (this.gohighlevelSingle = {
                id: "",
                label: "",
            }),
            (this.showgohilevelSig = true);
            this.modaltitle = "Allow ZapDepot to access your Gohighlevel Account?";
            this.v$.gohighlevelSingle.$reset();
        },
        showModal() {
            (this.gohighlevel = {
                id: "",
                label: "",
                // location_id: "",
                api_key: "",
            }),
            (this.showgohilevel = true);
            this.modaltitle = "Allow ZapDepot to access your Ready Funnels Pro Account?";
            this.v$.gohighlevel.$reset();
        },
        googleModel() {
            (this.google = {
                id: "",
                label: "",
            }),
            (this.googlePopUP = true);
            this.modaltitle = "Allow ZapDepot to access your Google Account Account?";
            this.v$.google.$reset();
        },
        closeGoghilevel() {
            this.showgohilevel = false;
        },
        closeActiveCam() {
            this.showactive = false;
        },
        closeGoSingle() {
            this.showgohilevelSig = false;
        },
        gooleModelCLose() {
            this.googlePopUP = false;
        },
        deleteGotogoogle(data) {
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

                        this.deleteGoogleAccount(data.id)
                            .then((response) => {
                                if (response.data.status == true) {
                                    this.$swal.fire(
                                        "Deleted!",
                                        "Account has been deleted.",
                                        "success"
                                    );
                                }
                            })
                            .catch((error) => {
                                this.toast.error(error.response.data.message);
                            });
                    }
                });

        },
        editgohighlevel(data) {
            this.modaltitle = "Allow ZapDepot to access your Ready Funnels Pro Account?";
            this.gohighlevel = {
                id: data.id,
                label: data.label,
                // location_id:data.location_id,
                api_key: data.api_key,
            };
            this.showgohilevel = true;
            this.v$.gohighlevel.$reset();
        },
        addgohilevel() {

           this.v$.gohighlevel.$touch();
            if (this.v$.gohighlevel.$invalid) {
                return;
            }
            let domain_url = 'https://zapdepot.io/integration/add/gohighlevel';
            // let domain_url = window.location.origin + '/integration/add/gohighlevel';
            let redirect_url = "https://marketplace.gohighlevel.com/oauth/chooselocation?response_type=code&redirect_uri=" + domain_url + "&client_id=62256ce0688c9632a80d915b-l0ghgtys&scope=contacts.write contacts.readonly conversations/message.readonly conversations/message.write users.write campaigns.readonly campaigns.readonly&state=ready_funnels_pro"
            window.location.href = redirect_url
            // this.v$.gohighlevel.$touch();
            // if (this.v$.gohighlevel.$invalid) {
            //     return;
            // }
            // this.loader = true;
            // this.addAccountGo(this.gohighlevel)
            // .then(() => {
            //     this.showgohilevel = false;
            //     this.loader = false;
            //     this.toast.success(
            //         "Account added successfully."
            //     );
            // })
            // .catch((error) => {
            //     this.loader = false;
            //     console.log(error.response);
            //     this.toast.error(error.response.data.message);
            // });
        },
        deletgohighlevel(id, type) {
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
                        let data = {
                            id: id,
                            type: type
                        }
                        this.deleteGohighaccount(data)
                            .then((response) => {
                                if (response.data.status == true) {
                                    this.$swal.fire(
                                        "Deleted!",
                                        "Account has been deleted.",
                                        "success"
                                    );
                                }
                            })
                            .catch((error) => {
                                this.toast.error(error.response.data.message);
                            });
                    }
                });
        },
        deletwebinar(id, type){
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
                    let data = {
                        id: id,
                        type: type
                    }
                    this.deleteWebinarSingle(data)
                        .then((response) => {
                            if (response.data.status == true) {
                                this.$swal.fire(
                                    "Deleted!",
                                    "Account has been deleted.",
                                    "success"
                                );
                            }
                        })
                        .catch((error) => {
                            this.toast.error(error.response.data.message);
                        });
                }
            });
        },
        addactive() {
            this.v$.activeCam.$touch();
            if (this.v$.activeCam.$invalid) {
                return;
            }
            this.loader = true;
            this.addActiveCamAccount(this.activeCam)
                .then(() => {
                    this.showactive = false;
                    this.loader = false;
                    this.toast.success(
                        "Acitve campaign account added successfully."
                    );
                })
                .catch((error) => {
                    this.loader = false;
                    this.toast.error(error.response.data.message);
                });
        },
        async getgoaccounts() {
            this.loader = true;
            await this.setAllGohighlevel()
                .then(() => {
                    this.loader = false;
                })
                .catch(() => {
                    this.loader = false;
                });
        },
        async getAcitveaccounts() {
            this.loader = true;
            await this.setAllActiveCamAccounts()
                .then(() => {
                    this.loader = false;
                })
                .catch(() => {
                    this.loader = false;
                });
        },
        async getgosingle() {
            this.loader = true;
            await this.setAllGohighlevelSingle()
                .then(() => {
                    this.loader = false;
                })
                .catch(() => {
                    this.loader = false;
                });
        },
        editActivelevel(data) {
            this.modaltitle = "Allow ZapDepot to access your ActiveCampaign Account?";
            this.activeCam = {
                id: data.id,
                label: data.label,
                api_url: data.api_url,
                api_key: data.api_key,
            };
            this.showactive = true;
            this.v$.activeCam.$reset();
        },
        deleteActiveCampaign(id) {
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
                        this.deleteActiveAccount(id)
                            .then((response) => {
                                if (response.data.status == true) {
                                    this.$swal.fire(
                                        "Deleted!",
                                        "Account has been deleted.",
                                        "success"
                                    );
                                }
                            })
                            .catch((error) => {
                                this.toast.error(error.response.data.message);
                            });
                    }
                });
        },
        addgohilevelsingle() {
            this.v$.gohighlevelSingle.$touch();
            if (this.v$.gohighlevelSingle.$invalid) {
                return;
            }
            let domain_url = window.location.origin + '/integration/add/gohighlevel';
            let redirect_url = "https://marketplace.gohighlevel.com/oauth/chooselocation?response_type=code&redirect_uri=" + domain_url + "&client_id=62256ce0688c9632a80d915b-l0ghgtys&scope=contacts.write contacts.readonly conversations/message.readonly conversations/message.write users.write campaigns.readonly campaigns.readonly&state=gohighlevel"
            window.location.href = redirect_url
        },
        redirectToGotowebinar() {
            let domain_url = window.location.origin + '/integration/add/gotowebinar';
            // let domain_url = "http://127.0.0.1:8000/api/integration/add/gotowebinar";
            let redirect_url = `https://api.getgo.com/oauth/v2/authorize?client_id=2cbf7ce6-09ae-4106-a391-6df9a4dac8c3&response_type=code&redirect_uri=${domain_url}`
            window.location.href = redirect_url
        },

        redirectToGoogleAccount() {
          this.v$.google.$touch();
            if (this.v$.google.$invalid) {
                return;
            }
          this.loader = true;
          let domain_url = window.location.origin + '/integration/add/googleAccount';
          this.google.url = domain_url;
            this.connectToGoogleAccount(this.google).then((resp) => {
              if(resp.data.status) {
                this.loader = false;
                window.location.href = resp.data.data

              } else {
                this.loader = false;

                this.toast.error(resp.data.message);
              }
            }).catch((errors) => {
              this.loader = false;
              console.log(errors)
            })
        }
    },
    async created() {
        this.getgoaccounts();
        this.getAcitveaccounts();
        this.getgosingle();
        this.getAllWebinarData();
        this.getAllGoogleDATA();
    },
};
</script>

<style scoped>
.custom-box {
  display: flex;
  justify-content: center;
  margin-top: 20px;
  margin-bottom: 29px;
}

.question-box {
  background-color: black;
  padding: 0px 6px;
  border-radius: 22px;
  color: #fff;
  cursor: pointer;
}
</style>
