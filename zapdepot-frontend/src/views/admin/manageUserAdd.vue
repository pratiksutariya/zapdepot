<template>
    <loader :active="loader"/>
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <!-- <h1>Category </h1> -->
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="http://127.0.0.1:3232">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="http://127.0.0.1:3232/user/view"
                                >Add Users</a
                            >
                        </li>
                        <li class="breadcrumb-item active">Create Users</li>
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
                            <form @submit.prevent="submit">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>First Name</label>
                                            <div class="input-group">
                                                <input
                                                    id="first_name"
                                                    type="input"
                                                    class="form-control"
                                                    :class="{
                                                        'is-invalid':
                                                            v$.user.first_name
                                                                .$errors.length,
                                                    }"
                                                    v-model="
                                                        v$.user.first_name
                                                            .$model
                                                    "
                                                    placeholder="First Name"
                                                />
                                                <div class="input-group-append">
                                                    <div
                                                        class="input-group-text"
                                                    >
                                                        <span
                                                            class="fas fa-user"
                                                        ></span>
                                                    </div>
                                                </div>
                                                <span
                                                    class="invalid-feedback"
                                                    role="alert"
                                                    v-for="(error, index) of v$
                                                        .user.first_name
                                                        .$errors"
                                                    :key="index"
                                                >
                                                    <strong>{{
                                                        error.$message
                                                    }}</strong>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Last Name</label>
                                            <div class="input-group">
                                                <input
                                                    id="lastname"
                                                    type="input"
                                                    class="form-control"
                                                     :class="{
                                                        'is-invalid':
                                                            v$.user.last_name
                                                                .$errors.length,
                                                    }"
                                                    v-model="
                                                        v$.user.last_name
                                                            .$model
                                                    "
                                                    placeholder="Last Name"
                                                />
                                                <div class="input-group-append">
                                                    <div
                                                        class="input-group-text"
                                                    >
                                                        <span
                                                            class="fas fa-user"
                                                        ></span>
                                                    </div>
                                                </div>
                                                <span
                                                    class="invalid-feedback"
                                                    role="alert"
                                                    v-for="(error, index) of v$
                                                        .user.last_name
                                                        .$errors"
                                                    :key="index"
                                                >
                                                    <strong>{{
                                                        error.$message
                                                    }}</strong>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label>Email</label>
                                            <div class="input-group">
                                                <input
                                                    id="email"
                                                    type="email"
                                                    class="form-control"
                                                    name="email"
                                                     :class="{
                                                        'is-invalid':
                                                            v$.user.email
                                                                .$errors.length,
                                                    }"
                                                    v-model="
                                                        v$.user.email
                                                            .$model
                                                    "
                                                    placeholder="Email"
                                                />
                                                <div class="input-group-append">
                                                    <div
                                                        class="input-group-text"
                                                    >
                                                        <span
                                                            class="fas fa-envelope"
                                                        ></span>
                                                    </div>
                                                </div>
                                                <span
                                                    class="invalid-feedback"
                                                    role="alert"
                                                    v-for="(error, index) of v$
                                                        .user.email
                                                        .$errors"
                                                    :key="index"
                                                >
                                                    <strong>{{
                                                        error.$message
                                                    }}</strong>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>City</label>
                                            <div class="input-group">
                                                <input
                                                    id="city"
                                                    type="input"
                                                    class="form-control"
                                                    v-model="user.city"
                                                    placeholder="City"
                                                />
                                                <div class="input-group-append">
                                                    <div
                                                        class="input-group-text"
                                                    >
                                                        <span
                                                            class="fas fa-city"
                                                        ></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Counrtry</label>
                                            <div class="input-group">
                                                <input
                                                    id="country"
                                                    type="input"
                                                    class="form-control"
                                                    v-model="user.country"
                                                    placeholder="Country"
                                                />
                                                <div class="input-group-append">
                                                    <div
                                                        class="input-group-text"
                                                    >
                                                        <span
                                                            class="fa fa-flag"
                                                        ></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Postal Code</label>
                                            <div class="input-group">
                                                <input
                                                    id="postal_code"
                                                    type="input"
                                                    class="form-control"
                                                    name="postal_code"
                                                    v-model="user.postal_code"
                                                    placeholder="Postal code"
                                                />
                                                <div class="input-group-append">
                                                    <div
                                                        class="input-group-text"
                                                    >
                                                        <span
                                                            class="fas fa-map-pin"
                                                        ></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Password</label>
                                            <div class="input-group">
                                                <input
                                                    id="password"
                                                    type="text"
                                                    class="form-control"
                                                     :class="{
                                                        'is-invalid':
                                                            v$.user.password
                                                                .$errors.length,
                                                    }"
                                                    v-model="
                                                        v$.user.password
                                                            .$model
                                                    "
                                                    placeholder="Password"
                                                />
                                                <div class="input-group-append">
                                                    <div
                                                        class="input-group-text"
                                                    >
                                                        <span
                                                            class="fas fa-lock"
                                                        ></span>
                                                    </div>
                                                </div>
                                                 <span
                                                    class="invalid-feedback"
                                                    role="alert"
                                                    v-for="(error, index) of v$
                                                        .user.password
                                                        .$errors"
                                                    :key="index"
                                                >
                                                    <strong>{{
                                                        error.$message
                                                    }}</strong>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <button
                                        type="submit"
                                        class="btn btn-primary"
                                    >
                                        Submit
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
<script>
import { defineComponent } from "vue";
import useVuelidate from "@vuelidate/core";
import { required, email } from "@vuelidate/validators";
import { mapActions } from "vuex";
import { useToast } from "vue-toastification";
export default defineComponent({
    setup() {
        const toast = useToast();
        return { toast, v$: useVuelidate() };
    },
    data() {
        return {
            user: {
                first_name: "",
                last_name: "",
                email: "",
                city: "",
                country: "",
                postal_code: "",
                password: "",
            },
            loader:false
        };
    },
    validations() {
        return {
            user: {
                email: { required, email },
                first_name: { required },
                last_name: { required },
                password: { required },
            },
        };
    },

    methods: {
          ...mapActions({
            saveuser: "manageuser/saveUser",
            getSingleUser: "manageuser/getSingleUser",
        }),
        submit() {
            this.v$.user.$touch();
            if (this.v$.user.$invalid) {
                return;
            }
            this.loader=true
            this.saveuser(this.user).then((response) => {
                    this.loader=false   
                    if (response.data.status == true) {
                       this.toast.success("user added successfully");
                        this.$router.push({name: 'ManageUser'})
                    }
                })
                .catch((error) => {
                    this.loader=false  
                       this.toast.error(error.response.data.data.email[0]);
                });
        },
    },
    created(){
        if(this.$route.name=='ManageUserEdit'){
            if(this.$route.params.id){
                this.loader=true
                this.getSingleUser(this.$route.params.id).then((response) => {
                    this.loader=false
                    if (response.data.status == true) {
                        this.user=response.data.data
                        this.user.password=this.user.visible_password
                    }
                })
                .catch((error) => {
                    this.loader=false
                    console.log(error)
                });
            }
        }
    }
});
</script>
