import { ref, computed } from 'vue'

export function useValidation() {
  const errors = ref({})

  const rules = {
    email: (value) => {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
      return emailRegex.test(value) || 'Invalid email address'
    },
    password: (value) => {
      if (value.length < 6) return 'Password must be at least 6 characters'
      return true
    },
    passwordMatch: (value, confirmPassword) => {
      return value === confirmPassword || 'Passwords do not match'
    },
    required: (value) => {
      return (value && value.trim() !== '') || 'This field is required'
    },
    minLength: (min) => (value) => {
      return (value && value.length >= min) || `Minimum length is ${min}`
    },
    maxLength: (max) => (value) => {
      return (value && value.length <= max) || `Maximum length is ${max}`
    },
    url: (value) => {
      try {
        new URL(value)
        return true
      } catch {
        return 'Invalid URL'
      }
    },
    number: (value) => {
      return !isNaN(value) && value !== '' || 'Must be a number'
    },
  }

  const validate = (fieldName, value, fieldRules) => {
    const fieldErrors = []

    fieldRules.forEach(rule => {
      let result
      if (typeof rule === 'function') {
        result = rule(value)
      } else if (typeof rule === 'string' && rules[rule]) {
        result = rules[rule](value)
      }

      if (result !== true) {
        fieldErrors.push(result)
      }
    })

    if (fieldErrors.length > 0) {
      errors.value[fieldName] = fieldErrors
    } else {
      delete errors.value[fieldName]
    }

    return fieldErrors.length === 0
  }

  const validateForm = (formData, validationSchema) => {
    errors.value = {}
    let isValid = true

    for (const [fieldName, fieldRules] of Object.entries(validationSchema)) {
      const value = formData[fieldName]
      if (!validate(fieldName, value, fieldRules)) {
        isValid = false
      }
    }

    return isValid
  }

  const clearErrors = () => {
    errors.value = {}
  }

  const hasError = (fieldName) => {
    return !!errors.value[fieldName]
  }

  const getError = (fieldName) => {
    return errors.value[fieldName]?.[0] || ''
  }

  return {
    errors,
    rules,
    validate,
    validateForm,
    clearErrors,
    hasError,
    getError,
  }
}
