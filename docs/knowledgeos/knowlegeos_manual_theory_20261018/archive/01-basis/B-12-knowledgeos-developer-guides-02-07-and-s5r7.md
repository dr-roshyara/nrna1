# B-12 — The other KnowledgeOS developer guides: structural assurance, session governance, research instruments

| Field | Value |
|---|---|
| Read (full) | `developer_guide/knowledgeos/` 00_index, 02…07 (6 guides + index) · `s5_r7/` 00…05 (6 guides) |
| Checked first | the number of lines in `s5_r7/` that mention "sealed" or "hold-out", counts only (0 sealed; 3 hold-out mentions). The mentions are about *mechanisms* (count-only quarantine check; synthetic hold-out sets in fixtures). **No hold-out or sealed content was exposed or extracted** |
| Not verified here | I did **not** run any of the test suites the guides cite. Their pass counts are [SRC], not [CHK] |
| Standing | engineering and research-tooling background, first-hand. **Not** corpus content |
| Labels | [SRC] stated in the source · [I] interpretation · [?] open |

## 1. What these guides add to "what has been built"

| # | Asset (date) | What it does [SRC] | Stated status [SRC] |
|---|---|---|---|
| G02 | **Track-2 structural profile** S1–S5 (2026-08-21), in `scripts/lib/EngineeringKnowledge/` capabilities CAP-001/003/004 | Read-only checks on governed Markdown: unique and monotonic section numbers · references resolve · vocabulary integrity · table shape · competing dispositions | warn-only; wired into no gate; exits 0 |
| G03 | **Author-side handoff report** (`knowledge-lint --report=handoff`, 2026-08-21) | Nine-element evidence report with provenance; fail-closed aggregate; the recommendation FIX / REVIEW / RESOLVE / ATTACH | warn-only; *"never emits APPROVED, REJECTED, or ACCEPTED"* |
| G04 | **Session bootstrap AST-017** (2026-08-22) | Resolves a governed AI session's lane, role, workflow state and authority from the authoritative workflow interpreter AST-015 | `planned → verify`; adoption not claimed |
| G05 | **Activate commissioned fresh session AST-019** (2026-08-24) | Binds a new session's identity to a commissioned responsibility via REGISTER → HANDOFF → human START | *"IMPLEMENTED · NOT VERIFIED · NOT ADOPTED · NOT AUTHORIZED"* |
| G06 | **Research-gate instrument** (2026-09-23…30), for the chronological-extraction programme only | Gate runner with INCONCLUSIVE and GOVERNANCE_INOPERATIVE outcomes, human-pasted pins, self-test on every run | *"temporary … not platform governance"* |
| G07 | **`admit.py --audit`** (2026-09-30) | Evidence-binding audit of read receipts: receipt / VOID / unrecognised rows | completes with BINDING_FAILURES_PRESENT (known 9-ID divergence); *"completes ≠ passes"* |
| S5 | **S5 verifier, contract revision 7** (2026-09-26…30), for the S-Series P3b study | Five bounded contexts (Universe · Witness · Evidence · Reconstruction · Statistics) composed by a rule-free composer into BATCH-PASS / -FAIL / -UNDETERMINED; execution facts come from the harness transcript; exact hypergeometric bounds | *"implemented, engineering-verified, NOT activated, NOT independently audited"* |

## 2. Principles these guides put into code

| # | Principle [SRC] | Where |
|---|---|---|
| P-1 | **Absence of evidence is not PASS.** A check that examined nothing is INCONCLUSIVE | G02 D-2 · G03 · G06 IC-1 |
| P-2 | **Three-valued composition (Strong Kleene):** F dominates U; U is never PASS; there is never a bare PASS | S5 01 · G03 D-3 aggregate |
| P-3 | **Mechanical assurance proves declared structure only.** It *"does NOT check soundness, completeness, authority, or provenance"* (D-4 statement, one constant) | G02 · G03 |
| P-4 | **Evidence ≠ decision.** *"Automation produces assurance evidence. Automation does NOT produce: architectural decisions · governance decisions · acceptance · authority · ownership · reviewer independence · migration authorization"* | G03 |
| P-5 | **Resolution is not activation.** A report creates no authority, ownership or state change | G04 |
| P-6 | **Separation never collapsed:** identity ≠ role ≠ eligibility ≠ authorization ≠ ownership ≠ continuation; identity is evidence-only | G04 |
| P-7 | **One authoritative interpreter.** Delegate, never re-fold; *"a `?? null` on an ownership field is a defect, not a default"* | G04 · G05 |
| P-8 | **A precondition is not the act.** `program_accepted` is *"a mechanical precondition for governance acceptance, never the act itself"* | S5 01 |
| P-9 | **Completes ≠ passes;** a known divergence is reported, never treated as PASS | G07 |
| P-10 | **Pins detect drift, not authorship.** *"A pin is not a signature"*; activation is a human act | G06 |
| P-11 | **Input ≠ evidence.** An input read never anchors a claim; only witnessed, byte-anchored reads count | S5 02, 03 |
| P-12 | **Discovery ≠ validation;** precedence only from explicitly dated, confirmed positions (a strict partial order) | S5 03 |

## 3. Contribution to the basis

- **[I] "What has been built" has three layers, not one:**
  1. **Product-side KnowledgeOS software:** the observation runtime (B-11) and the EngineeringKnowledge capability library (CAP-001/003/004, S1–S5, handoff report).
  2. **Governance machinery for AI sessions:** AST-015…019.
  3. **Research instruments for reconstructing the theory:** the gate runner, `admit.py`, and the S5 r7 verifier.
- **[I] Status pattern.** Almost everything is *implemented* and *warn-only* or *not activated / not adopted / not independently verified*. Operational use is thin.
- **[I] Same core everywhere.** P-1, P-2, P-4, P-8 and P-9 are the governance core of B-06 and KT-7/KT-8 (absence ≠ permission; a report is not the act; UNKNOWN is not a pass), now executed in code.
- **[I] Lead for Zero/Gap, strengthened.** The engineering record consistently uses a **three-valued logic (PASS / FAIL / UNDETERMINED)** with U never collapsing into PASS. If the corpus's two "Zeros" separate *proven empty* from *not determined*, this is their engineering antecedent. **Still to be tested against the corpus.**
- **[?]** The guides describe research tooling built for S-Series/F-Series lanes. Whether that tooling's outputs are valid evidence about the original theory is a separate question, and this lane does not assume it.
