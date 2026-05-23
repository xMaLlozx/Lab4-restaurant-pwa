import axios from 'axios'

export default {
  namespaced: true,
  state: () => ({ items: [], current: null }),
  mutations: {
    SET_NEWS(state, items)    { state.items   = items },
    SET_CURRENT(state, item)  { state.current = item  },
  },
  getters: { items: s => s.items, current: s => s.current },
  actions: {
    async fetchNews({ commit }) {
      const { data } = await axios.get('/news')
      commit('SET_NEWS', data.data || data)
    },
    async fetchNewsItem({ commit }, id) {
      const { data } = await axios.get(`/news/${id}`)
      commit('SET_CURRENT', data.data || data)
    },
  },
}
