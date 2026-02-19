<?php

/**
 * TenantHelper - Helper functions for multi-tenant demo mode detection
 *
 * Supports two modes:
 * - MODE 1: No organisation (organisation_id = NULL) - Demo testing
 * - MODE 2: With organisation (organisation_id = X) - Live multi-tenancy
 */

if (!function_exists('is_demo_mode')) {
    /**
     * Check if currently in demo mode (no organisation)
     *
     * Demo mode allows customers to test the system without creating an organisation
     *
     * @return bool
     */
    function is_demo_mode()
    {
        return session('current_organisation_id') === null;
    }
}

if (!function_exists('current_mode')) {
    /**
     * Get the current operating mode
     *
     * Returns MODE_1_DEMO or MODE_2_TENANT_{orgId}
     *
     * @return string
     */
    function current_mode()
    {
        $orgId = session('current_organisation_id');

        if ($orgId === null) {
            return 'MODE_1_DEMO';
        }

        return 'MODE_2_TENANT_' . $orgId;
    }
}

if (!function_exists('get_tenant_id')) {
    /**
     * Get the current tenant/organisation ID
     *
     * Returns NULL for demo mode, or the organisation ID for live mode
     *
     * @return int|null
     */
    function get_tenant_id()
    {
        return session('current_organisation_id');
    }
}

if (!function_exists('is_tenant_mode')) {
    /**
     * Check if currently in tenant mode (with organisation)
     *
     * Opposite of is_demo_mode()
     *
     * @return bool
     */
    function is_tenant_mode()
    {
        return session('current_organisation_id') !== null;
    }
}
