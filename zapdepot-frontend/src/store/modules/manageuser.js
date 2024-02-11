import axios from '@/plugins/apiService.js' 
const state = {
  getUserAll:[],
};


// mutations
const mutations = {
  SET_USER_ALL (state, value) {
      state.getUserAll = value
  },
}

// getters
const getters = {
  getUserAllData(state){
      return state.getUserAll
  }
}
//actions
const actions = {
  async getalluser({commit},page){
    let resp = await axios.get('/api/user/get-admin-user?page='+page)
    if(resp.data.status==true){
      commit('SET_USER_ALL',resp.data.data)
    }
    return resp;
  },
// eslint-disable-next-line no-empty-pattern
  async saveUser({},data) {
    let resp = await axios.post('/api/user/add-admin-user',data)
    return resp;
  },
// eslint-disable-next-line no-empty-pattern
  async getSingleUser({},data) {
    let resp = await axios.get('/api/user/get-single-user/'+data)
    return resp;
  },
// eslint-disable-next-line no-empty-pattern
  async deleteUser({},data) {
    let resp = await axios.get('/api/user/delete-user/'+data)
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
