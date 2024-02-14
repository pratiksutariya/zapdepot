<template>
  <div>
    <loader :active="loader" />
    <div class="row">
      <div class="col-12 row">
        <div class="col-md-6 col-sm-12">
          <div class="form-group">
            <label for="">Zaps</label>
            <select
              class="form-control"
              v-model="account_id"
              id=""
              @change="searchZapData()"
            >
              <option value="">All Zap Logs</option>
              <option v-for="zap in zapdata" :key="zap.id" :value="zap.id">
                {{ zap.name }}
              </option>
            </select>
          </div>
          <div>
            <!-- <button @click="resetData()" class=" btn btn-outline-primary">
              <i class="mr-2 nav-icon fas fa-refresh"></i>
              Reset
            </button> -->
            <div class="mr-2" role="group" aria-label="First group">
              <button type="button" data-toggle="tooltip" data-placement="top" title="Fetch All Logs" class="btn btn-secondary " @click="resetData()"><i class="nav-icon fas fa-refresh"></i></button>
              <button type="button" class="btn btn-secondary ml-2" title="Clear All Logs" @click="clearLogs()"><i class="nav-icon fas fa-trash"></i></button>
              <button type="button" class="btn btn-secondary ml-2" title="Error All Logs" @click="go_to_error_log()"><i class="nav-icon fas fa-ban"></i></button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      account_id : "",
    }
  },
  computed: {},
  props: {
    zapdata: Array,
  },
  methods: {
    searchZapData() {
      this.$emit('filterzap',this.account_id)
    },
    resetData() {
      this.account_id = '';
      this.$emit('filterzap',this.account_id)
    },
    clearLogs() {
      this.$emit('clear-zap-log',this.account_id)
    },
    go_to_error_log() {
      this.$router.push({ name: "ErrorLog" });
    }
  },
};
</script>

