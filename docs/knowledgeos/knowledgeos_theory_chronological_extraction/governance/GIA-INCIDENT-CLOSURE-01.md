# GIA-INCIDENT-CLOSURE-01 — F0031–F0040 governance incident: closure record and criteria

| | |
|---|---|
| **Kind** | governance incident closure **record**. ⛔ Not a history rewrite, not a rename, not an authorization |
| **Prepared by** | governance / control-plane session |
| **Date · HEAD** | 2026-09-23 · `93a03b899` |
| **Consumes** | `COMMIT-BOUNDARY-FINDING-01.md` (research session, `93a03b899`): Finding 1 (B-16) and Finding 2 (B-17) · `GIA-POST-B12-TRANSITION-ASSESSMENT-01.md` |
| **Architecture** | ⛔ **not reopened.** `ARCH` v1.1 stays frozen. This is incident closure, not architecture work |

---

## 1. Incident scope

**One incident** (the F0031–F0040 identity divergence) has three open strands:

| Strand | Record | Status |
|---|---|---|
| **S1** | the correction unit is **defined but not authorized** (RC-H-04) | open, pending L0 |
| **S2** | the **RC-H-04 identifier is ambiguous** repository-wide (B-17) | open, disambiguation proposed below |
| **S3** | **git provenance distortion:** research commits contain governance-authored files (B-16) | recorded. Closure by **attribution record**, not rewrite (§3) |

## 2. S2: RC-H-04 identifier ambiguity (B-17)

**Verified facts:**
- On **this branch** (`knowelegeos-modelling`, HEAD `93a03b899`), `git grep RC-H-04 HEAD` finds only the research-control meaning: *"schedule the F0031–F0040 correction unit"* (`architecture/research-control-architecture.md` §13).
- On **branch `kos-v11-ddd-refinement`** (worktree `.claude/worktrees/kos-v11-ddd`), commit `5df187ff2` (2026-08-22) defines `RC-H-01…RC-H-04` as SNF hypotheses. There, RC-H-04 = *"SNF-E improves coverage while preserving C's false-acceptance characteristics"*.
- **Scope:** the collision is **repository-wide across branches**, not within one branch. The research record's phrase "two live meanings in one repository" is accurate. On the current branch alone, RC-H-04 is not ambiguous.

**Self-correction (this session):**
- `GOVERNANCE-INTEGRITY-AUDIT-01.md` §11 records `H-Cn` as "no collision". **That result was scoped to this branch's trees**: worktrees and other branches were not searched, and the scope was not stated.
- This session minted the `H-C` series in RCA §13 after checking `RCI-` for collisions but **not** `H-C`.
- The audit's own text is left as issued. This record is the correction (RCI-015).

**Proposed disambiguation, requiring no rename and no new rule.** This follows the corpus's own practice of source-qualifying short labels (B-12 §4 #5):

> Until NR-1 (GIA-6) is decided, every **L0 authorization** of a research-control decision names it **fully qualified**:
> **`RCA:RC-H-04`** = *"RC-H-04 as defined in `docs/knowledgeos/knowledgeos_theory_chronological_extraction/architecture/research-control-architecture.md` §13"*, with that document's content taken as of **commit `6cffbea5e`**, the first commit of the RCA file (its text at `93a03b899` is unchanged for §13).

With the qualified form, *"authorize RCA:RC-H-04"* names exactly one thing. Both identifiers stay as written. Whether to later rename the governance series (e.g. under NR-1's governance prefix) remains GIA-6.

## 3. S3: git provenance distortion (B-16)

**Verified facts:**
- `6cffbea5e` (research commit "ID-ROOTCAUSE-01 Addendum A") contains **5** governance-session files: `architecture/research-control-architecture.md`, `control-invariants.yaml`, `control-coverage-matrix.yaml`, `INCREMENT-0-ROOT-CAUSE-CLOSURE.md`, `research_agent_contract.md`.
- `15a05f297` (research commit "stop RCI work") contains **1**: the draft of `GOVERNANCE-INTEGRITY-AUDIT-01.md`.
- The cause is recorded by the research session: `git add -A` over the whole extraction tree.
- **This session did not use `-A`.** Its commits `edabcfa6e`, `da9a5beec`, `95916b397` and `2c3e7e9d7` staged explicit paths.
- The complete integrity audit text was committed by this session in `2c3e7e9d7`.

**Decision: no history rewrite.** Rewriting would destroy the evidence of what happened, the same principle as for F0031–F0040 (RCI-015). The branch has also been shared through commits authored under the same git identity.

**Authoritative attribution** (this record is the correction; git remains the distorted witness for these six files):

| File | **Authored by** | First committed in | Committed by | Authorship evidence inside the file |
|---|---|---|---|---|
| `architecture/research-control-architecture.md` | governance session | `6cffbea5e` | research session | header "Status: PROPOSED — authority: generated"; RCA §0 evidence table; session log 2026-09-23 addendum "evidence-bound research control architecture" |
| `architecture/control-invariants.yaml` | governance session | `6cffbea5e` | research session | file header; session log (same addendum) |
| `architecture/control-coverage-matrix.yaml` | governance session | `6cffbea5e` | research session | file header "AS OF: HEAD da9a5beec"; session log |
| `architecture/INCREMENT-0-ROOT-CAUSE-CLOSURE.md` | governance session | `6cffbea5e` | research session | header "Consumes, does not repeat … ID-ROOTCAUSE-01"; session log addendum "Increment 0" |
| `architecture/research_agent_contract.md` | governance session | `6cffbea5e` | research session | header "Level L5"; session log |
| `governance/GOVERNANCE-INTEGRITY-AUDIT-01.md` | governance session | `15a05f297` (draft) · `2c3e7e9d7` (complete) | research session (draft) · governance session (complete) | header "Auditor: governance/control-plane reviewer"; session log addendum "GOVERNANCE-INTEGRITY-AUDIT-01" |

⚠️ **The rule for reading git:** for these six paths, `git log --diff-filter=A` returns the research commit. **Authorship is taken from this table, not from git.**

**Prevention:** GIA-8 (the research session must not commit governance artifacts) already covers this. The research session has stopped using `-A` (its Finding 1). **No new rule is proposed.**

## 4. Closure criteria for the incident

The incident is **closed** only when all of the following hold. The table records who acts:

| # | Criterion | Who | Status |
|---|---|---|---|
| C-1 | B-16 attribution accepted: §3 is the authoritative authorship record for the six files | L0 | open |
| C-2 | B-17 disambiguation accepted: the qualified form `RCA:RC-H-04` is used in the authorization (or L0 chooses another form) | L0 | open |
| C-3 | self-correction of the namespace-audit scope (§2) acknowledged | L0 | open |
| C-4 | **`RCA:RC-H-04` authorized**, with the remedy chosen (Increment 0 §5: B overlay or C freeze-and-forward; A excluded by RCI-015) | L0 | open |
| C-5 | the correction unit is executed by research: provenance overlay or freeze-forward; re-admission and reading of canonical F0032, F0035, F0036, F0040 and canonical F0045; the research-object consequences of the transition assessment §8 | research (authorized) | not started |
| C-6 | independent review of the correction unit by a session other than research | governance / audit | not started |
| C-7 | L0 accepts the correction; the incident is recorded **CLOSED** | L0 | not started |

**Ordering constraints:**
- **C-5 depends on C-4.** Per the transition assessment §15, C-4 also depends on extended 1a and 1b being accepted, so that re-admission runs through a trustworthy evidence path.
- If L0 wants the correction to run **before** 1b, that is an explicit L0 decision with its risk recorded: reading through an unreviewed admission path.

**Until C-7:** no F0041+, and no new research state built on F0031–F0040 citations (Increment 0 §4 recommendation).

## 5. Governance status table (current)

| Area | Status |
|---|---|
| Research-control architecture (RCA, invariants, coverage, contract) | **PROPOSED**, not adopted (RC-H-05); not reopened |
| L0 decision mechanism (GIA-1…10, RC-H-01…7) | prepared; **no decision taken** |
| Control plane (runner, gates, activation) | **FAIL** (integrity audit); extended 1a awaiting GIA-2/3 |
| Evidence binding (research-produced) | non-authoritative; 1b review pending (GIA-9) |
| Identifier integrity | **open:** NR-1 (GIA-6) · `RCA:RC-H-04` qualification (C-2) |
| Git provenance incident (B-16) | **recorded; attribution in §3**; closure at C-1 |
| `RCA:RC-H-04` authorization | **pending L0** |
| **Incident overall** | **NOT CLOSED** |

---

*Traceability:*
- `git grep RC-H-04` on `HEAD` and on `kos-v11-ddd-refinement`; commit `5df187ff2`.
- `git show --stat` for `6cffbea5e`, `15a05f297`, `edabcfa6e`, `da9a5beec`, `95916b397` and `2c3e7e9d7`.
- `COMMIT-BOUNDARY-FINDING-01.md` §§1–2.
- Nothing rewritten, renamed, activated or authorized.

---

## ERRATUM A (2026-09-23, append-only; the text above stands as committed)

Commit `e8ffaf6f5` applied a mechanical substitution `\bH-C([1-7])\b → RC-H-0\1` inside this record, which was then uncommitted and was swept into that commit (B-16, third occurrence). The rename itself is an **L0 decision** (`L0-DECISION-RECORD-01.md`, L0-DEC-01). Its application *inside this record*, however, made several statements false, because this record describes the collision **as it existed**. Corrections:

| Where | As it now reads | Correct statement |
|---|---|---|
| §2, 2nd bullet | branch `kos-v11-ddd-refinement` "defines `RC-H-01…RC-H-04` as SNF hypotheses. There, RC-H-04 = …" | that branch defines **`H-C1…H-C4`**. There, **`H-C4`** = *"SNF-E improves coverage while preserving C's false-acceptance characteristics"*. Verified: `git grep` on that branch, `docs/knowledgeos/reviews`: H-C1 ×6, H-C2 ×1, H-C3 ×2, H-C4 ×5, and **0** occurrences of `RC-H-` |
| §1 S1/S2, §2 heading and bullets 1 and 3 | "the RC-H-04 identifier is ambiguous" | the ambiguous identifier was **`H-C4`**. **`RC-H-04` has never been ambiguous**; it was introduced by L0-DEC-01 to remove the ambiguity |
| §2 proposal | "`RCA:RC-H-04`" as the qualified form | the proposal was **`RCA:H-C4`**. It is **superseded** by L0-DEC-01: `RC-H-04` is unique on all 7 local branches (verified), so no qualification is needed |
| §4 C-2 | "the qualified form `RCA:RC-H-04` is used" | **C-2 is satisfied by L0-DEC-01** (identifier accepted) |
| §4 C-4, §5 | "`RCA:RC-H-04` authorized" | read as **`RC-H-04` authorized**, still **OPEN** |

**Attribution table, extended (B-16, third occurrence).** Two more governance-authored files were first committed by the research session:

| File | Authored by | First committed in | Committed by |
|---|---|---|---|
| `governance/GIA-INCIDENT-CLOSURE-01.md` (this file) | governance session | `e8ffaf6f5` | research session |
| `docs/plans/20260923-0212-kos-evidence-binding-increment-1-plan.md` | governance session | `e8ffaf6f5` | research session |

That brings the total to **8** governance artifacts first committed under research commit messages. There is **no history rewrite**. B-16 stays **OPEN** as an incident finding.

**Overstatement recorded, not acted on.** The message of `e8ffaf6f5` says the *"Research Architecture and phase protocols remain FROZEN"*. At HEAD the Research Architecture **declares** "FROZEN — v1.1", with no recorded approver. The Phase-2 protocol states **"DRAFT v3.2 … Awaiting final pre-execution audit"**. No status is changed here.

**Status after this erratum:** C-1 OPEN · **C-2 SATISFIED (L0-DEC-01)** · C-3 OPEN · C-4 OPEN · C-5/6/7 NOT STARTED · incident **NOT CLOSED**.
