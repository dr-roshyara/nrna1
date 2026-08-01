# Slice 7C — Completion Evidence

**Date:** 2026-08-01 · **Prepared by:** Engineering, under **R-65**
**Status:** **RED → GREEN → VERIFY complete. ACCEPT is the ARB's act (R-34); engineering does not accept its own work.**

---

## 1. Changes made

| File | Change |
|---|---|
| `app/Console/Commands/AuditCleanup.php` | the retention guard — folder→election resolution, two fail-closed paths, the deletion veto |
| `tests/Feature/Audit/AuditCleanupTest.php` | **4 new tests**; **3 pre-existing fixtures amended** per R-65 |
| `developer_guide/election/08_retention_guard.md` · `00_index.md` | developer guide (Definition of Done) |

## 2. Changes deliberately NOT made

**No new port · no aggregate, entity or repository · no domain term · no context crossing · no change to `EvidencePreservationWindow`, `ResolvesEvidencePreservationWindow` or `EvidencePreservationDurations` · no config key added or changed · no change to the audit folder layout · nothing in Adjudication · WP-7B-R1 not absorbed, implemented or anticipated.**

**How deletion is performed is unchanged** — traversal, `File::deleteDirectory`, and both output lines are byte-identical in behaviour. **The guard gates the decision only.**

**`phpunit.xml` was not modified** — see the scope caveat in §4.

## 3. Verification

```
php artisan test tests/Feature/Audit/AuditCleanupTest.php   →  10 passed (21 assertions)
composer merge-gate                                         →  PASS
   Architecture fitness · Deptrac · greenfield PHPStan · widened regression
   266 tests · 665 assertions · 0 failures · 101 pre-existing risky notices
```

**RED was genuine:** before implementation, three tests failed — *window open ⇒ retained*, *unmappable ⇒ retained*, and *`--days` cannot override* — each because no guard existed. **The fourth new test passed in RED**, correctly: with a closed window, deleting by age happens to give the right answer, so it only becomes load-bearing once the guard exists.

## 4. ⚠️ Scope caveat — stated because "merge gate PASS" would otherwise imply more than it covers

**`GreenfieldCore` — the suite the merge gate runs — does not include `tests/Feature/Audit/`.** Its directory list covers `tests/Unit/Contexts/*`, `tests/Feature/Contexts/*` and `tests/Replay` only.

> **So the merge gate's PASS does not exercise this slice's tests.** The count is 266 before and after, which is the evidence. **The ten tests pass under `php artisan test`, run separately and reported above.**
>
> **`phpunit.xml` was not modified to widen the gate.** The authorization's boundary is `AuditCleanup.php`; changing what the blocking gate runs is a separate concern with its own owner. **Recorded as a finding, not silently fixed.**

## 5. Architectural invariants preserved

| Invariant | Evidence |
|---|---|
| **AC-1** how deletion is performed unchanged | traversal, removal and both output strings untouched; the six pre-existing tests still pass |
| **AC-2** no duration defined, defaulted or clamped | the command reads none; all three arrive through the port |
| **AC-3** MAD has one home | untouched; `DurationPolicyOwnershipTest` green in the gate |
| **AC-4** no crossing | no event added, changed or retired; nothing calls Adjudication |
| **AC-5** artifact A only | `storage/logs/audit` only |
| **AC-6 / AC-7** VO takes business values; fallback in the service | neither file touched |
| **AC-8** no new port, aggregate, entity, repository or domain term | none added |
| **AC-9** WP-7B-R1 untouched | `ResolvesEvidencePreservationWindow` unmodified |

**Ownership unchanged:** Audit / Retention owns the guard · Election owns the consumed capability · Adjudication supplies MAD as configuration.

## 6. Implementation finding — recorded, not a deviation

**`Election` carries the `BelongsToTenant` global scope, and a CLI run has no tenant session.** Without `withoutGlobalScopes()` every lookup returned `null`, every folder was preserved, and the command **failed safe but stopped working**.

> **The tests found it, not the reading.** The first GREEN attempt failed on four tests — the closed-window case and the three amended fixtures — all from this single cause.
>
> **This is within the authorized boundary:** an Infrastructure-layer read, using the mechanism the model itself documents, granting no cross-tenant capability to any user-facing path. **No stop-and-refer trigger was hit** — no new bounded context, aggregate, repository, port, domain term, crossing or policy change.
>
> **A soft-deleted election is simply not found, which the guard treats as *preserve* — failing in the safe direction.**

## 7. Operational evidence — evaluated per the four outcomes

| Outcome | Assessment |
|---|---|
| **No reusable knowledge** | — |
| **PKS Observation** | ⚠️ **candidate:** a blocking gate whose suite excludes the directory under change reports PASS without exercising it (§4). **Recurrence unknown; one occurrence.** |
| **Repeated operational evidence** | no |
| **KnowledgeOS candidate** | no — one corpus |

**Nothing is promoted.** **The §4 caveat is recorded here as evidence; whether it becomes a PKS observation is for the acceptance review, not for engineering to decide.**

## 8. Next

**ACCEPT is the ARB's act.** On acceptance: **R-66**, WP-7 closes, and WP-7B-R1 resumes as an independent refinement under R-60.

---

**Traceability:** **R-65** (authorization) · **R-59** · **R-60** · **R-44** · **AP-1 · AP-2** · **Constitutional Policy 2** · `.claude/plans/WP-7-retention-alignment.md` §5 · `developer_guide/election/08_retention_guard.md` · `composer merge-gate` (PASS). **Engineering supplies evidence; it does not accept its own work.**
