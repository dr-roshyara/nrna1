<?php

declare(strict_types=1);

namespace Tests\Architecture;

use PHPUnit\Framework\TestCase;

/**
 * C-1 — the guard for the ONE constraint whose manual enforcement is insufficient.
 *
 * "Never define, default, clamp or substitute a duration." Durations are Q-2's
 * business policy; a config adapter must consume them and fail closed. This is not a
 * hypothetical risk — it is this project's demonstrated blind spot:
 *
 *  - **AP-1**: `max(1, $days)` clamping inside an Infrastructure adapter — a business
 *    floor invented by infrastructure.
 *  - **AP-2**: the value `60` living in two homes.
 *
 * **Both passed every automated gate** — the Architecture suite, Deptrac, PHPStan max
 * and the widened regression suite — and were caught only by a human preservation
 * review that might not recur. This test closes that specific hole.
 *
 * Scope note: the executable gates scan `app/Contexts/{Contestation,Adjudication,
 * Election,Shared}` only, so a duration adapter placed outside them would be
 * unguarded. The adapters named below are the ones that resolve business durations.
 *
 * Provenance: WP-7 implementation guard commission (C-1) · WP-6 findings AP-1/AP-2 ·
 * `Layer_Verification_Rule.md` §2 (an implementation edit reaching level 1 is the
 * wrong abstraction).
 */
final class DurationPolicyOwnershipTest extends TestCase
{
    /**
     * Adapters that resolve business durations. A new one MUST be added here — the
     * list is the guard's reach, and an unlisted adapter is an unguarded one.
     */
    private const DURATION_ADAPTERS = [
        'app/Contexts/Adjudication/Infrastructure/Config/ConfiguredAdjudicationDurations.php',
        'app/Contexts/Election/Infrastructure/Config/ConfiguredEvidencePreservationDurations.php',
    ];

    /** Config files permitted to declare a duration VALUE (one home each). */
    private const DURATION_CONFIGS = [
        'config/adjudication.php',
        'config/election_preservation.php',
    ];

    // ── C-1a: no adapter may clamp or substitute a business duration ────────

    public function test_no_duration_adapter_clamps_or_substitutes_a_business_value(): void
    {
        $violations = [];

        foreach (self::DURATION_ADAPTERS as $path) {
            if (!is_file($path)) {
                continue; // not yet written — 7A creates the second one
            }

            $code = $this->strippedSource($path);

            foreach ($this->clampingViolations($code) as $why) {
                $violations[] = "{$path}: {$why}";
            }
        }

        $this->assertSame([], $violations, implode("\n", $violations));
    }

    // ── C-1b: MAD keeps exactly ONE home (AP-2) ─────────────────────────────

    public function test_the_maximum_adjudication_duration_has_exactly_one_home(): void
    {
        $homes = [];

        foreach (self::DURATION_CONFIGS as $path) {
            if (is_file($path) && str_contains(file_get_contents($path), 'maximum_adjudication_duration_days')) {
                $homes[] = $path;
            }
        }

        $this->assertCount(
            1,
            $homes,
            "MAD is Q-2's business policy and has exactly one home. Found: " . implode(', ', $homes),
        );
    }

    // ── C-1c: the guard must DISCRIMINATE, or it is ceremonial ──────────────

    /**
     * The platform's own Methodological Fitness Rule: *a criterion that never rejects a
     * candidate is presumed ceremonial until evidence shows otherwise.* C-1a passes
     * vacuously while the codebase is clean, so it proves nothing on its own.
     *
     * This asserts the detector would have CAUGHT AP-1 — the real defect, in its real
     * shape — and does not fire on the compliant code that replaced it.
     */
    public function test_the_detector_would_have_caught_ap1(): void
    {
        $apOneShape = '<?php $days = max(1, (int) $this->config->get("adjudication.mad_days"));';
        $substituteShape = '<?php $days = $this->config->get("x.days") ?? 60;';
        $compliantShape = <<<'PHP'
            <?php
            $days = $this->override($a) ?? $this->configuredDefault();
            if ($days < 1) {
                throw new RuntimeException('...must be at least one day.');
            }
            return new DateInterval('P'.$days.'D');
            PHP;

        $this->assertNotEmpty(
            $this->clampingViolations($apOneShape),
            'the detector must catch the clamp that was AP-1',
        );
        $this->assertNotEmpty(
            $this->clampingViolations($substituteShape),
            'the detector must catch a substituted business value',
        );
        $this->assertSame(
            [],
            $this->clampingViolations($compliantShape),
            'the detector must NOT fire on fail-closed code — a comparison is not a clamp',
        );
    }

    /**
     * @return list<string>
     */
    private function clampingViolations(string $code): array
    {
        $found = [];

        // A business floor/ceiling applied to a resolved value. This is AP-1's shape.
        if (preg_match('~\b(max|min)\s*\(~', $code)) {
            $found[] = 'clamps a duration with max()/min() — a business floor is Q-2\'s, not infrastructure\'s';
        }

        // Substituting a value when configuration is absent, instead of failing closed.
        if (preg_match('~\?\?\s*-?\d+~', $code) || preg_match('~\?:\s*-?\d+~', $code)) {
            $found[] = 'substitutes a numeric duration when configuration is absent — must fail closed instead';
        }

        // The declared value belongs in config, never in the adapter.
        if (preg_match('~\benv\s*\(~', $code)) {
            $found[] = 'reads env() directly — the declared value has one home, and it is the config file';
        }

        return $found;
    }

    /** Source with comments and docblocks removed, so prose cannot trip the detector. */
    private function strippedSource(string $path): string
    {
        $out = '';
        foreach (token_get_all(file_get_contents($path)) as $token) {
            if (is_array($token) && in_array($token[0], [T_COMMENT, T_DOC_COMMENT], true)) {
                continue;
            }
            $out .= is_array($token) ? $token[1] : $token;
        }

        return $out;
    }
}
