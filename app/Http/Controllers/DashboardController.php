<?php

namespace App\Http\Controllers;

use App\DataTransferObjects\UserStateData;
use App\Services\Dashboard\UserStateBuilder;
use App\Services\Dashboard\TrustSignalService;
use App\Services\Dashboard\ContentBlockPipeline;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;

/**
 * Dashboard Controller - GDPR/DSGVO Article 32 Compliant
 *
 * Special considerations for diaspora voting platform:
 * - Political opinion protection (DSGVO §26)
 * - Cross-border diaspora users (Germany + Nepal)
 * - Pseudonymization by design
 * - Minimal data transmission
 */
class DashboardController extends Controller
{
    public function __construct(
        private readonly UserStateBuilder $userStateBuilder,
        private readonly TrustSignalService $trustSignalService,
        private readonly ContentBlockPipeline $contentBlockPipeline,
    ) {
    }

    /**
     * Show personalized welcome dashboard
     *
     * GDPR Article 7: Conditions for consent
     * DSGVO §26: Special protection for political opinions
     */
    public function welcome()
    {
        \DB::enableQueryLog();
        $startTime = microtime(true);

        $user = Auth::user();
        echo "<!-- Auth::user() took " . round((microtime(true) - $startTime) * 1000, 2) . "ms, queries: " . count(\DB::getQueryLog()) . " -->\n";

        // GDPR Article 7: Valid consent required for any data processing
        // If gdpr_consent_accepted_at column doesn't exist yet, allow access (migration not yet run)
        $check1Start = microtime(true);
        if ($user->hasValidGdprConsent() === false && $this->hasGdprColumn($user)) {
            return redirect()->route('consent.required');
        }
        echo "<!-- GDPR check took " . round((microtime(true) - $check1Start) * 1000, 2) . "ms, queries: " . count(\DB::getQueryLog()) . " -->\n";

        // DSGVO §26: Special protection for political data (diaspora voting is inherently political)
        // Only redirect if user is diaspora voter AND political consent column exists
        $check2Start = microtime(true);
        if ($this->isDiasporaVoter($user) && $this->hasPoliticalDataConsentColumn($user)) {
            if (!$this->hasPoliticalDataConsent($user)) {
                return redirect()->route('political.consent');
            }
        }
        echo "<!-- Political consent check took " . round((microtime(true) - $check2Start) * 1000, 2) . "ms, queries: " . count(\DB::getQueryLog()) . " -->\n";

        // Build comprehensive user state (eager-loaded, optimized)
        $builderStart = microtime(true);
        $userState = $this->userStateBuilder->build($user);
        echo "<!-- UserStateBuilder took " . round((microtime(true) - $builderStart) * 1000, 2) . "ms, queries: " . count(\DB::getQueryLog()) . " -->\n";

        echo "<!-- TOTAL TIME: " . round((microtime(true) - $startTime) * 1000, 2) . "ms, TOTAL QUERIES: " . count(\DB::getQueryLog()) . " -->\n";

        // Get trust signals for German/NGO audience
        $trustSignals = $this->trustSignalService->getSignalsForUser($userState);

        // Register content blocks and process pipeline
        $contentBlocks = $this->registerContentBlocks($userState);

        // GDPR Article 25: Data protection by design and by default
        // Pseudonymized, minimal PII approach
        $safeUserData = [
            // Pseudonymized identifier (not direct ID)
            'identifier' => $user->getPseudonymizedId(),

            // Display name with consent check
            'display_name' => $this->getConsentsToNameDisplay($user)
                ? $user->name
                : __('user.anonymous_diaspora_member'),

            // Cultural & localization preferences
            'preferred_language' => $this->getPreferredLanguage($user),
            'timezone' => $this->getTimezone($user),
            'cultural_context' => $this->getCulturalContext($user),

            // Compliance metadata
            'gdpr_consent_level' => $this->getGdprConsentLevel($user),
            'data_hosting_location' => 'germany',
            'data_retention_days' => 30, // DSGVO-compliant retention period
        ];

        return Inertia::render('Dashboard/Welcome', [
            // Minimally exposed user data
            'user' => $safeUserData,

            // User state - only necessary computed properties
            'userState' => [
                'roles' => $userState->roles,
                'primary_role' => $userState->primary_role,
                'composite_state' => $userState->composite_state,
                'confidence_score' => $userState->confidence_score,
                'onboarding_step' => $userState->onboarding_step,
                'ui_mode' => $userState->ui_mode,
                'available_actions' => $userState->available_actions,
                'pending_actions' => $userState->pending_actions,
                'primary_action' => $userState->primary_action,
                'is_new_user' => $userState->roles === [],
                'has_multiple_roles' => count($userState->roles) > 1,
                'requires_gdpr_review' => \Schema::hasColumn('users', 'requires_gdpr_review')
                    ? ($user->getAttribute('requires_gdpr_review') ?? false)
                    : false,
            ],

            // Trust signals for German/NGO audience
            'trustSignals' => $trustSignals,

            // Content blocks for dynamic rendering
            'contentBlocks' => $contentBlocks,

            // GDPR/DSGVO compliance metadata for frontend transparency
            'compliance' => [
                'gdpr_article_32_compliant' => true,
                'dsgvo_compliant' => true,
                'data_protection_officer_email' => config('gdpr.dpo_email'),
                'supervisory_authority' => 'Berlin Commissioner for Data Protection',
            ],
        ]);
    }

    /**
     * Default dashboard (alias for welcome)
     */
    public function index()
    {
        return $this->welcome();
    }

    /**
     * Minimal test to isolate serialization issues
     */
    public function testMinimal()
    {
        $user = Auth::user();

        return Inertia::render('Dashboard/Welcome', [
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ],
            'userState' => [
                'roles' => [],
                'primary_role' => 'guest',
                'composite_state' => 'new_user_no_roles',
                'confidence_score' => 0,
                'onboarding_step' => 0,
                'ui_mode' => 'normal',
                'available_actions' => [],
                'primary_action' => null,
                'is_new_user' => true,
                'has_multiple_roles' => false,
                'requires_gdpr_review' => false,
            ],
            'trustSignals' => [],
            'contentBlocks' => [],
            'compliance' => [
                'gdpr_article_32_compliant' => true,
                'dsgvo_compliant' => true,
                'data_protection_officer_email' => 'test@example.com',
                'supervisory_authority' => 'Berlin Commissioner for Data Protection',
            ],
        ]);
    }

    // ============================================================================
    // HELPER METHODS - Safe attribute access with fallbacks
    // ============================================================================

    /**
     * Check if user has GDPR consent column in database
     */
    private function hasGdprColumn($user): bool
    {
        return \Schema::hasColumn('users', 'gdpr_consent_accepted_at');
    }

    /**
     * Check if user is diaspora voter
     */
    private function isDiasporaVoter($user): bool
    {
        if (!\Schema::hasColumn('users', 'is_diaspora_voter')) {
            return false;
        }
        return (bool) $user->getAttribute('is_diaspora_voter');
    }

    /**
     * Check if user has political data consent column
     */
    private function hasPoliticalDataConsentColumn($user): bool
    {
        return \Schema::hasColumn('users', 'has_political_data_consent');
    }

    /**
     * Check if user has given political data consent
     */
    private function hasPoliticalDataConsent($user): bool
    {
        return (bool) $user->getAttribute('has_political_data_consent') ?? false;
    }

    /**
     * Get user's name display consent
     */
    private function getConsentsToNameDisplay($user): bool
    {
        if (!\Schema::hasColumn('users', 'consents_to_name_display')) {
            return true; // Default: allow name display
        }
        return (bool) $user->getAttribute('consents_to_name_display') ?? true;
    }

    /**
     * Get user's preferred language
     */
    private function getPreferredLanguage($user): string
    {
        if (!\Schema::hasColumn('users', 'preferred_language')) {
            return config('app.locale', 'en');
        }
        return $user->getAttribute('preferred_language') ?? config('app.locale', 'en');
    }

    /**
     * Get user's timezone
     */
    private function getTimezone($user): string
    {
        if (!\Schema::hasColumn('users', 'timezone')) {
            return config('app.timezone', 'UTC');
        }
        return $user->getAttribute('timezone') ?? config('app.timezone', 'UTC');
    }

    /**
     * Get user's cultural context
     */
    private function getCulturalContext($user): string
    {
        if (!\Schema::hasColumn('users', 'cultural_context')) {
            return 'german'; // Default context
        }
        return $user->getAttribute('cultural_context') ?? 'german';
    }

    /**
     * Get user's GDPR consent level
     */
    private function getGdprConsentLevel($user): int
    {
        if (!\Schema::hasColumn('users', 'gdpr_consent_level')) {
            return 0;
        }
        return (int) $user->getAttribute('gdpr_consent_level') ?? 0;
    }

    /**
     * Register content blocks and process pipeline
     *
     * KISS Principle: Register only blocks that are needed for this user
     */
    private function registerContentBlocks(UserStateData $userState): array
    {
        // Import block classes for content rendering
        try {
            $actionBlock = app(\App\Services\Dashboard\Blocks\RoleBasedActionBlock::class);
            $this->contentBlockPipeline->register($actionBlock);
        } catch (\Exception $e) {
            \Log::warning('Failed to register RoleBasedActionBlock: ' . $e->getMessage());
        }

        // Register organization status block for admins
        if ($userState->primary_role === 'admin' && $userState->onboarding_step < 5) {
            try {
                $statusBlock = app(\App\Services\Dashboard\Blocks\OrganizationStatusBlock::class);
                $this->contentBlockPipeline->register($statusBlock);
            } catch (\Exception $e) {
                \Log::warning('Failed to register OrganizationStatusBlock: ' . $e->getMessage());
            }
        }

        // Register pending actions block
        if (!empty($userState->pending_actions)) {
            try {
                $pendingBlock = app(\App\Services\Dashboard\Blocks\PendingActionsBlock::class);
                $this->contentBlockPipeline->register($pendingBlock);
            } catch (\Exception $e) {
                \Log::warning('Failed to register PendingActionsBlock: ' . $e->getMessage());
            }
        }

        // Process content blocks pipeline
        $pipelineResult = $this->contentBlockPipeline->process($userState);

        // Debug: Log actual structure returned by pipeline
        \Log::debug('ContentBlockPipeline result structure', [
            'keys' => array_keys((array)$pipelineResult),
            'has_blocks_key' => isset($pipelineResult['blocks']),
            'type' => gettype($pipelineResult),
        ]);

        // Handle both cases: direct array or ['blocks' => ...]
        if (isset($pipelineResult['blocks']) && is_array($pipelineResult['blocks'])) {
            return $pipelineResult['blocks'];
        }

        // Assume it's already the blocks array
        return is_array($pipelineResult) ? $pipelineResult : [];
    }
}
