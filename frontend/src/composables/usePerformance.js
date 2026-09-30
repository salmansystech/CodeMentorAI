import { onMounted, onUnmounted } from 'vue'

export function usePerformance() {
  const logMetric = (name, value) => {
    if (window.performance && window.performance.measure) {
      console.log(`[Performance] ${name}: ${value}ms`)
    }
  }

  const measurePageLoad = () => {
    if (!window.performance) return

    onMounted(() => {
      setTimeout(() => {
        const perfData = window.performance.timing
        const pageLoadTime = perfData.loadEventEnd - perfData.navigationStart
        const connectTime = perfData.responseEnd - perfData.requestStart
        const renderTime = perfData.domComplete - perfData.domLoading

        logMetric('Page Load Time', pageLoadTime)
        logMetric('Connection Time', connectTime)
        logMetric('Render Time', renderTime)

        if (pageLoadTime > 3000) {
          console.warn('Page load time exceeds 3 seconds')
        }
      }, 0)
    })
  }

  const debounce = (func, delay) => {
    let timeoutId
    return function (...args) {
      clearTimeout(timeoutId)
      timeoutId = setTimeout(() => func.apply(this, args), delay)
    }
  }

  const throttle = (func, limit) => {
    let inThrottle
    return function (...args) {
      if (!inThrottle) {
        func.apply(this, args)
        inThrottle = true
        setTimeout(() => inThrottle = false, limit)
      }
    }
  }

  const lazyLoad = (imageElement) => {
    if (!('IntersectionObserver' in window)) {
      imageElement.src = imageElement.dataset.src
      return
    }

    const observer = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if (entry.isIntersecting) {
          entry.target.src = entry.target.dataset.src
          observer.unobserve(entry.target)
        }
      })
    })

    observer.observe(imageElement)

    onUnmounted(() => observer.disconnect())
  }

  const memoize = (func) => {
    const cache = new Map()
    return function (...args) {
      const key = JSON.stringify(args)
      if (cache.has(key)) {
        return cache.get(key)
      }
      const result = func.apply(this, args)
      cache.set(key, result)
      return result
    }
  }

  return {
    logMetric,
    measurePageLoad,
    debounce,
    throttle,
    lazyLoad,
    memoize,
  }
}
