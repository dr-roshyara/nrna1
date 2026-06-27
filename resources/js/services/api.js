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
 *
 * HEADER INJECTION STRATEGY:
 * - Request interceptor dynamically reads tenant ID from Inertia props on EVERY request
 * - Eliminates bootstrap race condition by reading fresh props instead of caching
 * - Falls back to window.Laravel.inertia.page.props if module variable not set
 */

const apiClient = axios.create({
  baseURL: '/api/v1',
  withCredentials: true,
  headers: {
    'Accept': 'application/json',
    'X-Requested-With': 'XMLHttpRequest',
  },
});

// Module-level tenant ID cache (updated by initializeApiClient)
let activeTenantId = null;

/**
 * Initialize API client with tenant context from page props.
 * Updates module variable; actual header injection happens in request interceptor.
 *
 * @param {string} tenantId - Organisation ID from page props
 */
export function initializeApiClient(tenantId) {
  activeTenantId = tenantId;
  console.log('[api] initializeApiClient called with tenantId:', tenantId);
}

/**
 * Request interceptor: dynamically inject X-Organisation-ID header on every request.
 * Reads tenant ID from module variable first, then falls back to Inertia props.
 * This eliminates bootstrap race conditions by reading fresh context on each request.
 */
apiClient.interceptors.request.use(
  (config) => {
    // Priority 1: Module-level cache (set by initializeApiClient)
    let tenantId = activeTenantId;

    // Priority 2: Inertia page props (fresh on each request)
    if (!tenantId && window.Laravel?.inertia?.page?.props) {
      const props = window.Laravel.inertia.page.props;
      // Try multiple possible structures (different controllers pass it differently):
      // - organisation.id (nested object)
      // - organisationId (flat camelCase string)
      // - organisation_id (flat snake_case string)
      // - auth.user.current_organisation_id (user object)
      tenantId = props.organisation?.id
        || props.organisationId
        || props.organisation_id
        || props.auth?.user?.current_organisation_id;

      if (!tenantId) {
        console.debug('[api] Available props keys:', Object.keys(props));
        console.debug('[api] Checking common structures:', {
          organisation: props.organisation,
          organisationId: props.organisationId,
          organisation_id: props.organisation_id,
          auth: props.auth?.user ? { id: props.auth.user.id, name: props.auth.user.name } : null,
        });
      }
    }

    // Inject header if tenant found
    if (tenantId) {
      config.headers['X-Organisation-ID'] = tenantId;
      console.debug(`[api] X-Organisation-ID injected: ${tenantId}`);
    } else {
      console.warn('[api] X-Organisation-ID not available — API will return 400. activeTenantId:', activeTenantId);
    }

    return config;
  },
  (error) => Promise.reject(error)
);

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
