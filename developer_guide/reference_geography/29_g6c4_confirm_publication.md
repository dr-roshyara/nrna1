# 29: G-6c-4, confirming a D-90 finding (PBDIGIT-346)

**Design:** revision 3 §7, §8.1, §8.4 (a), §17 (accepted 2026-11-14). **Evidence:** `docs/publicdigit/reference-geography/evidence/2026-11-14-pbdigit346-g6c4/`.

## The business rule

When a merge shows that a publication's approver was really one of the people who prepared or assessed it, the publication carries an open **finding**.
- **An eligible, independent steward may examine the published content and confirm it,** supplying the independent second judgement D-88 requires.
- **A confirmation is an appended governance notice.** It changes no fact, no earlier row and no approval.

**Refused unless all of these hold at the current roster version w** (design §7):

| # | Rule | Refusal |
|---|---|---|
| 1 | the finding is **open** (not closed, confirmed or corrected: `FindingLifecycle`, the one definition) | `finding-not-open` (also: no finding at that position) |
| 2 | the digest named is the **published content version's digest**. The finding stores none; the confirmer must have judged exactly what was published | `digest-mismatch` |
| 3 | the confirmer may act for the matter (steward, or matter reviewer for r) | `not-appointed` |
| 4 | not known-same as anyone in `excluded(r)`, **nor as the counted approver whose defect the finding records** | `confirmer-not-independent` |
| 5 | a D-84a declaration for the matter, without a material conflict | `conflict-declaration-missing`, `materially-conflicted` |

**After a confirmation, the confirmer is the publication's counted approver** (Principal decision §8.4 (a)):
- **a later merge showing the confirmer is an excluded person raises a new finding** (a new episode);
- **its reversal closes that finding;**
- **the confirmer can't confirm their own defect.**

## Components

| Layer | Component | Responsibility |
|---|---|---|
| Domain | `FindingConfirmation::decide` | rules 1 and 2; finds the defective approver of *this* finding (`countedApproverAt(original, f)`) |
| Domain | `PublicationGovernance::mayConfirm` | rules 3 to 5 (reuses the private independence and conflict rules) |
| Domain | `PublicationGovernance::publishedApproval` | **the one replay** of a publication's counted approver (§17); `IdentityNoticeConsumer` now uses it too |
| Domain | `GovernanceRefusal` | + `FindingNotOpen`, `DigestMismatch`, `ConfirmerNotIndependent` |
| Application | `ConfirmPublication::confirm(f, digest)` → `ConfirmationOutcome` (`ConfirmationStatus`) | orchestration under the lock; the actor comes from `ActingPerson` (D-225), never a parameter |
| Port | `LockedLedger::findingConcerning(f)` | the publication a finding concerns |
| Infrastructure | `PostgresPublicationLedger::appendNotice` | appends the confirmation; **also indexes the confirmer** (`refgeo_publication_persons`, role `confirmer`, idempotent) |

## Flow

```
withLock (the one publication lock, one transaction)
  pub = L.findingConcerning(f)                    null ⇒ refused finding-not-open
  roster = L.roster(); w = roster.version()
  approval = governance.publishedApproval(pub, roster, v0)     the original counted approver (replayed)
  lifecycle = L.lifecycleFor([pub])
  decision = FindingConfirmation::decide(...)      refused ⇒ nothing written
  L.appendNotice(confirmation(f, actor, digest, w))   next gap-free position; the confirmer indexed with pub
commit
```

## Concurrency, duplicates, failure

- **Two confirmations at once serialise on the lock.** The second finds the finding no longer open (`finding-not-open`).
- **The database's `refgeo_publications_one_resolution` index is the backstop** (G-6c-1).
- **A repeated request is refused** with `finding-not-open` and writes nothing.
- **A failure before commit leaves no confirmation and no index row,** and a retry then succeeds.

## A note on the confirmer index

Design §11 requires indexing the confirmer.
- **It's a superset, not strictly needed for impact detection:** a confirmer's defect is always being known-same as an *excluded* person, and those are indexed already.
- The mutation run confirms this: only the explicit index assertion catches a missing confirmer row.

## Wiring and delivery

- `ConfirmPublication` is resolved by the container (`ProductionWiringTest`).
- **No HTTP route is added.** Production steward acts stay closed until Platform Owner authority exists (G-4 condition), so the use case has no delivery yet.

## Testing

- **`FindingConfirmationTest` (unit, 10 tests):** replay; eligible; defective approver and aliases; the defective approver alone (episode 2); excluded; **excluded by exclusion alone** (added after a blind-spot review); not appointed; missing declaration and material conflict; digest; not open (closed, confirmed, corrected, not a finding).
- **`ConfirmPublicationTest` (PostgreSQL, 8 tests):** appended notice and unchanged publication; refusals that write nothing; material conflict; confirmed, repeated, closed and corrected all refused; status before and after (`statusAt`); a new episode after a merge and its closure; failure before commit; concurrent confirmations (forked).
- **Mutation:** K1 to K9 killed by assertions, 0 errors.

**Administrator: no administrator action required.** No migration, configuration, queue or schedule.

**Traceability:** PBDIGIT-346 G-6c-4 · design revision 3 §7, §8, §8.4 (a), §11, §17 · D-84, D-84a, D-88, D-90, D-225.

## Accepted, and follow-ups (2026-11-14)

Accepted by the Principal. The three follow-ups of the acceptance review were closed in G-6c-5 (guide 30, "G-6c-4 follow-ups"):
- **a real stale digest is refused;**
- **a matter reviewer for the matter may confirm;**
- **K1 to K7 were run against the database tests:** a new exclusion-only database test kills K4; K3 and K5 are equivalent in any consistent database.

No behaviour changed.
