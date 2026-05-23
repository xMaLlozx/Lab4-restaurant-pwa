<template>
  <div class="splash">
    <div class="splash-content" :class="{ 'fade-out': leaving }">
      <div class="splash-icon">🍕</div>
      <h1 class="splash-title">Ресторан</h1>
      <p class="splash-sub">Вкусная еда с доставкой</p>
      <div class="splash-loader"><div class="splash-bar"></div></div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
const emit   = defineEmits(['done'])
const leaving = ref(false)

onMounted(() => {
  setTimeout(() => {
    leaving.value = true
    setTimeout(() => emit('done'), 500)
  }, 1800)
})
</script>

<style scoped>
.splash {
  position: fixed; inset: 0;
  background: var(--clr-bg);
  display: flex; align-items: center; justify-content: center;
  z-index: 9999;
}
.splash-content {
  display: flex; flex-direction: column; align-items: center; gap: 12px;
  transition: opacity .5s, transform .5s;
}
.splash-content.fade-out { opacity: 0; transform: scale(.95); }
.splash-icon  { font-size: 5rem; animation: bounce 1s ease infinite alternate; }
.splash-title { font-size: 2.5rem; font-weight: 800; color: var(--clr-primary); letter-spacing: -1px; }
.splash-sub   { color: var(--clr-text-muted); font-size: 1rem; }
.splash-loader {
  width: 160px; height: 4px;
  background: var(--clr-border);
  border-radius: 4px; overflow: hidden;
  margin-top: 20px;
}
.splash-bar {
  height: 100%; background: var(--clr-primary); border-radius: 4px;
  animation: load 1.8s ease-in-out forwards;
}
@keyframes load { from { width: 0 } to { width: 100% } }
@keyframes bounce { from { transform: translateY(0) } to { transform: translateY(-12px) } }
</style>
