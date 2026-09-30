<template>
  <div class="space-y-8">
    <h1 class="text-3xl font-bold">🏆 Global Leaderboard</h1>

    <!-- Filters -->
    <div class="card flex gap-4">
      <button
        v-for="tf in timeframes"
        :key="tf"
        @click="timeframe = tf"
        :class="[
          'px-4 py-2 rounded-lg font-medium transition',
          timeframe === tf
            ? 'bg-indigo-600 text-white'
            : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
        ]"
      >
        {{ tf === 'all' ? 'All Time' : tf === 'month' ? 'This Month' : 'This Week' }}
      </button>
    </div>

    <!-- Your Rank -->
    <div class="card bg-gradient-to-r from-indigo-600 to-purple-600 text-white">
      <div class="flex justify-between items-center">
        <div>
          <p class="text-indigo-100">Your Rank</p>
          <p class="text-4xl font-bold">#{{ userPosition }}</p>
        </div>
        <div class="text-right">
          <p class="text-indigo-100">Points</p>
          <p class="text-3xl font-bold">{{ userStats.points }}</p>
        </div>
        <div class="text-right">
          <p class="text-indigo-100">Level</p>
          <p class="text-3xl font-bold">{{ userStats.level }}</p>
        </div>
      </div>
    </div>

    <!-- Leaderboard Table -->
    <div v-if="users.length" class="card overflow-x-auto">
      <table class="w-full">
        <thead>
          <tr class="border-b border-gray-200">
            <th class="text-left py-3 px-4 font-semibold">#</th>
            <th class="text-left py-3 px-4 font-semibold">User</th>
            <th class="text-left py-3 px-4 font-semibold">Level</th>
            <th class="text-left py-3 px-4 font-semibold">Points</th>
            <th class="text-left py-3 px-4 font-semibold">Streak</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(user, idx) in users" :key="user.id" :class="[
            'border-b border-gray-100 hover:bg-gray-50',
            user.id === authStore.user?.id ? 'bg-indigo-50' : ''
          ]">
            <td class="py-3 px-4">
              <span v-if="idx === 0" class="text-2xl">🥇</span>
              <span v-else-if="idx === 1" class="text-2xl">🥈</span>
              <span v-else-if="idx === 2" class="text-2xl">🥉</span>
              <span v-else class="font-semibold text-gray-600">#{{ idx + 1 }}</span>
            </td>
            <td class="py-3 px-4">
              <div class="font-semibold text-gray-900">{{ user.name }}</div>
              <div class="text-sm text-gray-600">{{ user.email }}</div>
            </td>
            <td class="py-3 px-4">
              <span class="badge badge-success">Level {{ user.level }}</span>
            </td>
            <td class="py-3 px-4">
              <p class="font-bold text-lg">{{ user.total_points }}</p>
            </td>
            <td class="py-3 px-4">
              <p v-if="user.current_streak > 0" class="text-lg">🔥 {{ user.current_streak }}</p>
              <p v-else class="text-gray-500">-</p>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div v-if="leaderboard" class="card flex justify-center gap-2">
      <button
        v-if="leaderboard.prev_page_url"
        @click="currentPage--"
        class="btn-secondary"
      >
        ← Previous
      </button>
      <span class="px-4 py-2">Page {{ leaderboard.current_page }}</span>
      <button
        v-if="leaderboard.next_page_url"
        @click="currentPage++"
        class="btn-secondary"
      >
        Next →
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { useAuthStore } from '../stores/auth'
import { useApi } from '../stores/auth'

const api = useApi()
const authStore = useAuthStore()

const timeframes = ['all', 'month', 'week']
const timeframe = ref('all')
const currentPage = ref(1)
const users = ref([])
const leaderboard = ref(null)
const userPosition = ref(0)
const userStats = ref({ points: 0, level: 1 })

const fetchLeaderboard = async () => {
  try {
    const response = await api.get('/leaderboard/global', {
      params: {
        timeframe: timeframe.value,
        page: currentPage.value,
        limit: 50,
      }
    })
    leaderboard.value = response.data.leaderboard
    users.value = response.data.leaderboard.data || []
    userPosition.value = response.data.user_position
    userStats.value = response.data.user_stats
  } catch (error) {
    console.error('Failed to fetch leaderboard:', error)
  }
}

watch([timeframe, currentPage], () => {
  fetchLeaderboard()
})

onMounted(() => {
  fetchLeaderboard()
})
</script>
