/**
 * Centralized CSRF Request Handler
 *
 * This composable provides a single, reliable way to make authenticated requests
 * with proper CSRF protection. It eliminates the need to manually handle CSRF
 * tokens throughout the codebase.
 *
 * WHY THIS EXISTS:
 * - Manual CSRF token extraction is fragile and error-prone
 * - Different patterns across components cause bugs
 * - This centralizes the logic in one place
 *
 * USAGE:
 *
 * 1. Simple POST request:
 *    const { request } = useCsrfRequest()
 *    await request('/logout', { method: 'POST' })
 *
 * 2. With custom error handling:
 *    const { request } = useCsrfRequest()
 *    try {
 *      await request('/organizations', {
 *        method: 'POST',
 *        body: JSON.stringify(data)
 *      })
 *    } catch (error) {
 *      // Handle error
 *    }
 *
 * 3. With loading state:
 *    const { request, isLoading } = useCsrfRequest()
 *    const submit = async () => {
 *      await request('/api/action', { method: 'POST' })
 *    }
 */

import { ref } from 'vue'

export const useCsrfRequest = () => {
  const isLoading = ref(false)
  const error = ref(null)

  /**
   * Get CSRF token from multiple sources with fallback strategy
   *
   * Priority:
   * 1. Meta tag (most reliable - Laravel sets this in HTML)
   * 2. Cookie (XSRF-TOKEN - Laravel automatic fallback)
   * 3. Return null if neither found
   */
  const getCsrfToken = () => {
    // Method 1: Meta tag (PREFERRED - most reliable)
    const metaElement = document.querySelector('meta[name="csrf-token"]')
    if (metaElement) {
      const token = metaElement.getAttribute('content') || metaElement.content
      if (token) {
        console.log('✓ CSRF token from meta tag')
        return token
      }
    }

    // Method 2: Cookie fallback (Laravel XSRF-TOKEN)
    const name = 'XSRF-TOKEN'
    const decodedCookie = decodeURIComponent(document.cookie)
      .split(';')
      .map(c => c.trim())
      .find(c => c.startsWith(name + '='))

    if (decodedCookie) {
      const token = decodeURIComponent(decodedCookie.substring(name.length + 1))
      console.log('✓ CSRF token from cookie')
      return token
    }

    // Token not found
    console.warn('⚠️ CSRF token not found')
    return null
  }

  /**
   * Make authenticated request with automatic CSRF protection
   *
   * @param {string} url - Request URL
   * @param {Object} options - Fetch options
   * @returns {Promise} - Response promise
   */
  const request = async (url, options = {}) => {
    isLoading.value = true
    error.value = null

    try {
      // Get CSRF token
      const csrfToken = getCsrfToken()
      if (!csrfToken) {
        throw new Error('CSRF token not found. Please refresh the page.')
      }

      // Build fetch options with CSRF protection
      const fetchOptions = {
        method: options.method || 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrfToken,
          'X-Requested-With': 'XMLHttpRequest',
          ...options.headers, // Allow custom headers to override
        },
        credentials: 'same-origin', // CRITICAL: Include session cookies
        ...options, // Allow other fetch options (body, etc.)
      }

      console.log(`📤 ${fetchOptions.method} ${url}`)

      // Make request
      const response = await fetch(url, fetchOptions)

      // Handle response
      if (!response.ok) {
        // Special handling for CSRF token expired (419)
        if (response.status === 419) {
          console.error('⚠️ CSRF token expired')
          error.value = 'CSRF token expired. Please refresh the page.'

          // Force page reload to regenerate token
          window.location.reload()
          return
        }

        // Other errors
        const errorData = await response.json().catch(() => ({}))
        throw {
          status: response.status,
          message: errorData.message || `Request failed: ${response.status}`,
          data: errorData,
        }
      }

      console.log('✓ Request successful')
      return await response.json()
    } catch (err) {
      console.error('❌ Request error:', err)
      error.value = err.message || 'Request failed'
      throw err
    } finally {
      isLoading.value = false
    }
  }

  /**
   * Logout user - specialized request for logout endpoint
   *
   * @returns {Promise}
   */
  const logout = async () => {
    return request(route('logout'), {
      method: 'POST',
      body: JSON.stringify({}),
    })
  }

  /**
   * POST request shorthand
   */
  const post = (url, data = {}) => {
    return request(url, {
      method: 'POST',
      body: JSON.stringify(data),
    })
  }

  /**
   * PUT request shorthand
   */
  const put = (url, data = {}) => {
    return request(url, {
      method: 'PUT',
      body: JSON.stringify(data),
    })
  }

  /**
   * PATCH request shorthand
   */
  const patch = (url, data = {}) => {
    return request(url, {
      method: 'PATCH',
      body: JSON.stringify(data),
    })
  }

  /**
   * DELETE request shorthand
   */
  const deleteRequest = (url, data = {}) => {
    return request(url, {
      method: 'DELETE',
      body: JSON.stringify(data),
    })
  }

  return {
    isLoading,
    error,
    request,
    logout,
    post,
    put,
    patch,
    delete: deleteRequest,
    getCsrfToken, // Expose for advanced use cases
  }
}
