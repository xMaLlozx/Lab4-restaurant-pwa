import axios from 'axios'

export default {
  namespaced: true,
  state: () => ({
    user:  JSON.parse(localStorage.getItem('user') || 'null'),
    token: localStorage.getItem('token') || null,
  }),
  getters: {
    isAuthenticated: s => !!s.token,
    user: s => s.user,
  },
  mutations: {
    SET_AUTH(state, { user, token }) {
      state.user  = user
      state.token = token
      localStorage.setItem('user',  JSON.stringify(user))
      localStorage.setItem('token', token)
    },
    CLEAR_AUTH(state) {
      state.user  = null
      state.token = null
      localStorage.removeItem('user')
      localStorage.removeItem('token')
    },
    UPDATE_USER(state, user) {
      state.user = user
      localStorage.setItem('user', JSON.stringify(user))
    },
  },
  actions: {
    async login({ commit }, credentials) {
      const { data } = await axios.post('/login', credentials)
      commit('SET_AUTH', data)
    },
    async register({ commit }, payload) {
      const { data } = await axios.post('/register', payload)
      commit('SET_AUTH', data)
    },
    async logout({ commit }) {
      try { await axios.post('/logout') } catch {}
      commit('CLEAR_AUTH')
    },
    async setTokenFromCallback({ commit }, token) {
  	localStorage.setItem('token', token)
  	axios.defaults.headers.common['Authorization'] = `Bearer ${token}`
  	try {
    	   const { data } = await axios.get('/user')
    	   commit('SET_AUTH', { user: data, token })
        } catch(e) {
    	  commit('SET_AUTH', { user: { name: 'Пользователь' }, token })
  	}
    },
    async updateProfile({ commit }, payload) {
      const { data } = await axios.put('/user', payload)
      commit('UPDATE_USER', data)
    },
  },
}
