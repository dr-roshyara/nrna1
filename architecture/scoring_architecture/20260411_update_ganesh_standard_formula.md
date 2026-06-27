# 🐘 Claude CLI Prompt: Update GaneshStandardFormula Based on Senior Researcher Review

## 📋 Copy This Complete Prompt into Claude CLI

```markdown
## Context
The GaneshStandardFormula has been peer-reviewed by a senior researcher. The review identified critical issues that must be addressed before the formula can be considered scientifically defensible.

## Summary of Required Changes

| Priority | Issue | Action |
|----------|-------|--------|
| **HIGH** | Multiplicative explosion (no cap) | Add MAX_COMBINED_MULTIPLIER = 2.0 |
| **HIGH** | Misleading "Shapley" naming | Rename to calculateSkillDiversityBonus |
| **MEDIUM** | Linear effort assumption | Add diminishing returns after 20 hours |
| **MEDIUM** | Institutional proof bias | Add community_attestation (1.1x) |
| **LOW** | Arbitrary verification weights | Keep but add justification comment |
| **LOW** | Weekly cap | Keep but note needs validation |

## The Updated Formula

### Core Changes

1. **Add multiplier cap** (prevents unfair amplification)
```php
private const MAX_COMBINED_MULTIPLIER = 2.0;
$cappedMultiplier = min($rawMultiplier, self::MAX_COMBINED_MULTIPLIER);
```

2. **Rename synergy method** (honest naming)
```php
// Before: calculateSynergy()
// After: calculateSkillDiversityBonus()
public function calculateSkillDiversityBonus(array $skills): float
```

3. **Add diminishing returns** (non-linear effort value)
```php
private const DIMINISHING_RETURNS_THRESHOLD = 20;
private function calculateEffectiveEffort(int $hours): float
```

4. **Add community attestation** (alternative high-weight proof)
```php
'community_attestation' => 1.1,  // Signed by 3 community members
```

### Full Updated Code

**File:** `app/Services/GaneshStandardFormula.php`

```php
<?php

namespace App\Services;

class GaneshStandardFormula
{
    private const TRACK_CONFIG = [
        'micro'    => ['base_rate' => 10, 'tier_bonus' => 0,   'min_base' => 0,   'weekly_cap' => 100],
        'standard' => ['base_rate' => 10, 'tier_bonus' => 50,  'min_base' => 31,  'weekly_cap' => null],
        'major'    => ['base_rate' => 10, 'tier_bonus' => 200, 'min_base' => 201, 'weekly_cap' => null],
    ];

    /**
     * VERIFICATION WEIGHTS
     * 
     * NOTE: These weights are ordinal (ranking is meaningful) but cardinal values
     * are heuristic. Future validation should calibrate based on:
     * - User perception surveys
     * - Correlation with contribution quality
     * - Fairness across user groups
     * 
     * Current weights:
     * - self_report (0.5): Unverified, lowest trust
     * - photo (0.7): Visual evidence, moderate trust
     * - document (0.8): Written record, high trust
     * - third_party (1.0): Independent confirmation, very high trust
     * - community_attestation (1.1): 3+ community members vouch, grassroots alternative
     * - institutional (1.2): Official organization letter, maximum trust
     */
    private const VERIFICATION_WEIGHTS = [
        'self_report'           => 0.5,
        'photo'                 => 0.7,
        'document'              => 0.8,
        'third_party'           => 1.0,
        'community_attestation' => 1.1,  // NEW: grassroots high-trust option
        'institutional'         => 1.2,
    ];

    /**
     * MAX_COMBINED_MULTIPLIER
     * 
     * Prevents multiplicative explosion where synergy × verification × sustainability
     * could unfairly amplify points. Without cap, a user could achieve 2.16x multiplier.
     * 
     * Value 2.0 is heuristic. Should be validated by measuring:
     * - Distribution of combined multipliers in production
     * - User perception of fairness at different cap levels
     * - Correlation with actual contribution quality
     */
    private const MAX_COMBINED_MULTIPLIER = 2.0;

    /**
     * DIMINISHING_RETURNS_THRESHOLD
     * 
     * After 20 hours, additional effort adds less value.
     * Based on cognitive fatigue and productivity research.
     * 
     * Formula: effective = threshold + log(extra + 1) × 5
     * 
     * Example:
     * - 20 hours → 20.0 effective
     * - 30 hours → 25.5 effective
     * - 40 hours → 29.7 effective
     * - 50 hours → 33.0 effective
     */
    private const DIMINISHING_RETURNS_THRESHOLD = 20;

    public function calculate(array $input, int $weeklyPoints): int
    {
        $track = $input['track'] ?? 'micro';
        $config = self::TRACK_CONFIG[$track];
        
        // Apply diminishing returns to effort
        $effortUnits = $input['effort_units'] ?? 0;
        $effectiveEffort = $this->calculateEffectiveEffort($effortUnits);
        
        $base = $effectiveEffort * $config['base_rate'];
        $tier = $base >= $config['min_base'] ? $config['tier_bonus'] : 0;
        $subtotal = $base + $tier;

        // Calculate multipliers
        $synergy = $this->calculateSkillDiversityBonus($input['team_skills'] ?? []);
        $verification = self::VERIFICATION_WEIGHTS[$input['proof_type'] ?? 'self_report'] ?? 1.0;
        $sustainability = ($input['is_recurring'] ?? false) ? 1.2 : 1.0;
        
        // Apply cap to prevent multiplicative explosion
        $rawMultiplier = $synergy * $verification * $sustainability;
        $cappedMultiplier = min($rawMultiplier, self::MAX_COMBINED_MULTIPLIER);
        
        $points = (int) floor($subtotal * $cappedMultiplier) + ($input['outcome_bonus'] ?? 0);

        // Apply weekly cap for micro-track
        if ($config['weekly_cap'] !== null) {
            $remaining = max(0, $config['weekly_cap'] - $weeklyPoints);
            $points = min($points, $remaining);
        }

        return $points;
    }

    /**
     * Calculate skill diversity bonus (formerly "synergy")
     * 
     * This is a heuristic, not a true Shapley value calculation.
     * It rewards teams with diverse skills as a proxy for marginal contribution.
     * 
     * Rules:
     * - 1 unique skill → 1.0x (individual contribution)
     * - 2 unique skills → 1.2x (pairwise synergy)
     * - 3+ unique skills → 1.5x (team synergy)
     * 
     * TODO: Validate thresholds empirically. Consider:
     * - Measuring correlation with project success
     * - User surveys on perceived fairness
     * - Alternative formulations (additive, diminishing returns for large teams)
     */
    public function calculateSkillDiversityBonus(array $skills): float
    {
        $uniqueCount = count(array_unique($skills));
        
        if ($uniqueCount >= 3) return 1.5;
        if ($uniqueCount >= 2) return 1.2;
        return 1.0;
    }

    /**
     * Calculate effective effort with diminishing returns
     * 
     * After DIMINISHING_RETURNS_THRESHOLD hours, each additional hour
     * contributes less to the base score.
     * 
     * Rationale:
     * - Recognizes cognitive fatigue
     * - Prevents extreme hours from dominating points
     * - Aligns with productivity research (marginal value decreases)
     * 
     * TODO: Validate with actual contribution data:
     * - Compare linear vs diminishing returns models
     * - Measure user satisfaction with different curves
     * - Adjust based on observed distribution
     */
    private function calculateEffectiveEffort(int $hours): float
    {
        if ($hours <= self::DIMINISHING_RETURNS_THRESHOLD) {
            return (float) $hours;
        }
        
        $extraHours = $hours - self::DIMINISHING_RETURNS_THRESHOLD;
        $effectiveExtra = log($extraHours + 1) * 5;
        
        return self::DIMINISHING_RETURNS_THRESHOLD + $effectiveExtra;
    }
}
```

## Update Tests

**File:** `tests/Feature/Contribution/PointsCalculatorTest.php`

Add new tests for the updated features:

```php
/** @test */
public function multiplier_cap_prevents_explosion()
{
    // 3 skills (1.5x) + institutional (1.2x) + recurring (1.2x) = 2.16x raw
    // Should be capped at 2.0x
    $points = $this->calculator->calculate([
        'track' => 'standard',
        'effort_units' => 10,
        'proof_type' => 'institutional',
        'team_skills' => ['coding', 'design', 'marketing'],
        'is_recurring' => true,
    ], $weeklyPoints = 0);
    
    // Without cap: floor(150 * 2.16) = 324
    // With cap: floor(150 * 2.0) = 300
    $this->assertEquals(300, $points);
}

/** @test */
public function diminishing_returns_applies_after_20_hours()
{
    // Test at threshold (20 hours)
    $points20 = $this->calculator->calculate([
        'track' => 'standard',
        'effort_units' => 20,
        'proof_type' => 'self_report',
    ], $weeklyPoints = 0);
    
    // Test at 40 hours (should be less than double)
    $points40 = $this->calculator->calculate([
        'track' => 'standard',
        'effort_units' => 40,
        'proof_type' => 'self_report',
    ], $weeklyPoints = 0);
    
    // 40 hours should NOT be exactly double 20 hours
    $this->assertLessThan($points20 * 2, $points40);
}

/** @test */
public function community_attestation_provides_grassroots_alternative()
{
    $points = $this->calculator->calculate([
        'track' => 'standard',
        'effort_units' => 10,
        'proof_type' => 'community_attestation',
        'team_skills' => ['teaching'],
    ], $weeklyPoints = 0);
    
    // 100 base + 50 tier = 150, × 1.1 = 165
    $this->assertEquals(165, $points);
}
```

## Also Update Create.vue Frontend

**File:** `resources/js/Pages/Contributions/Create.vue`

Add the new proof type to the frontend options:

```javascript
const proofTypes = [
    { value: 'self_report', label: 'Self-report', multiplier: 0.5 },
    { value: 'photo', label: 'Photo', multiplier: 0.7 },
    { value: 'document', label: 'Document', multiplier: 0.8 },
    { value: 'third_party', label: 'Third-party', multiplier: 1.0 },
    { value: 'community_attestation', label: 'Community Attestation', multiplier: 1.1 },
    { value: 'institutional', label: 'Institutional', multiplier: 1.2 },
]
```

## Update Migration for New Proof Type

**File:** `database/migrations/2026_04_11_000001_create_contributions_tables.php`

```php
$table->enum('proof_type', [
    'self_report', 'photo', 'document', 'third_party', 
    'community_attestation', 'institutional'  // Add this
])->default('self_report');
```

## Execution Order

```bash
# 1. Create migration for new proof type
php artisan make:migration add_community_attestation_to_proof_type_enum

# 2. Update the migration file with the code above
# 3. Run migration
php artisan migrate

# 4. Update the service class
# Edit app/Services/GaneshStandardFormula.php

# 5. Update tests
# Edit tests/Feature/Contribution/PointsCalculatorTest.php

# 6. Run tests to confirm all pass
php artisan test tests/Feature/Contribution/PointsCalculatorTest.php --no-coverage

# 7. Update frontend
# Edit resources/js/Pages/Contributions/Create.vue

# 8. Rebuild assets
npm run build

# 9. Run full test suite
php artisan test tests/Feature/Contribution/ --no-coverage
```

## Success Criteria

- [ ] All existing 6 tests pass (no regressions)
- [ ] New multiplier cap test passes
- [ ] New diminishing returns test passes
- [ ] New community attestation test passes
- [ ] Frontend shows new proof type option
- [ ] Migration runs without errors

## Documentation Update

Add to `developer_guide/contribution/CHANGELOG.md`:

```markdown
## v2 - Scientific Validation Updates (2026-04-11)

### Changed
- Added multiplier cap (MAX_COMBINED_MULTIPLIER = 2.0) to prevent unfair amplification
- Renamed `calculateSynergy()` to `calculateSkillDiversityBonus()` for honesty
- Added diminishing returns after 20 hours (non-linear effort value)
- Added `community_attestation` proof type (1.1x) as grassroots alternative to institutional

### Rationale
Based on senior researcher peer review identifying:
- Multiplicative explosion risk 
- Misleading Shapley reference
- Linear effort assumption
- Institutional proof bias

### Validation Needed
- Calibrate multiplier cap based on production data
- Validate diminishing returns curve with user studies
- Measure fairness impact of new proof type
```

Proceed with implementation. Update the code, run tests, and confirm all pass.
```

---

## 🐘 Baal Ganesh's Final Instruction

> *"The reviewer has sharpened our blade. Now we forge the steel. Implement these changes, run the tests, and let the code speak for itself."*

**Select Option 1 - Yes** to execute this update. 🚀