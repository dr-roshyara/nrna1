# Staging evidence · computational + empirical status (extractor 2, 2026-08-31)
Read-only extraction from the verification tree. **Recorded as evidence, adopted as nothing.**
Every row is the artifacts' own wording. Two dimensions kept apart per GN-74.

## Programme-level totals AS STATED (note: mutually unreconciled — see §D)
- `COMPUTABILITY-MATRIX.md`: "EXECUTED — **14 of 16 computable, 2 blocked**"
- `MATHEMATICAL-COMPLETENESS-AUDIT.md`: of 30 symbols — "**18 PASS · 3 PASS-with-defect · 9 FAIL**"
- `handoff/05`: "Formal defined **25/25** · Executable test exists **22/25** · Real-environment (L5)
  **9/25** · Zero remaining blocker **4/25**"
- `step-282/09`: "FC = TRUE · CC = MOSTLY TRUE (3 open) · **EC = FALSE** · **GC = NOT CLAIMED**"

## A · Per-construct (condensed; full table in the extraction record)
| Construct | Computational | Empirical (real-environment) |
|---|---|---|
| K=(𝒜,ℛ) | CLOSED; membership O(1) executed | **L5 observed** — 37 real docs → (𝒜,ℛ); lint exit 0. But `KnowledgeState` = **0 production files** |
| 𝒜 / ℛ | closed; acyclicity O(n+m) executed | L5 — 51 typed edges; **supersession cycles unchecked** (lint scopes to requires/depends_on, WARNING only) |
| Σ | **PARTIAL** — `dir` computes, `str` has no rule ("🔴 FAIL on `str`") | **NOT OBSERVABLE** — no epistemic status field; `epistemic` = 0 production files |
| Q_t | "CLOSED"; F17/F18 **10/10 PASS**; E4-R **7/7 PASS** | **NOT OBSERVABLE** — "EKP has no inquiry register"; "`Ask(p)` was never observed in the running system" |
| Evidence | partial — "no identity"; "no validator for the 9 fields" | **NOT OBSERVABLE** — "no evidence field in the schema" |
| Provenance Π | field read O(1) executed | **SPLIT**: theory-Π NOT OBSERVABLE ("authority is trust rank, not origin"); implementation-provenance **29 files**, `MessageProvenance` **18 PHP files** |
| Lineage | reverse reachability O(n+m) | **L5** — `php artisan test --filter=Lineage` → **47 passed / 125 assertions / exit 0**; 36 production files |
| Determination | **absent from the terminal theory** — no `Determine` op, no object | corpus-side only: **836 occurrences / 172 files**; no EKP observation |
| Authority / Authorization | **FAIL** — "3 rival relations, no body"; `Authorize` "signature only, wrong codomain" | L5 partial — "`authorities.yaml` is **an enum, not an evaluator**"; **no `Authorize()` runtime** |
| Policy | PASS **for a fully-specified policy**; `assessment` "BLOCKED … function defined; parameter is not" | L5 partial — knowledge-lint IS a policy evaluator (18 rules, exit 0) |
| 𝒪·qualify | **🔴 FAIL — no body** (1 undefined corpus hit) | not observable — "no qualification step exists" |
| 𝒪·commit/δ | **🔴 FAIL** — no body for the commit case; **executed: `K1 is K0 : True`** | not observable |
| 𝒪·merge | union PASS-but-INADEQUATE (no dedup, loses cross-state ℛ); `dedup` **does not exist** | not observed |
| 𝒪·replay | fold over History, executed | **NOT OBSERVABLE** — "git holds history; the platform does not model replay" (L1) |
| 𝒪 (universe) | "**PARTIALLY DERIVABLE** — 14-element lower bound FORCED; membership **NOT** derivable"; lower 14 / upper 18; undetermined band {Transform, Merge, Split, Reintroduce} | n/a |
| Identity | structural equality executed; **"PASS WITH CONTRADICTION — `id` hashes the mutable `e.state`"** | L5 — `knowledge_id` unique; graph byte-identical ×2. Round-trip 3/3; **RICH round-trip False, fields destroyed ['q','s','e','c','u']** |
| Measurement | "**model CLOSED / executor ABSENT**"; E20 **BLOCKED** (no (Ω,ℱ,P); `str` is ORDINAL) | NOT OBSERVABLE |
| History | closed; replay defined+executed; "History is EXTERNAL to K → states with different histories are EQUAL" | **L1 only** — not a platform concept |

## B · The unobservability figures — TWO different denominators, never to be conflated
- **15 of 24 TESTS** (`step-280/09`): "15 of 24 tests have NO real-environment observation" —
  L5 yes 5 (E1,E2,E10,E22,E23) · partial 4 (E4,E8,E12,E14) · **NO 15** (E3,E5,E6,E7,E9,E11,E13,
  E15,E16,E17,E18,E19,E20,E21,E24). Registered **G-65 / I-1**. "Only 8 of 24 tests carry Level-5
  evidence… **A test that passes against my own implementation of the specification cannot
  validate the specification against the world.**"
- **16 of 25 CONSTRUCTS** (`handoff/05`): "16 constructs have no real-environment witness".
- Their own guard: "**A red empirical cell is not a red theoretical cell.** Fifteen constructs are
  unobservable **because the EKP does not implement them**, not because the theory cannot define them."
- Evidence-level distribution (`step-280/06`): L5=8 · L4=13 · L3=1 · L2=1 · L0=1 —
  "**No Level-6 evidence exists — nothing here has been independently reproduced.**"

## C · Withdrawn / corrected evidence (recorded because the book must not cite it)
- **Tautology:** `evidence_volume` is a dead parameter — "the body never reads it… would emit the
  identical PASS for `evidence_volume = 0`, and would also 'pass' if the law it tests were false."
  → **WITHDRAWN as evidence** (TG-20). "The derivation and the `authorities.yaml` declaration
  stand; the execution does not."
- **Collinearity:** `status ⊥ authority` declared independent, **measured collinear** —
  "`draft ⇔ provisional` is a perfect biconditional: 13 of 13 in each direction"; 6 of 40 cells
  occupied. "Orthogonality is a *design* property of the EKP; it is *not* an observed property of
  its current content." Experiment 3 self-corrected ("That was FALSE — my own data contradicted my
  own conclusion"), retained rather than deleted. "Two of four experiments produced conclusions I
  had to withdraw."
- **132/132 grants carry `humanActRef`** — "a **discipline, not a binding**"; the field is
  free-text (83 prose, 49 paths). Supersedes the earlier "20/20".
- **93 of 132 knowledge files carry no frontmatter** — "a **real, running instance of 'not
  assessed'**, the state the theory says it cannot express."

## D · ⚠ UNRECONCILED DISAGREEMENTS INSIDE THE VERIFICATION LANE (decisive for book wording)
1. **Symbol counts:** `COMPUTABILITY-MATRIX` "14 of 16 computable, 2 blocked" (19:02) vs
   `THEORY-CLOSURE-AUDIT` "30/30 symbols resolve" (20:10) — "**The matrix's two blocked symbols
   were never addressed… '30/30' is not sustainable on the programme's own record**" — yet
   `step-282/12` (2026-08-31) again states "30/30 symbols resolved".
2. **Closure itself:** `THEORY-STATUS-VERDICT.md` (2026-08-30 20:52) — "**THE THEORY IS NOT
   CLOSED… 21 gaps, 3 blocking, 1 an internal contradiction**" **vs** `step-282/12` (2026-08-31
   00:37) — "**VERDICT B — THEORY THEORETICALLY CLOSED AT DECLARED SCOPE**", "THEORY-CRITICAL GAPS
   REMAINING: ZERO", on the ground that "§22 forbids keeping the theory open merely because
   implementation, empirical validation or governance is incomplete."
   `handoff/06` (00:46) keeps Verdict B **while withdrawing one of its own supports**: "That
   witness is a tautology and is WITHDRAWN as evidence… **The handoff is ready. It is a weaker
   starting position than the synthesis assumed.**"
   **Both sides agree on: EC = FALSE · 15/24 unobservable · GC = NOT CLAIMED.**
