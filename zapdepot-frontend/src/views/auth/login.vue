<template>
    <loader :acitve="loader"></loader>
    <div class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <img src="@/assets/logo/listerpro.png" style="width: 200px" />
            </div>
            <div class="card">
                <div class="card-body login-card-body">
                    <p class="login-box-msg">Sign in to start</p>
                    <form @submit.prevent="submit">
                        <div class="form-group">
                            <div class="input-group">
                                <input
                                    type="email"
                                    placeholder="Email"
                                    class="form-control"
                                    :class="{
                                        'is-invalid':
                                            v$.user.email.$errors.length,
                                    }"
                                    v-model="v$.user.email.$model"
                                    autocomplete="email"
                                    autofocus
                                />
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-envelope"></span>
                                    </div>
                                </div>
                                <span
                                    class="invalid-feedback"
                                    role="alert"
                                    v-for="(error, index) of v$.user.email
                                        .$errors"
                                    :key="index"
                                >
                                    <strong>{{ error.$message }}</strong>
                                </span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <input
                                    type="password"
                                    placeholder="Password"
                                    class="form-control"
                                    v-model="v$.user.password.$model"
                                    :class="{
                                        'is-invalid':
                                            v$.user.password.$errors.length,
                                    }"
                                    autocomplete="current-password"
                                />
                                <div class="input-group-append">
                                    <div class="input-group-text">
                                        <span class="fas fa-lock"></span>
                                    </div>
                                </div>
                                <span
                                    class="invalid-feedback"
                                    role="alert"
                                    v-for="(error, index) of v$.user.password
                                        .$errors"
                                    :key="index"
                                >
                                    <strong>{{ error.$message }}</strong>
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-8">
                                <div class="icheck-primary">
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        name="remember"
                                        id="remember"
                                    />
                                    <label
                                        class="form-check-label"
                                        for="remember"
                                    >
                                        Remember Me
                                    </label>
                                </div>
                            </div>
                            <!-- /.col -->
                            <div class="col-4">
                                <button
                                    type="submit"
                                    class="btn btn-primary btn-block"
                                >
                                    Log In
                                </button>
                            </div>
                        </div>
                    </form>
                    <p class="mb-0">
                        <a class="btn btn-link pb-0"> Forgot Your Password? </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import useVuelidate from "@vuelidate/core";
import { required, email } from "@vuelidate/validators";
import { mapActions, mapGetters } from "vuex";
import { useToast } from "vue-toastification";
import loader from '@/components/loader/loader.vue'
export default {
    name: "Login Page",
    components:{
        loader
    },
    setup() {
        const toast = useToast();
        return { toast, v$: useVuelidate() };
    },
    data() {
        return {
            user: {
                email: "",
                password: "",
            },
            loader:false
        };
    },
    computed: {
        ...mapGetters({
            getuser:"auth/user"
        }),
    },
    methods: {
        ...mapActions({
            login: "auth/login",
        }),
        submit() {
            //  this.toast.success("I'm an info toast!");
            this.v$.user.$touch();
            if (this.v$.user.$invalid) {
                return;
            }
            this.loader=true
            this.login(this.user)
                .then((response) => {
                    console.log(response)
                    this.loader = false;
                    if (response.data.status == true) {
                       this.toast.success("Login Successfully");
                        this.$router.push({name: 'Home'})
                    }
                })
                .catch((error) => {
                        this.loader=false
                       this.toast.error(error.response.data.message);
                });
        },
    },
    validations() {
        return {
            user: {
                email: { required, email },
                password: { required },
            },
        };
    },
};
</script>
