<template>
  <div class="page home-page">
    <!-- Hero -->
    <div class="hero">
      <div class="hero-bg"></div>
      <div class="hero-content">
        <p class="hero-tag">Быстрая доставка · 30 минут</p>
        <h1 class="hero-title">Вкусная еда<br><span>прямо к вам</span></h1>
        <router-link to="/menu" class="btn btn-primary hero-btn">Смотреть меню</router-link>
      </div>
    </div>

    <!-- Категории -->
    <section class="section">
      <h3 class="section-title">Категории</h3>
      <div class="categories-row">
        <router-link
          v-for="cat in categories" :key="cat.id"
          :to="`/menu?category_id=${cat.id}`"
          class="cat-chip"
        >{{ cat.name }}</router-link>
      </div>
    </section>

    <!-- Популярное -->
    <section class="section">
      <div class="section-header">
        <h3 class="section-title">Популярное</h3>
        <router-link to="/menu" class="see-all">Все →</router-link>
      </div>
      <div v-if="loading" class="loader-wrap"><div class="loader"></div></div>
      <div v-else class="products-grid">
        <ProductCard v-for="p in products.slice(0,6)" :key="p.id" :product="p" />
      </div>
    </section>

    <!-- Push -->
    <div v-if="isAuth && !subscribed" class="push-banner">
      <div class="push-icon">🔔</div>
      <div class="push-text">
        <strong>Включить уведомления</strong>
        <p>Статус заказа в реальном времени</p>
      </div>
      <button class="btn btn-primary push-btn" @click="subscribePush">Вкл</button>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { useStore } from 'vuex'
import axios from 'axios'
import ProductCard from '../ui/ProductCard.vue'

const store      = useStore()
const loading    = computed(() => store.getters['products/loading'])
const products   = computed(() => store.getters['products/products'])
const categories = computed(() => store.getters['products/categories'])
const isAuth     = computed(() => store.getters['auth/isAuthenticated'])
const subscribed = ref(false)

onMounted(async () => {
  await store.dispatch('products/fetchProducts')
  await store.dispatch('products/fetchCategories')
  if (isAuth.value) await store.dispatch('cart/fetchCart')
})

async function subscribePush() {
  if (!('serviceWorker' in navigator) || !('PushManager' in window)) return
  try {
    const reg = await navigator.serviceWorker.ready
    const sub = await reg.pushManager.subscribe({
      userVisibleOnly: true,
      applicationServerKey: urlBase64ToUint8Array(import.meta.env.VITE_VAPID_PUBLIC_KEY || ''),
    })
    const json = sub.toJSON()
    await axios.post('/push/subscribe', { endpoint: json.endpoint, keys: json.keys })
    subscribed.value = true
  } catch(e) { console.log('Push not available') }
}

function urlBase64ToUint8Array(base64String) {
  const padding = '='.repeat((4 - base64String.length % 4) % 4)
  const base64  = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/')
  const raw     = window.atob(base64)
  return Uint8Array.from([...raw].map(c => c.charCodeAt(0)))
}
</script>

<style scoped>
.hero {
  position: relative; margin: -16px -16px 24px;
  padding: 40px 20px 36px;
  overflow: hidden;
}
.hero-bg {
  position: absolute; inset: 0;
  background: linear-gradient(135deg, #1a0808 0%, #0e0e0e 60%);
}
.hero-bg::before {
  content: '';
  position: absolute; top: -40px; right: -40px;
  width: 220px; height: 220px;
  background: radial-gradient(circle, rgba(255,77,77,.2) 0%, transparent 70%);
  border-radius: 50%;
}
.hero-content { position: relative; z-index: 1; }
.hero-tag {
  font-size: .75rem; font-weight: 700; letter-spacing: .1em; text-transform: uppercase;
  color: var(--clr-primary); margin-bottom: 12px;
}
.hero-title {
  font-size: 2.2rem; font-weight: 800; line-height: 1.1;
  letter-spacing: -.04em; margin-bottom: 24px;
  font-family: var(--font-display);
}
.hero-title span { color: var(--clr-primary); }
.hero-btn { padding: 14px 28px; font-size: .9rem; }

.section { margin-bottom: 28px; }
.section-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; }
.section-title { font-size: 1.1rem; font-weight: 800; letter-spacing: -.02em; margin-bottom: 0; }
.see-all { font-size: .82rem; color: var(--clr-primary); font-weight: 700; text-decoration: none; }

.categories-row { display: flex; gap: 8px; overflow-x: auto; padding-bottom: 4px; margin-bottom: 0; scrollbar-width: none; }
.categories-row::-webkit-scrollbar { display: none; }
.cat-chip {
  white-space: nowrap; padding: 9px 18px;
  background: var(--clr-bg-card);
  border: 1px solid var(--clr-border);
  border-radius: 100px; color: var(--clr-text-soft);
  text-decoration: none; font-size: .82rem; font-weight: 600;
  transition: all .2s;
}
.cat-chip:hover { border-color: var(--clr-primary); color: var(--clr-primary); }

.products-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }

.push-banner {
  display: flex; align-items: center; gap: 12px;
  background: var(--clr-bg-card); border: 1px solid var(--clr-border-soft);
  border-left: 3px solid var(--clr-primary);
  border-radius: var(--radius); padding: 14px;
}
.push-icon { font-size: 1.5rem; }
.push-text { flex: 1; }
.push-text strong { font-size: .9rem; font-weight: 700; }
.push-text p { font-size: .78rem; color: var(--clr-text-muted); margin-top: 2px; }
.push-btn { padding: 9px 16px; font-size: .82rem; white-space: nowrap; }
</style>
