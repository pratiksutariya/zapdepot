<template>
<div>
    <loader :active="loader" />
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>My Account</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="#">Home</a>
                        </li>
                        <li class="breadcrumb-item active">My Account</li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <!-- <div class="row mt-4 ml-3 justify-content-between"> -->
                                <div class="col-md-12 detail-box">
                                    <form @submit.prevent="submit">
                                        <div class="card-body pb-0">
                                            <!-- <div class="row"> -->
                                                <!-- <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Username</label>
                                                        <input
                                                            class="form-control"
                                                            disabled
                                                            v-model="
                                                                user.username
                                                            "
                                                        />
                                                    </div>
                                                </div> -->

                                                <div class="col-md-12 form-group">
                                                    <label>First Name</label>
                                                    <input class="form-control" placeholder="Enter First Name" :class="{
                                                            'is-invalid':
                                                                v$.user
                                                                    .first_name
                                                                    .$errors
                                                                    .length,
                                                        }" v-model="
                                                            v$.user
                                                                .first_name
                                                                .$model
                                                        " />
                                                    <span class="invalid-feedback" role="alert" v-for="(
                                                            error, index
                                                        ) of v$.user
                                                            .first_name
                                                            .$errors" :key="index">
                                                        <strong>{{
                                                            error.$message
                                                        }}</strong>
                                                    </span>
                                                </div>
                                            <!-- </div>
                                            <div class="row"> -->
                                                <div class="col-md-12 form-group">
                                                    <label for="prodcut_name">Last Name</label>
                                                    <input class="form-control" placeholder="Enter Last Name" :class="{
                                                            'is-invalid':
                                                                v$.user
                                                                    .last_name
                                                                    .$errors
                                                                    .length,
                                                        }" v-model="
                                                            v$.user
                                                                .last_name
                                                                .$model
                                                        " />
                                                    <span class="invalid-feedback" role="alert" v-for="(
                                                            error, index
                                                        ) of v$.user
                                                            .last_name
                                                            .$errors" :key="index">
                                                        <strong>{{
                                                            error.$message
                                                        }}</strong>
                                                    </span>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="prodcut_name">Email</label>
                                                    <input class="form-control" placeholder="Enter Email" :class="{
                                                            'is-invalid':
                                                                v$.user
                                                                    .email
                                                                    .$errors
                                                                    .length,
                                                        }" v-model="
                                                            v$.user.email
                                                                .$model
                                                        " />
                                                    <span class="invalid-feedback" role="alert" v-for="(
                                                            error, index
                                                        ) of v$.user.email
                                                            .$errors" :key="index">
                                                        <strong>{{
                                                            error.$message
                                                        }}</strong>
                                                    </span>
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label>City</label>
                                                    <input class="form-control" placeholder="Enter City" v-model="user.city" />
                                                </div>
                                            <!-- </div>
                                            <div class="row"> -->
                                                <div class="col-md-12 form-group">
                                                    <label for="prodcut_name">Postal Code</label>
                                                    <input class="form-control" placeholder="Enter Postal Code" v-model="user.postal_code" />
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="prodcut_name">Country</label>
                                                    <input class="form-control" placeholder="Enter Country" v-model="user.country" />
                                                </div>

                                        </div>
                                        <div class="card-footer bg-white">
                                            <button type="submit" class="btn btn-primary">
                                                Submit
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            <!-- </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
</template>

<script>
import useVuelidate from "@vuelidate/core";
import {
    required,
    email
} from "@vuelidate/validators";
import {
    mapActions,
    mapGetters
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
            loader: false,
        };
    },
    validations() {
        return {
            user: {
                email: {
                    required,
                    email
                },
                first_name: {
                    required
                },
                last_name: {
                    required
                },
            },
        };
    },
    computed: {
        ...mapGetters({
            user: "auth/user",
        }),
    },
    methods: {
        ...mapActions({
            updateUser: "auth/updateUser",
        }),
        submit() {
            this.loader = true;
            this.updateUser(this.user)
                .then((response) => {
                    this.loader = false;
                    if (response.data.status == true) {
                        this.toast.success("Account Updated Successfully");
                    }
                })
                .catch((error) => {
                    this.loader = false;
                    if (error.response.status == 422) {
                        this.toast.error(error.response.data.data.email[0]);
                    } else {
                        this.toast.error(error.response.data.message);
                    }
                });
        },
    },
};
</script>
