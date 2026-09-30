<template>
  <div class="space-y-8">
    <h1 class="text-3xl font-bold">Code Review Results</h1>

    <div v-if="loading" class="text-center py-12">
      <p class="text-gray-600">Loading review...</p>
    </div>

    <div v-else-if="review && submission" class="space-y-8">
      <!-- Submission Info -->
      <div class="card">
        <div class="flex justify-between items-start">
          <div>
            <h2 class="text-2xl font-bold">{{ submission.title }}</h2>
            <p class="text-gray-600 mt-2">{{ submission.description }}</p>
            <div class="mt-4 flex items-center space-x-4">
              <span class="badge badge-success">{{ submission.language }}</span>
              <span class="text-sm text-gray-600">Submitted {{ formatDate(submission.submitted_at) }}</span>
            </div>
          </div>
          <div class="text-right">
            <p class="text-5xl font-bold text-indigo-600">{{ review.overall_score }}</p>
            <p class="text-gray-600">/ 100</p>
          </div>
        </div>
      </div>

      <!-- Issues Summary -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="card bg-red-50">
          <p class="text-red-800 font-semibold">🐛 Bugs</p>
          <p class="text-3xl font-bold text-red-600">{{ review.bugs_count }}</p>
        </div>
        <div class="card bg-yellow-50">
          <p class="text-yellow-800 font-semibold">🎨 Style Issues</p>
          <p class="text-3xl font-bold text-yellow-600">{{ review.style_issues_count }}</p>
        </div>
        <div class="card bg-blue-50">
          <p class="text-blue-800 font-semibold">⚡ Performance</p>
          <p class="text-3xl font-bold text-blue-600">{{ review.performance_issues_count }}</p>
        </div>
        <div class="card bg-orange-50">
          <p class="text-orange-800 font-semibold">🔒 Security</p>
          <p class="text-3xl font-bold text-orange-600">{{ review.security_issues_count }}</p>
        </div>
      </div>

      <!-- Issues Detail -->
      <div class="card">
        <h3 class="text-xl font-bold mb-4">Issues Found</h3>
        <div v-if="review.findings" class="space-y-4">
          <!-- Bugs -->
          <div v-if="review.findings.bugs?.length">
            <h4 class="font-semibold text-red-600 mb-2">🐛 Bugs</h4>
            <div v-for="(bug, idx) in review.findings.bugs" :key="`bug-${idx}`" class="bg-red-50 p-3 rounded-lg mb-2">
              <p class="font-semibold">Line {{ bug.line }}: {{ bug.message }}</p>
              <p class="text-sm text-gray-700 mt-1">{{ bug.explanation }}</p>
              <p class="text-sm text-green-700 mt-2">✓ {{ bug.fix }}</p>
            </div>
          </div>

          <!-- Style Issues -->
          <div v-if="review.findings.style_issues?.length">
            <h4 class="font-semibold text-yellow-600 mb-2">🎨 Style Issues</h4>
            <div v-for="(issue, idx) in review.findings.style_issues" :key="`style-${idx}`" class="bg-yellow-50 p-3 rounded-lg mb-2">
              <p class="font-semibold">Line {{ issue.line }}: {{ issue.issue }}</p>
              <p class="text-sm text-green-700 mt-1">✓ {{ issue.suggestion }}</p>
            </div>
          </div>

          <!-- Performance -->
          <div v-if="review.findings.performance?.length">
            <h4 class="font-semibold text-blue-600 mb-2">⚡ Performance</h4>
            <div v-for="(perf, idx) in review.findings.performance" :key="`perf-${idx}`" class="bg-blue-50 p-3 rounded-lg mb-2">
              <p class="font-semibold">Line {{ perf.line }}: {{ perf.issue }}</p>
              <p class="text-sm text-gray-700 mt-1">{{ perf.impact }}</p>
              <p class="text-sm text-green-700 mt-2">✓ {{ perf.suggestion }}</p>
            </div>
          </div>

          <!-- Security -->
          <div v-if="review.findings.security?.length">
            <h4 class="font-semibold text-orange-600 mb-2">🔒 Security</h4>
            <div v-for="(sec, idx) in review.findings.security" :key="`sec-${idx}`" class="bg-orange-50 p-3 rounded-lg mb-2">
              <p class="font-semibold">Line {{ sec.line }}: {{ sec.vulnerability }}</p>
              <p class="text-sm text-gray-700 mt-1">Risk: <span class="font-semibold">{{ sec.risk }}</span></p>
              <p class="text-sm text-green-700 mt-2">✓ {{ sec.fix }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Learning Resources -->
      <div class="card">
        <h3 class="text-xl font-bold mb-4">📚 Learning Resources</h3>
        <div v-if="resources.length" class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div v-for="resource in resources" :key="resource.id" class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
            <h4 class="font-semibold text-indigo-600">{{ resource.title }}</h4>
            <p class="text-sm text-gray-600 mt-2">{{ resource.content }}</p>
            <div class="mt-3 flex items-center justify-between">
              <span class="text-xs text-gray-500">{{ resource.type }}</span>
              <button class="text-indigo-600 hover:text-indigo-700 text-sm font-semibold">Learn →</button>
            </div>
          </div>
        </div>
        <p v-else class="text-gray-600">No learning resources yet.</p>
      </div>

      <!-- Actions -->
      <div class="flex gap-4">
        <router-link to="/submit" class="btn-primary">
          Submit Another Code
        </router-link>
        <router-link to="/" class="btn-secondary">
          Back to Dashboard
        </router-link>
      </div>
    </div>

    <div v-else-if="error" class="card bg-red-50 text-red-800">
      {{ error }}
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useApi } from '../stores/auth'
import { formatDistanceToNow } from 'date-fns'

const route = useRoute()
const api = useApi()

const review = ref(null)
const submission = ref(null)
const resources = ref([])
const loading = ref(true)
const error = ref('')

const formatDate = (date) => {
  return formatDistanceToNow(new Date(date), { addSuffix: true })
}

onMounted(async () => {
  try {
    const subResponse = await api.get(`/submissions/${route.params.id}`)
    submission.value = subResponse.data

    const revResponse = await api.get(`/reviews/${route.params.id}`)
    review.value = revResponse.data

    resources.value = submission.value.resources || []
  } catch (err) {
    error.value = 'Failed to load review'
    console.error(err)
  } finally {
    loading.value = false
  }
})
</script>
