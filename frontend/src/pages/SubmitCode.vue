<template>
  <div class="max-w-4xl mx-auto">
    <h1 class="text-3xl font-bold mb-8">Submit Your Code for Review</h1>

    <form @submit.prevent="handleSubmit" class="space-y-6">
      <!-- Language Selection -->
      <div class="card">
        <label class="block text-sm font-medium text-gray-700 mb-2">Programming Language</label>
        <select v-model="language" class="input" required>
          <option value="">Select a language</option>
          <option value="python">Python</option>
          <option value="javascript">JavaScript</option>
          <option value="typescript">TypeScript</option>
          <option value="java">Java</option>
          <option value="cpp">C++</option>
          <option value="php">PHP</option>
          <option value="go">Go</option>
          <option value="rust">Rust</option>
          <option value="sql">SQL</option>
        </select>
      </div>

      <!-- Title -->
      <div class="card">
        <label class="block text-sm font-medium text-gray-700 mb-2">Title</label>
        <input
          v-model="title"
          type="text"
          class="input"
          placeholder="e.g., Binary Search Algorithm"
        />
      </div>

      <!-- Description -->
      <div class="card">
        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
        <textarea
          v-model="description"
          class="input h-20"
          placeholder="What does this code do?"
        ></textarea>
      </div>

      <!-- Code Editor -->
      <div class="card">
        <label class="block text-sm font-medium text-gray-700 mb-2">Code</label>
        <div class="border border-gray-300 rounded-lg overflow-hidden">
          <textarea
            v-model="code"
            class="w-full h-64 p-4 font-mono text-sm bg-gray-900 text-green-400 border-0"
            placeholder="Paste your code here..."
            required
          ></textarea>
        </div>
        <p class="text-xs text-gray-500 mt-2">{{ code.length }} characters</p>
      </div>

      <!-- Submit Button -->
      <button
        type="submit"
        class="btn-primary w-full py-3 text-lg"
        :disabled="loading || !code || !language"
      >
        {{ loading ? 'Submitting...' : 'Submit for Review' }}
      </button>

      <p v-if="error" class="text-red-600 text-center">{{ error }}</p>
      <p v-if="success" class="text-green-600 text-center">{{ success }}</p>
    </form>

    <!-- Tips -->
    <div class="mt-12 grid grid-cols-1 md:grid-cols-3 gap-6">
      <div class="bg-blue-50 p-4 rounded-lg">
        <p class="text-lg font-semibold text-blue-900">💡 Tips for Best Results</p>
        <ul class="text-sm text-blue-800 mt-2 space-y-1">
          <li>• Include comments explaining your logic</li>
          <li>• Keep code focused (under 500 lines)</li>
          <li>• Use meaningful variable names</li>
        </ul>
      </div>

      <div class="bg-green-50 p-4 rounded-lg">
        <p class="text-lg font-semibold text-green-900">🎯 What We Check</p>
        <ul class="text-sm text-green-800 mt-2 space-y-1">
          <li>• Bugs and errors</li>
          <li>• Code style & readability</li>
          <li>• Performance issues</li>
        </ul>
      </div>

      <div class="bg-purple-50 p-4 rounded-lg">
        <p class="text-lg font-semibold text-purple-900">⭐ Earn Points</p>
        <ul class="text-sm text-purple-800 mt-2 space-y-1">
          <li>• 10 points per submission</li>
          <li>• Bonus for high scores</li>
          <li>• Badges for milestones</li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useApi } from '../stores/auth'

const api = useApi()
const router = useRouter()

const language = ref('')
const title = ref('')
const description = ref('')
const code = ref('')
const loading = ref(false)
const error = ref('')
const success = ref('')

const handleSubmit = async () => {
  loading.value = true
  error.value = ''
  success.value = ''

  try {
    const response = await api.post('/submissions', {
      code: code.value,
      language: language.value,
      title: title.value || 'Untitled',
      description: description.value,
    })

    success.value = 'Code submitted! Analyzing with AI...'
    setTimeout(() => {
      router.push(`/review/${response.data.submission.id}`)
    }, 1500)
  } catch (err) {
    error.value = err.response?.data?.message || 'Failed to submit code'
  } finally {
    loading.value = false
  }
}
</script>
