<template>
<loader :active="loader" />
<div class="row">
    <div class="col-12 row">
        <div class="col-md-6 col-sm-12">
            <label>Gohighlevel Account</label>
            <select v-if="getGohighLevelAccountsSingle.length" class="form-control" v-model="account_id" @change="set_account_id()">
                <option value="">Select Gohighlevel Account</option>
                <option v-for="account in getGohighLevelAccountsSingle" :key="account.id" :value="account.id">
                    {{ account.label }}
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
</div>
</template>

<script>
import {
    defineComponent
} from "vue";
export default defineComponent({
    setup() {},
    emits: ["set_account_id","set_tag_list_id"],
    props: {
        getGohighLevelAccountsSingle: Array,
        selected_account_id: Number,
        event_type: String,
    },
    data() {
        return {
            loader: false,
            getTags: [],
            tag_list_id: this.selected_tag_list_id,
            account_id: this.selected_account_id
            // zap:
        };
    },
    methods: {
        set_account_id() {
            let data = {
                event_type: this.event_type,
                account_id: this.account_id,
            };
            this.$emit("set_account_id", data);
            
        },
    },
});
</script>
