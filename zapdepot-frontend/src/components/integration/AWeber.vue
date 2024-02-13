<template>
  <div>
    <loader :active="loader" />
    <div class="row">
    <div class="col-12 row">
      <div class="col-md-6 col-sm-12">
        <label>Go To AWeber Account</label>
        <select
          v-if="getAweberAccounts.length"
          class="form-control"
          v-model="account_id"
          @change="aweberSelectedData()"
        >
         <option value="">Select Go To Webinar Account</option>
          <option
            v-for="account in getAweberAccounts"
            :key="account.id"
            :value="account.id"
          >
            {{ account.label }}
          </option>
        </select>
        <div v-else>
          <router-link :to="{ name: 'connectIntegration' }">
            <button
              type="button"
              class="btn btn-primary"
              :to="{ name: 'connectIntegration' }"
            >
              Setup Account
            </button>
          </router-link>
        </div>
      </div>
    </div>
    <div class="col-12 row mt-2" v-if="accounts_list.length > 0">
      <div class="col-md-6 col-sm-12">
        <label>Go To Aweber Events</label>
        <select class="form-control"  v-model="tag_list_id" @change="sendemit()">
          <option v-for="gotag in accounts_list" :key="gotag.id" :value="gotag.id">
            {{ gotag.name }}
          </option>
        </select>
      </div>
    </div>
    <!-- <div class="col-12 row mt-2" v-if="getTags.length > 0">
      <div class="col-md-6 col-sm-12">
        <label>Go To Webinar Events</label>
        <select class="form-control"  v-model="tag_list_id" @change="sendemit()">
          <option v-for="gotag in getTags" :key="gotag.id" :value="gotag.webinarID">
            {{ gotag.subject }}
          </option>
        </select>
      </div>
    </div> -->
  </div>
  </div>
</template>
<script>
import { defineComponent } from "vue";
import { mapGetters , mapActions } from "vuex";
export default defineComponent({
  setup() {},
  emits: ["set_account_id", "set_tag_list_id"],
  props: [
    "getAweberAccounts",
    "selected_tag_list_id",
    "action_type",
    "selected_account_id",
    "event_type",
  ],
  data() {
    return {
      loader: false,
      accounts_list: [],
      tag_list_id: this.selected_tag_list_id,
      account_id: this.selected_account_id,
      // zap:
    };
  },
  computed: {
    ...mapGetters({}),
  },

  methods: {
    ...mapActions({
      getAweberSelectedData: "integration/getAweberEvent",
    }),
    aweberSelectedData() {
      this.loader = true;
      let data = {
        account_id: this.account_id,
      };
      this.getAweberSelectedData(data)
      .then((response) => {
        if (response.data.status == true) {
            this.accounts_list = response.data.data.accounts_list;
          }
          this.loader = false;
          let data = {
            event_type: this.event_type,
            account_id: this.account_id,
          };
          this.$emit("set_account_id", data);
        })
        .catch(() => {
          this.loader = false;
        });
    },
    sendemit() {
      let data = {
        event_type: this.event_type,
        tag_list_id: this.tag_list_id,
      };
      this.$emit("set_tag_list_id", data);
    },
  },
  async mounted() {
    if (this.selected_account_id) {
      await this.aweberSelectedData();
    }
  },
});
</script>
