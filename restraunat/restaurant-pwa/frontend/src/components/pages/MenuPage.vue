<template>
  <div class="page">
    <h2 class="page-title">Меню</h2>

    <div class="search-wrap">
      <svg class="search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
      <input v-model="search" type="text" placeholder="Найти блюдо..." class="search-input" @input="onSearch" />
    </div>

    <div class="categories-row">
      <button class="cat-chip" :class="{ active: !activeCat }" @click="selectCat(null)">Все</button>
      <button
        v-for="cat in categories" :key="cat.id"
        class="cat-chip" :class="{ active: activeCat === cat.id }"
        @click="selectCat(cat.id)"
      >{{ cat.name }}</button>
    </div>

    <div v-if="loading" class="loader-wrap"><div class="loader"></div></div>
    <div v-else-if="products.length === 0" class="empty">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
      <p>Ничего не найдено</p>
    </div>
    <div v-else class="products-grid">
      <ProductCard v-for="p in products" :key="p.id" :product="p" />
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useStore } from 'vuex'
import { useRoute } from 'vue-router'
import ProductCard from '../ui/ProductCard.vue'

const store      = useStore()
const route      = useRoute()
const products   = computed(() => store.getters['products/products'])
const categories = computed(() => store.getters['products/categories'])
const loading    = computed(() => store.getters['products/loading'])
const activeCat  = ref(route.query.category_id ? Number(route.query.category_id) : null)
const search     = ref('')
let searchTimer  = null

onMounted(async () => {
  await store.dispatch('products/fetchCategories')
  await load()
})

async function load() {
  const params = {}
  if (activeCat.value) params.category_id = activeCat.value
  if (search.value)    params.search       = search.value
  await store.dispatch('products/fetchProducts', params)
}

function selectCat(id) { activeCat.value = id; load() }
function onSearch() { clearTimeout(searchTimer); searchTimer = setTimeout(load, 400) }
</script>

<style scoped>
.search-wrap {
  position: relative; margin-bottom: 14px;
}
.search-icon {
  position: absolute; left: 12px; top: 50%; transform: translateY(-50%);
  width: 16px; height: 16px; color: var(--clr-text-muted); pointer-events: none;
}
.search-input {
  background: var(--clr-bg-card); border: 1px solid var(--clr-border);
  border-radius: var(--radius-sm); color: var(--clr-text);
  padding: 12px 14px 12px 38px;
  font-size: .9rem; font-family: var(--font-main);
  outline: none; width: 100%; transition: border-color .2s;
}
.search-input:focus { border-color: var(--clr-primary); }
.search-input::placeholder { color: var(--clr-text-muted); }

.categories-row { display: flex; gap: 8px; overflow-x: auto; margin-bottom: 16px; padding-bottom: 2px; scrollbar-width: none; }
.categories-row::-webkit-scrollbar { display: none; }
.cat-chip {
  white-space: nowrap; padding: 8px 16px;
  background: var(--clr-bg-card); border: 1px solid var(--clr-border);
  border-radius: 100px; color: var(--clr-text-soft);
  font-size: .82rem; font-weight: 600; cursor: pointer;
  transition: all .2s; font-family: var(--font-main);
}
.cat-chip.active { background: var(--clr-primary); border-color: var(--clr-primary); color: #fff; }

.products-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; }

.empty { text-align: center; padding: 60px 0; color: var(--clr-text-muted); }
.empty svg { width: 48px; height: 48px; margin: 0 auto 12px; display: block; }
.empty p { font-size: .9rem; }
</style>
