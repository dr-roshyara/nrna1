# PBDIGIT-235 — A soft-deleted voting `Code` can still be used to cast a vote

**Type:** Defect candidate (voting process, code lifecycle semantics) · **Epic:** `PBDIGIT-EPIC-05` Voting · **Created:** 2026-10-05
**Found by:** Phase 4 E3 RED (`docs/plans/20261005-1105-phase4-final-cast-hardening-plan.md`) — the first R3-3 fixture soft-deleted the voter's `Code` and the final cast still succeeded
**Raised by:** `claude-code-session:9e4925bb-4151-4185-9e9c-5831184e54eb` (self-declared, not attestable)
**Existing-story search (freeze rule):** `PBDIGIT-219` (store crashes when the voter has **no** Code row) covers the missing-row case only; `PBDIGIT-231` (regenerated code keeps the code step verified) concerns regeneration, not deletion. No existing story covers a soft-deleted Code.

| | |
|---|---|
| **Status** | 🟡 **OPEN — not authorised; decision required.** Out of scope for Phase 4 E3 (PO, 2026-10-05, option B): `withoutGlobalScopes()` and the soft-delete semantics are **not** changed in E3. |
| **Finding (FACT, synthetic data)** | `App\Models\Code` uses `SoftDeletes`. `VoteController::store` loads the voter's code with `Code::withoutGlobalScopes()->where('user_id', …)->where('election_id', …)->first()` — removing **every** global scope, including `SoftDeletingScope`. A `Code` row with `deleted_at` set is therefore found and accepted: a temporary diagnostic probe (deleted, never committed) showed `store()` reached, one vote written, redirect to `vote/verify_to_show`. |
| **Why `withoutGlobalScopes()` is there (INFERENCE)** | To bypass the tenant scope (`BelongsToTenant`) — the slug flow must not depend on the ambient tenant (cf. PBDIGIT-65). The soft-delete bypass appears to be a side effect, not an intention. |
| **Open semantics (needs a decision)** | Is soft-deleting a `Code` meant to **revoke** it (then a deleted code must be refused, e.g. as `CodeMissing`)? Or is soft deletion an archival / audit mechanism that must not affect a voter's in-flight participation? Who soft-deletes codes, and when (admin action, regeneration, reset)? |
| **Affected boundary** | final cast — process facts read by `store()` (and possibly other `withoutGlobalScopes()` reads of `Code`, not surveyed) |
| **Constitutional impact** | possible participation through a credential the operator believed revoked; no ballot-secrecy impact |
| **Out of scope until decided** | no change to `withoutGlobalScopes()` call sites, no change to `Code` soft-delete behaviour, no new `CastAttempt` fact in Phase 4 |

**Traceability:** Phase 4 E3 RED (`tests/Feature/Voting/CastDecisionEnforcementTest.php` R3-3 uses `forceDelete()` for the PBDIGIT-219 case and notes this) · PBDIGIT-219 · ADR_20261005_0950 I5.
