import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import Layout from '../layouts/Layout.vue'
import Login from '../pages/Login.vue'
import Register from '../pages/Register.vue'
import Dashboard from '../pages/Dashboard.vue'
import SubmitCode from '../pages/SubmitCode.vue'
import ReviewResult from '../pages/ReviewResult.vue'
import Leaderboard from '../pages/Leaderboard.vue'
import Progress from '../pages/Progress.vue'

const routes = [
  {
    path: '/login',
    name: 'Login',
    component: Login,
    meta: { requiresGuest: true },
  },
  {
    path: '/register',
    name: 'Register',
    component: Register,
    meta: { requiresGuest: true },
  },
  {
    path: '/',
    component: Layout,
    meta: { requiresAuth: true },
    children: [
      {
        path: '',
        name: 'Dashboard',
        component: Dashboard,
      },
      {
        path: 'submit',
        name: 'SubmitCode',
        component: SubmitCode,
      },
      {
        path: 'review/:id',
        name: 'ReviewResult',
        component: ReviewResult,
      },
      {
        path: 'leaderboard',
        name: 'Leaderboard',
        component: Leaderboard,
      },
      {
        path: 'progress',
        name: 'Progress',
        component: Progress,
      },
    ],
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach((to, from, next) => {
  const authStore = useAuthStore()

  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    next({ name: 'Login' })
  } else if (to.meta.requiresGuest && authStore.isAuthenticated) {
    next({ name: 'Dashboard' })
  } else {
    next()
  }
})

export default router
