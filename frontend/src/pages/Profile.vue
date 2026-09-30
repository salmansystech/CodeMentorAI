<template>
  <div class="space-y-8">
    <h1 class="text-3xl font-bold">My Profile</h1>

    <!-- User Info Card -->
    <div class="card">
      <div class="flex items-center space-x-6">
        <div class="w-24 h-24 bg-indigo-600 rounded-full flex items-center justify-center text-4xl text-white">
          {{ initials }}
        </div>
        <div>
          <h2 class="text-2xl font-bold">{{ user?.name }}</h2>
          <p class="text-gray-600">{{ user?.email }}</p>
          <div class="mt-4 space-y-2">
            <p><strong>Level:</strong> <span class="text-indigo-600 font-bold">{{ user?.level }}</span></p>
            <p><strong>Points:</strong> <span class="text-purple-600 font-bold">{{ user?.total_points }}</span></p>
            <p><strong>Member Since:</strong> {{ formatDate(user?.created_at) }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <div class="card">
        <p class="text-gray-600 text-sm">Submissions</p>
        <p class="text-3xl font-bold text-indigo-600">{{ stats.total_submissions }}</p>
      </div>
      <div class="card">
        <p class="text-gray-600 text-sm">Reviewed</p>
        <p class="text-3xl font-bold text-purple-600">{{ stats.reviewed_submissions }}</p>
      </div>
      <div class="card">
        <p class="text-gray-600 text-sm">Current Streak</p>
        <p class="text-3xl font-bold text-orange-600">{{ user?.current_streak }}</p>
      </div>
      <div class="card">
        <p class="text-gray-600 text-sm">Best Streak</p>
        <p class="text-3xl font-bold text-green-600">{{ user?.best_streak }}</p>
      </div>
    </div>

    <!-- Badges Section -->
    <div class="card">
      <h3 class="text-2xl font-bold mb-6">Earned Badges</h3>
      <div v-if="badges.length" class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div v-for="badge in badges" :key="badge.id" class="border border-gray-200 rounded-lg p-4 text-center hover:shadow-md transition">
          <p class="text-3xl mb-2">{{ getBadgeIcon(badge.icon) }}</p>
          <p class="font-semibold text-gray-900">{{ badge.name }}</p>
          <p class="text-xs text-gray-600 mt-1">{{ badge.description }}</p>
        </div>
      </div>
      <p v-else class="text-gray-600 text-center py-8">No badges earned yet. Keep coding!</p>
    </div>

    <!-- Languages Section -->
    <div class="card">
      <h3 class="text-2xl font-bold mb-6">Preferred Languages</h3>
      <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
        <div v-for="lang in languages" :key="lang.language" class="text-center border border-gray-200 rounded-lg p-4">
          <p class="text-2xl font-bold text-indigo-600">{{ lang.count }}</p>
          <p class="text-gray-600 mt-2 capitalize">{{ lang.language }}</p>
        </div>
      </div>
    </div>

    <!-- Actions -->
    <div class="flex gap-4">
      <button @click="editProfile" class="btn-primary">Edit Profile</button>
      <button @click="changePassword" class="btn-secondary">Change Password</button>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useApi } from '../stores/auth'
import { useAuthStore } from '../stores/auth'
import { formatDistanceToNow } from 'date-fns'

const api = useApi()
const authStore = useAuthStore()

const user = ref(null)
const badges = ref([])
const languages = ref([])
const stats = ref({
  total_submissions: 0,
  reviewed_submissions: 0,
})

const initials = computed(() => {
  if (!user.value?.name) return '?'
  return user.value.name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2)
})

const formatDate = (date) => {
  if (!date) return 'N/A'
  return formatDistanceToNow(new Date(date), { addSuffix: true })
}

const getBadgeIcon = (iconName) => {
  const icons = {
    star: '⭐',
    bug: '🐛',
    trophy: '🏆',
    flame: '🔥',
    palette: '🎨',
    zap: '⚡',
    shield: '🛡️',
    book: '📚',
  }
  return icons[iconName] || '🏅'
}

const editProfile = () => {
  // TODO: Implement profile edit modal
}

const changePassword = () => {
  // TODO: Implement password change modal
}

onMounted(async () => {
  try {
    user.value = authStore.user

    const dashboardResponse = await api.get('/progress/dashboard')
    stats.value = dashboardResponse.data.stats

    const badgesResponse = await api.get('/badges/user')
    badges.value = badgesResponse.data

    const statsResponse = await api.get('/progress/stats')
    languages.value = statsResponse.data.languages_used
  } catch (error) {
    console.error('Failed to fetch profile data:', error)
  }
})
</script>
