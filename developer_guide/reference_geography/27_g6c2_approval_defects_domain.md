# 27: G-6c-2, the D-90 domain: approval defects and the finding lifecycle (PBDIGIT-346)

**Design:** revision 3, accepted 2026-11-14 (§4, §5.1, §6, §8, §10; decisions §9 = full coverage, §8.4 = re-evaluate). **Evidence:** `docs/publicdigit/reference-geography/evidence/2026-11-14-pbdigit346-g6c2/`.

## In business terms

**The business rules of D-90, in pure code with no database:**
- **which publications an identity merge or reversal can affect;**
- **whether an approval has become defective,** which raises a finding;
- **whether a defect has gone away,** which closes the finding;
- **whether a finding is still open;**
- **the approval status as known at any point in history.**

**Nothing writes notices yet.** That's the ledger, in G-6c-3.

## Components (all `App\Contexts\ReferenceGeography\Domain`)

| Class | Role |
|---|---|
| `StewardRoster::componentAt(person, v)` | everyone known-same as the person at v (the same relation as `knownSameAt`) |
| `StewardRoster::mergedPersonsOf(mergeId)`, `effectiveMergesWithin(persons, v)` | the persons a merge named; the merges joining a group (a finding's basis) |
| `ApprovalDefects::affectedPersons(roster, before, after, named)` | the components of the named persons before and after the change |
| `ApprovalDefects::evaluate(roster, w, candidates, lifecycles, change)` | per candidate: a **finding** (defective, none open) or a **closure** (sound, one open); corrected publications skipped |
| `FindingLifecycle` | **the one definition of "open"** (§8.1); `correctedAt`; `countedApproverAt` (the confirmer after a confirmation); `statusAt(k)` |
| `GovernanceNotice`, `NoticeKind` | finding (merge basis) · confirmation (confirmer, digest) · closure (what ended it) |
| `PublishedApproval` | position, revision, content version, digest, **counted approver** (replayed from `mayPublish`, §17), excluded |
| `ApprovalStatus`, `ApprovalStatusKind` | approved · approval-defective · confirmed · corrected (q) · finding-closed, plus the history up to k |

## How to use it (G-6c-3 will, under the lock)

```php
$before   = $roster->version();
$w        = $roster->recordMerge($mergeId, $persons);              // null ⇒ already applied: stop
$affected = ApprovalDefects::affectedPersons($roster, $before, $w, $persons);
$notices  = ApprovalDefects::evaluate($roster, $w, $candidatesInvolving($affected), $lifecycles, "merge:{$mergeId}");
// append each notice, in order, in the same transaction
```

## Design notes

- **Affected persons: the before ∪ after union equals the after-union** when *all* of a merge's named persons are used. The mutation run found this (mutant D1 is equivalent); test A8b pins it.
  - Why: a former path through the merge reaches it at a named person, along a prefix that doesn't use it.
  - **The accepted union is kept;** it's exact either way.
- **Counted approver:** the original before a confirmation, the latest confirmer after it (§8.4 (a)). History is preserved because `countedApproverAt` and `statusAt` take k.
- **Corrected publications are skipped** (full coverage: their facts are replaced).

## Testing

- `ApprovalDefectsTest`: A1–A6, cases C and D, B7, B8, B9, corrected-skip, **A8** (300 random rounds against a full-scan oracle), **A8b** (the equivalence).
- `FindingLifecycleTest`: B1, every resolution, status at every k, the counted approver, foreign entries refused.
- **Mutation:** D2 to D7 killed by assertions; **D1 equivalent** (proof above).

**Traceability:** PBDIGIT-346 G-6c-2 · design revision 3 §4–§10 · D-88, D-90.
