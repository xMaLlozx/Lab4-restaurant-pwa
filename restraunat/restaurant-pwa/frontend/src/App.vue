<template>
  <!-- Десктоп -->
  <div v-if="isDesktop" class="desktop-page">
    <aside class="desktop-side">
      <div class="desktop-logo">🍕</div>
      <h1>Ресторан<br><span>Доставка</span></h1>
      <p>PWA-приложение для заказа еды.<br>На телефоне работает как нативное.</p>
      <div class="desktop-features">
        <div class="feature">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
          Быстрая доставка
        </div>
        <div class="feature">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
          Работает офлайн
        </div>
        <div class="feature">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
          Push-уведомления
        </div>
      </div>
    </aside>

    <div class="desktop-phone-wrap">
      <div class="phone-shell">
        <div class="phone-btn-vol"></div>
        <div class="phone-btn-pwr"></div>
        <div class="phone-screen">
          <div class="phone-status-bar">
            <span>9:41</span>
            <span>●●●</span>
          </div>
          <div class="phone-app">
            <AppNav class="nav-in-phone" />
            <main class="phone-main">
              <router-view />
            </main>
            <BottomNav class="nav-in-phone" />
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Мобильный -->
  <div v-else class="app-shell">
    <AppNav v-if="!isSplash" />
    <main class="app-main">
      <SplashScreen v-if="isSplash" @done="isSplash = false" />
      <router-view v-else />
    </main>
    <BottomNav v-if="!isSplash" />
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import AppNav       from './components/layout/AppNav.vue'
import BottomNav    from './components/layout/BottomNav.vue'
import SplashScreen from './components/pages/SplashScreen.vue'

const isSplash = ref(true)

const isDesktop = computed(() => {
  if (typeof window === 'undefined') return false
  const ua = navigator.userAgent.toLowerCase()
  const isMobile = /android|iphone|ipad|ipod|mobile|phone/i.test(ua)
  if (isMobile) return false
  return window.innerWidth >= 1024
})
</script>

<style>
@import './assets/css/global.css';

/* Переопределяем fixed на absolute для навигации внутри телефона */
.nav-in-phone {
  position: absolute !important;
}

/* ===== DESKTOP ===== */
.desktop-page {
  display: flex;
  min-height: 100vh;
  background: #080808;
  background-image:
    radial-gradient(ellipse at 5% 50%, rgba(255,77,77,0.1) 0%, transparent 45%),
    radial-gradient(ellipse at 95% 20%, rgba(255,77,77,0.05) 0%, transparent 40%);
}

.desktop-side {
  width: 340px;
  flex-shrink: 0;
  display: flex;
  flex-direction: column;
  justify-content: center;
  padding: 60px 48px;
  gap: 20px;
}
.desktop-logo { font-size: 3.5rem; }
.desktop-side h1 {
  font-size: 2.6rem; font-weight: 800;
  font-family: var(--font-display);
  line-height: 1.1; letter-spacing: -.04em;
  color: var(--clr-text);
}
.desktop-side h1 span { color: var(--clr-primary); }
.desktop-side > p { color: var(--clr-text-muted); font-size: .9rem; line-height: 1.7; }
.desktop-features { display: flex; flex-direction: column; gap: 10px; margin-top: 8px; }
.feature {
  display: flex; align-items: center; gap: 10px;
  font-size: .88rem; color: var(--clr-text-soft); font-weight: 600;
}
.feature svg { width: 16px; height: 16px; color: var(--clr-primary); flex-shrink: 0; }

.desktop-phone-wrap {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 40px;
}

.phone-shell {
  position: relative;
  width: 375px;
  height: 812px;
  background: #141414;
  border-radius: 50px;
  border: 2px solid #2a2a2a;
  box-shadow:
    0 0 0 8px #0e0e0e,
    0 0 0 9px #2a2a2a,
    0 40px 80px rgba(0,0,0,.8);
  overflow: hidden;
  display: flex;
  flex-direction: column;
}

.phone-btn-vol {
  position: absolute; left: -10px; top: 120px;
  width: 4px; height: 60px; background: #2a2a2a; border-radius: 2px;
  box-shadow: 0 70px 0 #2a2a2a;
}
.phone-btn-pwr {
  position: absolute; right: -10px; top: 160px;
  width: 4px; height: 80px; background: #2a2a2a; border-radius: 2px;
}

.phone-screen {
  flex: 1;
  display: flex;
  flex-direction: column;
  background: var(--clr-bg);
  margin: 2px;
  border-radius: 48px;
  overflow: hidden;
}

.phone-status-bar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 12px 24px 4px;
  font-size: .72rem;
  font-weight: 700;
  color: var(--clr-text);
  background: var(--clr-bg);
  flex-shrink: 0;
  z-index: 10;
}

.phone-app {
  flex: 1;
  position: relative;
  overflow: hidden;
  display: flex;
  flex-direction: column;
}

.phone-main {
  flex: 1;
  overflow-y: auto;
  padding-top: var(--nav-h);
  padding-bottom: var(--bottom-h);
  scrollbar-width: none;
}
.phone-main::-webkit-scrollbar { display: none; }
</style>