<template>
  <div class="page">
    <h2 class="page-title">Профиль</h2>

    <div class="avatar-section">
      <div class="avatar">
        <img v-if="user?.avatar" :src="user.avatar" :alt="user.name" />
        <span v-else>{{ initials }}</span>
      </div>
      <h3>{{ user?.name }}</h3>
      <p class="user-email">{{ user?.email }}</p>
    </div>

    <div class="card form-card">
      <h4 class="section-title">Настройки</h4>
      <div class="form-group">
        <label>Имя</label>
        <input v-model="form.name" type="text" class="form-input" />
      </div>
      <div class="form-group">
        <label>Телефон</label>
        <input v-model="form.phone" type="tel" class="form-input" placeholder="+7 999 123 45 67" />
      </div>
      <div class="form-group">
        <label>Адрес доставки по умолчанию</label>
        <input v-model="form.address" type="text" class="form-input" placeholder="ул. Ленина, д. 1" />
      </div>
      <p v-if="saved" class="saved-msg">✅ Сохранено</p>
      <button class="btn btn-primary btn-full" :disabled="saving" @click="save">
        {{ saving ? 'Сохранение...' : 'Сохранить' }}
      </button>
    </div>

    <button class="btn btn-ghost btn-full mt" @click="logout">Выйти из аккаунта</button>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useStore } from 'vuex'
import { useRouter } from 'vue-router'

const store  = useStore()
const router = useRouter()
const user   = computed(() => store.getters['auth/user'])
const saving = ref(false)
const saved  = ref(false)
const form   = ref({ name: '', phone: '', address: '' })

const initials = computed(() => {
  return (user.value?.name || 'U').split(' ').map(w => w[0]).join('').toUpperCase().slice(0, 2)
})

onMounted(() => {
  if (user.value) {
    form.value.name    = user.value.name    || ''
    form.value.phone   = user.value.phone   || ''
    form.value.address = user.value.address || ''
  }
})

async function save() {
  saving.value = true
  try {
    await store.dispatch('auth/updateProfile', form.value)
    saved.value = true
    setTimeout(() => { saved.value = false }, 2000)
  } finally {
    saving.value = false
  }
}

async function logout() {
  await store.dispatch('auth/logout')
  router.push('/')
}
</script>

<style scoped>
.avatar-section { display: flex; flex-direction: column; align-items: center; gap: 8px; margin-bottom: 24px; }
.avatar {
  width: 80px; height: 80px; border-radius: 50%;
  background: var(--clr-primary); display: flex; align-items: center; justify-content: center;
  font-size: 1.8rem; font-weight: 700; color: #fff; overflow: hidden;
}
.avatar img { width: 100%; height: 100%; object-fit: cover; }
.user-email  { color: var(--clr-text-muted); font-size: .85rem; }
.form-card   { margin-bottom: 12px; }
.section-title { font-size: 1rem; font-weight: 700; margin-bottom: 14px; }
.saved-msg   { color: #4caf50; font-size: .85rem; margin-bottom: 8px; }
.mt { margin-top: 12px; }
</style>
