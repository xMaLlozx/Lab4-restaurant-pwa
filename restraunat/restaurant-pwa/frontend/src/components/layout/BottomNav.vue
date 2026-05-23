<template>
  <nav class="bottom-nav">
    <router-link to="/"       class="nav-item" exact-active-class="active">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
      <span>Главная</span>
    </router-link>
    <router-link to="/menu"   class="nav-item" active-class="active">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 3h18v4H3zM3 10h18v4H3zM3 17h18v4H3z"/></svg>
      <span>Меню</span>
    </router-link>
    <router-link to="/cart"   class="nav-item cart-item" active-class="active">
      <div class="cart-icon-wrap">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 001.98 1.61h9.72a2 2 0 001.98-1.61L23 6H6"/></svg>
        <span v-if="cartCount > 0" class="cart-badge">{{ cartCount }}</span>
      </div>
      <span>Корзина</span>
    </router-link>
    <router-link to="/news"   class="nav-item" active-class="active">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 22h16a2 2 0 002-2V4a2 2 0 00-2-2H8a2 2 0 00-2 2v16a4 4 0 01-4-4V6"/><path d="M10 9h8M10 13h8M10 17h4"/></svg>
      <span>Новости</span>
    </router-link>
    <router-link to="/orders" class="nav-item" active-class="active">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/><rect x="9" y="3" width="6" height="4" rx="1"/><path d="M9 12h6M9 16h4"/></svg>
      <span>Заказы</span>
    </router-link>
  </nav>
</template>

<script setup>
import { computed } from 'vue'
import { useStore } from 'vuex'
const store     = useStore()
const cartCount = computed(() => store.getters['cart/count'])
</script>

<style scoped>
.bottom-nav {
  position: fixed; bottom: 0; left: 0; right: 0;
  height: var(--bottom-h);
  background: rgba(14,14,14,.96);
  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);
  display: flex; align-items: center; justify-content: space-around;
  border-top: 1px solid var(--clr-border);
  z-index: 100;
  padding-bottom: env(safe-area-inset-bottom);
}
.nav-item {
  display: flex; flex-direction: column; align-items: center; gap: 4px;
  text-decoration: none; color: var(--clr-text-muted);
  font-size: .62rem; font-weight: 600; letter-spacing: .02em;
  padding: 8px 10px; border-radius: var(--radius-sm);
  transition: color .2s; flex: 1;
}
.nav-item svg { width: 20px; height: 20px; stroke-width: 1.8; transition: all .2s; }
.nav-item.active { color: var(--clr-primary); }
.nav-item.active svg { stroke: var(--clr-primary); }

.cart-icon-wrap { position: relative; }
.cart-badge {
  position: absolute; top: -6px; right: -8px;
  background: var(--clr-primary); color: #fff;
  font-size: .58rem; font-weight: 800;
  border-radius: 100px; min-width: 16px; height: 16px;
  display: flex; align-items: center; justify-content: center;
  padding: 0 4px; border: 2px solid var(--clr-bg);
}
</style>
