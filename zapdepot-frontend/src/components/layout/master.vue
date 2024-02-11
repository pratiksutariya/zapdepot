<template>
    <div class="hold-transition sidebar-mini layout-fixed">
        <div class="wrapper">
            <nav
                class="main-header navbar navbar-expand navbar-white navbar-light"
            >
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a
                            class="nav-link"
                            data-widget="control-sidebar"
                            data-slide="true"
                            href="#"
                            role="button"
                        >
                            <i class="fas fa-th-large"></i>
                        </a>
                    </li>
                </ul>
            </nav>
            <side-bar/>
            <div class="content-wrapper">
                <router-view/>
            </div>
              <footer class="main-footer">
                    <strong>Copyright &copy; 2024 <a class="link_text" href="#">ZAPDEPOT</a>.</strong>
                    All rights reserved.
                    <div class="float-right d-none d-sm-inline-block">
                    </div>
                </footer>
             <aside class="control-sidebar control-sidebar-dark">
            </aside>
        </div>
    </div>
</template>
<script>
import { defineComponent } from 'vue'
import sidebar from '@/components/sidebar.vue'
export default  defineComponent({
    name:'main-layout',
    components:{
        'side-bar':sidebar
    }
})
</script>
<style scoped>
.main-footer{
    color: black !important;
}
.link_text{
    color: black !important;
}
</style>
