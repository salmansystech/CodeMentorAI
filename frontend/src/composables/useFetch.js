import { ref } from 'vue'
import { useApi } from '../stores/auth'
import { useNotification } from './useNotification'

export function useFetch() {
  const api = useApi()
  const { error: errorNotification, success: successNotification } = useNotification()

  const data = ref(null)
  const loading = ref(false)
  const error = ref(null)

  const get = async (url, options = {}) => {
    loading.value = true
    error.value = null
    try {
      const response = await api.get(url, options)
      data.value = response.data
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to fetch data'
      errorNotification(error.value)
      throw err
    } finally {
      loading.value = false
    }
  }

  const post = async (url, payload = {}, options = {}) => {
    loading.value = true
    error.value = null
    try {
      const response = await api.post(url, payload, options)
      data.value = response.data
      successNotification(response.data?.message || 'Success')
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to submit data'
      errorNotification(error.value)
      throw err
    } finally {
      loading.value = false
    }
  }

  const put = async (url, payload = {}, options = {}) => {
    loading.value = true
    error.value = null
    try {
      const response = await api.put(url, payload, options)
      data.value = response.data
      successNotification(response.data?.message || 'Updated successfully')
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to update data'
      errorNotification(error.value)
      throw err
    } finally {
      loading.value = false
    }
  }

  const remove = async (url, options = {}) => {
    loading.value = true
    error.value = null
    try {
      const response = await api.delete(url, options)
      successNotification(response.data?.message || 'Deleted successfully')
      return response.data
    } catch (err) {
      error.value = err.response?.data?.message || 'Failed to delete'
      errorNotification(error.value)
      throw err
    } finally {
      loading.value = false
    }
  }

  return {
    data,
    loading,
    error,
    get,
    post,
    put,
    delete: remove,
  }
}
