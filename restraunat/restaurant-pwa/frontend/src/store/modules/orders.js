import axios from 'axios'

const orders = {
  namespaced: true,
  state: () => ({ items: [], current: null, loading: false }),
  mutations: {
    SET_ORDERS(state, items) { state.items = items },
    SET_CURRENT(state, order) { state.current = order },
    SET_LOADING(state, val) { state.loading = val },
  },
  getters: { items: s => s.items, current: s => s.current, loading: s => s.loading },
  actions: {
    async fetchOrders({ commit }) {
      commit('SET_LOADING', true)
      try {
        const { data } = await axios.get('/orders')
        commit('SET_ORDERS', data.data || data)
      } finally { commit('SET_LOADING', false) }
    },
    async createOrder({ commit, dispatch }, payload) {
      const { data } = await axios.post('/orders', payload)
      await dispatch('fetchOrders')
      return data
    },
    async fetchOrder({ commit }, id) {
      const { data } = await axios.get(`/orders/${id}`)
      commit('SET_CURRENT', data.data || data)
    },
  },
}

export default orders
