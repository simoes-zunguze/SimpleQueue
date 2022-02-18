import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
    token : false,
    url: 'http://localhost:8000/api',
  },
  mutations: {
    setToken: (state, token) =>{ state.token = token}
  },
  getters:{
    getToken(state){return state.token},
    getUrl(state){return state.url}
  },
  actions: {
  },
  modules: {
  }
})
