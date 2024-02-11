<template>
<div>
    <loader :active="loader" />
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="" class="brand-link text-center">
            <img src="@/assets/logo/listerpro.png" style="width: 150px" />
        </a>


        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <img src="@/assets/logo/default-user.png" class="img-circle elevation-2" alt="User Image" />
                </div>
                <div class="info">
                    <a class="d-block">{{getuser.first_name + getuser.last_name}}</a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                    <li class="nav-item">
                        <router-link :to="{ name: 'Home' }" class="nav-link">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </router-link>
                    </li>
                    <li class="nav-item" v-if="getuser.type=='admin'">
                        <router-link :to="{ name: 'ManageUser' }" class="nav-link">
                            <i class="nav-icon fa fa-user-plus"></i>
                            <p>Manage Users</p>
                        </router-link>
                    </li>
                    <li class="nav-item" v-if="getuser.type=='user'">
                        <router-link :to="{ name: 'Zaps' }" class="nav-link">
                            <i class="nav-icon fa fa-link"></i>
                            <p>My Zaps</p>
                        </router-link>
                    </li>
                    <li class="nav-item" v-if="getuser.type=='user'">
                        <router-link :to="{ name: 'connectIntegration' }" class="nav-link">
                            <i class="nav-icon fa-solid fa-border-all"></i>
                            <p>My Apps</p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link :to="{ name: 'myAccount' }" class="nav-link">
                            <span class="nav-icon fas fa-user"></span>
                            <p>My Account</p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <router-link :to="{ name: 'changePass' }" class="nav-link">
                            <i class="nav-icon fas fa-key"></i>
                            <p>Change Password</p>
                        </router-link>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link cursor-pointer" @click="dologout">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>Log Out</p>
                        </a>
                    </li>
                    <li class="nav-item" v-if="getuser.type=='user'">
                        <router-link :to="{ name: 'Log' }" class="nav-link">
                            <i class="nav-icon fa fa-info-circle"></i>
                            <p>Logs</p>
                        </router-link>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>
</div>
</template>

<script>
import {
    defineComponent
} from "vue";
import {
    useToast
} from "vue-toastification";
import {
    mapGetters,
    mapActions
} from "vuex";
export default defineComponent({
    setup() {
        const toast = useToast();
        return {
            toast
        };
    },
    data() {
        return {
            loader: false
        }
    },
    name: "side-main",
    computed: {
        ...mapGetters({
            getuser: "auth/user",
        }),
    },
    methods: {
        ...mapActions({
            logout: "auth/logout",
        }),
        dologout() {
            this.loader = true
            this.logout()
                .then(() => {
                    this.loader = false;
                    this.toast.success("Logout Successfully");
                    this.$router.push({
                        name: 'homepage'
                    })
                })
                .catch((error) => {
                    this.loader = false
                    console.log(error)
                    this.toast.error('Internal Server Error.');
                });
        }
    }
});
</script>

<style>
.router-link-active.router-link-exact-active.nav-link {
    background-color: #f7513b !important;
    color: #fff !important;
    display: flex !important;
    align-items: center !important;
}

.cursor-pointer {
    cursor: pointer;
}
.main-sidebar {
    background: #fff;
}
.sidebar a{
    color: rgb(17, 17, 17) !important;
}
</style>
<style scoped>
.nav-pills .nav-link:not(.active):hover{
    color: #fff !important;
    background-color: #fd8777 !important;
}
</style>
