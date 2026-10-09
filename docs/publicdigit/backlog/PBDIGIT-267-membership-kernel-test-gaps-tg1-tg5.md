# PBDIGIT-267: Membership kernel test gaps TG-1 … TG-5 (test-only)

**Type:** Test-only story · **Epic:** `PBDIGIT-EPIC-02` · **Created:** 2026-10-09 · **Raised by:** `claude-code-session:42e0287e…` (self-declared, not attestable). Created on Human ARB decision **ARB-TEST-01** ("TG-1 through TG-5 must be handled as a separate, explicitly authorised test-only work item"). **Index row:** pending (`backlog/README.md` carries another session's uncommitted edits). **ID note:** the next free number at creation. Another session has drafted items whose IDs await PO approval; if they collide, renumber this one.

| | |
|---|---|
| **Status** | `OPEN` · **not authorised** (requires explicit authorisation; not executed) |
| **Source** | mutation measurement of the Slice 0 kernel: audit `2026-10-09-membership-theory-synthesis-and-implementation-readiness-audit.md` §B.3 + errata E-4 |
| **Scope** | tests only, in `tests/Unit/Contexts/Membership/Domain/Organisational/`. **No production change** |

| Gap | Surviving mutant(s) | Test to add | Kills |
|---|---|---|---|
| TG-1 | `Member.php:26` (validation loop removed); `:28`, `:31` throws uncovered | `Member::fromLog` refuses a log whose seq is not 1..n, and one whose `recordedAt` decreases | the escaped `Foreach_` + uncovered `Throw_` |
| TG-2 | `AuthorityEvidence.php:25-27` | each required evidence field, empty or whitespace, is refused | `Foreach_`, `ArrayItemRemoval`, `UnwrapTrim`, `Throw_` |
| TG-3 | `UnwrapTrim` on `FactId`, `MemberId`, `PersonRef`, `EndKind` | whitespace-only identifiers are refused | 4 escaped + uncovered `Throw_` |
| TG-4 | `Decision.php:37,42` | `fact()` / `basedOnVersion()` on a refused decision throw `LogicException` | `Throw_` ×2 (+ message mutants, which are harmless) |
| TG-5 | `IntervalSet.php:76` | `IntervalSet::equals` is false for sets of different size (e.g. {[1,3),[5,9)} vs {[1,9)}) | `FalseValue` |

**Acceptance:**
- each listed mutant is killed;
- Infection re-run (scratch configuration as in audit §B.3) on the same scope;
- the MSI is reported with its scope;
- no production file changed.
