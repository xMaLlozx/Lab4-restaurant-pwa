<template>
  <div class="product-card" @click="$router.push(`/product/${product.id}`)">
    <div class="product-img">
      <img v-if="product.image" :src="product.image" :alt="product.name" />
      <div v-else class="product-img-placeholder">
        <div class="plate-art">
          <div class="plate-circle"></div>
          <div class="plate-inner"></div>
          <div class="plate-shine"></div>
        </div>
      </div>
      <div class="product-cat-badge" v-if="product.category">{{ product.category.name }}</div>
    </div>
    <div class="product-info">
      <h4 class="product-name">{{ product.name }}</h4>
      <p class="product-weight" v-if="product.weight">{{ product.weight }} г · {{ product.calories }} ккал</p>
      <div class="product-footer">
        <span class="product-price">{{ product.price }} ₽</span>
        <button class="add-btn" :class="{ added: justAdded }" @click.stop="addToCart">
          <span class="add-btn-icon">
            <svg v-if="!justAdded" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            <svg v-else viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
          </span>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'

const props     = defineProps({ product: Object })
const store     = useStore()
const router    = useRouter()
const justAdded = ref(false)
let adding      = false

async function addToCart() {
  if (adding) return
  if (!store.getters['auth/isAuthenticated']) { router.push('/login'); return }
  adding = true
  await store.dispatch('cart/addItem', { product_id: props.product.id, quantity: 1 })
  justAdded.value = true
  setTimeout(() => { justAdded.value = false; adding = false }, 1500)
}
</script>

<style scoped>
.product-card {
  background: var(--clr-bg-card);
  border-radius: var(--radius);
  overflow: hidden;
  cursor: pointer;
  border: 1px solid var(--clr-border);
  transition: box-shadow .25s;
}
.product-card:hover { box-shadow: 0 8px 24px rgba(0,0,0,.5); }
.product-card:active { opacity: .92; }

.product-img {
  width: 100%; aspect-ratio: 1/1;
  background: #181818;
  display: flex; align-items: center; justify-content: center;
  overflow: hidden; position: relative;
}
.product-img img { width: 100%; height: 100%; object-fit: cover; }

.product-img-placeholder {
  width: 100%; height: 100%;
  background: linear-gradient(145deg, #1c1c1c, #222);
  display: flex; align-items: center; justify-content: center;
}
.plate-art { position: relative; width: 56px; height: 56px; }
.plate-circle {
  position: absolute; inset: 0; border-radius: 50%;
  background: #2a2a2a; border: 3px solid #333;
}
.plate-inner {
  position: absolute; inset: 10px; border-radius: 50%;
  background: #242424; border: 1.5px solid #2e2e2e;
}
.plate-shine {
  position: absolute; top: 8px; left: 14px;
  width: 12px; height: 6px; border-radius: 50%;
  background: rgba(255,255,255,.06); transform: rotate(-30deg);
}

.product-cat-badge {
  position: absolute; top: 8px; left: 8px;
  background: rgba(0,0,0,.75); backdrop-filter: blur(6px);
  color: var(--clr-text-soft); font-size: .6rem; font-weight: 700;
  padding: 3px 8px; border-radius: 100px;
  letter-spacing: .05em; text-transform: uppercase;
}

.product-info  { padding: 10px 12px 12px; }
.product-name  { font-size: .88rem; font-weight: 700; margin-bottom: 3px; line-height: 1.3; }
.product-weight { font-size: .72rem; color: var(--clr-text-muted); margin-bottom: 10px; }
.product-footer { display: flex; align-items: center; justify-content: space-between; }
.product-price  { font-weight: 800; font-size: .95rem; }

.add-btn {
  width: 32px; height: 32px; flex-shrink: 0;
  background: var(--clr-primary);
  border: none; border-radius: 9px;
  cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: background .2s;
}
.add-btn.added { background: #2e7d32; }
.add-btn-icon {
  display: flex; align-items: center; justify-content: center;
  width: 14px; height: 14px; color: #fff; pointer-events: none;
}
.add-btn-icon svg { width: 14px; height: 14px; stroke: #fff; }
</style>
