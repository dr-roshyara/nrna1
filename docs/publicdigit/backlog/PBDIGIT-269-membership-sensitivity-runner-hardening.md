# PBDIGIT-269: Membership sensitivity-runner hardening (test tooling)

**Type:** Test-tooling story · **Epic:** `PBDIGIT-EPIC-02` · **Created:** 2026-10-10 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable), on the Human PO / ARB decisions D-A2 and G1 (2026-10-10). **ID note:** the next free ID (`git grep "PBDIGIT-269"` and the untracked files: no occurrence). **Index row:** added 2026-10-10.

| | |
|---|---|
| **Status** | `OPEN` · **not authorised**. Creating this story authorises no implementation. It is **not** a prerequisite of C-1 acceptance (D-A1) |
| **Source** | C-1 independent review `docs/publicdigit/reviews/2026-10-10-membership-c1-correction-independent-review.md`, finding C1 and condition G-1; disposition: acceptance review §12 |
| **Subject** | `docs/publicdigit/membership/architecture/models/c1_sensitivity/run.php` (introduced in `9ae69b7bc`, PBDIGIT-268) |
| **Scope** | test tooling only. **No production change; no new mutants** |

## Problem

The runner reports `KILLED` for any non-zero test exit, so a crash, an error or a fatal error can look like a genuine kill. It also deviates from the approved CP-2 design:
- it copies the working tree, not `git archive HEAD`;
- it does not abort when the canary survives.

The ten current kills were confirmed by hand (independent review §1 E). The runner itself does not guarantee the distinction.

## Items (each with a RED-first acceptance test of the runner)

| # | Change | Acceptance |
|---|---|---|
| a | classify `KILLED` only on an assertion failure (`Failures:` ≥ 1, no `Errors:`); report errors and fatal errors as `INVALID` | a mutant that crashes the test is reported `INVALID`, not `KILLED` |
| b | exactly one test must run, in the control and in the mutant run | a filter matching 0 or 2 tests fails the run |
| c | anchor the test filter at both ends | a test whose name is a suffix of another cannot be selected by mistake |
| d | abort the whole run if the canary M0 survives | a surviving canary stops the run with a non-zero exit before any other mutant |
| e | an explicit source: `git archive HEAD` by default (CP-2); the working tree only through an opt-in flag that is shown in the output | the output names the source; the default run ignores uncommitted test edits |
| f | apply patches with `patch --fuzz=0` | a patch that only applies with fuzz is reported `PATCH DID NOT APPLY` |
| g | detect changes to already-dirty files through content hashes before and after | modifying an already-modified file during a run is reported |

**Out of scope:** new mutants; changes to tests or production code; Infection configuration.
