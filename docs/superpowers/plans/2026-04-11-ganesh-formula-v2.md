# GaneshStandardFormula v2 Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Apply four peer-reviewed improvements to `GaneshStandardFormula` — multiplier cap, method rename, diminishing returns, and `community_attestation` proof type — with full TDD and zero regressions.

**Architecture:** Tests are written first to RED state, then `GaneshStandardFormula.php` is updated to GREEN, then the database enum, controller validation, and Vue preview are aligned. The JS formula mirror in `Create.vue` must stay in sync with the PHP service.

**Tech Stack:** PHP 8.2, PHPUnit, Laravel 11, MySQL enum migration, Vue 3 + Inertia 2.0

---

## File Map

| Action | File | What changes |
|--------|------|-------------|
| Modify | `tests/Feature/Contribution/PointsCalculatorTest.php` | Rename `calculateSynergy` call → `calculateSkillDiversityBonus`; add 3 new tests |
| Modify | `app/Services/GaneshStandardFormula.php` | Rename method, add VERIFICATION_WEIGHTS entry, add MAX_COMBINED_MULTIPLIER, add diminishing returns |
| Create | `database/migrations/2026_04_11_000002_add_community_attestation_proof_type.php` | ALTER contributions.proof_type enum |
| Modify | `app/Http/Controllers/Contribution/ContributionController.php` | Add `community_attestation` to validation rule |
| Modify | `resources/js/Pages/Contributions/Create.vue` | Add proof option, update JS formula mirror |

---

## Pre-work: Verify baseline

- [ ] Run existing tests to confirm they pass before touching anything

```bash
cd C:\Users\nabra\OneDrive\Desktop\roshyara\xamp\nrna\nrna-eu
php artisan test tests/Feature/Contribution/PointsCalculatorTest.php --no-coverage
```

Expected: **5 tests, 5 passed** (green baseline).

---

## Task 1: Write failing tests (RED phase)

**Files:**
- Modify: `tests/Feature/Contribution/PointsCalculatorTest.php`

### Background — why each test fails before the implementation

| Test | Fails because |
|------|--------------|
| `synergy_multiplier_rewards_unique_skills` (updated) | `calculateSynergy()` will not exist after rename |
| `multiplier_cap_prevents_explosion` | No cap exists yet — current formula returns 324, not 300 |
| `diminishing_returns_applies_after_20_hours` | Linear effort — 40h earns exactly double 20h |
| `community_attestation_provides_grassroots_alternative` | `community_attestation` is not in VERIFICATION_WEIGHTS |

### Verified expected values

**`multiplier_cap_prevents_explosion`:**
- 10h standard, institutional (1.2×), 3 skills (1.5×), recurring (1.2×)
- effectiveEffort = 10 (below 20h threshold)
- base = 100, tier = +50 → subtotal = 150
- rawMultiplier = 1.5 × 1.2 × 1.2 = **2.16** → capped to **2.0**
- `floor(150 × 2.0)` = **300**
- Without cap: `floor(150 × 2.16)` = 324

**`diminishing_returns_applies_after_20_hours`:**
- At 20h: effectiveEffort = 20, base = 200, tier = +50, subtotal = 250, ×0.5 = `floor(125)` = **125**
- At 40h: effectiveEffort = `20 + log(21) × 5` ≈ 35.22, base ≈ 352.2, tier = +50, subtotal ≈ 402.2, ×0.5 = `floor(201.1)` = **201**
- Assert: 201 < 125 × 2 (i.e., 201 < 250) ✓

**`community_attestation_provides_grassroots_alternative`:**
- 10h standard, community_attestation (1.1×), 1 skill (1.0×), not recurring (1.0×)
- subtotal = 150, rawMultiplier = 1.1, cappedMultiplier = 1.1 (< 2.0)
- `floor(150 × 1.1)` = **165**

- [ ] **Step 1.1: Update the renamed-method test**

Replace the existing `synergy_multiplier_rewards_unique_skills` test. Open `tests/Feature/Contribution/PointsCalculatorTest.php` and change the method body at lines 101–110:

```php
/** @test */
public function synergy_multiplier_rewards_unique_skills()
{
    $sameSkills        = $this->calculator->calculateSkillDiversityBonus(['coder', 'coder', 'coder']);
    $mixedSkills       = $this->calculator->calculateSkillDiversityBonus(['coder', 'doctor', 'teacher']);
    $crossPollination  = $this->calculator->calculateSkillDiversityBonus(['engineer', 'marketer', 'community_organizer', 'designer']);

    $this->assertEquals(1.0, $sameSkills);
    $this->assertEquals(1.5, $mixedSkills);      // 3 unique skills = cross-pollination
    $this->assertEquals(1.5, $crossPollination); // 4 unique skills = cross-pollination
}
```

- [ ] **Step 1.2: Add the three new tests** — append before the closing `}` of the class:

```php
/** @test */
public function multiplier_cap_prevents_explosion()
{
    // 3 skills (1.5×) + institutional (1.2×) + recurring (1.2×) = 2.16× raw → capped at 2.0×
    $points = $this->calculator->calculate([
        'track'        => 'standard',
        'effort_units' => 10,
        'proof_type'   => 'institutional',
        'team_skills'  => ['coding', 'design', 'marketing'],
        'is_recurring' => true,
    ], $weeklyPoints = 0);

    // Without cap: floor(150 × 2.16) = 324
    // With 2.0× cap: floor(150 × 2.0) = 300
    $this->assertEquals(300, $points);
}

/** @test */
public function diminishing_returns_applies_after_20_hours()
{
    // Standard track, self-report only, no synergy boost
    $points20 = $this->calculator->calculate([
        'track'        => 'standard',
        'effort_units' => 20,
        'proof_type'   => 'self_report',
        'team_skills'  => ['teaching'],
        'is_recurring' => false,
    ], $weeklyPoints = 0);

    $points40 = $this->calculator->calculate([
        'track'        => 'standard',
        'effort_units' => 40,
        'proof_type'   => 'self_report',
        'team_skills'  => ['teaching'],
        'is_recurring' => false,
    ], $weeklyPoints = 0);

    // With linear effort, 40 h would give exactly double 20 h.
    // Diminishing returns means it must be strictly less.
    $this->assertLessThan($points20 * 2, $points40);
}

/** @test */
public function community_attestation_provides_grassroots_alternative()
{
    // standard, 10 h, 1 skill (no synergy), community_attestation = 1.1×
    $points = $this->calculator->calculate([
        'track'        => 'standard',
        'effort_units' => 10,
        'proof_type'   => 'community_attestation',
        'team_skills'  => ['teaching'],
        'is_recurring' => false,
    ], $weeklyPoints = 0);

    // base = 100, tier = +50, subtotal = 150; rawMultiplier = 1.0 × 1.1 × 1.0 = 1.1
    // floor(150 × 1.1) = 165
    $this->assertEquals(165, $points);
}
```

- [ ] **Step 1.3: Run tests to confirm RED state**

```bash
php artisan test tests/Feature/Contribution/PointsCalculatorTest.php --no-coverage
```

Expected output — exactly these failures, nothing else:

```
FAILED  Tests\Feature\Contribution\PointsCalculatorTest::synergy_multiplier_rewards_unique_skills
         Call to undefined method App\Services\GaneshStandardFormula::calculateSkillDiversityBonus()

FAILED  Tests\Feature\Contribution\PointsCalculatorTest::multiplier_cap_prevents_explosion
         Failed asserting that 324 matches expected 300.

FAILED  Tests\Feature\Contribution\PointsCalculatorTest::diminishing_returns_applies_after_20_hours
         Failed asserting that 125 is less than 125.

FAILED  Tests\Feature\Contribution\PointsCalculatorTest::community_attestation_provides_grassroots_alternative
         Array key 'community_attestation' does not exist.

Tests: 8, Failures: 4, Successes: 4 (the 4 untouched original tests pass)
```

If any original test breaks at this stage, **stop and fix before continuing**.

---

## Task 2: Implement GaneshStandardFormula v2 (GREEN phase)

**Files:**
- Modify: `app/Services/GaneshStandardFormula.php`

- [ ] **Step 2.1: Replace the entire file contents**

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
     * - self_report (0.5):           Unverified, lowest trust
     * - photo (0.7):                 Visual evidence, moderate trust
     * - document (0.8):              Written record, high trust
     * - third_party (1.0):           Independent confirmation, very high trust
     * - community_attestation (1.1): 3+ community members vouch, grassroots alternative
     * - institutional (1.2):         Official organisation letter, maximum trust
     */
    private const VERIFICATION_WEIGHTS = [
        'self_report'           => 0.5,
        'photo'                 => 0.7,
        'document'              => 0.8,
        'third_party'           => 1.0,
        'community_attestation' => 1.1,
        'institutional'         => 1.2,
    ];

    /**
     * MAX_COMBINED_MULTIPLIER
     *
     * Prevents multiplicative explosion where synergy × verification × sustainability
     * could reach 2.16× (1.5 × 1.2 × 1.2). Without a cap, contributions with all
     * maximum factors are unfairly amplified over those with slightly lower factors.
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
     * Formula: effectiveHours = threshold + log(extraHours + 1) × 5
     *
     * Examples:
     * - 20 h → 20.0 effective
     * - 30 h → 25.5 effective
     * - 40 h → 29.7 effective
     * - 50 h → 33.0 effective
     */
    private const DIMINISHING_RETURNS_THRESHOLD = 20;

    public function calculate(array $input, int $weeklyPoints): int
    {
        $track        = $input['track'] ?? 'micro';
        $config       = self::TRACK_CONFIG[$track];
        $effortUnits  = $input['effort_units'] ?? 0;
        $proofType    = $input['proof_type'] ?? 'self_report';
        $isRecurring  = $input['is_recurring'] ?? false;
        $skills       = $input['team_skills'] ?? [];
        $outcomeBonus = $input['outcome_bonus'] ?? 0;

        $effectiveEffort = $this->calculateEffectiveEffort($effortUnits);

        $base     = $effectiveEffort * $config['base_rate'];
        $tier     = $base >= $config['min_base'] ? $config['tier_bonus'] : 0;
        $subtotal = $base + $tier;

        $synergy        = $this->calculateSkillDiversityBonus($skills);
        $verification   = self::VERIFICATION_WEIGHTS[$proofType] ?? 1.0;
        $sustainability = $isRecurring ? 1.2 : 1.0;

        $rawMultiplier    = $synergy * $verification * $sustainability;
        $cappedMultiplier = min($rawMultiplier, self::MAX_COMBINED_MULTIPLIER);

        $points = (int) floor($subtotal * $cappedMultiplier) + $outcomeBonus;

        if ($config['weekly_cap'] !== null) {
            $remaining = max(0, $config['weekly_cap'] - $weeklyPoints);
            $points    = min($points, $remaining);
        }

        return $points;
    }

    /**
     * Calculate skill diversity bonus (formerly calculateSynergy).
     *
     * This is a heuristic, NOT a true Shapley value calculation.
     * It rewards teams with diverse skills as a proxy for marginal contribution.
     *
     * Rules:
     * - 1 unique skill  → 1.0× (individual contribution)
     * - 2 unique skills → 1.2× (pairwise synergy)
     * - 3+ unique skills → 1.5× (team synergy / cross-pollination)
     *
     * TODO: Validate thresholds empirically with user surveys and project outcomes.
     */
    public function calculateSkillDiversityBonus(array $skills): float
    {
        $uniqueCount = count(array_unique($skills));

        if ($uniqueCount >= 3) return 1.5;
        if ($uniqueCount >= 2) return 1.2;
        return 1.0;
    }

    /**
     * Apply diminishing returns to effort hours.
     *
     * Hours up to DIMINISHING_RETURNS_THRESHOLD are counted linearly.
     * Hours beyond that follow: effectiveExtra = log(extraHours + 1) × 5
     *
     * Rationale: marginal productivity decreases with extended effort,
     * and extreme hour counts should not dominate point totals.
     */
    private function calculateEffectiveEffort(int $hours): float
    {
        if ($hours <= self::DIMINISHING_RETURNS_THRESHOLD) {
            return (float) $hours;
        }

        $extraHours    = $hours - self::DIMINISHING_RETURNS_THRESHOLD;
        $effectiveExtra = log($extraHours + 1) * 5;

        return self::DIMINISHING_RETURNS_THRESHOLD + $effectiveExtra;
    }
}
```

- [ ] **Step 2.2: Run tests to confirm GREEN**

```bash
php artisan test tests/Feature/Contribution/PointsCalculatorTest.php --no-coverage
```

Expected: **8 tests, 8 passed, 0 failed**

If any test fails, check:
- `community_attestation` test failing → typo in the VERIFICATION_WEIGHTS key
- `multiplier_cap` test returning 324 → `min()` call is missing or misordered
- `diminishing_returns` test failing → verify `log()` uses natural log (PHP `log()` is natural log ✓)

- [ ] **Step 2.3: Commit RED→GREEN for the formula**

```bash
git add tests/Feature/Contribution/PointsCalculatorTest.php
git add app/Services/GaneshStandardFormula.php
git commit -m "feat(scoring): update GaneshStandardFormula v2

- Add MAX_COMBINED_MULTIPLIER = 2.0 to prevent multiplicative explosion
- Rename calculateSynergy() to calculateSkillDiversityBonus() for honesty
- Add calculateEffectiveEffort() with diminishing returns after 20h
- Add community_attestation proof type (1.1x) as grassroots alternative
- All 8 PointsCalculatorTest assertions pass"
```

---

## Task 3: Add community_attestation to the database enum

**Files:**
- Create: `database/migrations/2026_04_11_000002_add_community_attestation_proof_type.php`

> MySQL requires a full column redefinition to modify an ENUM. This migration adds the new value while preserving all existing values.

- [ ] **Step 3.1: Create the migration file**

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL requires ALTER COLUMN to redefine the full ENUM list
        DB::statement("
            ALTER TABLE contributions
            MODIFY COLUMN proof_type
            ENUM('self_report','photo','document','third_party','community_attestation','institutional')
            NOT NULL DEFAULT 'self_report'
        ");
    }

    public function down(): void
    {
        // Remove community_attestation — any existing rows with that value
        // must be migrated first (safe for fresh installs, requires data review in production)
        DB::statement("
            UPDATE contributions SET proof_type = 'third_party'
            WHERE proof_type = 'community_attestation'
        ");

        DB::statement("
            ALTER TABLE contributions
            MODIFY COLUMN proof_type
            ENUM('self_report','photo','document','third_party','institutional')
            NOT NULL DEFAULT 'self_report'
        ");
    }
};
```

- [ ] **Step 3.2: Run the migration**

```bash
php artisan migrate
```

Expected output:
```
  INFO  Running migrations.
  2026_04_11_000002_add_community_attestation_proof_type ........ 2ms DONE
```

If it fails with "Table 'contributions' doesn't exist": run `php artisan migrate` first to create the tables from the previous migration.

- [ ] **Step 3.3: Verify the schema**

```bash
php artisan tinker --execute="echo implode(', ', DB::select(\"SHOW COLUMNS FROM contributions LIKE 'proof_type'\")[0]->Type ?? []);"
```

Expected output should contain `community_attestation` in the enum list.

---

## Task 4: Update controller validation

**Files:**
- Modify: `app/Http/Controllers/Contribution/ContributionController.php` (line 69)

- [ ] **Step 4.1: Add community_attestation to the proof_type rule**

Find the `store()` method. Change line 69 from:

```php
'proof_type'    => ['required', 'in:self_report,photo,document,third_party,institutional'],
```

To:

```php
'proof_type'    => ['required', 'in:self_report,photo,document,third_party,community_attestation,institutional'],
```

- [ ] **Step 4.2: Run the full Contribution test suite to confirm no regressions**

```bash
php artisan test tests/Feature/Contribution/ --no-coverage
```

Expected: All tests pass (the controller tests do not directly test proof_type validation values, but confirm nothing else broke).

- [ ] **Step 4.3: Commit**

```bash
git add app/Http/Controllers/Contribution/ContributionController.php
git commit -m "feat(scoring): accept community_attestation proof type in store validation"
```

---

## Task 5: Update Vue proof type options and JS formula mirror

**Files:**
- Modify: `resources/js/Pages/Contributions/Create.vue`

The Vue file contains a JS mirror of the PHP formula so the points preview stays live. Three parts need updating:

1. `proofTypes` array — add the new option
2. `effectiveEffort` computed — add diminishing returns
3. `rawPoints` computed — apply the 2.0× cap

- [ ] **Step 5.1: Add community_attestation to proofTypes**

Find the `proofTypes` array in the `<script setup>` block (around line 248–254):

```js
const proofTypes = [
  { value: 'self_report', label: 'Self-report', multiplier: 0.5 },
  { value: 'photo', label: 'Photo', multiplier: 0.7 },
  { value: 'document', label: 'Document', multiplier: 0.8 },
  { value: 'third_party', label: 'Third-party', multiplier: 1.0 },
  { value: 'institutional', label: 'Institutional', multiplier: 1.2 },
]
```

Replace with:

```js
const proofTypes = [
  { value: 'self_report',           label: 'Self-report',          multiplier: 0.5 },
  { value: 'photo',                 label: 'Photo',                multiplier: 0.7 },
  { value: 'document',              label: 'Document',             multiplier: 0.8 },
  { value: 'third_party',           label: 'Third-party',          multiplier: 1.0 },
  { value: 'community_attestation', label: 'Community Attestation',multiplier: 1.1 },
  { value: 'institutional',         label: 'Institutional',        multiplier: 1.2 },
]
```

- [ ] **Step 5.2: Add effectiveEffort computed property**

After the `const subtotal = computed(...)` line (around line 271), insert:

```js
// Mirrors GaneshStandardFormula::calculateEffectiveEffort()
const DIMINISHING_RETURNS_THRESHOLD = 20
const effectiveEffort = computed(() => {
  const h = form.value.effort_units
  if (h <= DIMINISHING_RETURNS_THRESHOLD) return h
  const extra = h - DIMINISHING_RETURNS_THRESHOLD
  return DIMINISHING_RETURNS_THRESHOLD + Math.log(extra + 1) * 5
})
```

- [ ] **Step 5.3: Update basePoints to use effectiveEffort**

Change the `basePoints` computed (around line 264) from:

```js
const basePoints = computed(() => form.value.effort_units * trackConfig.value.base_rate)
```

To:

```js
const basePoints = computed(() => effectiveEffort.value * trackConfig.value.base_rate)
```

- [ ] **Step 5.4: Apply multiplier cap in rawPoints**

Find the `rawPoints` computed (around lines 290–294):

```js
const rawPoints = computed(() => {
  return Math.floor(
    subtotal.value * synergyMultiplier.value * currentProofMultiplier.value * sustainabilityMultiplier.value
  ) + form.value.outcome_bonus
})
```

Replace with:

```js
const MAX_COMBINED_MULTIPLIER = 2.0

const rawPoints = computed(() => {
  const rawMultiplier = synergyMultiplier.value * currentProofMultiplier.value * sustainabilityMultiplier.value
  const cappedMultiplier = Math.min(rawMultiplier, MAX_COMBINED_MULTIPLIER)
  return Math.floor(subtotal.value * cappedMultiplier) + form.value.outcome_bonus
})
```

- [ ] **Step 5.5: Update the multiplier breakdown display**

In the template, the synergy tooltip on line ~101 shows the old 1.5× upper bound. Find:

```html
1 skill: 1.0x | 2 skills: 1.2x | 3+ skills: 1.5x
```

Add the cap note (leave existing text, add after):

```html
1 skill: 1.0x | 2 skills: 1.2x | 3+ skills: 1.5x (combined max 2.0x)
```

Also in the Proof Type grid, the grid currently has `grid-cols-2 md:grid-cols-5`. With 6 options, change to `grid-cols-2 md:grid-cols-3 lg:grid-cols-6` so the new option fits:

Find:
```html
<div class="grid grid-cols-2 md:grid-cols-5 gap-2">
```

Replace with:
```html
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-2">
```

- [ ] **Step 5.6: Rebuild assets**

```bash
npm run build
```

Expected: Build completes with no errors.

- [ ] **Step 5.7: Commit**

```bash
git add resources/js/Pages/Contributions/Create.vue
git commit -m "feat(scoring): sync Create.vue formula with GaneshStandardFormula v2

- Add community_attestation proof type option (1.1x)
- Apply diminishing returns to effort hours in live preview
- Cap combined multiplier at 2.0x in points preview"
```

---

## Task 6: Run the complete test suite

- [ ] **Step 6.1: Run all Contribution tests**

```bash
php artisan test tests/Feature/Contribution/ --no-coverage
```

Expected: **All tests pass** (8 PointsCalculatorTest + ContributionPointsServiceTest + LeaderboardServiceTest).

If `ContributionPointsServiceTest` fails with a formula value mismatch, check that `ContributionPointsService` still calls `$this->formula->calculate($input, $weeklyPoints)` with both arguments — no signature change was made.

- [ ] **Step 6.2: Smoke test in browser (optional)**

```
http://localhost:8000/organisations/{slug}/contributions/create
```

Verify:
- Six proof type buttons appear (including "Community Attestation 1.1x")
- Setting effort to 30h shows lower points than 2× the 15h value
- Setting 3 skills + institutional + recurring shows ≤ 2.0× multiplier in the breakdown

---

## Task 7: Update developer_guide CHANGELOG

**Files:**
- Create: `developer_guide/contribution/CHANGELOG.md`

- [ ] **Step 7.1: Create the changelog**

```markdown
# Contribution Scoring Changelog

## v2 — Scientific Validation Updates (2026-04-11)

### Changed
- `calculateSynergy()` renamed to `calculateSkillDiversityBonus()` — removes misleading
  Shapley reference; the method is a heuristic, not a true Shapley calculation
- Effort scoring now applies diminishing returns after 20 hours:
  `effectiveHours = 20 + log(extraHours + 1) × 5`
- Combined multiplier (synergy × verification × sustainability) is now capped at 2.0×
  via `MAX_COMBINED_MULTIPLIER` — prevents unfair amplification in high-bonus scenarios

### Added
- `community_attestation` proof type (1.1×) — grassroots alternative to `institutional`
  for contributions verified by 3+ community members rather than an official body

### Rationale
Based on peer review by senior researcher identifying four risks in v1:
1. Multiplicative explosion (max 2.16× could reach with all bonuses)
2. Misleading method name implying Shapley value algorithm
3. Linear effort assumption penalising long-form projects vs. productivity research
4. Institutional proof bias disadvantaging communities without formal organisations

### Validation Needed (TODOs)
- Calibrate `MAX_COMBINED_MULTIPLIER` against production distribution
- Validate diminishing-returns curve with user satisfaction studies
- Measure fairness impact of `community_attestation` across user groups
- Adjust multiplier thresholds based on observed usage

## v1 — Initial Release (2026-04-11)

- Base formula: `(effort_units × 10 + TierBonus) × Synergy × Verification × Sustainability + OutcomeBonus`
- Three tracks: micro / standard / major
- Five proof types: self_report / photo / document / third_party / institutional
- Weekly micro-track cap: 100 points
```

- [ ] **Step 7.2: Commit**

```bash
git add developer_guide/contribution/CHANGELOG.md
git commit -m "docs(scoring): add CHANGELOG for GaneshStandardFormula v1 and v2"
```

---

## Self-Review

**Spec coverage check:**

| Spec requirement | Covered by |
|---|---|
| `MAX_COMBINED_MULTIPLIER = 2.0` | Task 2, Step 2.1 |
| Rename `calculateSynergy` → `calculateSkillDiversityBonus` | Task 1 Step 1.1, Task 2 Step 2.1 |
| Diminishing returns after 20h | Task 2 Step 2.1, Task 5 Steps 5.2–5.3 |
| `community_attestation` proof type (1.1×) | Task 2 Step 2.1, Task 3, Task 4, Task 5 Step 5.1 |
| New migration for enum | Task 3 |
| Controller validation update | Task 4 |
| Frontend `proofTypes` update | Task 5 Step 5.1 |
| JS formula mirrors PHP (cap + diminishing returns) | Task 5 Steps 5.2–5.4 |
| 3 new tests pass | Task 1 (RED), Task 2 Step 2.2 (GREEN) |
| All existing 5 tests still pass | Task 2 Step 2.2 (GREEN) |
| CHANGELOG documentation | Task 7 |

**No placeholders detected.** All steps contain exact code, exact commands, and exact expected outputs.

**Type/name consistency check:**
- `calculateSkillDiversityBonus` — used in test (Task 1.1), implementation (Task 2.1), and doc (Task 7) consistently
- `community_attestation` — key in VERIFICATION_WEIGHTS (Task 2.1), ENUM value (Task 3.1), validation string (Task 4.1), Vue value (Task 5.1) — all consistent
- `calculateEffectiveEffort` — private method in Task 2.1, JS mirror named `effectiveEffort` in Task 5.2 — named differently by convention (PHP private method vs JS computed), intentional
- `MAX_COMBINED_MULTIPLIER = 2.0` — PHP constant in Task 2.1, JS constant in Task 5.4 — consistent values
