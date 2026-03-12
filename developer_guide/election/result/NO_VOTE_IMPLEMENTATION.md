# No-Vote (Abstention) Feature — Developer Guide

**Date:** 2026-03-11
**Branch:** multitenancy
**Scope:** Demo election voting flow — abstention tracking, storage, display, and results

---

## Overview

A voter can choose to **skip (abstain from) a post** instead of selecting a candidate.
This feature ensures that abstentions are:

1. Captured in the voting form (`Create.vue`)
2. Carried through the session to the verification page (`Verify.vue`)
3. Persisted to the database (`demo_results`, `demo_votes`)
4. Displayed in the results dashboard (`/demo/result`)
5. Included in the PDF export with correct percentages

---

## Database Changes

### Migration: `2026_03_11_000000_add_no_vote_to_results_tables.php`

```php
Schema::table('demo_results', function (Blueprint $table) {
    $table->boolean('no_vote')->default(false)->after('vote_count');
    $table->unsignedBigInteger('candidacy_id')->nullable()->change(); // allow null for abstention rows
});

Schema::table('results', function (Blueprint $table) {
    $table->boolean('no_vote')->default(false)->after('vote_count');
    $table->unsignedBigInteger('candidacy_id')->nullable()->change();
});
```

**Key points:**
- `no_vote = true` rows have `candidacy_id = NULL`
- One row per abstained post per vote
- Both `demo_results` and `results` tables updated

### Model: `app/Models/BaseResult.php`

```php
protected $fillable = [
    'organisation_id', 'election_id', 'vote_id', 'post_id',
    'candidacy_id', 'vote_count', 'position_order',
    'no_vote',  // ← ADDED
];

protected $casts = ['no_vote' => 'boolean'];  // ← ADDED
```

---

## Backend Changes

### 1. Saving Abstentions — `DemoVoteController::saveCandidateSelections()`

**File:** `app/Http/Controllers/Demo/DemoVoteController.php`

When a voter skips a post, a `DemoResult` row is saved with `no_vote = true` and `candidacy_id = null`:

```php
foreach ($all_candidates as $index => $selection) {
    $column_name = 'candidate_' . str_pad($index + 1, 2, '0', STR_PAD_LEFT);
    $vote_data = $this->prepareVoteData($selection);

    if ($vote_data) {
        $vote->$column_name = json_encode($vote_data);

        if (!empty($selection['candidates'])) {
            $this->saveCandidateResults($vote->id, $selection);
        } elseif (!empty($selection['no_vote'])) {
            DemoResult::create([
                'vote_id'      => $vote->id,
                'post_id'      => $selection['post_id'],
                'candidacy_id' => null,
                'no_vote'      => true,
            ]);
        }
    }
}
```

### 2. Verify Page Data Flow — `DemoVoteController::verify()`

**File:** `app/Http/Controllers/Demo/DemoVoteController.php`

`sanitize_vote_data()` is called before `process_vote_data_for_verification()` to fix inconsistent session data:

```php
// Sanitize before processing: fixes no_vote=false with empty candidates
$vote_data = $this->sanitize_vote_data($vote_data);
$processed_vote_data = $this->process_vote_data_for_verification($vote_data);
```

`sanitize_selection()` converts `{no_vote: false, candidates: []}` → `{no_vote: true}`:

```php
private function sanitize_selection($selection)
{
    $no_vote = $selection['no_vote'] ?? false;
    $candidates = $selection['candidates'] ?? [];
    if ($no_vote === false && count($candidates) === 0) {
        $selection['no_vote'] = true;
    }
    return $selection;
}
```

### 3. Results Counting — `DemoResultController` (index method)

**File:** `app/Http/Controllers/Demo/DemoResultController.php`

No_vote entries in vote columns are detected and counted:

```php
if (isset($candidateData['no_vote']) && $candidateData['no_vote'] === true) {
    $noVoteCount++;
    $postResults['total_votes_for_post']++;  // abstentions count toward total
    continue;
}
```

`total_votes_for_post` = candidate votes + no_vote votes → percentages always sum to 100%.

### 4. PDF Export — `DemoResultController` (pdf method)

Abstentions row always rendered at end of each post, percentage is calculated as remainder:

```php
$noVoteCount = intval($postResult['no_vote_count'] ?? 0);
$candidatePercentSum = array_sum(array_column($postResult['candidates'], 'vote_percent'));
$noVotePercent = round(max(0, 100 - $candidatePercentSum), 1) . '%';

$pdf->SetTextColor(180, 0, 0);
$pdf->SetFillColor(255, 235, 235);
$pdf->Cell(10, 6, '-', 1, 0, 'C', true);
$pdf->Cell(70, 6, 'Abstentions (No Vote)', 1, 0, 'L', true);
$pdf->Cell(25, 6, (string)$noVoteCount, 1, 0, 'C', true);
$pdf->Cell(23, 6, $noVotePercent, 1, 1, 'R', true);
$pdf->SetTextColor(0, 0, 0);
```

---

## Frontend Changes

### 1. Voting Form — `Create.vue`

**File:** `resources/js/Pages/Vote/DemoVote/Create.vue`

**Problem:** Skipped posts were only pushed to `no_vote_posts: [postId, ...]` — a flat ID array. `process_vote_data_for_verification()` only reads `national_selected_candidates` / `regional_selected_candidates`, so skipped posts were invisible to the verify page.

**Fix:** When a post is skipped, also add it to the appropriate candidates array with `no_vote: true`:

```javascript
if (noVoteSelections.value[post.id]) {
    voteData.no_vote_posts.push(post.id)

    // Also add to candidates array so verify page and backend can see it
    const isNational = props.posts.national.some(p => p.id === post.id)
    const noVotePostType = isNational ? 'national' : 'regional'
    voteData[`${noVotePostType}_selected_candidates`].push({
        post_id: post.id,
        post_name: post.name,
        required_number: post.required_number,
        no_vote: true,
        candidates: []
    })
}
```

**Data flow after fix:**

```
noVoteSelections[post.id] = true
    → no_vote_posts: [postId]                          (backward compat)
    → national_selected_candidates: [{                  (new)
          post_id, post_name, no_vote: true, candidates: []
      }]
    → backend sanitize_vote_data() sees it ✓
    → process_vote_data_for_verification() sees it ✓
    → saveCandidateSelections() saves DemoResult no_vote row ✓
    → Verify.vue receives no_vote: true in post data ✓
```

### 2. Verify Page — `Verify.vue`

**File:** `resources/js/Pages/Vote/DemoVote/Verify.vue`

No-vote indicator shown in red in the right summary column for each skipped post:

```vue
<div v-if="post.no_vote" class="mt-2 flex items-center gap-1.5 text-xs text-red-600">
  <svg ...> <!-- circle-slash icon --> </svg>
  <span>{{ $t('pages.vote-verify.summary.no_vote_label', 'No Vote / Enthaltung') }}</span>
</div>
<div v-else-if="post.candidates.length > 0" class="mt-1">
  <!-- candidate list -->
</div>
```

**Candidate name fallback** (field name differs between `Create.vue` and `CreateVotingform.vue`):
```vue
{{ candidate.candidacy_name || candidate.name }}
```

### 3. Vote Show Page — `DemoVote/VoteShow.vue` and `Vote/VoteShow.vue`

**Files:**
- `resources/js/Pages/Vote/DemoVote/VoteShow.vue`
- `resources/js/Pages/Vote/VoteShow.vue`

No-vote section styled red (was gray):

```vue
<!-- Before -->
<div class="w-10 h-10 bg-gray-200 rounded-full ...">
<p class="font-medium text-gray-900">...</p>
<p class="text-sm text-gray-600">...</p>

<!-- After -->
<div class="w-10 h-10 bg-red-100 rounded-full ...">
<p class="font-medium text-red-600">...</p>
<p class="text-sm text-red-500">...</p>
```

### 4. Results Dashboard — `Demo/Result/Candidate.vue`

**File:** `resources/js/Pages/Demo/Result/Candidate.vue`

**Changes:**

a) **Abstentions row always visible** (removed `v-if="no_vote_count > 0"`):
```vue
<!-- Desktop table: always show -->
<tr class="bg-red-50 dark:bg-red-900/20 font-semibold">
  <td colspan="2" class="... text-red-600">Abstentions (No Vote)</td>
  <td class="... text-red-600">{{ final_result.no_vote_count || 0 }}</td>
  <td class="... text-red-600">{{ noVotePercent }}%</td>
  <td></td>
</tr>

<!-- Mobile card: always show -->
<div class="bg-red-50 ... border-l-4 border-red-400">
  <p class="font-semibold text-red-600">Abstentions (No Vote)</p>
  <span class="text-red-600 font-bold">{{ noVotePercent }}%</span>
  <p class="text-xs text-red-500">{{ final_result.no_vote_count || 0 }} voters abstained</p>
</div>
```

b) **Percentages always sum to 100%** — no_vote gets the remainder:
```javascript
const candidatePercentSum = computed(() =>
  props.final_result.candidates.reduce((sum, c) => sum + parseFloat(c.vote_percent || 0), 0)
)

const noVotePercent = computed(() => {
  if (!props.final_result.total_votes_for_post) return '0.00'
  return Math.max(0, 100 - candidatePercentSum.value).toFixed(2)
})
```

---

## Translation Keys Added

### `resources/js/locales/pages/VoteVerify/{de,en,np}.json`

```json
{
  "summary": {
    "no_vote_label": "No Vote / Abstention"   // en
    "no_vote_label": "Keine Stimme / Enthaltung"  // de
    "no_vote_label": "मत नगरेको / अनुपस्थित"   // np
  }
}
```

---

## Percentage Correctness Guarantee

| Method | Formula |
|--------|---------|
| Candidate % | `round(candidate_votes / total_votes_for_post * 100, 2)` |
| No-vote % (Vue) | `100 - sum(candidate percentages)` |
| No-vote % (PDF) | `100 - array_sum(vote_percent column)` |
| Total | Always exactly **100%** |

`total_votes_for_post` = `Σ candidate votes` + `no_vote_count`

---

## Files Changed — Summary

| File | Change |
|------|--------|
| `database/migrations/2026_03_11_000000_add_no_vote_to_results_tables.php` | Add `no_vote` column to `demo_results` and `results`; make `candidacy_id` nullable |
| `app/Models/BaseResult.php` | Add `no_vote` to `$fillable` and `$casts` |
| `app/Http/Controllers/Demo/DemoVoteController.php` | Save no_vote rows in `saveCandidateSelections()`; call `sanitize_vote_data()` in `verify()` |
| `app/Http/Controllers/Demo/DemoResultController.php` | Count no_vote in totals; always render abstentions row in PDF with remainder percent |
| `resources/js/Pages/Vote/DemoVote/Create.vue` | Include skipped posts in candidates array with `no_vote: true` on submit |
| `resources/js/Pages/Vote/DemoVote/Verify.vue` | Show red no_vote indicator in right column |
| `resources/js/Pages/Vote/DemoVote/VoteShow.vue` | Style no_vote section red |
| `resources/js/Pages/Vote/VoteShow.vue` | Style no_vote section red |
| `resources/js/Pages/Demo/Result/Candidate.vue` | Always show abstentions row (red); percentages sum to 100% via remainder |
| `resources/js/locales/pages/VoteVerify/{de,en,np}.json` | Add `no_vote_label` translation key |

---

## Testing Checklist

- [ ] Vote in demo election, skip at least one national post and one regional post
- [ ] Verify page shows skipped posts with red "No Vote" label
- [ ] Submit verification code → vote saved
- [ ] `/demo/result` shows all candidates including 0-vote candidates
- [ ] Abstentions row appears at bottom of every post (even if 0)
- [ ] All percentages in each post sum to exactly 100%
- [ ] Download PDF — abstentions row present, red, correct percentage
- [ ] Vote show page (`/demo-vote/show/{uuid}`) shows skipped posts in red
