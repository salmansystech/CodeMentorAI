<template>
  <div class="space-y-8">
    <h1 class="text-3xl font-bold">📈 Your Progress</h1>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div class="card">
        <p class="text-gray-600 text-sm">Total Submissions</p>
        <p class="text-4xl font-bold text-indigo-600">{{ stats.total_submissions }}</p>
      </div>
      <div class="card">
        <p class="text-gray-600 text-sm">Reviewed</p>
        <p class="text-4xl font-bold text-purple-600">{{ stats.reviewed_submissions }}</p>
      </div>
      <div class="card">
        <p class="text-gray-600 text-sm">Improvement Rate</p>
        <p class="text-4xl font-bold text-green-600">{{ improvementRate }}%</p>
      </div>
    </div>

    <!-- Issues Breakdown -->
    <div class="card">
      <h2 class="text-2xl font-bold mb-6">Issues Found</h2>
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="text-center">
          <p class="text-4xl font-bold text-red-600">{{ issueStats.bugs }}</p>
          <p class="text-gray-600 mt-2">🐛 Bugs</p>
        </div>
        <div class="text-center">
          <p class="text-4xl font-bold text-yellow-600">{{ issueStats.style }}</p>
          <p class="text-gray-600 mt-2">🎨 Style</p>
        </div>
        <div class="text-center">
          <p class="text-4xl font-bold text-blue-600">{{ issueStats.performance }}</p>
          <p class="text-gray-600 mt-2">⚡ Performance</p>
        </div>
        <div class="text-center">
          <p class="text-4xl font-bold text-orange-600">{{ issueStats.security }}</p>
          <p class="text-gray-600 mt-2">🔒 Security</p>
        </div>
      </div>
    </div>

    <!-- Languages Used -->
    <div class="card">
      <h2 class="text-2xl font-bold mb-4">Languages You Practice</h2>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div v-for="lang in languages" :key="lang.language" class="text-center border border-gray-200 rounded-lg p-4">
          <p class="text-3xl font-bold text-indigo-600">{{ lang.count }}</p>
          <p class="text-gray-600 mt-2 capitalize">{{ lang.language }}</p>
        </div>
      </div>
    </div>

    <!-- Achievements -->
    <div class="card">
      <h2 class="text-2xl font-bold mb-4">🏆 Your Achievements</h2>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="text-center border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
          <p class="text-4xl">🌟</p>
          <p class="font-semibold text-gray-900 mt-2">Getting Started</p>
          <p class="text-sm text-gray-600">First submission</p>
        </div>
        <div class="text-center border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
          <p class="text-4xl">🐛</p>
          <p class="font-semibold text-gray-900 mt-2">Bug Hunter</p>
          <p class="text-sm text-gray-600">10 bugs fixed</p>
        </div>
        <div class="text-center border border-gray-200 rounded-lg p-4 opacity-50">
          <p class="text-4xl">🔥</p>
          <p class="font-semibold text-gray-900 mt-2">On Fire</p>
          <p class="text-sm text-gray-600">7-day streak</p>
        </div>
        <div class="text-center border border-gray-200 rounded-lg p-4 opacity-50">
          <p class="text-4xl">🏅</p>
          <p class="font-semibold text-gray-900 mt-2">Master</p>
          <p class="text-sm text-gray-600">50+ submissions</p>
        </div>
      </div>
    </div>

    <!-- Improvement Tips -->
    <div class="bg-gradient-to-r from-indigo-50 to-purple-50 border border-indigo-200 rounded-lg p-6">
      <h3 class="text-lg font-bold text-indigo-900 mb-4">💡 Tips to Improve Faster</h3>
      <ul class="space-y-2 text-indigo-800">
        <li>✓ Submit code regularly to build a streak</li>
        <li>✓ Focus on one language deeply</li>
        <li>✓ Review your feedback and apply the suggestions</li>
        <li>✓ Read similar problems to learn patterns</li>
      </ul>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useApi } from '../stores/auth'

const api = useApi()

const stats = ref({
  total_submissions: 0,
  reviewed_submissions: 0,
})

const issueStats = ref({
  bugs: 0,
  style: 0,
  performance: 0,
  security: 0,
})

const languages = ref([])
const improvementRate = ref(0)

const fetchProgress = async () => {
  try {
    const response = await api.get('/progress/stats')
    issueStats.value = response.data.stats
    improvementRate.value = response.data.improvement_rate
    languages.value = response.data.languages_used
  } catch (error) {
    console.error('Failed to fetch progress:', error)
  }
}

const fetchDashboard = async () => {
  try {
    const response = await api.get('/progress/dashboard')
    stats.value = response.data.stats
  } catch (error) {
    console.error('Failed to fetch dashboard:', error)
  }
}

onMounted(() => {
  fetchProgress()
  fetchDashboard()
})
</script>
