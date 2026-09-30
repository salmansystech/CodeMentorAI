<template>
  <div class="space-y-8">
    <!-- Header -->
    <div class="text-center mb-8">
      <h1 class="text-4xl font-bold text-gray-900">Welcome back, {{ authStore.user?.name }}! 👋</h1>
      <p class="text-gray-600 mt-2">Keep learning and improving your coding skills</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <div class="card">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-600 text-sm">Level</p>
            <p class="text-3xl font-bold text-indigo-600">{{ stats.level }}</p>
          </div>
          <div class="text-4xl">🎖️</div>
        </div>
      </div>

      <div class="card">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-600 text-sm">Points</p>
            <p class="text-3xl font-bold text-purple-600">{{ stats.total_points }}</p>
          </div>
          <div class="text-4xl">⭐</div>
        </div>
      </div>

      <div class="card">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-600 text-sm">Streak</p>
            <p class="text-3xl font-bold text-orange-600">{{ stats.current_streak }}</p>
          </div>
          <div class="text-4xl">🔥</div>
        </div>
      </div>

      <div class="card">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-600 text-sm">Badges</p>
            <p class="text-3xl font-bold text-green-600">{{ stats.badges_earned }}</p>
          </div>
          <div class="text-4xl">🏆</div>
        </div>
      </div>
    </div>

    <!-- Call to Action -->
    <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-lg p-8 text-white text-center">
      <h2 class="text-2xl font-bold mb-4">Ready to improve your coding?</h2>
      <p class="text-indigo-100 mb-6">Submit your code for an AI-powered review and get personalized feedback</p>
      <router-link to="/submit" class="inline-block bg-white text-indigo-600 px-6 py-3 rounded-lg font-semibold hover:bg-indigo-50">
        Submit Code Now
      </router-link>
    </div>

    <!-- Recent Submissions -->
    <div class="card">
      <h2 class="text-2xl font-bold mb-6">Recent Submissions</h2>
      <div v-if="recentSubmissions.length" class="space-y-4">
        <div v-for="submission in recentSubmissions" :key="submission.id" class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
          <div class="flex items-center justify-between">
            <div>
              <h3 class="font-semibold text-gray-900">{{ submission.title }}</h3>
              <p class="text-sm text-gray-600">{{ submission.language }} · {{ formatDate(submission.submitted_at) }}</p>
            </div>
            <div class="text-right">
              <router-link
                :to="`/review/${submission.id}`"
                class="text-indigo-600 hover:text-indigo-700 font-semibold"
              >
                View Review →
              </router-link>
              <p v-if="submission.review" class="text-sm">
                Score: <span class="font-bold text-green-600">{{ submission.review.overall_score }}/100</span>
              </p>
            </div>
          </div>
        </div>
      </div>
      <p v-else class="text-gray-600 text-center py-8">
        No submissions yet. <router-link to="/submit" class="text-indigo-600 hover:text-indigo-700">Submit your first code</router-link>
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '../stores/auth'
import { useApi } from '../stores/auth'
import { formatDistanceToNow } from 'date-fns'

const authStore = useAuthStore()
const api = useApi()

const stats = ref({
  level: 1,
  total_points: 0,
  current_streak: 0,
  badges_earned: 0,
  total_submissions: 0,
  reviewed_submissions: 0,
})

const recentSubmissions = ref([])

const formatDate = (date) => {
  return formatDistanceToNow(new Date(date), { addSuffix: true })
}

onMounted(async () => {
  try {
    const response = await api.get('/progress/dashboard')
    stats.value = response.data.stats
    recentSubmissions.value = response.data.recent_submissions
  } catch (error) {
    console.error('Failed to fetch dashboard data:', error)
  }
})
</script>
