# Human ARB Decision Record: Membership, PBDIGIT-266, Decision Identifiers (2026-10-09)

**Recorded by:** `claude-code-session:42e0287e…` (self-declared, not attestable). **The decisions are the Human ARB's.** This file records them; it does not make them.
**Source of the decisions:** the Human ARB commission "Human ARB Decision Record and Conditional PBDIGIT-266 Execution", given to this session on 2026-10-09, §1 "Accepted Human ARB decisions". The wording below is quoted verbatim.
**Recorded on:** 2026-10-09, **after** the documents it affects. Earlier records are **not** rewritten; they are superseded only where stated below.

## Decisions (verbatim)

| ID | Decision (verbatim) | Status |
|---|---|---|
| **ARB-MEM-01** | *Correction before cutover.* "Verified correction/annulment capability is required before Membership live writes and cutover. Reflect this as a mandatory architecture gate. Do not implement correction/annulment under this commission." | **ACCEPTED** (Human ARB) |
| **ARB-SEC-01** | *PBDIGIT-266.* "Security tests are authorised only after isolated test-database readiness is confirmed. The scope is investigation and tests only. No application fixes, route changes, middleware changes, schema changes, or configuration changes are authorised." | **ACCEPTED** (Human ARB), **conditional** on the isolation gate |
| **ARB-DEC-01** | *Decision identifiers.* "Preserve Slice 1a `D-1`, which identifies the adopted FactId uniqueness invariant (`INV-FID`). Propose `S2-D1` through `S2-D6` for the Slice 2 decisions. This naming proposal is not approved until the authoritative decision record says so. Do not silently rename ratified decisions." | **ACCEPTED** (Human ARB). **`S2-D1 … S2-D6` remains a PROPOSAL, not approved** |
| **ARB-MEM-02** | *Slice 1b.* "Slice 1b remains conditionally accepted pending evidence and a formal acceptance act. Do not describe it as formally accepted." | **ACCEPTED** (Human ARB) |
| **ARB-TEST-01** | *Kernel test gaps.* "TG-1 through TG-5 must be handled as a separate, explicitly authorised test-only work item. Do not execute those tests under this commission." | **ACCEPTED** (Human ARB) |

## Consequences recorded

1. **ARB-MEM-01, a mandatory gate.** In the Membership roadmap (`2026-10-09-membership-decision-readiness-pbdigit266-and-correction-order.md` §6), the **correction slice (#14)** is a **hard prerequisite** of:
   - cutover machinery activation (#17);
   - migration dry runs and rollout (#18);
   - live HTTP / UI writes (#19).
   
   It is no longer a recommendation. The compensating-controls alternative (that document, §3 C6) is **closed** by this decision. Correction / annulment remains unimplemented and unauthorised.
2. **ARB-SEC-01.** `PBDIGIT-266` is authorised for **tests only**, **conditionally on confirmed isolated test-database readiness**. Gate check on 2026-10-09: **FAIL** (see the session log and §"Gate evidence" below). **No test was run.**
3. **ARB-DEC-01.**
   - "D-1" in the Slice 1a contract keeps its meaning: **INV-FID**.
   - The Slice 2 decisions stay as currently numbered in their documents. The prefix `S2-` is a **proposal pending an approval record**. No reference has been renamed.
   - **Until approval, cite the Slice 2 decisions qualified, e.g. "Slice 2 pack D-1 (admission basis)".**
4. **ARB-MEM-02.** Slice 1b (`PBDIGIT-260`) is **conditionally accepted**. A formal acceptance act is still required. No document may describe it as formally accepted.
5. **ARB-TEST-01.** TG-1 … TG-5 are a separate test-only work item (`PBDIGIT-267`, created **unauthorised**). Not executed.

## Gate evidence (ARB-SEC-01, non-destructive inspection, 2026-10-09)

| Condition | Evidence | Result |
|---|---|---|
| 1. Dedicated isolated test DB | `phpunit.xml:70` `DB_DATABASE=nrna_test`, the **shared** test DB (every PHPUnit process runs `migrate:fresh` on it). The only dedicated DB seen, `nrna_test_adjudication`, was created by the PO for **another session's** adjudication runs (session log, "Mission 2") and is not assigned to PBDIGIT-266 | **FAIL** |
| 2. Effective target unambiguous | the `phpunit.xml` `<server>` values + `.env.testing` (file not read) resolve to `nrna_test` (`php artisan db:show --env=testing`, read-only); `bootstrap/cache/config.php` absent | PASS (but the target is shared) |
| 3. Not in concurrent use | `db:show` reports **9 open connections** on `nrna_test`; at least one other session is active in this working tree | **UNKNOWN** |
| 4. No fallback to dev / shared DB | the configured target *is* the shared DB | **FAIL** |
| 5. Side effects controlled | `MAIL_MAILER=array`, `QUEUE_CONNECTION=sync`, `SESSION_DRIVER=array` (`phpunit.xml:82-84`); the plan adds `Notification::fake()` | PASS |
| 6. Executable within test-only scope | the tests must still be written (new test files, which is permitted). The existing plan needs the corrections of the decision-readiness doc §4 | PASS (conditional) |

**Gate: FAIL.**

**Prerequisite to pass it:** a **dedicated test database assigned to PBDIGIT-266** (for example `nrna_test_266`), created by the PO (`publicdigit_user` lacks CREATEDB), plus a run configuration that pins it. The proven pattern is a scratch PHPUnit config / bootstrap that asserts `current_database()` before any test. Alternatively, an **explicit PO assignment** of an existing dedicated DB with confirmation that no other session uses it during the run.

Provisioning needs **separate authorisation**. This record provisions nothing.

## Re-verification (second commission `…/brainstorming/20261009_2315_implementaiton.md`, 2026-10-09)

The commission re-states the same five decisions; they are **already recorded above** and are not duplicated. It adds one clarification to decision 3: **use qualified document-local identifiers for cross-document citations.** This is recorded as part of ARB-DEC-01.

**Gate re-check (non-destructive):**
- `phpunit.xml:70` → `nrna_test`; `bootstrap/cache/config.php` absent. Read-only catalogue query via `php artisan tinker --env=testing` (`SELECT datname … FROM pg_database`, `current_database()`):
  - databases: `nrna_test` (1 connection, the probe itself), `nrna_test_adjudication` (0; assigned to the other session's adjudication runs), `publicdigit`, `publicdigit_db`, `postgres`;
  - `current_database()` = `nrna_test`.
- **No database is dedicated to, or assigned for, PBDIGIT-266.** An absent config cache is not sufficient.
- **Gate: FAIL. No tests run.**
