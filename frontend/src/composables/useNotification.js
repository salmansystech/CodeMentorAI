import { ref } from 'vue'

const notifications = ref([])

export function useNotification() {
  const addNotification = (message, type = 'info', duration = 3000) => {
    const id = Date.now()
    const notification = { id, message, type }

    notifications.value.push(notification)

    if (duration > 0) {
      setTimeout(() => {
        removeNotification(id)
      }, duration)
    }

    return id
  }

  const removeNotification = (id) => {
    notifications.value = notifications.value.filter(n => n.id !== id)
  }

  const success = (message, duration = 3000) => {
    return addNotification(message, 'success', duration)
  }

  const error = (message, duration = 3000) => {
    return addNotification(message, 'error', duration)
  }

  const warning = (message, duration = 3000) => {
    return addNotification(message, 'warning', duration)
  }

  const info = (message, duration = 3000) => {
    return addNotification(message, 'info', duration)
  }

  return {
    notifications,
    addNotification,
    removeNotification,
    success,
    error,
    warning,
    info,
  }
}
