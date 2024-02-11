<template>
  <loader :active="loader" />
  <div class="row">
    <div class="col-12 row">
      <div class="col-md-6 col-sm-12">
        <label>Active Campaign Account</label>
        <select
          class="form-control"
          v-if="getActiveCamAccounts.length"
          v-model="account_id"
          @change="changeAccout"
        ><option value="">Select Active Campaign Account</option>
          <option
            v-for="account in getActiveCamAccounts"
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
    <div class="col-12 row mt-2" v-if="account_id">
      <div class="col-md-6 col-sm-12">
        <label>Action Type</label>
        <select class="form-control" v-model="type" @change="getdata()">
            <option value="">Select Action Type</option>
          <option
            v-for="(account, index) in action_type_array"
            :key="index"
            :value="account.id"
          >
            {{ account.name }}
          </option>
        </select>
      </div>
    </div>
    <div class="col-12 row mt-2" v-if="type == 'tag'">
      <div class="col-md-6 col-sm-12">
        <label>Tag</label>
        <select
          class="form-control"
          v-model="tag_list_id"
          @change="set_tag_type_data()"
        >
            <option value="">Select Tag</option>
          <option
            v-for="(account, index) in tag_data"
            :key="index"
            :value="account.id"
          >
            {{ account.tag }}
          </option>
        </select>
      </div>
    </div>
    <div class="col-12 row mt-2" v-else-if="type == 'list'">
      <div class="col-md-6 col-sm-12">
        <label>List</label>
        <select
          class="form-control"
          v-model="tag_list_id"
          @change="set_tag_type_data()"
        >
        <option value="">Select List</option>
          <option
            v-for="(account, index) in list_data"
            :key="index"
            :value="account.id"
          >
            {{ account.name }}
          </option>
        </select>
      </div>
    </div>
  </div>
</template>
<script>
import { defineComponent } from "vue";
import { mapActions, mapGetters } from "vuex";
export default defineComponent({
  setup() {},
  emits: ["set_account_id", "set_tag_list_id", "set_action_type"],
  props: {
    getActiveCamAccounts: Array,
    selected_account_id: Number,
    action_type: String,
    event_type: String,
    selected_tag_list_id: Number,
  },
  watch: {
    selected_tag_list_id() {
      console.log(this.selected_tag_list_id);
    },
  },
  data() {
    return {
      loader: false,
      tag_data: [],
      list_data: [],
      action_type_array: [
        {
          id: "tag",
          name: "Tag",
        },
        {
          id: "list",
          name: "List",
        },
      ],
      selected_action_type: this.action_type,
      type: this.action_type,
      account_id: this.selected_account_id,
      tag_list_id: this.selected_tag_list_id,
    };
  },
  computed: {
    ...mapGetters({}),
  },
  methods: {
    ...mapActions({
      getActiveCampaignTags: "integration/getActiveCampaignTags",
      getActiveCampaignList: "integration/getActiveCampaignList",
    }),
    getdata() {
      if (this.type == "tag") {
        this.getactiveTags();
      } else if (this.type == "list") {
        this.getactiveList();
      }
      let data = {
        event_type: this.event_type,
        action_type: this.type,
      };
      this.$emit("set_action_type", data);
    },
    changeAccout() {
      let data = {
        event_type: this.event_type,
        account_id: this.account_id,
      };
      this.$emit("set_account_id", data);
      this.type = "";
    },
    getactiveTags() {
      this.loader = true;
      this.getActiveCampaignTags(this.account_id)
        .then((response) => {
          this.loader = false;
          if (response.data.status == true) {
            this.tag_data = response.data.data;
          }
        })
        .catch((error) => {
          this.loader = false;
          this.toast.error(error.response.data.message);
        });
    },
    getactiveList() {
      this.loader = true;
      this.getActiveCampaignList(this.account_id)
        .then((response) => {
          this.loader = false;
          if (response.data.status == true) {
            this.list_data = response.data.data;
          }
        })
        .catch((error) => {
          this.loader = false;
          this.toast.error(error.response.data.message);
        });
    },
    set_tag_type_data() {
      let data = {
        event_type: this.event_type,
        tag_list_id: this.tag_list_id,
      };
      this.$emit("set_tag_list_id", data);
    },
  },
  async mounted() {
    if (this.selected_account_id) {
      this.getdata();
    }
  },
});
</script>

