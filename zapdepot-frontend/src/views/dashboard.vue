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
            <div class="row" v-if="user.type == 'user'">
                <div class="col-12 col-sm-6 col-md-3 cursor" onclick='window.location.href = ""'>
                        <router-link :to="{ name: 'Zaps' }">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-success elevation-1"><i class="fa-solid fa-z"></i></span>
                        <div class="info-box-content noblue">
                            <span class="info-box-text">Total Zaps</span>
                            <span class="info-box-number">{{getDashData && getDashData.zap ? getDashData.zap : 0}}</span>
                        </div>
                    </div>
                        </router-link>
                </div>
                <div class="col-12 col-sm-6 col-md-3 cursor" onclick='window.location.href = ""'>
                    <router-link :to="{ name: 'connectIntegration' }">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-primary elevation-1"><i class="fa-solid fa-a"></i></span>

                        <div class="info-box-content noblue">
                            <span class="info-box-text">Total Apps</span>
                            <span class="info-box-number">{{getDashData && getDashData.app ? getDashData.app : 0}}</span>
                        </div>
                    </div>
                        </router-link>

                </div>
            </div>
            <div class="row" v-else>
                <div class="col-12 col-sm-6 col-md-3 cursor" onclick='window.location.href = ""'>
                        <router-link :to="{ name: 'Zaps' }">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-success elevation-1"><i class="fa-solid fa-z"></i></span>
                        <div class="info-box-content noblue">
                            <span class="info-box-text">Total Zaps</span>
                            <span class="info-box-number">{{getAdminDashData && getAdminDashData.zapdata ? getAdminDashData.zapdata : 0}}</span>
                        </div>
                    </div>
                        </router-link>
                </div>
                <div class="col-12 col-sm-6 col-md-3 cursor" onclick='window.location.href = ""'>
                    <router-link :to="{ name: 'connectIntegration' }">
                    <div class="info-box mb-3">
                        <span class="info-box-icon bg-primary elevation-1"><i class="fab fa-app-store"></i></span>

                        <div class="info-box-content noblue">
                            <span class="info-box-text">Total Apps</span>
                            <span class="info-box-number">{{getAdminDashData && getAdminDashData.appdata ? getAdminDashData.appdata : 0}}</span>
                        </div>
                    </div>
                        </router-link>

                </div>
            </div>
        </div>
    </section>
</template>

<script>
import loader from '@/components/loader/loader.vue'
import { mapGetters,mapActions } from "vuex";
export default {
    name: "Home Page",
    components:{
        loader,
    },
    computed:{
         ...mapGetters({
                getDashData: "integration/getDashData",
                getAdminDashData: "integration/getAdminDashData",
                user : "auth/user"
            }),
    },
    created() {
        if(this.user.type == "user"){
         this.getAllDashData();
        }else{
         this.getAllAdminDashData();

        }
    },
    methods: {
            ...mapActions({
                getAllDashData: "integration/getAllDashData",
                getAllAdminDashData: "integration/getAllAdminDashData"
            }),
    }
}
</script>

<style scoped>
.noblue{
    color:black;
}
.bg-success{
    background-color: #ff8585 !important;
}
.bg-primary{
    background-color: #6db4ff!important;
}
</style>
<style>
.breadcrumb-item a {
    color: #f7513b !important;  
}
</style>