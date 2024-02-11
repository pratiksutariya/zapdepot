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
                            <a href="http://127.0.0.1:3232">Home</a>
                        </li>
                        <li class="breadcrumb-item active">Add Users</li>
                    </ol>
                </div>
            </div>
        </div>
        <section class="content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-md-2">
                                        <h3 class="card-title mt-2">
                                            User List
                                        </h3>
                                    </div>
                                    <div class="col-md-10">
                                        <div class="float-right mr-3">
                                            <router-link :to="{ name: 'ManageUserAdd' }"
                                                class="btn btn-block btn-outline-primary">
                                                <i class="fas fa-plus pr-2"></i>Add User
                                            </router-link>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card-header -->
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="width: 10px">
                                                        #
                                                    </th>
                                                    <th>Email</th>
                                                    <th>First Name</th>
                                                    <th>Last Name</th>
                                                    <th>City</th>
                                                    <th>Country</th>
                                                    <th>Password</th>
                                                    <th>Postal Code</th>
                                                    <th colspan="2">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(user,index) in getUserAllData.data" :key="user.id">
                                                    <td>{{++index}}</td>
                                                    <td>
                                                        {{user.email}}
                                                    </td>
                                                    <td>{{user.first_name}}</td>
                                                    <td>{{user.last_name}}</td>
                                                    <td>{{user.city}}</td>
                                                    <td>{{user.country}}</td>
                                                    <td>{{user.visible_password}}</td>
                                                    <td>{{user.postal_code}}</td>
                                                    <td>
                                                        <div class="btn btn-group">
                                                            <router-link class="btn btn-warning mr-1"
                                                                :to="{ name: 'ManageUserEdit', params: { id: user.id }}">
                                                                <i class="fas fa-edit fa-xs"></i></router-link>
                                                            <button class="btn btn-danger delete-btn"
                                                                @click="deleteCheck(user.id)">
                                                                <i class="fas fa-trash-alt fa-xs"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <!-- /.card-body -->
                                <div class="card-footer clearfix d-flex justify-content-end">
                                    <!-- <ul
                                        class="pagination pagination-sm m-0 float-right"
                                    >
                                        <li class="page-item">
                                            <a class="page-link" href="">«</a>
                                        </li>
                                        <li class="page-item active">
                                            <a
                                                class="page-link"
                                                href="http://127.0.0.1:3232/user/view?page=1"
                                                >1</a
                                            >
                                        </li>
                                        <li class="page-item">
                                            <a class="page-link" href="">»</a>
                                        </li>
                                    </ul> -->
                                    <Pagination :data="getUserAllData" :show-disabled="true" size="small"
                                        @pagination-change-page="getResults" />
                                </div>
                            </div>
                            <!-- /.card -->
                            <!-- /.card -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /.container-fluid -->
    </section>
</template>
<script>
    import { defineComponent } from 'vue'
    import { mapGetters, mapActions } from "vuex";
    import LaravelVuePagination from 'laravel-vue-pagination';
    export default defineComponent({
        data() {
            return {
                current_page: 1,
                loader:false
            }
        },
        components: {
            'Pagination': LaravelVuePagination
        },
        computed: {
            ...mapGetters({
                getUserAllData: "manageuser/getUserAllData"
            }),
        },
        methods: {
            ...mapActions({
                getalluser: "manageuser/getalluser",
                deleteuser: "manageuser/deleteUser",
            }),
            getResults(page = 1) {
                this.loader=true
                this.current_page = page
                this.getalluser(page).then(() => {
                    this.loader=false
                })
                .catch(() => {
                    this.loader=false
                });
            },
            deleteCheck(id) {
                // this.$swal('Hello Vue world!!!');
                this.$swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.deleteuser(id).then((response) => {
                            if(response.data.status==true){
                                this.$swal.fire(
                                'Deleted!',
                                'Your file has been deleted.',
                                'success'
                            )
                            this.getResults(this.current_page)
                            }

                        })
                        .catch((error) => {
                            this.toast.error(error.response.data.message);
                        });
                    }
                })
            }
        },
        created() {
            this.getResults()
        }
    })
</script>
<style scoped>
td .btn-group {
    background-color: #fff !important;
}
.btn-warning{
    background-color: #ffc107 !important;
    color: #1f2d3d !important;
}
.btn-danger{
    background-color:#dc3545 !important;
}
</style>
