# 05 — VE-01 Ballot integrity: RED contract and design (no GREEN yet)

**Story:** `PBDIGIT-237` · **Status:** 🔴 RED contract written; design approved as direction; **GREEN not authorised** · **Related:** `PBDIGIT-236` (D6, regional authority — open)
**Evidence:** `docs/publicdigit/reviews/2026-10-05-voting-engine-verification-gate-report.md` · **Design:** `docs/publicdigit/reviews/2026-10-05-ve01-ballot-integrity-design-and-adr-amendment-proposal.md` · **Forensics:** `docs/publicdigit/reviews/2026-10-05-d4-forensic-schema-verification-and-query-set.md`

> This guide describes **tests and an approved design direction**, not shipped behaviour. The final cast still commits the session ballot unchecked. Do not build on the target structure below until GREEN is authorised.

---

## Purpose

Phase 4 (E1–E4) answers *may this voter cast now?* and makes participation consumption atomic. It does not answer *is the content of the ballot valid?* VE-01 is that missing question: the final cast persists whatever ballot sits in the voter's session.

## Where it fits

```
store() (real election)
  forCast(CastAttempt) → CastDecision           Phase 4 — frozen, unchanged
  [ballot integrity — MISSING today]           VE-01 — beside forCast(), before the CAS
  CAS codes.has_voted false→true                Phase 4 E4
  save_vote() → Vote::createResultsFromCandidates()
```

## What happens today (FACT, from source and T1)

`Vote::createResultsFromCandidates()` (`app/Models/Vote.php:61`) decodes every `candidate_NN` column and writes one `results` row per entry. `post_id` and `candidacy_id` come from the **stored session payload**; only `election_id` comes from the vote:

```php
$decoded = json_decode($value, true);
$postId = $decoded['post_id'] ?? null;
// …
Result::create([
    'election_id'  => $this->election_id,
    'post_id'      => $postId,
    'candidacy_id' => $candidate['candidacy_id'],
    // …
]);
```

Foreign keys stop only ids that do not exist. A candidacy of another election, a post of another region, the same candidacy twice or more selections than the maximum are all committed — and `ResultController::index` counts them.

## The two test files

| File | Role | State |
|---|---|---|
| `tests/Feature/Voting/BallotIntegrityCharacterizationTest.php` | **T1 characterization** through the real chain: ballot submit → final cast → `results` → published tally | A, F, G, J2 pass; B, C, D, E, H, I (partly), J, L fail = the defect |
| `tests/Feature/Voting/FinalCastBallotInvariantTest.php` | **VE-01 RED contract** at the final-cast boundary; the ballot is placed straight into the session | 4 GREEN (valid, D2, allowed abstention, N3) · 16 RED |

The contract bypasses the ballot-submit step on purpose — **session data is untrusted input**, so the final cast must hold on its own:

```php
return $this->actingAs($voter)
    ->withSession([
        $code->session_name => [
            'national_selected_candidates' => $national,
            'regional_selected_candidates' => $regional,
            // …
        ],
    ])
    ->post(route('slug.vote.store', ['vslug' => $slug->slug]), ['voting_code' => $code->code_to_open_voting_form]);
```

Every invalid case asserts the same refusal shape (`assertInvalidBallotRefusedBeforeCas()`): no 500 · no vote · `codes.has_voted` unchanged · no result rows · **the CAS statement never ran** (captured with `DB::listen`) · a reasoned `vote` error, not the generic `vote_error`.

## Decisions encoded (PO, frozen)

| ID | Rule |
|---|---|
| D1 | only selections from the voter's authoritative ballot; each candidacy at most once |
| D2 | `posts.required_number` is the **maximum** (0 … max) |
| D3 | malformed ballot → fail closed |
| D5 | only `approved` candidacies are electable (central fix in `BallotAssemblyService`) |
| N1–N5 | abstention + candidates invalid · empty list = Not answered · omitted post = Not answered · final cast enforces the election's abstention permission · abstention outside the ballot invalid |
| D6 | **open** (`PBDIGIT-236`) — VE-01 uses today's `users.region` for characterization only |

## Target design (approved as direction — not implemented)

```
SubmittedBallot (session, untrusted)   AuthoritativeBallot (current ballot assembly authority)
              └──────────── BallotValidator (pure) ────────────┘
                     ValidatedBallot | BallotRejection
authorization AND ballot validation complete  →  CAS  →  save_vote(ValidatedBallot)  →  COMMIT
```

- **Beside `forCast()`**, not inside: `CastDecision` and its 14 reasons stay unchanged.
- **One CastContext** (Organisation · User · Election · ElectionVoter) feeds both decisions.
- **Order invariant:** both checks pass before the CAS; their relative order is not constitutional.
- ADR amendment (approved in principle, revision act pending): I1 gains "ValidatedBallot exists" and "exactly the validated content is persisted"; new I11 (transport data untrusted), I12 (`save_vote()` accepts only a `ValidatedBallot`), I13 (both checks before the CAS, one CastContext).

## Pitfalls for whoever implements GREEN

- **D5 affects fixtures.** `CandidacyFactory` defaults to `status => 'pending'`. Once `BallotAssemblyService` returns approved candidacies only, existing voting tests whose fixtures rely on the default will see empty ballots. Plan a PO-approved, fixture-only adaptation against a pre-GREEN baseline.
- **T1 is a characterization of the defect.** Its failing cases must be reclassified or retired with PO approval at GREEN — they are not a second contract.
- **IP-density rule.** Several casts from one IP trip the Trust → Legitimacy density rule; the T1 helper `bayernVoter()` gives every voter's code its own `client_ip`.
- **No voter ↔ ballot link.** Validation reads voter-side context and writes nothing ballot-side beyond the validated content (ADR I10, interim anonymity rule).
- **Historical data.** D4 only *measures*; no correction, deletion or migration under VE-01.

## Testing

```bash
php artisan test tests/Feature/Voting/FinalCastBallotInvariantTest.php      # 4 pass / 16 fail until GREEN
php artisan test tests/Feature/Voting/BallotIntegrityCharacterizationTest.php
```

Synthetic data only; never run test suites in parallel against the shared test database.

**Traceability:** PBDIGIT-237 · PBDIGIT-236 · gate report §4–§18 · ADR_20261005_0950 I1/I10 · design proposal §2–§7 · D4.0 query set.
