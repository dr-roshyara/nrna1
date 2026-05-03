<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Jobs;

use App\Contexts\Platform\Application\Services\ContextInstaller;
use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Spatie\Multitenancy\Jobs\NotTenantAware;

/**
 * Install Membership Module for Tenant Job
 *
 * OPTIONAL GEOGRAPHY ARCHITECTURE - Membership is Independent
 *
 * This job installs ONLY the Membership context (member management) for a specific tenant.
 * Geography is now a completely separate, optional module that can be installed independently.
 *
 * Key Features:
 * - Installs Membership ONLY (core business value)
 * - NO Geography dependency (removed for loose coupling)
 * - Fast installation (2 seconds - no geography mirroring wait)
 * - Members can be added immediately after installation
 * - Geography can be installed later via separate InstallGeographyModule job
 *
 * Business Benefits:
 * - Immediate value delivery (add members right away)
 * - Progressive enhancement (add geography when needed)
 * - Pay-as-you-grow (geography as optional add-on)
 * - Flexible for different tenant types (small party, large party, diaspora)
 *
 * Usage:
 *   InstallMembershipModule::dispatch($tenant);
 *
 * Queue: tenant-provisioning (dedicated queue for tenant setup operations)
 *
 * Architecture Pattern: Loose Coupling
 * - Members table has nullable geography fields (no FK constraints)
 * - Application validates geography IDs using GeographyLookupInterface
 * - Geography module installed separately when admin chooses
 *
 * @package App\Contexts\Membership\Application\Jobs
 */
class InstallMembershipModule implements ShouldQueue, NotTenantAware
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     * Increased from default to handle potential temporary database issues.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The maximum number of seconds the job can run before timing out.
     * Increased for complex database operations and migrations.
     *
     * @var int
     */
    public $timeout = 600;

    /**
     * The tenant to install the module for.
     * Uses type hinting for better IDE support and runtime safety.
     *
     * @var Tenant
     */
    protected Tenant $tenant;

    /**
     * Create a new job instance.
     * Assigns job to tenant-provisioning queue for better resource management.
     *
     * @param Tenant $tenant The tenant instance to install the module for
     */
    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
        $this->onQueue('tenant-provisioning');
    }

    /**
     * Execute the job - Install Membership Module ONLY.
     *
     * Process Flow (PURE MEMBERSHIP INSTALLATION):
     * 1. Log installation start with tenant context
     * 2. Delegate to Platform Context's ContextInstaller
     * 3. Installer runs Membership migrations from Tenant/ subfolder
     * 4. Installer updates ModuleRegistry (tenant_modules table)
     * 5. Update tenant metadata (backward compatibility)
     * 6. Log success - Membership ready to use
     *
     * What's NOT Included:
     * - NO Geography mirroring (completely separate module now)
     * - NO Geography dependency (loose coupling achieved)
     * - NO geography wait time (fast installation)
     *
     * After Installation:
     * - Tenant can immediately add members (geography fields nullable)
     * - Admin can optionally install Geography module later
     * - Members can be enriched with geography data when needed
     *
     * @param ContextInstaller $installer Platform Context installer service
     * @return void
     * @throws \RuntimeException If Membership installation fails
     */
    public function handle(ContextInstaller $installer): void
    {
        // CRITICAL LOG: Track installation start for monitoring
        Log::info('Starting Membership module installation (Geography-independent)', [
            'tenant_id' => $this->tenant->id,
            'tenant_slug' => $this->tenant->slug,
            'tenant_database' => $this->tenant->getDatabaseName(),
            'job_queue' => $this->queue,
            'architecture' => 'loose_coupling',
            'geography_status' => 'optional_separate_installation',
        ]);

        try {
            // STEP 1: Install Membership Module ONLY
            // NO Geography dependency - pure Membership installation
            $result = $installer->install(
                contextName: 'Membership',
                tenantSlug: $this->tenant->slug
            );

            if ($result->isSuccessful()) {
                // CRITICAL LOG: Track successful installation with details
                Log::info('Membership module installation completed successfully', [
                    'tenant_id' => $this->tenant->id,
                    'tenant_slug' => $this->tenant->slug,
                    'landlord_status' => $result->landlord['status'] ?? 'unknown',
                    'tenant_tables_count' => count($result->tenant ?? []),
                    'tenant_tables' => array_keys($result->tenant ?? []),
                    'timestamp' => now()->toIso8601String(),
                    'next_steps' => 'Admin can now add members immediately or install Geography module optionally',
                    'geography_status' => 'not_installed_yet',
                    'business_value' => 'Tenant can start adding members right away',
                ]);

                // Update tenant metadata (optional - ModuleRegistry already tracks it)
                // Kept for backward compatibility with existing admin panels
                $this->updateTenantMetadata('installed');

            } else {
                // Installation returned but with failures
                $failures = implode('; ', $result->getFailures());

                // CRITICAL LOG: Track partial or complete failure
                Log::error('Membership module installation returned with failures', [
                    'tenant_id' => $this->tenant->id,
                    'tenant_slug' => $this->tenant->slug,
                    'failures' => $result->getFailures(),
                    'landlord_status' => $result->landlord['status'] ?? 'unknown',
                ]);

                throw new \RuntimeException("Installation failed: {$failures}");
            }

        } catch (\Exception $e) {
            // CRITICAL LOG: Track all installation failures with full context
            Log::error('Membership module installation failed', [
                'tenant_id' => $this->tenant->id,
                'tenant_slug' => $this->tenant->slug,
                'error' => $e->getMessage(),
                'exception_class' => get_class($e),
                'trace' => $e->getTraceAsString(),
                'tenant_database' => $this->tenant->getDatabaseName(),
            ]);

            // Mark installation as failed in metadata
            $this->markInstallationFailed($e->getMessage());

            // Re-throw to trigger Laravel's job failure handling
            throw $e;
        }
    }

    /**
     * Update tenant metadata to mark Membership module as installed.
     * Records installation details including timestamp and version.
     *
     * This provides backward compatibility for existing admin panels
     * that check tenant metadata for module installation status.
     *
     * @param string $status Installation status ('installed' or 'failed')
     * @return void
     */
    protected function updateTenantMetadata(string $status): void
    {
        Log::debug('Updating tenant metadata with module installation status', [
            'tenant_id' => $this->tenant->id,
            'status' => $status,
        ]);

        $metadata = $this->tenant->metadata ?? [];

        // Initialize modules array if not present
        $metadata['modules'] = $metadata['modules'] ?? [];

        // Set membership module installation details
        $metadata['modules']['membership'] = [
            'installed' => $status === 'installed',
            'installed_at' => now()->toIso8601String(),
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
     * Records error details for debugging and future retry attempts.
     *
     * This method is defensive - it catches and logs its own errors
     * to avoid masking the original installation failure.
     *
     * @param string $errorMessage Descriptive error message from installation
     * @return void
     */
    protected function markInstallationFailed(string $errorMessage): void
    {
        try {
            // CRITICAL LOG: Track when we're marking installation as failed
            Log::warning('Marking membership module installation as failed', [
                'tenant_id' => $this->tenant->id,
                'tenant_slug' => $this->tenant->slug,
                'error' => $errorMessage,
            ]);

            $metadata = $this->tenant->metadata ?? [];
            $metadata['modules'] = $metadata['modules'] ?? [];

            // Track failure details including retry count
            $metadata['modules']['membership'] = [
                'installed' => false,
                'last_install_attempt' => now()->toIso8601String(),
                'status' => 'failed',
                'error' => $errorMessage,
                'retry_count' => ($metadata['modules']['membership']['retry_count'] ?? 0) + 1,
            ];

            $this->tenant->update(['metadata' => $metadata]);

            // CRITICAL LOG: Confirm failure was recorded successfully
            Log::info('Tenant metadata updated with installation failure', [
                'tenant_id' => $this->tenant->id,
                'error_recorded' => true,
                'retry_count' => $metadata['modules']['membership']['retry_count'],
            ]);

        } catch (\Exception $e) {
            // CRITICAL LOG: Track if we failed to record the failure
            // This helps identify metadata corruption or database issues
            Log::error('Failed to mark installation as failed in metadata', [
                'tenant_id' => $this->tenant->id,
                'tenant_slug' => $this->tenant->slug,
                'original_error' => $errorMessage,
                'metadata_error' => $e->getMessage(),
                'metadata_exception_class' => get_class($e),
            ]);

            // Don't re-throw - we want the original error to propagate
        }
    }

    /**
     * Handle job failure after all retry attempts exhausted.
     * Called by Laravel's queue system when job fails permanently.
     *
     * Logs comprehensive failure context for operations team investigation.
     *
     * @param \Throwable $exception The exception that caused the job to fail
     * @return void
     */
    public function failed(\Throwable $exception): void
    {
        // CRITICAL LOG: Track permanent job failure with full context
        // Operations team needs this for alerting and investigation
        Log::error('Membership module installation job failed after all retries', [
            'tenant_id' => $this->tenant->id,
            'tenant_slug' => $this->tenant->slug,
            'tenant_database' => $this->tenant->getDatabaseName(),
            'exception' => $exception->getMessage(),
            'exception_class' => get_class($exception),
            'exception_code' => $exception->getCode(),
            'job_attempts' => $this->attempts(),
            'max_tries' => $this->tries,
            'trace' => $exception->getTraceAsString(),
            'job_queue' => $this->queue,
            'failed_at' => now()->toIso8601String(),
        ]);

        // Record final failure in tenant metadata
        $this->markInstallationFailed(
            sprintf(
                'Job failed after %d attempts: %s',
                $this->attempts(),
                $exception->getMessage()
            )
        );
    }
}
