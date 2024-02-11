<template>
<loader :active="loader" />
<div class="row">
    <div class="col-12 row">
        <div class="col-md-6 col-sm-12">
            <label>Google Account</label>
            <select v-if="allGoogleAccounts" class="form-control" v-model="account_id" @change="googleSheetsFetch()">
                <option value="">Select Google Account</option>
                <option v-for="(item , index) in allGoogleAccounts" :value="item.id" :key="index">
                    {{item.label}}
                </option>
            </select>
            <div v-else>
                <router-link :to="{ name: 'connectIntegration' }">
                    <button type="button" class="btn btn-primary" :to="{ name: 'connectIntegration' }">
                        Setup Account
                    </button>
                </router-link>
            </div>
        </div>
    </div>
    <div class="col-12 row mt-3" v-if="getSpredSheets && getSpredSheets.length > 0">
        <div class="col-md-6 col-sm-12">
            <label>Google Account Lists</label>
            <select class="form-control" v-model="tag_list_id" @change="sendemit()">
                <option value="">Select Spreadsheet</option>
                <option v-for="(item , index) in getSpredSheets" :value="item.id" :key="index">
                    {{item.name + `-----` + item.id}}
                </option>
            </select>
        </div>
    </div>
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
    },
    setup() {},
    data() {
        return {
            account_id: this.selected_account_id,
            spredsheet_id: '',
            getSpredSheets: [],
            tag_list_id: this.selected_tag_list_id,
        }
    },
    async mounted() {
        if (this.selected_account_id) {
            await this.googleSheetsFetch();
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
        googleSheetsFetch() {
            this.loader = true;
            let data = {
                account_id: this.account_id,
            };
            this.getGoogleSheets(data)
                .then((response) => {
                    if (response.data.status == true) {
                        console.log(response.data)
                        this.getSpredSheets = response.data.data
                        this.loader = false;
                    }
                    // this.loader = false;
                    let data2 = {
                        event_type: this.event_type,
                        account_id: this.account_id,
                    };
                    this.$emit("set_account_id", data2);
                })
                .catch(() => {
                    this.loader = false;
                });
        }
    },

});
</script>
