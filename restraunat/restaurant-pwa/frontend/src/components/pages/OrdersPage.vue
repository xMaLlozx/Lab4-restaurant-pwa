<template>
  <div class="page">
    <h2 class="page-title">Мои заказы</h2>

    <div v-if="loading" class="loader-wrap"><div class="loader"></div></div>
    <div v-else-if="orders.length === 0" class="empty">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/></svg>
      <p>У вас ещё нет заказов</p>
      <router-link to="/menu" class="btn btn-primary">Сделать первый заказ</router-link>
    </div>

    <div v-else class="orders-list">
      <div v-for="order in orders" :key="order.id" class="order-card">
        <div class="order-header">
          <div>
            <span class="order-id">Заказ #{{ order.id }}</span>
            <span class="order-date">{{ formatDate(order.created_at) }}</span>
          </div>
          <span class="order-status" :class="order.status">{{ statusLabel(order.status) }}</span>
        </div>
        <div class="order-items">
          <span v-for="item in order.items" :key="item.id" class="order-dish">
            {{ item.product?.name }} × {{ item.quantity }}
          </span>
        </div>
        <div class="order-footer">
          <span class="order-addr">{{ order.address }}</span>
          <strong class="order-total">{{ order.total }} ₽</strong>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted } from 'vue'
import { useStore } from 'vuex'

const store   = useStore()
const orders  = computed(() => store.getters['orders/items'])
const loading = computed(() => store.getters['orders/loading'])

onMounted(() => store.dispatch('orders/fetchOrders'))

const statusMap = { pending:'Принят', confirmed:'Подтверждён', cooking:'Готовится', delivering:'В пути', done:'Доставлен', cancelled:'Отменён' }
const statusColors = { pending:'#888', confirmed:'#64b5f6', cooking:'#ffb74d', delivering:'#29b6f6', done:'#66bb6a', cancelled:'#ef5350' }

function statusLabel(s) { return statusMap[s] || s }
function formatDate(d) {
  return new Date(d).toLocaleString('ru-RU', { day:'2-digit', month:'2-digit', hour:'2-digit', minute:'2-digit' })
}
</script>

<style scoped>
.empty { text-align: center; padding: 60px 0; display: flex; flex-direction: column; align-items: center; gap: 16px; }
.empty svg { width: 56px; height: 56px; color: var(--clr-text-muted); }
.empty p { color: var(--clr-text-muted); font-size: .95rem; }

.orders-list { display: flex; flex-direction: column; gap: 12px; }
.order-card {
  background: var(--clr-bg-card); border: 1px solid var(--clr-border);
  border-radius: var(--radius); padding: 16px;
  display: flex; flex-direction: column; gap: 12px;
}
.order-header { display: flex; justify-content: space-between; align-items: flex-start; }
.order-id   { display: block; font-weight: 800; font-size: .95rem; letter-spacing: -.01em; }
.order-date { font-size: .75rem; color: var(--clr-text-muted); margin-top: 2px; display: block; }

.order-status {
  font-size: .72rem; font-weight: 700; padding: 4px 10px;
  border-radius: 100px; letter-spacing: .04em; text-transform: uppercase;
  background: var(--clr-bg-elevated);
}
.order-status.done       { background: rgba(102,187,106,.12); color: #66bb6a; }
.order-status.cancelled  { background: rgba(239,83,80,.12);   color: #ef5350; }
.order-status.delivering { background: rgba(41,182,246,.12);  color: #29b6f6; }
.order-status.cooking    { background: rgba(255,183,77,.12);  color: #ffb74d; }
.order-status.confirmed  { background: rgba(100,181,246,.12); color: #64b5f6; }
.order-status.pending    { background: var(--clr-bg-elevated); color: var(--clr-text-muted); }

.order-items { display: flex; flex-wrap: wrap; gap: 6px; }
.order-dish {
  font-size: .78rem; color: var(--clr-text-soft);
  background: var(--clr-bg-elevated); padding: 4px 10px;
  border-radius: var(--radius-xs); border: 1px solid var(--clr-border);
}
.order-footer { display: flex; justify-content: space-between; align-items: center; padding-top: 8px; border-top: 1px solid var(--clr-border); }
.order-addr  { font-size: .78rem; color: var(--clr-text-muted); }
.order-total { color: var(--clr-primary); font-size: 1rem; font-weight: 800; }
</style>
