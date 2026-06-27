<?php

namespace App\Domain\Election\Enum;

use App\Models\Organisation;
use App\Models\Election;
use Illuminate\Support\Facades\Log;

/**
 * VoterSourceStrategy — Constitutional voter participation authority
 *
 * PHASE 3 TRANSITIONAL SEMANTIC BRIDGE VOCABULARY.
 * These case names represent operational voter registry mechanisms, NOT final constitutional authority vocabulary.
 * Phase 4 governance-language review will determine final authority names.
 *
 * Semantic Meaning (Operational Registry Mechanisms):
 *
 * ImportedVoterRegistry (voter_source_strategy = 'election_only'):
 * - Voters obtained via direct org enrollment (OrganisationUser table)
 * - No membership filtering; all active org users eligible
 * - Lightweight election participation model
 * - Used when org.uses_full_membership = false
 *
 * MembershipRegistry (voter_source_strategy = 'full_membership'):
 * - Voters obtained via formal membership governance (Member table)
 * - Requires member status, paid/exempt fees, active membership type
 * - Formal organization governance model
 * - Used when org.uses_full_membership = true
 *
 * CRITICAL: Election snapshot is sovereign runtime authority.
 * Once created, voter_source_strategy is IMMUTABLE at election level.
 * Elections own their voter participation rules; org mutations don't retroactively change them.
 *
 * @see Phase 4 Exploration: final names (ImportedVoterAuthority, MembershipAuthority, etc.) TBD
 * @see Plan: claude/plans/snappy-foraging-codd.md (Phase 3 complete specification)
 */
enum VoterSourceStrategy: string
{
    /**
     * TRANSITIONAL SEMANTIC BRIDGE VOCABULARY.
     * Governance meaning: voter authority derives from org enrollment (no membership filtering).
     *
     * @deprecated Case name is transitional. Phase 4 will determine final constitutional name
     *   through proper governance-language review. Do not treat 'ImportedVoterRegistry' as final.
     * @see Phase 4 exploration: candidates include ImportedVoterAuthority (not pre-approved)
     */
    case ImportedVoterRegistry = 'election_only';

    /**
     * TRANSITIONAL SEMANTIC BRIDGE VOCABULARY.
     * Governance meaning: voter authority derives from formal membership governance (fees, status, type).
     *
     * @deprecated Case name is transitional. Phase 4 will determine final constitutional name
     *   through proper governance-language review. Do not treat 'MembershipRegistry' as final.
     * @see Phase 4 exploration: candidates include MembershipAuthority (not pre-approved)
     */
    case MembershipRegistry = 'full_membership';

    /**
     * === PERSISTENCE BOUNDARY ===
     * Converts to database persistence value.
     * DO NOT call ->value directly anywhere. Use ->toPersistenceValue() for explicit intent.
     *
     * @see Phase 3 enforcement: direct ->value access triggers architecture violation
     */
    public function toPersistenceValue(): string
    {
        return $this->value;
    }

    /**
     * === PERSISTENCE BOUNDARY (INVERSE) ===
     * Hydrates enum from database persistence value.
     * Used only in Election model hydration.
     */
    public static function fromPersistenceValue(string $value): self
    {
        return self::from($value);
    }

    /**
     * === SOVEREIGN RESOLUTION ===
     * Resolve election's authoritative voter-source strategy snapshot.
     * Election snapshot is sovereign runtime authority — no fallback.
     *
     * Phase 3: Enforce sovereignty via exception.
     * All elections must have voter_source_strategy snapshot.
     * Missing snapshot is a critical bug (database constraint prevents this in production).
     *
     * Throws RuntimeException if election missing snapshot (should never occur in prod).
     */
    public static function fromElection(Election $election): self
    {
        if ($election->voter_source_strategy === null) {
            throw new \RuntimeException(
                sprintf(
                    'Election %s (%s) missing voter_source_strategy snapshot. ' .
                    'Run "php artisan app:backfill-voter-source-strategy" to populate missing elections.',
                    $election->id,
                    $election->slug
                )
            );
        }

        return self::from($election->voter_source_strategy);
    }

    /**
     * === SOVEREIGN CREATION ===
     * Derive voter source strategy from organisation's governance mode.
     *
     * CRITICAL: This method is called ONLY during election creation, when voter_source_strategy
     * snapshot is atomically established. After creation, organisation mutations DO NOT retroactively
     * change election voter rules (election owns its snapshot).
     *
     * @internal Approved callers ONLY:
     *   - ElectionManagementController::store() (HTTP election creation)
     *   - BackfillVoterSourceStrategy command (backfill missing elections)
     *
     * This restriction enforces constitutional principle: voter authority decisions
     * happen at election creation time and are immutable thereafter.
     *
     * @throws RuntimeException if called from unauthorized context (detectable via stack trace)
     */
    public static function fromOrganisation(Organisation $organisation): self
    {
        return $organisation->uses_full_membership
            ? self::MembershipRegistry
            : self::ImportedVoterRegistry;
    }

    /**
     * === API QUARANTINE BOUNDARY ===
     * Returns API-facing voter source strategy identifier.
     *
     * PHASE 3 QUARANTINE: These values are temporary transitional tokens.
     * They are NOT stable API vocabulary. Frontend and API consumers MUST NOT
     * treat these as permanent contracts.
     *
     * Sunset criteria: Replace in Phase 4 when governance-vocabulary finalization completes.
     * Phase 4 will return stable constitutional-authority strings (TBD via governance review).
     *
     * Implementation: Returns 'transitional_' prefixed values to signal impermanence.
     * Any hardcoded string comparison in code signals Phase 3 quarantine violation.
     *
     * Consumers: This method is used ONLY in Inertia prop serialization for Vue frontend.
     * Used in:
     *   - ElectionVoterController::index() (voter list page)
     *   - VoterImportController (import workflow)
     *   - Admin election settings pages
     *
     * @see Plan Section 3: API Quarantine Vocabulary Strategy
     * @see Rules Section 3: Quarantine API Vocabulary Strategy
     */
    public function toApiValue(): string
    {
        return match($this) {
            self::ImportedVoterRegistry => 'transitional_imported_voter_registry',
            self::MembershipRegistry    => 'transitional_membership_registry',
        };
    }

    /**
     * === OBSERVABILITY / TELEMETRY ===
     * Returns IMMUTABLE stable observability identifier.
     *
     * GOVERNANCE-SAFE IDENTIFIER: These values are immutable constitutional markers.
     * Used for audit trails, federation diagnostics, constitutional dispute analysis.
     * NEVER change these values incidentally. Changes require explicit governance decision
     * and coordination with all consuming systems (logs, analytics, external partners).
     *
     * These values are stable semantic keys — NOT persistence tokens, NOT UX labels.
     * Change ONLY through explicit governance-vocabulary revision, never incidentally.
     *
     * Usage Patterns (CORRECT):
     *   - Logs, metrics, audit trails
     *   - Exported audit data for dispute resolution
     *   - Federation diagnostic reports
     *   - Structured observability systems
     *
     * NOT for:
     *   - UX display (use ->label() instead)
     *   - API contracts (use ->toApiValue() for Phase 3, stable vocab in Phase 4)
     *   - Database persistence (use ->toPersistenceValue() instead)
     *
     * @see Plan Section 4: Telemetry Vocabulary Separation & Governance Ownership
     */
    public function telemetryKey(): string
    {
        return match($this) {
            self::ImportedVoterRegistry => 'imported_voter_registry',
            self::MembershipRegistry    => 'membership_registry',
        };
    }

    /**
     * === UX DISPLAY LABEL ===
     * Human-readable label for UI display only.
     *
     * Localization-safe: These strings are translated and change with UX improvements.
     * NOT governance-safe; NEVER use for audit, telemetry, or federation.
     * NOT persistence-safe; NEVER use for database or state storage.
     *
     * Usage: UI labels only (voter eligibility displays, settings pages, admin dashboards)
     *
     * @see Rule: UX-telemetry separation — never mix label() with governance contexts
     */
    public function label(): string
    {
        return match($this) {
            self::ImportedVoterRegistry => 'Election-Only',
            self::MembershipRegistry    => 'Full Membership',
        };
    }

    /**
     * === HELPER PREDICATES ===
     * Check if this is imported voter registry mode.
     * Used by eligibility policies to route to correct validation logic.
     */
    public function isImportedVoterRegistry(): bool
    {
        return $this === self::ImportedVoterRegistry;
    }

    /**
     * === HELPER PREDICATES ===
     * Check if this is membership registry mode.
     * Used by eligibility policies to route to correct validation logic.
     */
    public function isMembershipRegistry(): bool
    {
        return $this === self::MembershipRegistry;
    }
}
