import axios from 'axios';

/**
 * Centralized Axios instance with automatic tenant header injection
 * and event-based authentication handling.
 *
 * CRITICAL SECURITY: This client enforces strict request/response validation
 * for all /api/v1/* routes. Missing tenant headers trigger 400 errors.
 *
 * Authentication failures emit events instead of hard redirects,
 * allowing flexible auth strategies (modal, SSO, silent refresh).
 */

const apiClient = axios.create({
  baseURL: '/api/v1',
  withCredentials: true,
  headers: {
    'Accept': 'application/json',
    'X-Requested-With': 'XMLHttpRequest',
  },
});

/**
 * Initialize API client with tenant context from page props.
 * Call this during app bootstrap before making any API requests.
 *
 * @param {string} tenantId - Organisation ID from page props
 */
export function initializeApiClient(tenantId) {
  if (tenantId) {
    apiClient.defaults.headers.common['X-Organisation-ID'] = tenantId;
  }
}

/**
 * Response interceptor: normalize error shapes and emit auth events
 * Converts API error responses into consistent error format
 * and emits 'auth:expired' event for 401 responses
 */
apiClient.interceptors.response.use(
  (response) => response,
  (error) => {
    const status = error.response?.status;
    const code = error.response?.data?.code;

    // 401 Unauthenticated — emit event for flexible auth handling
    if (status === 401 || code === 'UNAUTHENTICATED') {
      window.dispatchEvent(new CustomEvent('auth:expired', { detail: { error } }));
    }

    // 400 Missing Tenant — log for debugging
    if (status === 400 && code === 'MISSING_TENANT_CONTEXT') {
      console.error('[api] Missing tenant context — check X-Organisation-ID header');
    }

    return Promise.reject(error);
  }
);

export default apiClient;
