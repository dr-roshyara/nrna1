# Candidate theory note 06 — structured reconstruction of the promotion-chain definitions (no new read)

⚠ generated · research hypothesis · not canonical · **no model selected**. Tags: [SOURCE] [ASSUME] [DERIVED] [HYP] [TEST].
**Basis:** only fragments already released and read under L0-REL-13: Phase-02.6 L32; PKS_ARB_Review_Discipline L216–221. §13.2 of the Retrospective was not needed and was not read. Reviewer quotations from Phase-02.7 are **not released**, so they are not used as evidence.

## Corrections to notes 04–05
- **C-1 (scope of A1).**
  - [SOURCE-SUPPORTED] *governance status* (ES-001.2) is created or changed only by explicit authority decisions.
  - [HYP] the claim that this generalizes to *all* KnowledgeOS standing.
  - Note 05's A1 is re-scoped accordingly.
- **C-2 (MT framing).**
  - "MT1-P1 INCONSISTENT" means that *MT1-P1 as specified admits a behaviour (promotion without the floor, enabled by a durable grant) that the chain excludes*.
  - It does **not** mean that one mechanism was selected over another. Under H3′ (below), MT1-P1 restricted to single-act histories is observationally equal to MT2-P1 (note 03 TEST).
  - **Correct statement:** the corpus excludes the behaviour that distinguishes MT1-P1 from MT2-P1. It does not choose between their mechanisms.

## 1. Exact fragments read
- Phase-02.6 L32 (`baaf69aa…`).
- PKS_ARB_Review_Discipline L216–221 (`e42ef5d9…`).

## 2. Object being promoted [SOURCE]
- L32: "a **platform artifact**" (it gains authority).
- PKS: an artifact under review, becoming a "**Promoted Baseline**" (the governed state).
- There are two object kinds with two different chains, so the chain is **kind-specific**.

## 3. Promotion event semantics
- [SOURCE] PKS names exactly one act: "**Authority Promotion Decision (the act)**". Its owner is the Authority.
- [SOURCE] L32: "Each stage requires its own Human Decision Event".
- Supported schema: PromotionEvent(object, owner/authority, stage, decision) [SOURCE].
- The fields **evidence** and **time** are *not* stated as fields. Evidence enters only through the derived readiness state.

## 4. Human Decision Event semantics [SOURCE]
- Every stage transition is an HDE (L32).
- Steps are owned, and different steps have different owners and authorities (PKS).
- **ready ≠ decided ≠ promoted ≠ frozen** are distinct [SOURCE: "a state DERIVED BY VERIFICATION, not a decision"; the "verified-ready ≠ promoted", "frozen ≠ promoted" family].
- Ready is a **derived predicate**. Decided is an **act**. Promoted Baseline and Frozen are **states**.

## 5. Effects on the dimensions
- **Authority** [SOURCE L32]: Generated → … → Authoritative. It is gained *through the chain*.
- **Status** [SOURCE L32]: Frozen appears **as a stage of the same chain**, after Authoritative.
- [DERIVED from SOURCE] **In the platform-artifact chain, (authority, status) is a function of chain position.** Along this chain the two are *not* independent dynamics. The metamodel's "⊥" holds as a **vocabulary distinction**, not as independent evolution. **This is a tension with Metamodel §6 and is recorded, not resolved.**
- **Ladder** (ES-006.1) vs these chains: equivalence **not stated**. The rungs differ.
- **Evidence:** it enters as the precondition via derived readiness (PKS). It is not a stage.
- **Version / identity:** unchanged by promotion within the chain (as stated). Changes after freezing produce a **new version** (Phase-02.6 §5, read under L0-REL-11).

## 6. SINGLE-ACT vs TWO-ACT
- **Phase-02.6 L32: SEQUENTIAL-STATES, ONE ACT PER TRANSITION.** "Approved, then frozen" (§5) is therefore **two successive promotions, each with its own HDE**. It is *not* an authorization and an execution of one promotion.
- **PKS: SINGLE-ACT** promotion (the act), preceded by a **derived** readiness state and by earlier, separately owned acts (review, disposition, verification) that are *not* authorizations to promote.
- **H3′** [DERIVED from SOURCE]: each promotion transition is exactly one constitutive act. No authorization-then-execution split is described. **H3 in the form needed by MT holds**, although the chain as a whole is multi-act.

## 7. Supersession
- [SOURCE §5, SOURCE §6] Supersession is an **independent, decision-triggered (ADR) version transition** applying to frozen artifacts. It is **not a stage of the promotion chain** and not a consequence of promotion.
- [HYP] The predecessor receives the status `superseded` (Metamodel vocabulary). This link is not stated in the chain fragments.
- Supersession ≠ loss of standing by revocation. It is **replacement by x′**.

## 8. Re-promotion
- [SOURCE] Stages are never skipped, and the chain is forward. Changes after freezing yield x′ (§5).
- **Re-promotion of the same x after a loss: NOT STATED.**
- [HYP] Change is realized as **versioning (x → x′)**, not as re-promotion of x.
- [TEST, consistency] Note 03's model result "under single-act + monotone authority, the same object can never be re-promoted" **matches** the source's versioning route. The model and the source agree independently. This is not a proof.

## 9. Minimal formal transition model (candidate T1, re-stated in A–D terms)
- **B (constitutive event)** [SOURCE]: HDE(x, stage, owner, outcome) is what creates each new standing. Standing is a function of the decision record (for governance status: SOURCE; beyond that: HYP, per C-1).
- **A (state transition)** [SOURCE]: chain position c(x) ∈ C_K (linear, forward, no skipping). The transition c → next(c) happens only by an HDE and only if Ready_c(x) (derived).
- **C (version transition)** [SOURCE]: Supersede(x → x′) by decision (ADR), for frozen artifacts; never an in-place edit.
- **D (product of dimensions)** [SOURCE vocabulary, DERIVED dynamics]: authority, status, evidence, ladder and version are distinct *concepts*. Along a given chain, **authority and status are coordinates of c(x)**, and evidence is a separate vector feeding Ready.
- **The combination:** x = (id/version, K, c ∈ C_K, E⃗) with Ready = f(E⃗, verification); events are HDEs and Supersede. That is B + A + C, with D only as a conceptual separation.
- **Smaller than MK:** there are no independent authority or status axes, because the chain position suffices for the chains read so far.

## 10. What remains unknown
- Whether authority and status can vary *independently* outside these chains (the Metamodel says ⊥; the chain couples them).
- Whether Ready means the floor or the bar (EP-02b; the R-39 tension).
- Whether the ES-006.1 ladder is another C_K or a different concept.
- Whether the predecessor's status becomes `superseded` by rule.
- Re-promotion of the same x.
- Whether ES-001.2's decision-mediation generalizes beyond governance status (C-1).
- **Corroboration:** the ES-001 L26 and PKS L218 readings are single SELF readings at MEDIUM.

## 11. Computational consequences
- [TEST, note 03] H3 collapses MT1 ≡ MT2 per P-class.
- [TEST, note 05] Sensitivity: MT2-P1 is consistent under every coding. Each elimination depends on one MEDIUM reading.
- [DERIVED] With c(x) linear, forward and not skipped, and with authority and status as coordinates of c: **I5 (Frozen ⇒ previously Authoritative) follows directly** from the source chain. The note 01 verifier's I5-MODEL is now a consequence of a SOURCE chain, not of an assumed move semantics.
- No new verifier was built. The chain is linear, so enumeration would only restate L32.

## 12. Is ML justified?
- **No.** The work consists of semantic interpretation of a handful of located definitional sentences.
- There is no demonstrated recall gap and no sealed labelled set yet.
- **Seed for later RB-1:** the anchors used across L0-REL-03…13 (≈ 35 cells) are a first sealed known-relevant set.
