import axios from '@/plugins/apiService.js'
const state = {
    getAllGohilevel: [],
    getAllGohilevelSingle: [],
    getAllActive: [],
    SgoTags: [],
    RgoTags: [],
    getAllDashData: '',
    getAllAdminDashData: '',
    getAllWebinarAccounts: [],
    allGoogleAccounts: [],
    allGoogleAccountsSheets: [],
    getAllWebinarEvent: '',
};


// mutations
const mutations = {
    SET_GO_HIGH_LEVEL_ACCOUNT_ALL(state, value) {
        state.getAllGohilevel = value
    },
    SET_ACTIVE_CAM_ACCOUNT_ALL(state, value) {
        state.getAllActive = value
    },
    SET_GO_TAGS(state, value) {
        state.SgoTags = value
    },
    SET_GO_TAGR(state, value) {
        state.RgoTags = value
    },
    SET_GO_HIGH_LEVEL_ACCOUNT_ALL_SINGLE(state, value) {
        state.getAllGohilevelSingle = value
    },
    SET_DASH_DATA(state, value) {
        state.getAllDashData = value
    },
    SET_ADMIN_DASH_DATA(state, value) {
        state.getAllAdminDashData = value
    },
    SET_WEBINAR_ALL_DATA(state, value) {
        state.getAllWebinarAccounts = value
    },
    SET_WEB_EVENTS(state, value) {
        state.getAllWebinarEvent = value
    },
    SET_GET_GOOGLE_ACCOUNTS(state, value) {
        state.allGoogleAccounts = value
    },
    SET_GET_GOOGLE_ACCOUNTS_SHEETS(state, value) {
        state.allGoogleAccountsSheets = value
    }
}

// getters
const getters = {
        getGohighLevelAccounts(state) {
            return state.getAllGohilevel
        },
        getActiveCamAccounts(state) {
            return state.getAllActive
        },
        getSGoTags(state) {
            return state.SgoTags
        },
        getRGoTags(state) {
            return state.RgoTags
        },
        getGohighLevelAccountsSingle(state) {
            return state.getAllGohilevelSingle
        },
        getDashData(state) {
            return state.getAllDashData
        },
        getAdminDashData(state) {
            return state.getAllAdminDashData
        },
        getWebinarAccounts(state) {
            return state.getAllWebinarAccounts
        },
        getAllWebinarEventAccount(state) {
            return state.getAllWebinarEvent
        },
        getGoogleAccounts(state) {
            return state.allGoogleAccounts
        },
        getalldriveSheetsGoogleAccounts(state) {
            return state.allGoogleAccountsSheets
        }
    }
    //actions
const actions = {
    async getAllGohilevelAccounts({ commit }) {
        let resp = await axios.get('/api/get-all-account-go')
        if (resp.data.status == true) {
            commit('SET_GO_HIGH_LEVEL_ACCOUNT_ALL', resp.data.data)
        }
        return resp;
    },
    // eslint-disable-next-line no-empty-pattern
    async connectToGoogleAccount({}, data) {
        let resp = await axios.post('/api/integration/connect-to-google-account',data)
        return resp;
    },
    // eslint-disable-next-line no-empty-pattern
    async addGoogleAccountauth({}, data) {
        let resp = await axios.post('/api/integration/add-connect-to-google-account',data)
        return resp;
    },
    // eslint-disable-next-line no-empty-pattern
    async addAweberAccountauth({}, data) {
        let resp = await axios.post('/api/integration/add-connect-to-aweber-account',data)
        return resp;
    },


    async getAllGoogleDATA({ commit }) {
        let resp = await axios.get('/api/get-all-google-accounts')
        if (resp.data.status == true) {
            commit('SET_GET_GOOGLE_ACCOUNTS', resp.data.data)
        }
        return resp;
    },

    async getGoogleSheets({ commit } ,data) {
        let resp = await axios.post('/api/get-all-google-accounts-sheets',data)
        if (resp.data.status == true) {
            commit('SET_GET_GOOGLE_ACCOUNTS_SHEETS', resp.data.data)
        }
        return resp;
    },

    // eslint-disable-next-line no-unused-vars
    async webHookUrlSave({ commit } ,data) {
        let resp = await axios.post('/api/webhooks-url-save',data)
        if (resp.data.status == true) {
            // commit('SET_GET_GOOGLE_ACCOUNTS_SHEETS', resp.data.data)
        }
        return resp;
    },

    async deleteGoogleAccount({ dispatch } , data) {
        let resp = await axios.get('/api/delete-google-accounts/'+data)
        if (resp.data.status == true) {
            dispatch("getAllGoogleDATA")
        }
        return resp;
    },

    async setAllGohilevelAccountsSingle({ commit }) {
        let resp = await axios.get('/api/get-all-account-go-single')
        if (resp.data.status == true) {
            commit('SET_GO_HIGH_LEVEL_ACCOUNT_ALL_SINGLE', resp.data.data)
        }
        return resp;
    },
    async addGohighlevelAccount({ dispatch }, data) { 

        let resp = await axios.post('/api/add-gohighlevel', data)
        if (resp.data.status == true) {
            dispatch('getAllGohilevelAccounts')
        }
        return resp;
    },
    // eslint-disable-next-line no-empty-pattern
    async addGohighlevelAccountSingle({}, data) {

        let resp = await axios.post('/api/add-gohighlevel-single', data)
        if (resp.data.status == true) {
            // dispatch('setAllGohilevelAccountsSingle')
        }
        return resp;
    },
    async deleteGohighaccount({ dispatch }, data) {
        console.log(data)
        let resp = await axios.get('/api/delete-gohighlevel-account/' + data.id)
        if (resp.data.status == true) {
            if (data.type == "agency") {
                dispatch('getAllGohilevelAccounts')
            } else {
                dispatch('setAllGohilevelAccountsSingle')
            }
        }
        return resp;
    },
    //active-campaign
    async getAllActiveCamAccounts({ commit }) {
        let resp = await axios.get('/api/get-all-account-active')
        if (resp.data.status == true) {
            commit('SET_ACTIVE_CAM_ACCOUNT_ALL', resp.data.data)
        }
        return resp;
    },
    async addActiveCamAccount({ dispatch }, data) {
        let resp = await axios.post('/api/add-acitve-campaign', data)
        if (resp.data.status == true) {
            dispatch('getAllActiveCamAccounts')
        }
        return resp;
    },
    async deleteActiveAccount({ dispatch }, data) {
        let resp = await axios.get('/api/delete-acitve-campaign-account/' + data)
        if (resp.data.status == true) {
            dispatch('getAllActiveCamAccounts')
        }
        return resp;
    },
    async getSGoTagsAccounts({ commit }, data) {
        let resp = await axios.post('/api/get-gohighlevel-tags', data)
        if (resp.data.status == true) {
            commit('SET_GO_TAGS', resp.data.data)
        }
        return resp;
    },
    async getRGoTagsAccounts({ commit }, data) {
        let resp = await axios.post('/api/get-gohighlevel-tags', data)
        if (resp.data.status == true) {
            commit('SET_GO_TAGR', resp.data.data)
        }
        return resp;
    },
    // eslint-disable-next-line no-empty-pattern
    async getActiveCampaignTags({}, data) {
        let resp = await axios.get('/api/get-active-campaign-tags/' + data)
        return resp;
    },
    // eslint-disable-next-line no-empty-pattern
    async getActiveCampaignList({}, data) {
        let resp = await axios.get('/api/get-active-campaign-list/' + data)
        return resp;
    },
    async getAllDashData({ commit }) {
        let resp = await axios.get('/api/get-dashboard-data')
        if (resp.data.status == true) {
            commit('SET_DASH_DATA', resp.data.data)
        }
        return resp;
    },
    async getAllAdminDashData({ commit }) {
        let resp = await axios.get('/api/user/get-dashboard')
        if (resp.data.status == true) {
            commit('SET_ADMIN_DASH_DATA', resp.data.data)
        }
        return resp;
    },
    // eslint-disable-next-line no-unused-vars
    async addGotoweninar({ commit, dispatch }, data) {
        let resp = await axios.post('/api/integration/add/gotowebinar', data)
        if (resp.data.status == true) {
            dispatch('getAllWebinarData')
        }
        return resp;
    },
    async getAllWebinarData({ commit }) {
        let resp = await axios.get('/api/integration-get-data-gotowebinar')
        if (resp.data.status == true) {
            commit('SET_WEBINAR_ALL_DATA', resp.data.data)
        }
        return resp;
    },
    async deleteWebinarSingle({ dispatch }, data) {
        let resp = await axios.get('/api/delete-single-gotowebinar/' + data.id)
        if (resp.data.status == true) {
            dispatch('getAllWebinarData')
        }
        return resp;
    },
    async getwebinarEvent({ commit }, data) {
        let resp = await axios.post('/api/connect-gotowebinar-events', data)
        if (resp.data.status == true) {
            commit('SET_WEB_EVENTS', resp.data.data)
        }
        return resp;
    },
}
export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
};
