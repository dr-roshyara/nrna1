# Step 2: National-only election — no false regional warning

**Commit:** `81aa13b83` — `fix(voting): don't warn about missing regional candidates on national-only elections`

## Purpose

`CreateVotingPage.vue` shows a "no regional candidates available" warning to any voter who has a
`region` set on their profile but sees zero regional posts. This fired even on **purely
national-only elections** — a valid, common configuration, not an error. A voter with
`region = 'Europe'` on a national-only election would see a warning implying something was wrong,
when nothing was.

## Where it fits

```
app/Http/Controllers/VoteController.php       ← $electionProp gains has_regional_posts
resources/js/Pages/Vote/CreateVotingPage.vue  ← hasRegionButNoPosts() guard
```

## Design decision

The correct invariant is:

```
has_regional_posts = false
        ↓
regional warning = NEVER
```

i.e. the warning may only appear when **both** are true: the election actually defines a regional
section, **and** the current voter's own region has no posts within it. A national-only election
must never trigger it, regardless of what region value the voter happens to have.

## How it works

`VoteController::create()`'s `$electionProp` now includes:

```php
'has_regional_posts' => $election->isDemo()
    ? DemoPost::where('election_id', $election->id)->where('is_national_wide', false)->exists()
    : Post::withoutGlobalScopes()->where('election_id', $election->id)->where('is_national_wide', false)->exists(),
```

(The same computation `BallotAssemblyService::buildElectionProp()` already performs for Ballot
Preview — see Step 1 — kept independent here since `VoteController`'s demo-election branch uses
`DemoPost`, which the service intentionally does not handle.)

`CreateVotingPage.vue`'s `hasRegionButNoPosts` computed property:

```js
hasRegionButNoPosts() {
    return this.election?.has_regional_posts === true &&
           !!this.user_region &&
           this.normalizedRegionalPosts.length === 0 &&
           !this.isLoading
}
```

## Testing

`tests/Feature/Vote/VoteControllerBallotAssemblyRegressionTest.php`:
- `test_election_prop_has_regional_posts_is_false_for_national_only_election_even_when_voter_has_a_region` — a national-only election, voter region `'Europe'`, asserts `election.has_regional_posts === false` and no warning condition is met.
- `has_regional_posts` assertions added to the existing mixed-posts test (`true`) and no-region test, so all three combinations (mixed election + region, mixed election + no region, national-only + region) are covered.

## Pitfalls

- This is distinct from "voter has no region set" (`user_region` empty) — that case was already handled correctly before this fix and is covered by a separate existing test. Don't conflate the two conditions when touching this logic again.
- Demo elections use `DemoPost`, not `Post` — the ternary above is required, not incidental.

## Traceability

Commit: `81aa13b83`. Reported and approved as a "potential intentional improvement" during Ballot Preview's manual-walkthrough checkpoint, then implemented as its own isolated fix per the project's "no unintentional behavioral change" discipline.
