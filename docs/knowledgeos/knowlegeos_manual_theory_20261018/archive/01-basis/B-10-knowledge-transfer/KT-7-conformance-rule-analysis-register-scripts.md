# KT-7 — Conformance, the Rule model, the Decision Register, scripts and Python (2026-08-16, 09:05–12:03)

| Field | Value |
|---|---|
| Read (full) | `20260816_0905_architecture_conformance.md` (293 l.) · `20260816_0914_rule_model_and_conflict_analysis.md` (374 l.) · `20260816_1040_decision_register.md` (211 l.) · `20260816_1144_script_capability_evidence_inventory_v1.md` (416 l.) · `20260816_1203_python_migration_gap_analysis.md` (232 l.) |
| Standing of the sources | all **Generated · PROPOSED**. Each says it is *"never authoritative without human review"* |
| Standing here | engineering / PKS background. **Not** KnowledgeOS corpus |
| Labels | [SRC] stated in the source · [I] my interpretation · [?] open |

## 1. Architecture conformance (0905)

- **[SRC] Governing rule:** *"A gate may only enforce an architecture that a recorded human act has decided. Gates follow ratification; they never precede it."*
- **[SRC] P-8 "Environment over instruction":** a constraint that is built into the environment is preferred to one that is only written as an instruction.
- **[SRC]** A staged conformance model (stages 0–4): rules move from undecided, to decided, to a mechanical check, to a blocking gate.
- **[SRC]** 12 invariants were adjudicated against this staging.
- **[I]** This is the platform rule *"governance precedes automation"* (B-06 principle 12), applied to architecture checks.

## 2. What a Rule is, and what a conflict is (0914)

- **[SRC] A Rule** = Subject + Applicability Predicate + Normative Effect + Scope + Temporal Validity + Authority + Exceptions.
- **[SRC] Normative Effect:** MUST · MUST_NOT · SHOULD · SHOULD_NOT · MAY.
- **[SRC] Applicability is "a predicate, not a label."**
- **[SRC] Conflict is defined by satisfiability:** *"Is there any possible state of the world in which both rules apply and their normative effects cannot simultaneously be satisfied?"* A rule applies only where applicability, scope and temporal validity all hold.
- **[SRC] Relationship taxonomy:** CONSISTENT · SPECIALIZES · REFINES · DUPLICATES · OVERLAPS · CONFLICTS · SUPERSEDES · EXCEPTION_TO, plus UNKNOWN.
  - *"Absence of a detected conflict is not evidence of consistency."*
  - KT-8 §2 later corrects this list: SUPERSEDES and EXCEPTION_TO are decisions, not findings.
- **[SRC] Priority numbers are rejected** as a way to resolve conflicts.
- **[SRC] AI may emit PotentialConflict, never Conflict = TRUE.**
- **[SRC] A proven result carries no confidence value.** Confidence belongs only to candidate detection.

## 3. The Decision Register (1040)

- **[SRC]** 29 open questions (OQ-1…OQ-29) were collected in one register.
- **[SRC] Two root questions:**
  - **OQ-1:** is the PKS software at all, or a set of specifications?
  - **OQ-8:** what is a Rule?
- **[SRC]** 13 of the questions are recorded as decidable now, without further evidence. OQ-1 is one of them.

## 4. Scripts and Python (1144, 1203)

- **[SRC] Script inventory (1144):**
  - about 57 mechanisms;
  - contracts K-1…K-20, of which **only K-17 is compiled**;
  - 2 blocking hooks;
  - 3 fail-open incidents;
  - `scripts/` has no owner;
  - two governance regimes exist that do not know about each other.
- **[SRC] Python (1203):**
  - Python is a recorded *direction*, never ratified;
  - PHP stays as a permanent adapter;
  - the evidence store has one completed cycle and has been idle since 08-05.
- **[I]** This is the same honest gap found in B-06 §5: a lot of specification, very little operational evidence.

## 5. Contribution to the basis

| # | Contribution | Kind |
|---|---|---|
| KT7-1 | **Conflict = joint unsatisfiability over the intersection of applicability, scope and time.** This is a precise, testable definition | [SRC] |
| KT7-2 | **UNKNOWN is a first-class result.** "No conflict found" ≠ "consistent" | [SRC]; matches B-06 principle 10 |
| KT7-3 | **Gates follow ratification.** Mechanism never precedes the human decision | [SRC]; matches B-06 principle 12 |
| KT7-4 | Rule fields are the same shape as the Track B rule model (KT-6, RM-1) | [I] cross-confirmation |
| KT7-5 | **[?]** OQ-1 and OQ-8 are open in the register. I found no record that either was answered | open |
