import axios from 'axios'

export default {
  namespaced: true,
  state: () => ({
    items: [],
    loading: false,
    toast: null,
  }),
  mutations: {
    SET_ITEMS(state, items) { state.items = items },
    SET_LOADING(state, val) { state.loading = val },
    SET_TOAST(state, msg)   { state.toast = msg },
  },
  getters: {
    items:   s => s.items,
    count:   s => s.items.reduce((sum, i) => sum + i.quantity, 0),
    total:   s => s.items.reduce((sum, i) => sum + i.subtotal, 0),
    loading: s => s.loading,
    toast:   s => s.toast,
  },
  actions: {
    async fetchCart({ commit }) {
      commit('SET_LOADING', true)
      try {
        const { data } = await axios.get('/cart')
        commit('SET_ITEMS', data.data || data)
      } finally {
        commit('SET_LOADING', false)
      }
    },
    async addItem({ dispatch, commit }, { product_id, quantity = 1 }) {
      await axios.post('/cart', { product_id, quantity })
      await dispatch('fetchCart')
    },
    async updateItem({ dispatch }, { id, quantity }) {
      await axios.put(`/cart/${id}`, { quantity })
      await dispatch('fetchCart')
    },
    async removeItem({ dispatch }, id) {
      await axios.delete(`/cart/${id}`)
      await dispatch('fetchCart')
    },
    async clearCart({ commit }) {
      await axios.delete('/cart')
      commit('SET_ITEMS', [])
    },
  },
}
