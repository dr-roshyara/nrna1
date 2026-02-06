# 🎬 Demo Candidates Factory Guide

## Overview

The **DemoCandidateFactory** generates realistic random demo candidates for testing election voting workflows without affecting real election data.

---

## Quick Start

### Generate Candidates via Seeder

```bash
php artisan db:seed --class=DemoCandidateSeeder
```

This creates 17 demo candidates:
- **President**: 3 candidates
- **Vice President**: 3 candidates
- **Secretary**: 3 candidates
- **Treasurer**: 3 candidates
- **Member at Large**: 5 candidates

---

## Factory Usage Examples

### 1. **Create Single Candidate**

```php
use App\Models\DemoCandidate;

// Create 1 random candidate
DemoCandidate::factory()->create();
```

### 2. **Create Multiple Candidates**

```php
// Create 10 random candidates
DemoCandidate::factory()->count(10)->create();
```

### 3. **Create Candidates for Specific Post**

```php
// Create 5 President candidates
DemoCandidate::factory()
    ->count(5)
    ->forPost('president')
    ->create();

// Available posts:
// 'president'
// 'vice_president'
// 'secretary'
// 'treasurer'
// 'member_at_large'
```

### 4. **Create Candidates for Specific Election**

```php
use App\Models\Election;

$demoElection = Election::where('type', 'demo')->first();

DemoCandidate::factory()
    ->count(5)
    ->forElection($demoElection)
    ->create();

// Or using election ID:
DemoCandidate::factory()
    ->count(5)
    ->forElection(1)
    ->create();
```

### 5. **Chain Multiple Conditions**

```php
// Create 3 Vice President candidates for demo election
DemoCandidate::factory()
    ->count(3)
    ->forPost('vice_president')
    ->forElection($demoElection)
    ->create();
```

### 6. **Set Custom Candidate Name**

```php
DemoCandidate::factory()
    ->withName('Ramesh Sharma')
    ->forPost('president')
    ->create();
```

### 7. **Set Proposer Information**

```php
DemoCandidate::factory()
    ->withProposer('Michael Brown', 'demo_prop_001')
    ->count(3)
    ->create();
```

### 8. **Set Supporter Information**

```php
DemoCandidate::factory()
    ->withSupporter('Emily Davis', 'demo_supp_001')
    ->count(3)
    ->create();
```

### 9. **Combine All Options**

```php
DemoCandidate::factory()
    ->count(5)
    ->forPost('secretary')
    ->forElection($demoElection)
    ->withName('Jane Doe')
    ->withProposer('John Smith')
    ->withSupporter('Alice Johnson')
    ->create();
```

---

## Using in Tests (PHPUnit/Pest)

### Example Test

```php
<?php

namespace Tests\Feature;

use App\Models\DemoCandidate;
use App\Models\Election;
use Tests\TestCase;

class CandidateVotingTest extends TestCase
{
    /** @test */
    public function it_displays_all_candidates_for_a_post()
    {
        $election = Election::where('type', 'demo')->first();

        // Create 5 Secretary candidates
        $candidates = DemoCandidate::factory()
            ->count(5)
            ->forPost('secretary')
            ->forElection($election)
            ->create();

        $this->assertCount(5, $candidates);

        // Verify they're all for secretary post
        $candidates->each(function ($candidate) {
            $this->assertEquals('secretary', $candidate->post_id);
        });
    }

    /** @test */
    public function candidates_have_required_fields()
    {
        $candidate = DemoCandidate::factory()
            ->forPost('president')
            ->create();

        $this->assertNotNull($candidate->candidacy_id);
        $this->assertNotNull($candidate->user_name);
        $this->assertNotNull($candidate->post_id);
        $this->assertNotNull($candidate->proposer_name);
        $this->assertNotNull($candidate->supporter_name);
    }
}
```

---

## Using in Tinker (Interactive Console)

```bash
php artisan tinker
```

```php
// Generate 10 random candidates
DemoCandidate::factory()->count(10)->create();

// Generate 3 President candidates
DemoCandidate::factory()->count(3)->forPost('president')->create();

// Generate candidates for all posts
$posts = ['president', 'vice_president', 'secretary', 'treasurer', 'member_at_large'];
foreach ($posts as $post) {
    DemoCandidate::factory()->count(3)->forPost($post)->create();
}

// View candidates
DemoCandidate::all();

// View candidates for specific post
DemoCandidate::where('post_id', 'president')->get();

// Delete all demo candidates
DemoCandidate::truncate();

// Exit tinker
exit();
```

---

## Using in Database Seeders

### Custom Seeder Example

```php
<?php

namespace Database\Seeders;

use App\Models\DemoCandidate;
use App\Models\Election;
use Illuminate\Database\Seeder;

class CustomDemoCandidateSeeder extends Seeder
{
    public function run()
    {
        $demoElection = Election::where('type', 'demo')->first();

        // Create many candidates for stress testing
        DemoCandidate::factory()
            ->count(100)
            ->forElection($demoElection)
            ->create();

        $this->command->info('✅ Generated 100 random demo candidates!');
    }
}
```

Run it:
```bash
php artisan db:seed --class=CustomDemoCandidateSeeder
```

---

## Factory Methods Reference

### Available Methods

| Method | Purpose | Example |
|--------|---------|---------|
| `count(n)` | Create N candidates | `count(10)` |
| `forPost(string)` | Set candidate's post | `forPost('president')` |
| `forElection(Election\|int)` | Set election | `forElection($election)` |
| `withName(string)` | Set candidate name | `withName('John Doe')` |
| `withProposer(string, string)` | Set proposer info | `withProposer('Jane', 'id')` |
| `withSupporter(string, string)` | Set supporter info | `withSupporter('Bob', 'id')` |

### Generated Fields

| Field | Type | Generated By |
|-------|------|-------------|
| `candidacy_id` | string | `DEMO_ABC_1234` (random) |
| `user_id` | string | `demo_user_1234` (random) |
| `user_name` | string | Faker `name()` |
| `candidacy_name` | string | Same as `user_name` |
| `post_id` | string | Specified or random |
| `post_name` | string | English post name |
| `post_nepali_name` | string | Nepali post name |
| `proposer_id` | string | `demo_prop_1234` (random) |
| `proposer_name` | string | Faker `name()` |
| `supporter_id` | string | `demo_supp_1234` (random) |
| `supporter_name` | string | Faker `name()` |
| `election_id` | int | Demo election ID |
| `image_path_1-3` | string | null (can be extended) |

---

## Post Types

```php
'president'       // अध्यक्ष
'vice_president'  // उपाध्यक्ष
'secretary'       // सचिव
'treasurer'       // कोषाध्यक्ष
'member_at_large' // सामान्य सदस्य
```

---

## Common Tasks

### Reset Demo Candidates

```bash
# Delete all demo candidates
php artisan tinker
>>> DemoCandidate::truncate();
>>> exit();

# Reseed with fresh data
php artisan db:seed --class=DemoCandidateSeeder
```

### Generate Large Dataset for Performance Testing

```bash
php artisan tinker

>>> use App\Models\DemoCandidate;
>>> use App\Models\Election;
>>> $demo = Election::where('type', 'demo')->first();
>>> DemoCandidate::factory()->count(500)->forElection($demo)->create();
>>> DemoCandidate::count();
=> 500
>>> exit();
```

### Get Candidate Statistics

```bash
php artisan tinker

>>> DemoCandidate::groupBy('post_id')->selectRaw('post_id, COUNT(*) as count')->get();
>>> DemoCandidate::where('post_id', 'president')->count();
>>> DemoCandidate::pluck('post_name')->unique();
>>> exit();
```

---

## Best Practices

✅ **DO:**
- Use factory in tests to ensure fresh test data
- Chain methods for readability: `->count(5)->forPost('president')->create()`
- Use `tinker` for quick data generation during development
- Clear demo candidates before each test suite

❌ **DON'T:**
- Hardcode candidate data in seeders (use factory instead)
- Generate candidates without specifying an election
- Mix demo and production candidate data
- Use factory for production elections

---

## Troubleshooting

### Issue: `Election not found`

**Error:** `Trying to get property of non-object`

**Solution:** Ensure demo election exists
```bash
php artisan db:seed --class=ElectionSeeder
php artisan db:seed --class=DemoCandidateSeeder
```

### Issue: Factory not found

**Solution:** Ensure proper namespace
```php
use App\Models\DemoCandidate;
use Database\Factories\DemoCandidateFactory;
```

### Issue: Unique constraint on candidacy_id

**Solution:** The factory generates unique IDs, but if duplicates occur:
```bash
php artisan tinker
>>> DemoCandidate::truncate();
>>> exit();
```

---

## Integration with Voting Workflow

### Display Candidates in View

```php
// Controller
$candidates = DemoCandidate::where('post_id', 'president')
    ->with('user')
    ->get();

return view('vote', ['candidates' => $candidates]);
```

### Template (Blade/Vue)

```blade
@foreach($candidates as $candidate)
    <div class="candidate">
        <h3>{{ $candidate->user_name }}</h3>
        <p>{{ $candidate->proposer_name }}</p>
        <p>{{ $candidate->supporter_name }}</p>
    </div>
@endforeach
```

---

## Advanced: Extending the Factory

### Add Image Generation

```php
// database/factories/DemoCandidateFactory.php

public function withImages()
{
    return $this->state(function (array $attributes) {
        return [
            'image_path_1' => 'candidates/demo_' . Str::random(8) . '.jpg',
            'image_path_2' => 'candidates/demo_' . Str::random(8) . '.jpg',
            'image_path_3' => 'candidates/demo_' . Str::random(8) . '.jpg',
        ];
    });
}
```

Usage:
```php
DemoCandidate::factory()->withImages()->create();
```

---

## Summary

| Task | Command |
|------|---------|
| Generate candidates | `php artisan db:seed --class=DemoCandidateSeeder` |
| Interactive generation | `php artisan tinker` |
| Create in tests | `DemoCandidate::factory()->count(5)->create()` |
| Filter by post | `DemoCandidate::where('post_id', 'president')->get()` |
| Reset data | `DemoCandidate::truncate()` |

---

**Happy testing! 🎉**
