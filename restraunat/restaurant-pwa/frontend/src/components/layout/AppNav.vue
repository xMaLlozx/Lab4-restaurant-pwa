<template>
  <header class="app-nav">
    <router-link to="/" class="nav-logo">
      <div class="nav-logo-icon">🍕</div>
      <span>Ресторан</span>
    </router-link>
    <div class="nav-right">
      <router-link v-if="isAuth" to="/profile" class="nav-avatar" :title="user?.name">
        <span>{{ initials }}</span>
      </router-link>
      <router-link v-else to="/login" class="nav-login-btn">Войти</router-link>
    </div>
  </header>
</template>

<script setup>
import { computed } from 'vue'
import { useStore } from 'vuex'
const store  = useStore()
const isAuth = computed(() => store.getters['auth/isAuthenticated'])
const user   = computed(() => store.getters['auth/user'])
const initials = computed(() => {
  return (user.value?.name || 'U').split(' ').map(w => w[0]).join('').toUpperCase().slice(0,2)
})
</script>

<style scoped>
.app-nav {
  position: fixed; top: 0; left: 0; right: 0;
  height: var(--nav-h);
  background: rgba(14,14,14,.92);
  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);
  display: flex; align-items: center; justify-content: space-between;
  padding: 0 16px;
  z-index: 100;
  border-bottom: 1px solid var(--clr-border);
}
.nav-logo {
  display: flex; align-items: center; gap: 8px;
  text-decoration: none; color: var(--clr-text);
  font-weight: 800; font-size: 1rem; letter-spacing: -.02em;
}
.nav-logo-icon {
  width: 32px; height: 32px; border-radius: 8px;
  background: var(--clr-primary);
  display: flex; align-items: center; justify-content: center;
  font-size: .95rem;
}
.nav-avatar {
  width: 34px; height: 34px; border-radius: 50%;
  background: var(--clr-bg-elevated);
  border: 1.5px solid var(--clr-border-soft);
  display: flex; align-items: center; justify-content: center;
  font-size: .7rem; font-weight: 800; color: var(--clr-text);
  text-decoration: none; transition: border-color .2s;
}
.nav-avatar:hover { border-color: var(--clr-primary); }
.nav-login-btn {
  font-size: .82rem; font-weight: 700; color: var(--clr-primary);
  text-decoration: none; padding: 7px 14px;
  border: 1.5px solid var(--clr-primary);
  border-radius: 100px; transition: all .2s;
}
.nav-login-btn:hover { background: var(--clr-primary); color: #fff; }
</style>
