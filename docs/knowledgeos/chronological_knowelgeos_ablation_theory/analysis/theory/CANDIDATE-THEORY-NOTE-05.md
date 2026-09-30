# Candidate theory note 05 — the promotion chain; H3 refined; one robust MT survivor; T1 consolidated (candidate)

⚠ generated · research hypothesis · not canonical · **no model selected**. Tags: [SOURCE] [ASSUME] [DERIVED] [HYP] [TEST].

## 1. Source facts (L0-REL-13)
- **Phase-02.6 L32** (UL definition) [SOURCE]: "The only path by which a platform artifact gains authority: `Generated → ARB Review → Capability Certification → ADR Approval → Authoritative → Frozen`. **Each stage requires its own Human Decision Event. Stages are never skipped.**"
- **PKS_ARB_Review_Discipline L216–220** (Authority, 2026-07-30) [SOURCE]:
  - Review Complete (reviewer) → Findings Dispositioned (Authority) → Remediation Verified (reviewer / verification) → **Promotion Ready ("a state DERIVED BY VERIFICATION, not a decision by anyone")** → **Authority Promotion Decision ("the act")** → **Promoted Baseline ("the governed state")**.
  - "Each step has a different owner and a different authority; no step may absorb the one after it".
  - Non-collapse family: verified-ready ≠ promoted · frozen ≠ promoted · review outcome ≠ adoption · recommendation ≠ issuance ≠ adoption · closing a review ≠ adopting. "Every member was added because the program conflated it once."

## 2. Semantics
- **H3 refined (H3′)** [DERIVED from SOURCE]:
  - the strong "one act" reading is **false as a description of the chain**, which has many distinct owned acts;
  - but **the promotion itself is one act**, and its precondition is a **derived state, not a granted authorization**;
  - **no durable authorization grant exists** in the described chain.
- **Consequence for MT** [DERIVED]:
  - MT1's defining mechanism, a granted `au` that licenses promotion after the floor is lost, has **no counterpart**;
  - the act is taken from the verification-derived ready state, so the floor holds at the event (EP-02a);
  - mapping "derived readiness" onto EF is an interpretation → **MEDIUM**.
- **Readiness vs bar** [HYP, open]:
  - if "ready" means the bar (EB) is met, then EP-02b holds and **every MT model would be inconsistent**, since MT permits promotion below the bar;
  - this is not stated, and it is in tension with the historical R-39 exception. **Recorded; not coded.**
- **Coupling rule (resolves note 01's under-constraint in part)** [SOURCE]:
  - Frozen is reachable only via … → Authoritative → Frozen, and stages are never skipped;
  - so **Frozen ⇒ previously Authoritative**;
  - **I5 is upgraded from I5-MODEL to SOURCE-supported**;
  - authority and status are **not** fully orthogonal.
- **Kind-dependent chains** [SOURCE]: the platform-artifact chain and the PKS-baseline chain differ, which supports typed objects K with a chain per kind.
- **Non-collapse axioms** [SOURCE]: a family of *inequalities between acts and states*. It is historically motivated ("added because conflated once"). These are among the most robust candidates for a theory: they are purely formal, and each is testable against an event record.

## 3. Engine (frozen; cumulative 35 cells; basis SECONDARY-REPRODUCED)
- **L0-REL-13:** EP-02a SUPPORTED · EP-02c REFUTED (MEDIUM) · EP-02b AMBIGUOUS · EP-12 SUPPORTED (HIGH).
- **Cumulative:**
  - MT1-P0, MT2-P0 **INCONSISTENT-WITH EP-04a, EP-04b** (ES-001.2);
  - MT1-P1 **INCONSISTENT-WITH EP-02a, EP-02c** (the chain);
  - **MT2-P1 NOT-ELIMINATED**;
  - MT0 inconsistent;
  - flag: MODEL-FAMILY-INCONCLUSIVE (EP-01: authority and scope cannot be expressed in MT).
  - Counts: 4 SUPPORTED · 2 REFUTED · 5 AMBIGUOUS · 8 SILENT.

## 4. Sensitivity [TEST] (`runs/CUMULATIVE/SENSITIVITY.json`)

| Downgraded to AMBIGUOUS | Surviving primaries |
|---|---|
| none | MT2-P1 |
| promotion chain (C1) | MT1-P1, MT2-P1 |
| ES-001.2 (G1) | MT1-P0, MT2-P0, MT2-P1 |
| both | all four |

- **MT2-P1 survives in every coding.**
- **Each elimination depends on exactly one MEDIUM SELF reading.**
- **Supported statement:** "MT2-P1 is the only MT projection consistent with every reading." **Not supported:** "MT2-P1 is correct." The MT family also cannot express authority, per-item scope, supersession with versioning, Defer or kind-specific chains.

## 5. T1: the consolidated candidate theory (not canonical; not built as code)
- **State:** an object x with identity, derived-from, a kind K, a chain position C_K(x) (a kind-specific linear chain), an evidence vector E⃗ (non-aggregated) and derived predicates (e.g. Ready(x) = f(verification of x)).
- **Acts:** owned, explicit decision events d = (act, x, owner, outcome ∈ {Approve, Reject, Defer}, t).
- **Axioms:**
  - **A1 performative standing:** ΔStanding(x) ⇒ ∃ explicit decision (ES-001.2).
  - **A2 one decision per stage:** each chain step is its own decision event, never skipped (Phase-02.6).
  - **A3 derived ≠ decided:** readiness is derived from verification and is never itself a decision (PKS).
  - **A4 non-collapse:** the listed inequalities between acts and states.
  - **A5 supersession:** a change of a frozen x is a new version x′ via an explicit decision; never in place (Phase-02.6 §5).
  - **A6 evidence burden at the act:** promotion requires demonstrated evidence (ES-006.1, R-37); default non-acceptance.
  - **A7 coupling:** Frozen ⇒ previously Authoritative.
- **Projection onto MT:** T1 ↦ event-time guard (MT2) + explicit revocation (P1), i.e. **MT2-P1**. That agrees with the engine's robust survivor, which is a **consistency check between two independent routes**, not a proof.
- **Falsifiers (pre-registrable):**
  - (F1) any historical status change without a recorded decision;
  - (F2) any promotion from a non-ready state;
  - (F3) any skipped stage;
  - (F4) any in-place redefinition of a frozen artifact;
  - (F5) any record where a derived state was treated as a decision;
  - (F6) any automatic loss of standing on evidence change.
  - **R-39 is the known candidate test case for F2/EP-02b** (below-bar promotion).

## 6. Next
1. **Independent corroboration** of the two MEDIUM groups: ES-001 L26 and PKS L218. That is 3 lines in total; a human or non-Claude reader decides EP-04a/04b and EP-02a/02c.
2. **A pre-registered T1 specification**, with the falsifiers F1–F6, before any test.
3. **R-39 as the falsification test of T1** (F2 / below-bar). This returns R-39 to the critical path, now as a *test*, not as a model-selection pointer.
