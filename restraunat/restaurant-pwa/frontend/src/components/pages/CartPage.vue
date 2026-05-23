<template>
  <div class="page">
    <h2 class="page-title">Корзина</h2>

    <div v-if="loading" class="loader-wrap"><div class="loader"></div></div>

    <div v-else-if="items.length === 0" class="empty-cart">
      <div class="empty-icon">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 001.98 1.61h9.72a2 2 0 001.98-1.61L23 6H6"/></svg>
      </div>
      <h3>Корзина пуста</h3>
      <p>Добавьте что-нибудь из меню</p>
      <router-link to="/menu" class="btn btn-primary">Перейти в меню</router-link>
    </div>

    <div v-else>
      <div class="cart-list">
        <div v-for="item in items" :key="item.id" class="cart-item card">
          <!-- Заглушка товара -->
          <div class="item-thumb">
            <img v-if="item.product?.image" :src="item.product.image" :alt="item.product.name" />
            <div v-else class="item-thumb-placeholder">
              <div class="thumb-plate"></div>
            </div>
          </div>

          <div class="item-body">
            <h4 class="item-name">{{ item.product?.name }}</h4>
            <span class="item-unit-price">{{ item.product?.price }} ₽ · 1 шт</span>

            <div class="item-controls">
              <div class="qty-row">
                <button class="qty-btn" @click="update(item, item.quantity - 1)">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="5" y1="12" x2="19" y2="12"/></svg>
                </button>
                <span class="qty-val">{{ item.quantity }}</span>
                <button class="qty-btn" @click="update(item, item.quantity + 1)">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
                </button>
                <button class="del-btn" @click="remove(item.id)">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                </button>
              </div>
              <strong class="item-subtotal">{{ item.subtotal }} ₽</strong>
            </div>
          </div>
        </div>
      </div>

      <!-- Итого -->
      <div class="summary card">
        <div class="summary-row">
          <span>Позиций</span>
          <span>{{ cartCount }}</span>
        </div>
        <div class="summary-row">
          <span>Доставка</span>
          <span class="free-delivery">Бесплатно</span>
        </div>
        <div class="summary-divider"></div>
        <div class="summary-row total-row">
          <span>Итого</span>
          <strong>{{ cartTotal }} ₽</strong>
        </div>
        <router-link to="/checkout" class="btn btn-primary btn-full checkout-btn">
          Оформить заказ · {{ cartTotal }} ₽
        </router-link>
        <button class="btn btn-ghost btn-full clear-btn" @click="clear">Очистить корзину</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted } from 'vue'
import { useStore } from 'vuex'

const store     = useStore()
const items     = computed(() => store.getters['cart/items'])
const loading   = computed(() => store.getters['cart/loading'])
const cartCount = computed(() => store.getters['cart/count'])
const cartTotal = computed(() => store.getters['cart/total'])

onMounted(() => store.dispatch('cart/fetchCart'))

function update(item, qty) {
  if (qty < 1) return remove(item.id)
  store.dispatch('cart/updateItem', { id: item.id, quantity: qty })
}
function remove(id) { store.dispatch('cart/removeItem', id) }
function clear()    { store.dispatch('cart/clearCart') }
</script>

<style scoped>
.empty-cart {
  display: flex; flex-direction: column; align-items: center;
  text-align: center; padding: 80px 0; gap: 12px;
}
.empty-icon {
  width: 72px; height: 72px; border-radius: 50%;
  background: var(--clr-bg-card); border: 1px solid var(--clr-border);
  display: flex; align-items: center; justify-content: center;
  margin-bottom: 4px;
}
.empty-icon svg { width: 28px; height: 28px; color: var(--clr-text-muted); }
.empty-cart h3 { font-size: 1.1rem; font-weight: 700; }
.empty-cart p  { color: var(--clr-text-muted); font-size: .88rem; }

.cart-list { display: flex; flex-direction: column; gap: 10px; margin-bottom: 16px; }

.cart-item {
  display: flex; gap: 12px; padding: 14px;
}

.item-thumb {
  width: 64px; height: 64px; flex-shrink: 0;
  border-radius: var(--radius-sm); overflow: hidden;
}
.item-thumb img { width: 100%; height: 100%; object-fit: cover; }
.item-thumb-placeholder {
  width: 100%; height: 100%;
  background: var(--clr-bg-elevated);
  display: flex; align-items: center; justify-content: center;
}
.thumb-plate {
  width: 36px; height: 36px; border-radius: 50%;
  background: #2a2a2a; border: 3px solid #333;
  box-shadow: inset 0 0 0 6px #242424;
}

.item-body { flex: 1; display: flex; flex-direction: column; gap: 4px; }
.item-name { font-size: .92rem; font-weight: 700; letter-spacing: -.01em; }
.item-unit-price { font-size: .75rem; color: var(--clr-text-muted); }

.item-controls {
  display: flex; align-items: center;
  justify-content: space-between; margin-top: 6px;
}
.qty-row { display: flex; align-items: center; gap: 6px; }

.qty-btn {
  width: 30px; height: 30px; border-radius: var(--radius-xs);
  background: var(--clr-bg-elevated); border: 1px solid var(--clr-border);
  color: var(--clr-text); cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: all .15s;
}
.qty-btn svg { width: 12px; height: 12px; }
.qty-btn:hover { border-color: var(--clr-primary); color: var(--clr-primary); }

.qty-val {
  min-width: 24px; text-align: center;
  font-weight: 700; font-size: .9rem;
}

.del-btn {
  width: 30px; height: 30px; border-radius: var(--radius-xs);
  background: transparent; border: 1px solid var(--clr-border);
  color: var(--clr-text-muted); cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: all .15s; margin-left: 2px;
}
.del-btn svg { width: 13px; height: 13px; }
.del-btn:hover { border-color: #ef5350; color: #ef5350; }

.item-subtotal { font-weight: 800; font-size: .95rem; }

/* Summary */
.summary { display: flex; flex-direction: column; gap: 12px; }
.summary-row { display: flex; justify-content: space-between; align-items: center; font-size: .9rem; color: var(--clr-text-soft); }
.free-delivery { color: #66bb6a; font-weight: 600; font-size: .82rem; }
.summary-divider { height: 1px; background: var(--clr-border); }
.total-row { font-size: 1rem; color: var(--clr-text); }
.total-row strong { font-size: 1.2rem; font-weight: 800; }

.checkout-btn { margin-top: 4px; padding: 16px; font-size: .95rem; }
.clear-btn { font-size: .82rem; color: var(--clr-text-muted); }
</style>
