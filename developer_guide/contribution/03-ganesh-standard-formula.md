# 03 — The Ganesh Standard Formula

## Purpose

`GaneshStandardFormula` is the pure scoring engine. It takes contribution data as an array and returns an integer point value. It has no framework dependencies, no database access, and no side effects — making it trivially testable.

**File:** `app/Services/GaneshStandardFormula.php`

---

## The Formula

```
Points = floor( (Base + TierBonus) × Synergy × Verification × Sustainability ) + OutcomeBonus
```

Then, for micro-track only, the result is capped by the remaining weekly allowance.

---

## Step-by-Step Breakdown

### 1. Base Score

```
Base = effort_units × base_rate
```

All tracks use `base_rate = 10`, so 5 hours = 50 base points.

### 2. Tier Bonus

Each track has a tier threshold and bonus. If the base score meets the threshold, the bonus is added.

| Track | min_base (threshold) | tier_bonus | weekly_cap |
|-------|---------------------|------------|------------|
| micro | 0 | 0 | 100 |
| standard | 31 | 50 | none |
| major | 201 | 200 | none |

**Example:** Standard track, 10 hours:
- Base = 10 × 10 = 100
- 100 >= 31 → tier bonus = 50
- Subtotal = 150

**Example:** Standard track, 2 hours:
- Base = 2 × 10 = 20
- 20 < 31 → tier bonus = 0
- Subtotal = 20

### 3. Synergy Multiplier

Based on the number of **unique** skills in `team_skills`:

| Unique Skills | Multiplier | Label |
|---------------|------------|-------|
| 0 or 1 | 1.0x | Single domain |
| 2 | 1.2x | Mixed skills |
| 3+ | 1.5x | Cross-pollination |

```php
public function calculateSynergy(array $skills): float
{
    $uniqueCount = count(array_unique($skills));
    if ($uniqueCount >= 3) return 1.5;
    if ($uniqueCount >= 2) return 1.2;
    return 1.0;
}
```

### 4. Verification Weight

Higher proof quality = higher multiplier:

| Proof Type | Weight | Rationale |
|------------|--------|-----------|
| self_report | 0.5x | Honor system, halved |
| photo | 0.7x | Visual evidence |
| document | 0.8x | Written record |
| third_party | 1.0x | External witness |
| institutional | 1.2x | Official confirmation — bonus multiplier |

### 5. Sustainability Bonus

| Recurring? | Multiplier |
|-----------|------------|
| No | 1.0x |
| Yes | 1.2x |

Recurring activities (weekly tutoring, monthly cleanups) get a 20% boost because sustained effort creates more community value than one-off events.

### 6. Outcome Bonus

An admin-assigned integer (0–200) for measurable outcomes. Added **after** all multiplications, not multiplied.

### 7. Weekly Cap (Micro Track Only)

```php
if ($config['weekly_cap'] !== null) {
    $remaining = max(0, $config['weekly_cap'] - $weeklyPoints);
    $points = min($points, $remaining);
}
```

Micro-track is capped at 100 points per ISO week per user. This prevents gaming through many small self-reported entries.

Standard and major tracks have **no weekly cap**.

---

## Full Calculation Example

**Input:**
- Track: standard
- Effort: 10 hours
- Proof: photo
- Skills: ['Teaching', 'Healthcare', 'Design'] (3 unique)
- Recurring: yes
- Outcome bonus: 0
- Weekly points already earned: 0

**Calculation:**
```
Base        = 10 × 10 = 100
Tier        = 100 >= 31 → +50
Subtotal    = 150
Synergy     = 3 unique skills → ×1.5
Verification = photo → ×0.7
Sustainability = recurring → ×1.2
Outcome     = +0

Points = floor(150 × 1.5 × 0.7 × 1.2) + 0
       = floor(189.0)
       = 189
```

---

## Track Configuration Constant

```php
private const TRACK_CONFIG = [
    'micro'    => ['base_rate' => 10, 'tier_bonus' => 0,   'min_base' => 0,   'weekly_cap' => 100],
    'standard' => ['base_rate' => 10, 'tier_bonus' => 50,  'min_base' => 31,  'weekly_cap' => null],
    'major'    => ['base_rate' => 10, 'tier_bonus' => 200, 'min_base' => 201, 'weekly_cap' => null],
];
```

---

## Important: Frontend Mirror

The `Create.vue` component contains a JavaScript copy of this formula for the live points preview. If the formula changes, **both files must be updated**:

- Backend: `app/Services/GaneshStandardFormula.php`
- Frontend: `resources/js/Pages/Contributions/Create.vue` (the `TRACK_CONFIG` constant and computed properties)

The frontend preview is an estimate. The backend is authoritative.
