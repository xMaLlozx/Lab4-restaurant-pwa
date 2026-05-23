import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import axios from 'axios'

axios.defaults.baseURL = 'http://localhost:8000/api'
axios.defaults.headers.common['Accept'] = 'application/json'

axios.interceptors.request.use(config => {
  const token = localStorage.getItem('token')
  if (token) config.headers.Authorization = `Bearer ${token}`
  return config
})

axios.interceptors.response.use(
  res => res,
  err => {
    if (err.response?.status === 401) {
      store.dispatch('auth/logout')
      router.push('/login')
    }
    return Promise.reject(err)
  }
)

const app = createApp(App)
app.use(router)
app.use(store)
app.mount('#app')
