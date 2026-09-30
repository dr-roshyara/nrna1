# Candidate theory note 04 — performative standing (ES-001.2) and the first model constraint

⚠ generated · research hypothesis · not canonical · **no model selected**. Tags: [SOURCE] [ASSUME] [DERIVED] [HYP] [TEST].

## 1. Source facts (L0-REL-11)
- **ES-001.2** (`ES-001-Engineering-Constitution.md` L26, `59df3c64…`, ARB 2026-07-11) [SOURCE]:
  - "Documents Record Governance; They Do Not Create It. **Only explicit ARB decisions create governance.** Workflow words … are permission to proceed — **never Approved/Promoted/Retired/Closed**."
  - "STOP and ask per item (**Approve / Reject / Defer**)."
  - "**Authors propose; the authority adopts** (… no artifact may assert an unoccurred adoption)."
- **Phase-02.6 §5** (L155–161, `baaf69aa…`) [SOURCE]:
  - "On ARB approval …: status → `approved`, **then** `frozen` via the promotion chain."
  - Afterwards: "any new term or changed definition requires an ADR and produces a **superseding version** … Terms may be added by supersession; they may never be silently redefined."

## 2. Transition semantics
- **Performative standing** [SOURCE → INTERPRETATION]:
  - every governance status, *acquisition* (Approved, Promoted) and *loss* (Retired, Closed) alike, exists only through an explicit decision act of the authority;
  - records record standing; they do not create it;
  - so no change of standing happens as a side effect of evidence or text.
- **Decision** = Decide(authority, item) ∈ {Approve, Reject, **Defer**} [SOURCE]. It is ternary, and Defer is a legitimate, non-final outcome.
- **Two roles, one constitutive act** [SOURCE]: Propose (author) then Adopt (authority). The proposal is not an authorization; the authority's act *is* the adoption. **This is consistent with H3**, but it does not prove H3.
- **Change of a frozen artifact = supersession** [SOURCE §5]: an explicit decision (an ADR) produces a successor *version*; the old version is not edited ("never silently redefined"). This is an identity- or version-creating transition.
- **Phase-02.6's "approved, then frozen"** is two successive statuses. **Whether they come from two acts is NOT stated** [SOURCE limits]. H3 is neither falsified nor confirmed, so the per-kind H3 test was not run.

## 3. Candidate theory T1 = T0 + performative standing
- **P-1** [SOURCE]: ΔStanding(x, t) ⇒ ∃ explicit decision d(x, t) by the authority. This covers both directions.
- **P-2** [DERIVED from P-1]: evidence change, contradiction or record text **cannot by itself** change standing. It can only *motivate* a proposal (Propose → Decide).
- **P-3** [SOURCE]: change of a frozen x ⇒ Decide(ADR) ∧ a new version x′ supersedes x (no in-place edit).
- **P-4** [SOURCE]: decision outcomes are {Approve, Reject, Defer}.
- **Answer to T0's open axis (B2)** [DERIVED under P-1, HYP pending corroboration]: contradictions do not remove standing automatically. Loss happens only by an explicit decision: Retire/Close, or supersession through a new version. **This is neither pure P0 nor pure P1**, but P1 plus supersession. MT's `rv` covers the explicit-act part only.
- **H1** (note 01: standing is a function of a record of decisions) is now **source-supported** for governance: "documents record governance" (the record) and "only explicit decisions create it" (the events).

## 4. Engine result (frozen engine, cumulative 31 cells; basis SECONDARY-REPRODUCED)
- **EP-04a SUPPORTED, EP-04b REFUTED** (ES-001.2, MEDIUM).
  - → **MT1-P0, MT2-P0: INCONSISTENT-WITH EP-04a, EP-04b.**
  - MT0 (control): INCONSISTENT-WITH EP-04a.
  - **MT1-P1, MT2-P1: NOT-ELIMINATED.**
- **EP-01 SUPPORTED** (named authority, per-item) → CANNOT-EXPRESS for the whole MT family → **MODEL-FAMILY-INCONCLUSIVE**. The guard is correct: one SELF reader, MEDIUM confidence.
- EP-11 is AMBIGUOUS: supersession is a defined act, but no evidence trigger is stated.
- **Outcome counts:** 3 SUPPORTED · 1 REFUTED · 6 AMBIGUOUS · 9 SILENT.

## 5. Consequences for model identification [DERIVED from the frozen tables]
- Surviving: {MT1-P1, MT2-P1}. They differ only on EVENT-FLOOR-SAFETY and REVAL-a, i.e. EQ-2/EQ-5, which is **1 bit** between them.
- **Under H3 they are identical** (note 03 TEST). So the whole remaining MT discrimination reduces to **one question: does the corpus contain a two-act promotion (a decision separate from its execution) for knowledge objects?**
- Expressiveness limits of MT confirmed by evidence: authority and per-item scope (EP-01), supersession with versioning (P-3), the Defer outcome (P-4). **These are not repaired inside MT.** They belong to T1.

## 6. Strength of the constraint (honest)
- **Single SELF reader, interpretation confidence MEDIUM.** The mapping "governance status" → MT `ad` is an interpretation.
- **Formal basis:** SECONDARY-REPRODUCED.
- **This is a model constraint, not a model selection.** It should be **corroborated by an independent reader** (ES-001.2 is two lines) before it is relied on.

## 7. Next highest-information evidence
1. **Corroboration:** an INDEPENDENT (human or non-Claude) reading of ES-001.2 L26 against EP-04a / EP-04b. This is cheap and converts the constraint from SELF to corroborated.
2. **The H3 decider:** any knowledge-promotion procedure that separates the decision from its execution. The Phase-02.6 "approved, then frozen via the promotion chain" hints at a promotion-chain mechanism. The source defining **"the promotion chain"** is the precise next locate target.
