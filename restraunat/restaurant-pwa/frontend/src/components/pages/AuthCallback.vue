<script setup>
import { onMounted } from 'vue'
import { useStore } from 'vuex'
import { useRouter, useRoute } from 'vue-router'

const store  = useStore()
const router = useRouter()
const route  = useRoute()

onMounted(async () => {
  // Токен из URL (прямой переход)
  const token = route.query.token
  if (token) {
    await store.dispatch('auth/setTokenFromCallback', token)
    router.push('/')
    return
  }

  // Токен через postMessage от основного окна
  window.addEventListener('message', async (event) => {
    if (event.origin !== 'http://localhost:3000') return
    if (event.data?.type === 'AUTH_TOKEN') {
      await store.dispatch('auth/setTokenFromCallback', event.data.token)
      router.push('/')
    }
  })
})
</script>