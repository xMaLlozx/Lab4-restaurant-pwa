<template>
  <div class="page">
    <h2 class="page-title">Новости</h2>
    <div v-if="loading" class="loader-wrap"><div class="loader"></div></div>
    <div v-else class="news-list">
      <router-link v-for="item in news" :key="item.id" :to="`/news/${item.id}`" class="news-card">
        <div class="news-img-wrap">
          <img v-if="item.image" :src="item.image" class="news-img" />
          <div v-else class="news-img-placeholder">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.2"><path d="M4 22h16a2 2 0 002-2V4a2 2 0 00-2-2H8a2 2 0 00-2 2v16a4 4 0 01-4-4V6"/><path d="M10 9h8M10 13h8M10 17h4"/></svg>
          </div>
        </div>
        <div class="news-body">
          <span class="news-date">{{ formatDate(item.published_at) }}</span>
          <h3 class="news-title">{{ item.title }}</h3>
          <p class="news-preview">{{ item.body?.slice(0, 90) }}...</p>
          <span class="news-link">Читать →</span>
        </div>
      </router-link>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from 'vue'
import { useStore } from 'vuex'

const store   = useStore()
const news    = computed(() => store.getters['news/items'])
const loading = ref(false)

onMounted(async () => { loading.value = true; await store.dispatch('news/fetchNews'); loading.value = false })

function formatDate(d) {
  return new Date(d).toLocaleDateString('ru-RU', { day: '2-digit', month: 'long' })
}
</script>

<style scoped>
.news-list { display: flex; flex-direction: column; gap: 12px; }
.news-card {
  display: flex; gap: 14px;
  background: var(--clr-bg-card); border: 1px solid var(--clr-border);
  border-radius: var(--radius); padding: 14px;
  text-decoration: none; color: var(--clr-text);
  transition: transform .2s, border-color .2s;
}
.news-card:hover { transform: translateX(4px); border-color: var(--clr-border-soft); }

.news-img-wrap { flex-shrink: 0; width: 80px; height: 80px; border-radius: var(--radius-sm); overflow: hidden; }
.news-img { width: 100%; height: 100%; object-fit: cover; }
.news-img-placeholder {
  width: 100%; height: 100%;
  background: var(--clr-bg-elevated);
  display: flex; align-items: center; justify-content: center;
}
.news-img-placeholder svg { width: 28px; height: 28px; color: var(--clr-text-muted); }

.news-body { flex: 1; display: flex; flex-direction: column; gap: 4px; }
.news-date  { font-size: .72rem; color: var(--clr-primary); font-weight: 700; text-transform: uppercase; letter-spacing: .06em; }
.news-title { font-size: .92rem; font-weight: 700; line-height: 1.3; letter-spacing: -.01em; }
.news-preview { font-size: .78rem; color: var(--clr-text-muted); line-height: 1.5; flex: 1; }
.news-link  { font-size: .78rem; color: var(--clr-primary); font-weight: 700; }
</style>
