# CHANGELOG — Protocol B r2 → r3 (targeted repair of REVIEW-r2 FAIL)

Only the defects listed in TASK.md were changed. Nothing was executed; all traces below were done by reading.
The version labels moved from r2 to r3 (PROTOCOL-B title, scope_b.sh header).

## Blocking

- **BLK-2: token boundaries on both sides for every vocabulary token.**
  - **Regexes.** In the K-NORMREG, K-STATUS and K-DEPLOY regexes, the leading `(^|/)` was replaced by
    `(^|[/._-])`. The trailing boundary `([._/-]|$)` is unchanged. A token may now start a path segment, or
    follow `.`, `_` or `-` inside a name. No repository-specific path or file name was added; the rule is
    general. *(scope_b.sh classifier awk and its BLK-2 comment; PROTOCOL-B §1 boundary sentence and table rows)*
  - **E-exclusion globs widened to match.** For each doc token t and doc ext x, four infix forms were added:
    `**/*[._-]$t.$x`, `**/*[._-]$t[._-]*.$x`, `**/*[._-]$t/**/*.$x` and `**/*[._-]$t[._-]*/**/*.$x`. For each
    K-DEPLOY token, the four corresponding forms were added: `**/*[._-]$t`, `**/*[._-]$t/**`, `**/*[._-]$t[._-]*` and
    `**/*[._-]$t[._-]*/**`. *(scope_b.sh CHG loops; PROTOCOL-B §1 "E-exclusion globs")*
  - **Equivalence stated.** §1 now says that the glob set and the regex are equivalent by construction. Each
    glob form is one case (leading boundary × trailing boundary) of the regex, and the path-end case is
    explained. §1 also names the wildmatch semantics the construction relies on. No residual mismatch is
    known at the pattern level, so no new §6 item was added for it. §1 says the equivalence is argued, not executed.
  - **Re-trace of the four REVIEW-r2 paths** (lower-cased):
    | Path | Register regex | Status regex | Channel |
    |---|---|---|---|
    | `engineering/architecture/adr/adr-aip-log-platform-rulings.md` | `-rulings.`, ext md → **yes** | (else skipped) | **K-NORMREG** (was: none). No K-DEPLOY token. |
    | `docs/implementation/program_status.md` | no register token at any boundary | `/program_status.` (also `_status.`) → yes | **K-STATUS** (unchanged) |
    | `docs/approvals/x.md` | no | `/approvals/` → yes | **K-STATUS** (unchanged) |
    | `…/register.md` | `/register.` → yes | (else skipped) | **K-NORMREG** (unchanged) |
    - Effect: the rulings register is now a K-NORMREG path, so it enters `npaths`. Its authors are in N_c for
      K-STATUS and K-DEPLOY. The false `NOT_EVIDENCED_AT_TIPS` for K-NORMREG and the false
      `IND[no_norm_source_distinct_from_candidate…]` described in REVIEW-r2 no longer follow from the file name.
    - The ADR path is under `engineering/`, which is not an E root, so the new E globs do not change E for it.
  - **Side effect (not a new defect id):** the E pathspec list roughly doubles, from 800 to 1600 doc-token
    globs and from 28 to 56 K-DEPLOY globs. This affects runtime only, in the NB-8 family.

## Non-blocking (fixed)

- **NB-11:** `GIT_LITERAL_PATHSPECS`, `GIT_ICASE_PATHSPECS`, `GIT_GLOB_PATHSPECS`, `GIT_NOGLOB_PATHSPECS` and
  `GIT_TRACE`, `GIT_TRACE2`, `GIT_TRACE2_EVENT`, `GIT_TRACE2_PERF`, `GIT_TRACE_PACKET`, `GIT_TRACE_PERFORMANCE`,
  `GIT_TRACE_SETUP`, `GIT_TRACE_CURL`, `GIT_TRACE_PACK_ACCESS` are now unset by name. A `compgen -v GIT_TRACE`
  loop also unsets every other `GIT_TRACE*` / `GIT_TRACE2*` variable. Both happen before the first git call.
  *(scope_b.sh l.13–15; PROTOCOL-B §5 Guarantees)*

## Non-blocking (declared as a disclosed limitation)

- **NB-12:** a one-line §6 item. The vocabularies also catch data files that are not registers or status
  records. The r3 infix boundary (BLK-2) widens this imprecision; the item says so and gives examples.
  *(PROTOCOL-B §6)*

## Unchanged (by requirement)

- These are unchanged:
  - the tip set;
  - the E roots and the other exclusions;
  - K-CI, K-HOOK and K-CODEOWNERS;
  - K-SIG and K-LOCALHOOK;
  - the Q1–Q8 rules, the §3 mapping and the headline;
  - the output files and their columns;
  - every other §6 item.
- The script is still read-only and emits counts only. The self path is still resolved and hashed before
  the one `cd`. OUT refusal, the fail-closed identity guard and neutrality are kept.
- **Line count:** r2 had 174 lines; r3 `scope_b.sh` has **181** lines (≤ ~185).
