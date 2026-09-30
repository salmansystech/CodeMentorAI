<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <router-link to="/" class="text-2xl font-bold text-indigo-600">
              🎓 CodeMentor AI
            </router-link>
          </div>

          <div class="flex items-center space-x-4">
            <router-link to="/submit" class="text-gray-600 hover:text-gray-900">
              Submit Code
            </router-link>
            <router-link to="/leaderboard" class="text-gray-600 hover:text-gray-900">
              Leaderboard
            </router-link>
            <router-link to="/progress" class="text-gray-600 hover:text-gray-900">
              Progress
            </router-link>
            <div class="relative group">
              <button class="text-gray-600 hover:text-gray-900 flex items-center space-x-2">
                <span>{{ authStore.user?.name }}</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
              </button>
              <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition">
                <button
                  @click="logout"
                  class="block w-full text-left px-4 py-2 text-gray-600 hover:bg-gray-100"
                >
                  Logout
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <router-view />
    </main>
  </div>
</template>

<script setup>
import { useAuthStore } from '../stores/auth'
import { useRouter } from 'vue-router'

const authStore = useAuthStore()
const router = useRouter()

const logout = async () => {
  await authStore.logout()
  router.push('/login')
}
</script>
