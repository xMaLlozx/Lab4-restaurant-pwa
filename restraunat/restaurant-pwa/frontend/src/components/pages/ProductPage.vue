<template>
  <div class="page">
    <button class="back-btn" @click="$router.back()">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="15 18 9 12 15 6"/></svg>
      Назад
    </button>

    <div v-if="loading" class="loader-wrap"><div class="loader"></div></div>

    <div v-else-if="product" class="product-detail">
      <!-- Фото -->
      <div class="product-hero">
        <img v-if="product.image" :src="product.image" :alt="product.name" />
        <div v-else class="product-hero-placeholder">
          <div class="hero-plate">
            <div class="hero-plate-outer"></div>
            <div class="hero-plate-inner"></div>
            <div class="hero-plate-shine"></div>
          </div>
          <span class="hero-cat-label">{{ product.category?.name }}</span>
        </div>
      </div>

      <!-- Инфо -->
      <div class="product-info card">
        <div class="product-cat">{{ product.category?.name }}</div>
        <h1 class="product-name">{{ product.name }}</h1>
        <p class="product-desc">{{ product.description }}</p>

        <div class="product-meta" v-if="product.weight || product.calories">
          <div class="meta-pill" v-if="product.weight">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
            {{ product.weight }} г
          </div>
          <div class="meta-pill" v-if="product.calories">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2a7 7 0 017 7c0 5-7 13-7 13S5 14 5 9a7 7 0 017-7z"/></svg>
            {{ product.calories }} ккал
          </div>
        </div>

        <div class="buy-row">
          <span class="big-price">{{ product.price }} ₽</span>
          <div class="qty-control">
            <button class="qty-btn" @click="dec">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/></svg>
            </button>
            <span class="qty-val">{{ qty }}</span>
            <button class="qty-btn" @click="inc">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            </button>
          </div>
        </div>

        <button class="btn btn-primary btn-full add-cart-btn" @click="addToCart">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 001.98 1.61h9.72a2 2 0 001.98-1.61L23 6H6"/></svg>
          В корзину · {{ product.price * qty }} ₽
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useStore } from 'vuex'
import { useRoute, useRouter } from 'vue-router'

const store   = useStore()
const route   = useRoute()
const router  = useRouter()
const product = computed(() => store.getters['products/current'])
const loading = computed(() => store.getters['products/loading'])
const qty     = ref(1)

onMounted(() => store.dispatch('products/fetchProduct', route.params.id))

function inc() { qty.value++ }
function dec() { if (qty.value > 1) qty.value-- }

async function addToCart() {
  if (!store.getters['auth/isAuthenticated']) { router.push('/login'); return }
  await store.dispatch('cart/addItem', { product_id: product.value.id, quantity: qty.value })
  router.push('/cart')
}
</script>

<style scoped>
.back-btn {
  display: flex; align-items: center; gap: 4px;
  background: none; border: none; color: var(--clr-text-muted);
  font-size: .9rem; font-family: var(--font-main); cursor: pointer;
  margin-bottom: 16px; padding: 0;
}
.back-btn svg { width: 18px; height: 18px; }
.back-btn:hover { color: var(--clr-text); }

.product-hero {
  width: calc(100% + 32px); margin: -16px -16px 0;
  aspect-ratio: 4/3; overflow: hidden; position: relative;
  max-height: 260px;
}
.product-hero img { width: 100%; height: 100%; object-fit: cover; }

.product-hero-placeholder {
  width: 100%; height: 100%;
  background: linear-gradient(145deg, #1a1a1a, #222);
  display: flex; flex-direction: column;
  align-items: center; justify-content: center; gap: 16px;
}
.hero-plate { position: relative; width: 90px; height: 90px; }
.hero-plate-outer {
  position: absolute; inset: 0; border-radius: 50%;
  background: #282828; border: 4px solid #333;
}
.hero-plate-inner {
  position: absolute; inset: 14px; border-radius: 50%;
  background: #222; border: 2px solid #2e2e2e;
}
.hero-plate-shine {
  position: absolute; top: 12px; left: 22px;
  width: 18px; height: 9px; border-radius: 50%;
  background: rgba(255,255,255,.05); transform: rotate(-30deg);
}
.hero-cat-label {
  font-size: .75rem; font-weight: 700; text-transform: uppercase;
  letter-spacing: .08em; color: var(--clr-text-muted);
}

.product-info {
  margin-top: -16px; position: relative; z-index: 1;
  border-radius: var(--radius) var(--radius) 0 0;
}
.product-cat  { font-size: .75rem; font-weight: 700; color: var(--clr-primary); text-transform: uppercase; letter-spacing: .06em; margin-bottom: 8px; }
.product-name { font-size: 1.6rem; font-weight: 800; letter-spacing: -.03em; margin-bottom: 10px; font-family: var(--font-display); }
.product-desc { color: var(--clr-text-muted); font-size: .9rem; line-height: 1.6; margin-bottom: 16px; }

.product-meta { display: flex; gap: 10px; margin-bottom: 20px; }
.meta-pill {
  display: flex; align-items: center; gap: 6px;
  background: var(--clr-bg-elevated); border: 1px solid var(--clr-border);
  padding: 7px 12px; border-radius: 100px;
  font-size: .82rem; color: var(--clr-text-soft); font-weight: 600;
}
.meta-pill svg { width: 14px; height: 14px; color: var(--clr-primary); }

.buy-row {
  display: flex; align-items: center; justify-content: space-between;
  margin-bottom: 16px;
}
.big-price { font-size: 2rem; font-weight: 800; letter-spacing: -.04em; }

.qty-control { display: flex; align-items: center; gap: 12px; }
.qty-btn {
  width: 38px; height: 38px; border-radius: 50%;
  background: var(--clr-bg-elevated); border: 1px solid var(--clr-border-soft);
  color: var(--clr-text); cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: all .2s;
}
.qty-btn svg { width: 14px; height: 14px; }
.qty-btn:hover { border-color: var(--clr-primary); color: var(--clr-primary); }
.qty-val { font-size: 1.1rem; font-weight: 800; min-width: 24px; text-align: center; }

.add-cart-btn { gap: 10px; padding: 16px; font-size: .95rem; }
.add-cart-btn svg { width: 18px; height: 18px; }
</style>
