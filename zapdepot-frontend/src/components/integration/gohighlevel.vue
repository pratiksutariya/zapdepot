<template>
  <div>
    <loader :active="loader" />
  <div class="row">
    <div class="col-12 row">
      <div class="col-md-6 col-sm-12">
        <label>Ready Funnels Pro Account</label>
        <select
          v-if="getGohighLevelAccounts.length"
          class="form-control"
          v-model="account_id"
          @change="getgohileveltags()"
        >
        <option value="">Select Ready Funnels Pro Account</option>
          <option
            v-for="account in getGohighLevelAccounts"
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
    <div class="col-12 row mt-2" v-if="getTags.length">
      <div class="col-md-6 col-sm-12">
        <label>Ready Funnels Pro Tags</label>
        <select class="form-control"  v-model="tag_list_id" @change="sendemit()">
        <option value="">Select Ready Funnels Pro Tags</option>
          <option v-for="gotag in getTags" :key="gotag.id" :value="gotag.name">
            {{ gotag.name }}
          </option>
        </select>
      </div>
    </div>
  </div>
  </div>
</template>
<script>
import { defineComponent } from "vue";
import { mapGetters, mapActions } from "vuex";
export default defineComponent({
  setup() {},
  emits: ["set_account_id", "set_tag_list_id"],
  props: {
    getGohighLevelAccounts: Array,
    selected_account_id: Number,
    selected_tag_list_id: String,
    event_type: String,
  },
  data() {
    return {
      loader: false,
      getTags: [],
      tag_list_id: this.selected_tag_list_id,
      account_id: this.selected_account_id,
      // zap:
    };
  },
  computed: {
    ...mapGetters({}),
    // tag_list_id: function () {
    //     return this.selected_tag_list_id;
    // },
    // account_id: function () {
    //     return this.selected_account_id;
    // },
  },
  methods: {
    ...mapActions({
      getSGoTagsAccounts: "integration/getSGoTagsAccounts",
    }),
    getgohileveltags() {
      this.loader = true;
      let data = {
        account_id: this.account_id,
      };
      this.getSGoTagsAccounts(data)
        .then((response) => {
          if (response.data.status == true) {
            this.getTags = response.data.data;
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
      await this.getgohileveltags();
    }
  },
});
</script>
