# 28: G-6c-3, Identity merges and reversals with their D-90 notices, as one act (PBDIGIT-346)

**Design:** revision 3 §5, §5.1, §5.2, §17 (accepted 2026-11-14). **Evidence:** `docs/publicdigit/reference-geography/evidence/2026-11-14-pbdigit346-g6c3/`.

## In business terms

**Identity decides who is the same person. Reference Geography records what that means for its own publications,** in its own append-only history.
- **When Identity reports a merge or a reversal,** Geography records it **together with** every finding (an approval has become defective) and every closure (a defect has gone away) that it causes.
- **It's all or nothing:** the merge is never "consumed" while its consequences are missing.
- **A repeated notice changes nothing.**

## The invariant

> An Identity merge or reversal is either fully consumed, with every required finding and closure, or not consumed at all.

**The consumer checkpoint IS the roster entry** (unique per merge id and kind), written in the same transaction as the notices. There is no second checkpoint table that could advance alone.

## Components

| Layer | Component | Role |
|---|---|---|
| Domain | `ApprovalDefects`, `FindingLifecycle`, `StewardRoster` (G-6c-2) | all decisions: who is affected, finding or closure, what is open |
| Domain | `PublicationGovernance::mayPublish` | **replayed** at a publication's stored roster version to get its counted approver (§17) |
| Application | `IdentityNoticeConsumer` | orchestrates the act under `PublicationLedger::withLock` |
| Application | `PublicationOfRevision` | a published revision as read under the lock (position, closed revision, roster version) |
| Port | `LockedLedger` (+4) | `recordRoster`, `publicationsInvolving`, `lifecycleFor`, `appendNotice` |
| Infrastructure | `PostgresPublicationLedger` | implements them; `append` now also writes `refgeo_publication_persons` |
| Infrastructure | `PostgresStewardRosterStore::storeEntriesAfter` | the one roster-entry insert, shared by both writers |

## The flow (`IdentityNoticeConsumer::merged` / `reversed`)

```
withLock (one transaction: READ COMMITTED, then refgeo_sequence FOR UPDATE: THE publication lock)
  roster = L.roster(); before = roster.version()
  w = roster.recordMerge(m, S) | roster.recordReversal(m)      null ⇒ already consumed: return null, nothing written
  L.recordRoster(roster)                                       the checkpoint
  P = ApprovalDefects::affectedPersons(roster, before, w, S)   S for a reversal = the stored merge's persons ([] if it never arrived)
  candidates = L.publicationsInvolving(P), each with its counted approver = mayPublish(revision, roster, v0).secondPerson
  for n in ApprovalDefects::evaluate(roster, w, candidates, L.lifecycleFor(…), "merge:m" | "reversal:m"):
      L.appendNotice(n)                                        next gap-free position; G-4 clock rule; roster_version = w
commit; any exception rolls back ALL of it
```

- **Publication indexes its persons in the same transaction:** `append` writes every counting approver (`approver`) and every excluded person (`excluded`) for second-person publications.
- **The counted approver is replayed, not stored** (§17). That's exact because a published revision is closed (G-4f trigger) and every roster query takes a version.
  - **An indexed publication whose replay gives no second person is an integrity fault:** the act is refused, rolled back, and not consumed.
- **Notice rows:** `governance_rule = 'd-90-notice'` (the column is NOT NULL; a notice is no governance decision); `independence` NULL.

## Concurrency (one lock, so serialised)

| Interleaving | Result | Test |
|---|---|---|
| Merge starts while a publication holds the lock | waits, then sees the committed publication and raises its finding | A9 |
| Publication starts while a merge holds the lock | waits, then is **refused** (`ApproverNotIndependent`): case A at publication, no finding needed | A9 |
| Close through τ while either holds it | `BoundaryNotClosed` (busy), as G-5 | (unchanged) |

## Failure and recovery

- **A failure anywhere leaves no roster entry and no notice** (A10). It is proven at three injection points:
  1. **immediately after the roster entry is written** (an `AFTER INSERT` trigger on `refgeo_roster_entries`; the error names the written version);
  2. **at the first notice write;**
  3. **after all notices, before commit.** The hook first observes the finding and the roster entry inside the transaction, then fails.
- **Redelivery of the same Identity notice then applies it fully.** Nothing manual is needed; Identity's redelivery is the recovery.
- **Redelivery after success returns `null`** and writes nothing (A5).

## The architecture guard

`ReferenceGeographyNoBypassTest` rule 1 (the ledger) now allows `Application/IdentityNoticeConsumer.php`. That's **the accepted design T-1**, not a loosening: the consumer must hold the publication lock. Rule 3 (roster store) **no longer** allows the consumer: it doesn't use the store any more, so the guard got tighter there.

## Testing

```
MEMBERSHIP_PG_IMAGE=<pinned> MEMBERSHIP_PG_EVIDENCE_DIR=<dir> bash scripts/testing/membership-pg.sh --testsuite ReferenceGeographyPostgres --filter IdentityNoticeImpactTest
```

**`IdentityNoticeImpactTest` (10 tests):**
- A1 (merge then reversal: finding, then closure; gap-free; at w);
- A2 (A–B–C–D chain, reverse B–C);
- A3/A4 (two paths);
- A5 (redelivery; reversal before merge);
- A6/A7 (index completeness; Y indexed but X counted);
- A8 (**full-scan oracle on the cluster after each of 16 random steps**);
- A9 ×2 (forked lock interleavings);
- A10 (two failure injections, then redelivery);
- "starts its own transaction".

**Unit:** `RosterServicesTest` uses `tests/Support/ReferenceGeography/InMemoryPublicationLedger`.

**Mutation** (`mutation-check-g6c3.php`, copy-loaded, no source edited): C1 (no index), C2 (wrong counted approver), C3 (lifecycles ignored), C4 (reversal names nobody), C5 (no checkpoint), C6 (resolutions not read). **All killed by assertions, 0 errors; each run executed the 10 tests.**

## Pitfalls

- **Never write roster entries for an Identity notice outside `withLock`.** The roster store's `writeUnderLock` is for steward appointments and declarations only.
- **A new failure point inside the act needs no compensation:** throw, and the transaction rolls back.
- **Not here yet:** confirmation writes (G-6c-4, role `confirmer`) and correction links (G-6c-5). `lifecycleFor` already reads both.

**Administrator action: none.** No migration, configuration or worker is involved.

**Traceability:** PBDIGIT-346 G-6c-3 · design revision 3 §5, §17 · D-88, D-90 · G-4 interpretation §2, §4.
