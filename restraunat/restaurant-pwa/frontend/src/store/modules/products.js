import axios from 'axios'

export default {
  namespaced: true,
  state: () => ({
    items:      [],
    categories: [],
    current:    null,
    loading:    false,
    meta:       {},
  }),
  mutations: {
    SET_PRODUCTS(state, { data, meta }) { state.items = data; state.meta = meta || {} },
    SET_CATEGORIES(state, data)         { state.categories = data },
    SET_CURRENT(state, product)         { state.current = product },
    SET_LOADING(state, val)             { state.loading = val },
  },
  actions: {
    async fetchProducts({ commit }, params = {}) {
      commit('SET_LOADING', true)
      try {
        const { data } = await axios.get('/products', { params })
        commit('SET_PRODUCTS', data)
      } finally {
        commit('SET_LOADING', false)
      }
    },
    async fetchCategories({ commit }) {
      const { data } = await axios.get('/categories')
      commit('SET_CATEGORIES', data.data || data)
    },
    async fetchProduct({ commit }, id) {
      commit('SET_LOADING', true)
      try {
        const { data } = await axios.get(`/products/${id}`)
        commit('SET_CURRENT', data.data || data)
      } finally {
        commit('SET_LOADING', false)
      }
    },
  },
  getters: {
    products:   s => s.items,
    categories: s => s.categories,
    current:    s => s.current,
    loading:    s => s.loading,
  },
}
