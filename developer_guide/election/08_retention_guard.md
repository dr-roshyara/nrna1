# 08 — The Retention Guard (`audit:cleanup` becomes EPW-aware)

**Slice:** WP-7C · **Authorized by:** R-65 · **Preceded by:** `06_evidence_preservation_durations.md` · `07_evidence_preservation_window.md`

## Purpose

`audit:cleanup` used to delete any audit folder older than `--days`. Under **Constitutional Policy 2** that is not safe: *evidence required for a legally permissible challenge must never expire before that challenge can no longer be initiated or resolved.* **The guard makes deletion conditional on the election's Evidence Preservation Window being closed.**

## Where it fits

| Layer | Piece |
|---|---|
| **Infrastructure** | `app/Console/Commands/AuditCleanup.php` — traversal, the folder→election parser, the guard, deletion, reporting |
| **Application** (Election) | `ResolvesEvidencePreservationWindow::isOpenFor()` — answers *is the window open* |
| **Domain** (Election) | `EvidencePreservationWindow` — the window itself |

**Audit / Retention owns the deletion decision. Election owns the capability that answers it.** Adjudication supplies the Maximum Adjudication Duration **as configuration**; nothing calls into Adjudication.

## How it works

```php
if ($modifiedTime < $cutoff && !$this->isPreserved($folder, $preservation, $now)) {
    File::deleteDirectory($folder);
}
```

**The age cutoff still decides candidacy; the guard vetoes.** How deletion is performed — traversal, `File::deleteDirectory`, the `Deleted:` and `Cleanup complete.` lines — is unchanged (R-65).

### Resolving a folder to its election

Audit folders are named `{slug}_{Ymd}_{Hi}` by `ElectionAuditService`, so the slug is everything before the timestamp suffix:

```php
if (preg_match('/^(?<slug>.+)_\d{8}_\d{4}$/', $folderName, $matches) !== 1) {
    return null;   // unparseable ⇒ preserved
}
```

### Two fail-closed paths

**A folder is preserved when its name cannot be parsed, and when no election matches the slug.** Evidence is never treated as expired on the strength of a missing fact — the same rule `ResolvesEvidencePreservationWindow` applies to a missing anchor.

### ⚠️ The tenant scope — the one non-obvious line

```php
Election::query()->withoutGlobalScopes()->where('slug', $slug)->first();
```

**`Election` carries the `BelongsToTenant` global scope, and a CLI run has no tenant session.** Without the bypass every lookup returns `null`, every folder is preserved, and the command silently stops deleting anything — **it fails safe, but it also stops working.**

**This was found by the tests, not by reading:** the closed-window and pre-existing deletion tests failed on the first GREEN attempt for exactly this reason.

**Retention is a system-wide job — audit folders are not tenant-scoped.** The bypass grants no cross-tenant capability to any user-facing path. **A soft-deleted election is simply not found, which the guard already treats as *preserve*.**

## How to extend

**Adding a duration or changing one** — that is Q-2's decision and lands in `config/election_preservation.php` (CW, LSM) or `config/adjudication.php` (MAD). **Never in this command, and never a second copy of MAD** (AP-2, enforced by `Tests\Architecture\DurationPolicyOwnershipTest`).

**Changing the anchor** — that is `ResolvesEvidencePreservationWindow::anchorOf()`, currently INTERIM pending Q-2, and the subject of **WP-7B-R1**. The guard is unaffected: it consumes `isOpenFor()` and never sees the anchor.

## Testing

`tests/Feature/Audit/AuditCleanupTest.php` — ten tests. Four are the guard:

| Test | Asserts |
|---|---|
| `it_retains_a_folder_whose_preservation_window_is_open` | an open window beats the age cutoff |
| `it_deletes_a_folder_whose_preservation_window_has_closed` | the guard releases — it is a guard, not a freeze |
| `it_retains_a_folder_that_cannot_be_mapped_to_an_election` | fail closed |
| `days_option_no_longer_overrides_the_retention_invariant` | `--days=1` cannot delete inside an open window |

**Six pre-existing tests were kept.** Three assert deletion, so under the new criterion their fixtures needed a resolvable election with a **closed** window (anchor 400 days back, past every interim bootstrap). **No assertion was weakened and no test was removed** — the amendment was authorized by R-65.

> ⚠️ **`tests/Feature/Audit/` is not in the `GreenfieldCore` testsuite, so `composer merge-gate` does not run these tests.** Run them directly: `php artisan test tests/Feature/Audit/AuditCleanupTest.php`.

## Pitfalls

- **Do not read MAD from a retention config.** It has one home; this adapter reads Adjudication's key.
- **Do not "fix" a preserved folder by loosening the guard.** Preservation on an unresolvable mapping is the required behaviour, not a bug.
- **Do not drop `withoutGlobalScopes()`** — the command will appear to work and delete nothing.
- **Do not put the deletion decision in Election.** Election answers; Audit/Retention acts.

---

**Traceability:** **R-65** (Slice 7C authorization) · **R-59** (7B accepted) · **R-60** (WP-7B-R1) · **R-44** (Election's own durations port) · **AP-1** fail closed · **AP-2** one home per parameter · **Constitutional Policy 2** (`EPIC-003 §THE FOUR DECISIONS` №2) · `.claude/plans/WP-7-retention-alignment.md` §5 Slice 7C.
