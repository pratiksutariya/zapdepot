<template>
    <loader :active="loader" />
    <div class="row">
        <div class="col-12 row d-block">
            <label>WebHooks</label>
            <div class="col-md-9 col-sm-12 input-group p-0">
                <!-- <select v-if="allGoogleAccounts" class="form-control" v-model="account_id"
                    @change="googleSheetsFetch()">
                    <option value="">Select webHook</option>
                    <option v-for="(item, index) in allGoogleAccounts" :value="item.id" :key="index">
                        {{ item.label }}
                    </option>
                </select> -->
                <input v-on:focus="$event.target.select()" type="text" class="form-control" placeholder="Generate URL"
                    v-model="generateUrl" readonly ref="myinput">
                <div class="input-group-append">
                    <button class="btn btn-outline-primary" @click="copy"><i class="fa-solid fa-copy"></i></button>
                    <button class="btn btn-outline-primary" @click="generate">Generate</button>  
                </div>
                <!-- <div v-else>
                    <router-link :to="{ name: 'connectIntegration' }">
                        <button type="button" class="btn btn-primary" :to="{ name: 'connectIntegration' }">
                            Setup Account
                        </button>
                    </router-link>
                </div> -->
            </div>

        </div>

        <!-- <div class="col-12 row mt-3" v-if="getSpredSheets && getSpredSheets.length > 0">
        <div class="col-md-6 col-sm-12">
            <label>webHook Lists</label>
            <select class="form-control" v-model="tag_list_id" @change="sendemit()">
                <option value="">Select Spreadsheet</option>
                <option v-for="(item , index) in getSpredSheets" :value="item.id" :key="index">
                    {{item.name + `-----` + item.id}}
                </option>
            </select>
        </div>
    </div> -->
    </div>
</template>

<script>
import {
    defineComponent
} from "vue";
import {
    mapActions,
    mapGetters
} from "vuex";
// import { mapGetters, mapActions , mapState } from "vuex";

export default defineComponent({
    emits: ["set_account_id"],
    props: {
        allGoogleAccounts: Array,
        selected_account_id: Number,
        selected_tag_list_id: String,
        event_type: String,
        zap_id: {
            default:null,
        },
    },
    setup() { },
    data() {
        return {
            account_id: this.selected_account_id,
            spredsheet_id: '',
            getSpredSheets: [],
            tag_list_id: this.selected_tag_list_id,
            defaultUrl: `${window.location.origin}/api/zapdepod-hooks/catch`,
            generateUrl: "",
        }
    },
    async mounted() {
        if (this.selected_account_id) {
            // await this.googleSheetsFetch();
            this.generateUrl = `${window.location.origin}/api/zapdepod-hooks/catch/${this.selected_account_id}`;
        }

    },
    created() {

    },
    computed: {
        ...mapGetters({
            allSheets: "integration/getalldriveSheetsGoogleAccounts"
        })
    },
    methods: {
        ...mapActions({
            getGoogleSheets: "integration/getGoogleSheets",
            webHookUrlSave: "integration/webHookUrlSave",
        }),
        set_account_id() {
            let data = {
                event_type: this.event_type,
                account_id: this.account_id,
            };
            this.$emit("set_account_id", data);
        },
        sendemit() {
            let data = {
                event_type: this.event_type,
                tag_list_id: this.tag_list_id,
            };
            this.$emit("set_tag_list_id", data);
        },
        generate() {
            var uuid = require("uuid");
            var key = uuid.v4();
            this.generateUrl = this.defaultUrl + '/' + key;
            var data = {
                event_type: this.event_type,
                tag_list_id : this.generateUrl,
                account_id : key
            }
            this.$emit("set_account_id", data);
            this.savewebhookurl(this.generateUrl , key);
            // this.$emit("set_tag_list_id", data);
        },
        savewebhookurl(url , url_key) {
           let pass_Data = {
              webhook_url : url ,
              webhook_url_key : url_key ,
              zap_id : this.zap_id 
           }
           this.webHookUrlSave(pass_Data);
        },
        copy() {
            this.$refs.myinput.focus();
            document.execCommand('copy');
        }
    },

});
</script>
