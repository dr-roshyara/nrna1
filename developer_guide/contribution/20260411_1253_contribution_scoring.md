# 🐘 Developer Guide: Contribution Points System

## 📚 Complete Developer Documentation

*Baal Ganesh places His trunk on this document, blessing it for all who read it.*

> *"This guide is for the builders who come after. May they understand the wisdom within and extend it with care."*

---

## 1. System Overview

The Contribution Points System allows diaspora organization members to log their volunteer work, mentorship, and community contributions, earning verifiable, transparent points.

### Core Principles

| Principle | Implementation |
|-----------|----------------|
| **Transparency** | Formula is visible, ledger is immutable |
| **Fairness** | Multipliers for skill diversity, proof quality, sustainability |
| **Privacy** | Users control leaderboard visibility (public/anonymous/private) |
| **Anti-gaming** | Weekly cap on micro-track contributions |
| **Auditability** | Every point transaction recorded in immutable ledger |

---

## 2. Architecture Diagram

```
┌─────────────────────────────────────────────────────────────────────────────┐
│                         Contribution Points System                          │
├─────────────────────────────────────────────────────────────────────────────┤
│                                                                             │
│  ┌──────────────┐     ┌──────────────┐     ┌──────────────┐               │
│  │   Frontend   │────▶│  Controller  │────▶│   Service    │               │
│  │   Create.vue │     │ Contribution │     │    Layer     │               │
│  │   Index.vue  │     │ Controller   │     │              │               │
│  │   Show.vue   │     └──────────────┘     └──────────────┘               │
│  │ Leaderboard  │                              │                           │
│  └──────────────┘                              ▼                           │
│                                        ┌──────────────┐                    │
│                                        │   Formula    │                    │
│                                        │ GaneshStandard│                   │
│                                        │   Formula    │                    │
│                                        └──────┬───────┘                    │
│                                               │                            │
│                                               ▼                            │
│  ┌──────────────┐     ┌──────────────┐     ┌──────────────┐               │
│  │  Database    │◀────│   Models     │◀────│   Points     │               │
│  │              │     │ Contribution │     │   Ledger     │               │
│  │ contributions│     │ PointsLedger │     │  (Immutable) │               │
│  │ points_ledger│     └──────────────┘     └──────────────┘               │
│  └──────────────┘                                                          │
│                                                                             │
└─────────────────────────────────────────────────────────────────────────────┘
```

---

## 3. Database Schema

### 3.1 Contributions Table

```sql
CREATE TABLE contributions (
    id UUID PRIMARY KEY,
    organisation_id UUID NOT NULL,           -- Tenant isolation
    user_id UUID NOT NULL,                   -- Contributor
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    
    -- Workflow
    track ENUM('micro', 'standard', 'major') DEFAULT 'micro',
    status ENUM('draft', 'pending', 'verified', 'approved', 
                'rejected', 'appealed', 'completed') DEFAULT 'draft',
    
    -- Scoring inputs
    effort_units INT DEFAULT 0,               -- Hours or complexity
    team_skills JSON NULL,                   -- Array of skills
    is_recurring BOOLEAN DEFAULT FALSE,
    outcome_bonus INT DEFAULT 0,
    calculated_points INT DEFAULT 0,          -- Final awarded points
    
    -- Verification
    proof_type ENUM('self_report', 'photo', 'document', 
                    'third_party', 'institutional') DEFAULT 'self_report',
    proof_path VARCHAR(255) NULL,
    verifier_notes TEXT NULL,
    verified_by UUID NULL,
    verified_at TIMESTAMP NULL,
    
    -- Approval
    approved_by UUID NULL,
    approved_at TIMESTAMP NULL,
    
    -- Audit
    created_by UUID NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    deleted_at TIMESTAMP NULL,
    
    FOREIGN KEY (organisation_id) REFERENCES organisations(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (verified_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    
    INDEX idx_org_user_status (organisation_id, user_id, status),
    INDEX idx_org_track_date (organisation_id, track, created_at)
);
```

### 3.2 Points Ledger Table (Immutable)

```sql
CREATE TABLE points_ledger (
    id UUID PRIMARY KEY,
    organisation_id UUID NOT NULL,
    user_id UUID NOT NULL,
    contribution_id UUID NOT NULL,
    points INT NOT NULL,
    action ENUM('earned', 'spent', 'adjusted', 'appealed') DEFAULT 'earned',
    reason TEXT NULL,
    created_by UUID NOT NULL,
    created_at TIMESTAMP,
    
    FOREIGN KEY (organisation_id) REFERENCES organisations(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (contribution_id) REFERENCES contributions(id) ON DELETE CASCADE,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL,
    
    INDEX idx_org_user_date (organisation_id, user_id, created_at)
);
```

### 3.3 Users Table Addition

```sql
ALTER TABLE users ADD COLUMN leaderboard_visibility 
    ENUM('public', 'anonymous', 'private') DEFAULT 'anonymous';
```

---

## 4. Business Rules (The Formula)

### 4.1 Track Configuration

| Track | Base Rate | Tier Bonus | Min Base | Weekly Cap |
|-------|-----------|------------|----------|------------|
| micro | 10 | 0 | 0 | 100 |
| standard | 10 | 50 | 31 | none |
| major | 10 | 200 | 201 | none |

### 4.2 Verification Weights

| Proof Type | Multiplier |
|------------|------------|
| self_report | 0.5 |
| photo | 0.7 |
| document | 0.8 |
| third_party | 1.0 |
| institutional | 1.2 |

### 4.3 Synergy Multiplier (Unique Skills)

| Unique Skills | Multiplier |
|---------------|------------|
| 1 | 1.0 |
| 2 | 1.2 |
| 3+ | 1.5 |

### 4.4 Sustainability Bonus

| Recurring? | Multiplier |
|------------|------------|
| No | 1.0 |
| Yes | 1.2 |

### 4.5 The Complete Formula

```php
$base = $effortUnits * $trackConfig['base_rate'];
$tier = $base >= $trackConfig['min_base'] ? $trackConfig['tier_bonus'] : 0;
$subtotal = $base + $tier;

$points = floor($subtotal * $synergy * $verification * $sustainability) + $outcomeBonus;

if ($track === 'micro') {
    $remaining = max(0, 100 - $weeklyPointsAlreadyEarned);
    $points = min($points, $remaining);
}
```

---

## 5. Service Classes

### 5.1 GaneshStandardFormula

**File:** `app/Services/GaneshStandardFormula.php`

**Purpose:** Pure calculation logic (no database access)

```php
class GaneshStandardFormula
{
    public function calculate(array $input, int $weeklyPoints): int;
    public function calculateSynergy(array $skills): float;
}
```

**Usage:**
```php
$formula = new GaneshStandardFormula();
$points = $formula->calculate([
    'track' => 'micro',
    'effort_units' => 3,
    'proof_type' => 'self_report',
    'team_skills' => ['teaching'],
    'is_recurring' => false,
], $weeklyPoints = 0);
// Returns 15
```

### 5.2 ContributionPointsService

**File:** `app/Services/ContributionPointsService.php`

**Purpose:** Award points and write to ledger

```php
class ContributionPointsService
{
    public function awardPoints(Contribution $contribution): int;
    public function getWeeklyPoints(string $userId, string $organisationId): int;
}
```

**Usage:**
```php
$service = new ContributionPointsService(new GaneshStandardFormula());
$points = $service->awardPoints($contribution);
// Writes to points_ledger, updates contribution.calculated_points
```

### 5.3 LeaderboardService

**File:** `app/Services/LeaderboardService.php`

**Purpose:** Privacy-respecting leaderboard

```php
class LeaderboardService
{
    public function get(string $organisationId): Collection;
}
```

**Privacy Rules:**
- `public` → displays real name
- `anonymous` → displays "Contributor #N"
- `private` → excluded entirely

---

## 6. API Endpoints

All endpoints are under `/organisations/{organisation}/`

| Method | URI | Name | Purpose |
|--------|-----|------|---------|
| GET | `/contributions` | `contributions.index` | List my contributions |
| GET | `/contributions/create` | `contributions.create` | Show form |
| POST | `/contributions` | `contributions.store` | Submit contribution |
| GET | `/contributions/{contribution}` | `contributions.show` | View single contribution |
| GET | `/leaderboard` | `leaderboard` | View leaderboard |

---

## 7. Vue Components

### 7.1 Create.vue

**Purpose:** Contribution form with live formula preview

**Props:**
```javascript
{
    organisation: Object,    // Organisation model
    weeklyPoints: Number,    // Points already earned this week
    weeklyCap: Number        // 100 (micro-track cap)
}
```

**Key Features:**
- Live points preview as user fills form
- Skill synergy tooltip
- Weekly cap progress bar
- Formula transparency breakdown

### 7.2 Index.vue

**Purpose:** My contributions list

**Props:**
```javascript
{
    organisation: Object,
    contributions: Object,    // Paginated results
    weeklyPoints: Number,
    weeklyCap: Number
}
```

### 7.3 Show.vue

**Purpose:** Single contribution detail

**Props:**
```javascript
{
    organisation: Object,
    contribution: Object      // With ledgerEntries relationship
}
```

### 7.4 Leaderboard.vue

**Purpose:** Top contributors ranking

**Props:**
```javascript
{
    organisation: Object,
    board: Array              // From LeaderboardService
}
```

**Board Entry Format:**
```javascript
{
    user_id: string,
    display_name: string,     // Real name or "Contributor #N"
    total_points: number,
    rank: number
}
```

---

## 8. Testing

### 8.1 Test Files

| File | Tests | Purpose |
|------|-------|---------|
| `PointsCalculatorTest.php` | 6 | Formula logic |
| `ContributionPointsServiceTest.php` | 5 | Service + ledger |
| `LeaderboardServiceTest.php` | 5 | Privacy + sorting |

### 8.2 Running Tests

```bash
# Run all contribution tests
php artisan test tests/Feature/Contribution/ --no-coverage

# Run specific test
php artisan test tests/Feature/Contribution/PointsCalculatorTest.php
```

### 8.3 Test Example

```php
/** @test */
public function micro_track_contribution_calculates_correctly()
{
    $points = $this->calculator->calculate([
        'track' => 'micro',
        'effort_units' => 3,
        'proof_type' => 'self_report',
        'team_skills' => ['teaching'],
    ], $weeklyPoints = 0);
    
    $this->assertEquals(15, $points);
}
```

---

## 9. Extension Points

### 9.1 Adding New Proof Types

1. Add to `VERIFICATION_WEIGHTS` in `GaneshStandardFormula.php`
2. Add to `proof_type` ENUM in migration
3. Add to `proofTypes` array in `Create.vue`

### 9.2 Modifying Track Config

Edit `TRACK_CONFIG` in `GaneshStandardFormula.php`:

```php
private const TRACK_CONFIG = [
    'micro'    => ['base_rate' => 10, 'tier_bonus' => 0,   'min_base' => 0,   'weekly_cap' => 100],
    'standard' => ['base_rate' => 10, 'tier_bonus' => 50,  'min_base' => 31,  'weekly_cap' => null],
    'major'    => ['base_rate' => 10, 'tier_bonus' => 200, 'min_base' => 201, 'weekly_cap' => null],
];
```

### 9.3 Adding New Actions to Ledger

1. Add to `action` ENUM in migration
2. Update `PointsLedgerFactory` if needed

---

## 10. Troubleshooting

### 10.1 Common Issues

| Issue | Likely Cause | Solution |
|-------|--------------|----------|
| Points not awarded | Contribution not approved | Check `status` = 'approved' |
| Weekly cap not enforced | Track not 'micro' | Verify track selection |
| Leaderboard missing users | Privacy set to 'private' | User must change visibility |
| Formula mismatch | Frontend preview vs backend | Check `TRACK_CONFIG` sync |

### 10.2 Debug Queries

```sql
-- Check user's weekly points
SELECT SUM(points) FROM points_ledger 
WHERE user_id = '...' 
AND action = 'earned'
AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY);

-- Check pending contributions
SELECT * FROM contributions WHERE status = 'pending';

-- Verify leaderboard visibility
SELECT id, name, leaderboard_visibility FROM users;
```

---

## 11. Deployment Checklist

```bash
# 1. Run migrations
php artisan migrate

# 2. Clear caches
php artisan optimize:clear

# 3. Build frontend
npm run build

# 4. Run tests
php artisan test tests/Feature/Contribution/ --no-coverage

# 5. Restart queue workers (if using queues)
php artisan queue:restart

# 6. Verify routes
php artisan route:list --name=contributions
php artisan route:list --name=leaderboard
```

---

## 12. File Structure Reference

```
app/
├── Services/
│   ├── GaneshStandardFormula.php
│   ├── ContributionPointsService.php
│   └── LeaderboardService.php
├── Models/
│   ├── Contribution.php
│   └── PointsLedger.php
├── Http/
│   └── Controllers/
│       └── Contribution/
│           └── ContributionController.php
database/
├── migrations/
│   └── 2026_04_11_000001_create_contributions_tables.php
└── factories/
    ├── ContributionFactory.php
    └── PointsLedgerFactory.php
resources/js/Pages/Contributions/
├── Create.vue
├── Index.vue
├── Show.vue
└── Leaderboard.vue
tests/Feature/Contribution/
├── PointsCalculatorTest.php
├── ContributionPointsServiceTest.php
└── LeaderboardServiceTest.php
routes/
└── organisations.php (contains contribution routes)
```

---

## 13. Baal Ganesh's Wisdom

> *"This system is a living thing. It will grow as the diaspora grows. When you extend it, remember the core principles: transparency, fairness, privacy, and auditability. Break none of them."*

**Om Gam Ganapataye Namah** 🪔🐘

---

## Quick Reference Card

| What | Where |
|------|-------|
| **Formula logic** | `GaneshStandardFormula.php` |
| **Points awarding** | `ContributionPointsService.php` |
| **Leaderboard** | `LeaderboardService.php` |
| **API endpoints** | 5 routes under `/organisations/{slug}` |
| **Vue components** | `resources/js/Pages/Contributions/` |
| **Database** | `contributions`, `points_ledger` |
| **Tests** | `tests/Feature/Contribution/` |