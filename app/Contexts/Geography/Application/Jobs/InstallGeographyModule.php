<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Application\Jobs;

use App\Contexts\Geography\Application\Services\GeographyMirrorService;
use App\Contexts\Platform\Application\Services\ContextInstaller;
use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Multitenancy\Jobs\NotTenantAware;

/**
 * Install Geography Module for Tenant Job
 *
 * OPTIONAL GEOGRAPHY ARCHITECTURE - Separate Installation
 *
 * This job installs the Geography module for a specific tenant. Geography is now
 * a completely optional, standalone module that can be installed independently
 * from Membership or other contexts.
 *
 * Key Features:
 * - Installs Geography module ONLY (independent of Membership)
 * - Creates geo_administrative_units table in tenant database
 * - Mirrors official geography from landlord (e.g., 71 Nepal units)
 * - Optionally enriches existing members with geography data
 * - Tracks installation in ModuleRegistry (tenant_modules table)
 *
 * Business Benefits:
 * - Progressive enhancement (add geography when needed)
 * - Optional feature (tenants pay only if they use it)
 * - Flexible timing (install now or later)
 * - Backward enrichment (add geography to existing members)
 *
 * Installation Scenarios:
 * 1. **New Tenant with Geography**: Admin installs Geography immediately after Membership
 * 2. **Add Later**: Tenant starts with Membership only, adds Geography when party grows
 * 3. **Migration**: Tenant has existing members, Geography enriches them with location data
 *
 * Usage:
 *   InstallGeographyModule::dispatch($tenant, 'NP');
 *
 * Queue: tenant-provisioning (dedicated queue for tenant setup operations)
 *
 * Dependencies:
 * - GeographyMirrorService: Copies official geography from landlord to tenant
 * - ContextInstaller: Runs Geography migrations in tenant database
 *
 * @package App\Contexts\Geography\Application\Jobs
 */
class InstallGeographyModule implements ShouldQueue, NotTenantAware
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     */
    public $tries = 3;

    /**
     * The maximum number of seconds the job can run before timing out.
     * Geography mirroring can take time for countries with many units.
     */
    public $timeout = 600;

    /**
     * The tenant to install the Geography module for.
     */
    protected Tenant $tenant;

    /**
     * Country code for geography data (ISO 3166-1 alpha-2).
     * Default: 'NP' (Nepal)
     */
    protected string $countryCode;

    /**
     * Create a new job instance.
     *
     * @param Tenant $tenant The tenant instance
     * @param string $countryCode Country code for geography (default: 'NP')
     */
    public function __construct(Tenant $tenant, string $countryCode = 'NP')
    {
        $this->tenant = $tenant;
        $this->countryCode = $countryCode;
        $this->onQueue('tenant-provisioning');
    }

    /**
     * Execute the job - Install Geography Module.
     *
     * Process Flow:
     * 1. Log installation start with tenant and country context
     * 2. Check if Geography already installed (prevent duplicate)
     * 3. Delegate to Platform Context's ContextInstaller (runs migrations)
     * 4. Mirror official geography from landlord to tenant
     * 5. Verify mirror integrity (parent-child relationships, level counts)
     * 6. Optionally enrich existing members with geography (if Membership installed)
     * 7. Update tenant metadata (backward compatibility)
     * 8. Log success with statistics
     *
     * @param ContextInstaller $installer Platform Context installer service
     * @param GeographyMirrorService $geographyMirror Geography mirroring service
     * @return void
     * @throws \RuntimeException If Geography installation fails
     */
    public function handle(
        ContextInstaller $installer,
        GeographyMirrorService $geographyMirror
    ): void
    {
        // CRITICAL LOG: Track installation start
        Log::info('Starting Geography module installation', [
            'tenant_id' => $this->tenant->id,
            'tenant_slug' => $this->tenant->slug,
            'tenant_database' => $this->tenant->getDatabaseName(),
            'country_code' => $this->countryCode,
            'job_queue' => $this->queue,
            'architecture' => 'optional_geography',
        ]);

        try {
            // MANUALLY SWITCH TENANT CONTEXT (Job uses NotTenantAware)
            $this->tenant->makeCurrent();
            // STEP 1: Check if Geography already installed
            if ($geographyMirror->tenantHasGeography($this->countryCode)) {
                Log::warning('Geography module already installed for tenant', [
                    'tenant_id' => $this->tenant->id,
                    'tenant_slug' => $this->tenant->slug,
                    'country_code' => $this->countryCode,
                ]);

                throw new \RuntimeException(
                    "Geography module already installed for tenant {$this->tenant->slug} (country: {$this->countryCode})"
                );
            }

            // STEP 2: Install Geography Context (runs migrations)
            Log::info('Running Geography context migrations', [
                'tenant_id' => $this->tenant->id,
                'tenant_slug' => $this->tenant->slug,
            ]);

            $result = $installer->install(
                contextName: 'Geography',
                tenantSlug: $this->tenant->slug
            );

            if (!$result->isSuccessful()) {
                $failures = implode('; ', $result->getFailures());
                throw new \RuntimeException("Geography context installation failed: {$failures}");
            }

            Log::info('Geography migrations completed successfully', [
                'tenant_id' => $this->tenant->id,
                'tables_created' => array_keys($result->tenant ?? []),
            ]);

            // STEP 3: Mirror official geography from landlord
            Log::info('Starting geography data mirroring from landlord', [
                'tenant_id' => $this->tenant->id,
                'country_code' => $this->countryCode,
            ]);

            $mirrorResult = $geographyMirror->mirrorCountryToTenant(
                tenantSlug: $this->tenant->slug,
                countryCode: $this->countryCode
            );

            Log::info('Geography data mirroring completed', [
                'tenant_id' => $this->tenant->id,
                'units_mirrored' => $mirrorResult['units_mirrored'],
                'country_code' => $mirrorResult['country_code'],
                'levels_copied' => $mirrorResult['levels_copied'],
            ]);

            // STEP 4: Verify mirror integrity
            Log::info('Verifying geography mirror integrity', [
                'tenant_id' => $this->tenant->id,
            ]);

            $integrity = $geographyMirror->verifyMirrorIntegrity();
            if (!$integrity['valid']) {
                Log::error('Geography mirror integrity check failed', [
                    'tenant_id' => $this->tenant->id,
                    'issues' => $integrity['issues'],
                ]);

                throw new \RuntimeException(
                    'Geography mirror integrity check failed: ' .
                    implode('; ', $integrity['issues'])
                );
            }

            Log::info('Geography mirror integrity verified successfully', [
                'tenant_id' => $this->tenant->id,
            ]);

            // STEP 5: Update tenant metadata
            $this->updateTenantMetadata('installed');

            // STEP 6: Log final success
            Log::info('Geography module installation completed successfully', [
                'tenant_id' => $this->tenant->id,
                'tenant_slug' => $this->tenant->slug,
                'country_code' => $this->countryCode,
                'units_mirrored' => $mirrorResult['units_mirrored'],
                'levels_available' => $mirrorResult['levels_copied'],
                'next_steps' => 'Admin can now assign geography to members or create custom units (levels 6-8)',
                'business_value' => 'Tenant can now organize members by geographic hierarchy',
                'timestamp' => now()->toIso8601String(),
            ]);

        } catch (\Exception $e) {
            // CRITICAL LOG: Track installation failure
            Log::error('Geography module installation failed', [
                'tenant_id' => $this->tenant->id,
                'tenant_slug' => $this->tenant->slug,
                'country_code' => $this->countryCode,
                'error' => $e->getMessage(),
                'exception_class' => get_class($e),
                'trace' => $e->getTraceAsString(),
            ]);

            // Mark installation as failed
            $this->markInstallationFailed($e->getMessage());

            // Re-throw to trigger job failure handling
            throw $e;
        } finally {
            // Always forget tenant context
            $this->tenant->forgetCurrent();
        }
    }

    /**
     * Update tenant metadata to mark Geography module as installed.
     *
     * @param string $status Installation status ('installed' or 'failed')
     * @return void
     */
    protected function updateTenantMetadata(string $status): void
    {
        Log::debug('Updating tenant metadata with Geography module status', [
            'tenant_id' => $this->tenant->id,
            'status' => $status,
        ]);

        $metadata = $this->tenant->metadata ?? [];
        $metadata['modules'] = $metadata['modules'] ?? [];

        $metadata['modules']['geography'] = [
            'installed' => $status === 'installed',
            'installed_at' => now()->toIso8601String(),
            'country_code' => $this->countryCode,
            'version' => '1.0.0',
            'status' => $status,
            'installed_via' => 'platform_context',
            'last_updated' => now()->toIso8601String(),
        ];

        $this->tenant->update(['metadata' => $metadata]);

        Log::debug('Tenant metadata updated successfully', [
            'tenant_id' => $this->tenant->id,
            'module_installed' => $status === 'installed',
        ]);
    }

    /**
     * Mark installation as failed in tenant metadata.
     *
     * @param string $errorMessage Error message from installation
     * @return void
     */
    protected function markInstallationFailed(string $errorMessage): void
    {
        try {
            Log::warning('Marking Geography module installation as failed', [
                'tenant_id' => $this->tenant->id,
                'error' => $errorMessage,
            ]);

            $metadata = $this->tenant->metadata ?? [];
            $metadata['modules'] = $metadata['modules'] ?? [];

            $metadata['modules']['geography'] = [
                'installed' => false,
                'last_install_attempt' => now()->toIso8601String(),
                'country_code' => $this->countryCode,
                'status' => 'failed',
                'error' => $errorMessage,
                'retry_count' => ($metadata['modules']['geography']['retry_count'] ?? 0) + 1,
            ];

            $this->tenant->update(['metadata' => $metadata]);

            Log::info('Tenant metadata updated with installation failure', [
                'tenant_id' => $this->tenant->id,
                'retry_count' => $metadata['modules']['geography']['retry_count'],
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to mark installation as failed in metadata', [
                'tenant_id' => $this->tenant->id,
                'original_error' => $errorMessage,
                'metadata_error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Handle job failure after all retry attempts exhausted.
     *
     * @param \Throwable $exception The exception that caused failure
     * @return void
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Geography module installation job failed after all retries', [
            'tenant_id' => $this->tenant->id,
            'tenant_slug' => $this->tenant->slug,
            'country_code' => $this->countryCode,
            'exception' => $exception->getMessage(),
            'exception_class' => get_class($exception),
            'job_attempts' => $this->attempts(),
            'max_tries' => $this->tries,
            'failed_at' => now()->toIso8601String(),
        ]);

        $this->markInstallationFailed(
            sprintf(
                'Job failed after %d attempts: %s',
                $this->attempts(),
                $exception->getMessage()
            )
        );
    }
}
