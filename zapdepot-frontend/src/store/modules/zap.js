/* eslint-disable no-empty-pattern */
import axios from '@/plugins/apiService.js'
const state = {
    getZapAll: [],
    getZapLog: [],
    zapLog: [],
};


// mutations
const mutations = {
    SET_ZAP_ALL(state, value) {
        state.getZapAll = value
    },
    SET_ZAP_LOG(state, value) {
        state.getZapLog = value
    },
    SET_ZAP_SINGLE_LOG(state , value) {
        state.zapLog = value
    }
}

// getters
const getters = {
        getZapAccounts(state) {
            return state.getZapAll
        },
        getZapLogs(state) {
            return state.getZapLog
        },
    }
    //actions
const actions = {
    async getAllZapAccounts({ commit } , data) {
        let resp = await axios.get('/api/get-all-zaps?page='+data)
        if (resp.data.status == true) {
            commit('SET_ZAP_ALL', resp.data.data)
        }
        return resp;
    },
    async logsGet({ commit }, data) {
        let resp = await axios.get('/api/get-all-logs' + data.parmas)
        if (resp.data.status == true) {
            commit('SET_ZAP_LOG', resp.data.data)
        }
        return resp;
    },

    async clearZapLog({ dispatch }, data) {
        let resp = await axios.get('/api/clear-all-logs')
        if (resp.data.status == true) {
            dispatch("logsGet",data)
        }
        return resp;
    },
    // eslint-disable-next-line no-empty-pattern
    async addZap({}, data) {
        let resp = await axios.post('/api/add-zap', data)
        return resp;
    },
    // eslint-disable-next-line no-empty-pattern
    async updateZapStatus({}, data) {
        let resp = await axios.post('/api/update-status-zap', data)
        return resp;
    },
    // eslint-disable-next-line no-empty-pattern
    async updateZapName({}, data) {
        let resp = await axios.post('/api/update-name-zap', data)
        return resp;
    },
    // eslint-disable-next-line no-empty-pattern
    async deleteZap({}, data) {
        console.log(data)
        let resp = await axios.get('/api/delete-zap/' + data)
        return resp;
    },
    // eslint-disable-next-line no-empty-pattern
    async SetSelectZap({}, data) {
        let resp = await axios.get('/api/get-zap/' + data)
        return resp;
    },
    async fetchZapLogs({commit}, data) {
        commit('SET_ZAP_SINGLE_LOG' , '')
        let resp = await axios.get('/api/get-zap-logs/' + data)
        if(resp.data.status) {
           commit('SET_ZAP_SINGLE_LOG' , resp.data.data)
        }
        return resp;
    },
    // eslint-disable-next-line no-empty-pattern
    async updateZap({}, data) {
     console.log(data);
        let resp = await axios.post('/api/update-zap', data)
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
