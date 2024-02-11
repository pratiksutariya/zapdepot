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
                            <a href="">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Change Password</li>
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
                                <div class="card-body pb-0">
                                    <div class="form-group">
                                        <label>Current Password</label>
                                        <div class="input-group">
                                            <input
                                                type="password"
                                                class="form-control"
                                                autocomplete="current_password"
                                                placeholder="Current Password"
                                                :class="{
                                                    'is-invalid':
                                                        v$.user
                                                            .currrent_password
                                                            .$errors.length,
                                                }"
                                                v-model="
                                                    v$.user.currrent_password
                                                        .$model
                                                "
                                            />
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <span
                                                        class="fas fa-lock"
                                                    ></span>
                                                </div>
                                            </div>
                                            <span
                                                class="invalid-feedback"
                                                role="alert"
                                                v-for="(error, index) of v$.user
                                                    .currrent_password.$errors"
                                                :key="index"
                                            >
                                                <strong>{{
                                                    error.$message
                                                }}</strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>New Password</label>

                                        <div class="input-group">
                                            <input
                                                id="password"
                                                type="password"
                                                class="form-control"
                                                autocomplete="new-password"
                                                placeholder="New Password"
                                                :class="{
                                                    'is-invalid':
                                                        v$.user.new_password
                                                            .$errors.length,
                                                }"
                                                v-model="
                                                    v$.user.new_password.$model
                                                "
                                            />
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <span
                                                        class="fas fa-lock"
                                                    ></span>
                                                </div>
                                            </div>
                                            <span
                                                class="invalid-feedback"
                                                role="alert"
                                                v-for="(error, index) of v$.user
                                                    .new_password.$errors"
                                                :key="index"
                                            >
                                                <strong>{{
                                                    error.$message
                                                }}</strong>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Confirm Password</label>

                                        <div class="input-group">
                                            <input
                                                id="password-confirm"
                                                type="password"
                                                class="form-control"
                                                placeholder="Retype Password"
                                                :class="{
                                                    'is-invalid':
                                                        v$.user.confirm_password
                                                            .$errors.length,
                                                }"
                                                v-model="
                                                    v$.user.confirm_password
                                                        .$model
                                                "
                                            />
                                            <div class="input-group-append">
                                                <div class="input-group-text">
                                                    <span
                                                        class="fas fa-lock"
                                                    ></span>
                                                </div>
                                            </div>
                                            <span
                                                class="invalid-feedback"
                                                role="alert"
                                                v-for="(error, index) of v$.user
                                                    .confirm_password.$errors"
                                                :key="index"
                                            >
                                                <strong>{{
                                                    error.$message
                                                }}</strong>
                                            </span>
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
import useVuelidate from "@vuelidate/core";
import { required, sameAs } from "@vuelidate/validators";
import { mapActions } from "vuex";
import { useToast } from "vue-toastification";
export default {
    setup() {
        const toast = useToast();
        return { toast, v$: useVuelidate() };
    },
    data(){
        return{
            user:{
                currrent_password:"",
                new_password:"",
                confirm_password:""
            },
            loader:false
        }
    },
      validations() {
        return {
            user: {
                currrent_password: { required  },
                new_password: { required },
                confirm_password: { sameAsPassword: sameAs(this.user.new_password) },
            },
        };
    },
    methods:{
        ...mapActions({
            passwordUpdate:'auth/updateUserPassword'
        }),
        submit(){
             this.v$.user.$touch();
            if (this.v$.user.$invalid) {
                return;
            }
            this.loader=true
            this.passwordUpdate(this.user)
                .then((response) => {
                    console.log(response)
                    this.loader = false;
                    if (response.data.status == true) {
                       this.toast.success("Password Updated successfully");
                    }
                })
                .catch((error) => {
                        this.loader=false
                       this.toast.error(error.response.data.message);
                });
        }
    }
}
</script>
