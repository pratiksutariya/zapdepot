<template>
    <loader :active="loader" />
</template>
<script>
import { defineComponent } from "vue";
import { mapActions} from "vuex";
import { useToast } from "vue-toastification";
export default defineComponent({
    setup() {
        const toast = useToast();
        return { toast};
    },
    data() {
        return {
            data:{
                code:'',
                state:''
            },
            loader: false,
        };
    },
    methods:{
         ...mapActions({
            addAccountGo: "integration/addGohighlevelAccountSingle",
            addGoogleAccountauth: "integration/addGoogleAccountauth",
            addAweberAccountauth: "integration/addAweberAccountauth",
            addGotoweninar: "integration/addGotoweninar",
         }),
         adddata(){
             if(!this.data.state){
                    this.$router.push({ name: "connectIntegration" });
             }
            this.loader = true;
            this.addAccountGo(this.data)
                .then((response) => {
                    this.loader = false;
                    if (response.data.status == true) {
                       this.toast.success("Account added successfully");
                        this.$router.push({name: 'connectIntegration'})
                    }
                })
                .catch((error) => {
                    this.loader = false;
                       this.toast.error(error.response.data.message);
                        this.$router.push({name: 'connectIntegration'})
                });
         },
         addGoogleAccount(){
             if(!this.data.state){
                    this.$router.push({ name: "connectIntegration" });
             }
            this.loader = true;
            this.addGoogleAccountauth(this.data)
                .then((response) => {
                    this.loader = false;
                    if (response.data.status == true) {
                       this.toast.success("Account added successfully");
                        this.$router.push({name: 'connectIntegration'})
                    }
                })
                .catch((error) => {
                    this.loader = false;
                       this.toast.error(error.response.data.message);
                        this.$router.push({name: 'connectIntegration'})
                });
         },
         addAweberAccount(){
             if(!this.data.state){
                    this.$router.push({ name: "connectIntegration" });
             }
            this.loader = true;
            this.addAweberAccountauth(this.data)
                .then((response) => {
                    this.loader = false;
                    if (response.data.status == true) {
                       this.toast.success("Account added successfully");
                        this.$router.push({name: 'connectIntegration'})
                    }
                })
                .catch((error) => {
                    this.loader = false;
                       this.toast.error(error.response.data.message);
                        this.$router.push({name: 'connectIntegration'})
                });
         },
         addgotodata(){
             console.log(this.data.state)
             if(!this.data.state){
                    this.$router.push({ name: "connectIntegration" });
             }
            this.loader = true;
             this.addGotoweninar(this.data).then((response) => {
                 this.loader = false;
                if (response.data.status == true) {
                    if(response.data.msg == 'warn'){
                        this.$swal
                        .fire({
                            title: "Account Alredy Exist",
                            text: "You can only add 1 account at a time. To add another account please sign out from the existing account and sign in back from different account in your browser.",
                            icon: "warning",
                            showCancelButton: false,
                            confirmButtonColor: "#3085d6",
                            cancelButtonColor: "#d33",
                            confirmButtonText: "Ok",
                        })
                        // this.toast.warning(response.data.message);
                    }else{
                        this.toast.success(response.data.message);
                    }
                    this.$router.push({name: 'connectIntegration'})
                }

             }).catch((error) => {
                 this.loader = false;
                       this.toast.error(error.response.data.message);
                        this.$router.push({name: 'connectIntegration'})
             })
         }
    },
    created() {
        if (this.$route.query.code) {
            this.data={
                code:this.$route.query.code,
                state:this.$route.query.state
            }
            if(this.$route.params.type=="gohighlevel"){
                this.adddata();
            }else if(this.$route.params.type=="gotowebinar"){
                this.addgotodata();
            } else if (this.$route.params.type=="googleAccount") {
                this.addGoogleAccount();
            } else if (this.$route.params.type=="aweber") {
                this.addAweberAccount();
            }
        } else {
            this.$router.push({ name: "connectIntegration" });
        }
    },
});
</script>
