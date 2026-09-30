<template>
  <div v-if="notifications.length" class="fixed top-4 right-4 z-50 space-y-2 max-w-sm">
    <div
      v-for="notification in notifications"
      :key="notification.id"
      :class="[
        'p-4 rounded-lg shadow-lg text-white animate-slide-in',
        {
          'bg-green-600': notification.type === 'success',
          'bg-red-600': notification.type === 'error',
          'bg-yellow-600': notification.type === 'warning',
          'bg-blue-600': notification.type === 'info',
        }
      ]"
    >
      <p class="font-medium">{{ notification.message }}</p>
    </div>
  </div>
</template>

<script setup>
import { useNotification } from '../composables/useNotification'

const { notifications } = useNotification()
</script>

<style scoped>
@keyframes slideIn {
  from {
    transform: translateX(400px);
    opacity: 0;
  }
  to {
    transform: translateX(0);
    opacity: 1;
  }
}

.animate-slide-in {
  animation: slideIn 0.3s ease-out;
}
</style>
