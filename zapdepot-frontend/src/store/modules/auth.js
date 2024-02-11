import axios from '@/plugins/apiService.js' 
import router from '@/router'
const state = {
  authenticated:false,
  user:'',
  idToken:'',
};


// mutations
const mutations = {
 SET_AUTHENTICATED (state, value) {
        state.authenticated = value
  },
  SET_USER (state, value) {
      state.user = value
  },
  SET_ID_TOKEN(state, value){
      state.idToken = value
  }
}

// getters
const getters = {
  authenticated(state){
      return state.authenticated
  },
  user(state){
      return state.user
  },
  getIdToken(state){
      return state.idToken
  }
}
//actions
const actions = {
  /** User login  **/
  async login({ commit }, data) {
    let resp = await axios.post('/api/login', data)
    if(resp.data.status==true){
      commit('SET_USER',resp.data.data.user_data)
      commit('SET_ID_TOKEN',resp.data.data.accesstoken)
      commit('SET_AUTHENTICATED',true)
    }
    return resp;
  },
  async updateUser({ commit }, data) {
    let resp = await axios.post('/api/update-user', data)
    if(resp.data.status==true){
      commit('SET_USER',resp.data.data)
    }
    return resp;
  },
// eslint-disable-next-line no-empty-pattern
  async updateUserPassword({}, data) {
    let resp = await axios.post('/api/update-user-password', data)
    return resp;
  },
  async logout({ commit }){
    let resp = await axios.post('/api/logout')
      commit('SET_USER','')
      commit('SET_ID_TOKEN','')
      commit('SET_AUTHENTICATED',false)
    return resp;
  },
  async logoutIfToken({ commit }){
      commit('SET_USER','')
      commit('SET_ID_TOKEN','')
      commit('SET_AUTHENTICATED',false)
      router.push("/login");
  }
}
export default {
  namespaced: true,
  state,
  getters,
  actions,
  mutations
};
