import { createRouter, createWebHistory } from 'vue-router'
import store from '../store'

const routes = [
  { path: '/',            name: 'Home',     component: () => import('../components/pages/HomePage.vue') },
  { path: '/menu',        name: 'Menu',     component: () => import('../components/pages/MenuPage.vue') },
  { path: '/product/:id', name: 'Product',  component: () => import('../components/pages/ProductPage.vue') },
  { path: '/cart',        name: 'Cart',     component: () => import('../components/pages/CartPage.vue'),    meta: { requiresAuth: true } },
  { path: '/checkout',    name: 'Checkout', component: () => import('../components/pages/CheckoutPage.vue'), meta: { requiresAuth: true } },
  { path: '/orders',      name: 'Orders',   component: () => import('../components/pages/OrdersPage.vue'),  meta: { requiresAuth: true } },
  { path: '/profile',     name: 'Profile',  component: () => import('../components/pages/ProfilePage.vue'), meta: { requiresAuth: true } },
  { path: '/news',        name: 'News',     component: () => import('../components/pages/NewsPage.vue') },
  { path: '/news/:id',    name: 'NewsItem', component: () => import('../components/pages/NewsItemPage.vue') },
  { path: '/login',       name: 'Login',    component: () => import('../components/pages/LoginPage.vue') },
  { path: '/auth/callback', name: 'AuthCallback', component: () => import('../components/pages/AuthCallback.vue') },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior: () => ({ top: 0 }),
})

router.beforeEach((to, from, next) => {
  const isAuth = store.getters['auth/isAuthenticated']
  if (to.meta.requiresAuth && !isAuth) {
    next({ name: 'Login', query: { redirect: to.fullPath } })
  } else {
    next()
  }
})

export default router
