# Candidate theory note 03 — R-37 and the single-act hypothesis (H3)

⚠ generated · research hypothesis · not canonical · no model selected. Tags: [SOURCE] [ASSUME] [DERIVED] [HYP] [TEST]. The corpus is treated as **evidence + brainstorming material**; the theory is ours to build and attack.

## 1. Source facts (R-37 row, `ADR-AIP-LOG-Platform-Rulings.md` L23, `7795c14b…`, 2026-07-10)
- [SOURCE] "Architecture Freeze 2.0 (structural)": no new top-level folders, domain objects, capability hierarchy or reorganizations **until** (1) C3 passes, (2) PB-004 completes and (3) the retrospective analyzes real implementation evidence.
- [SOURCE] ARB extension, same day: "*burden of proof, not prohibition*: any **proposal** … must include evidence that the existing architecture was insufficient; improvements without demonstrated insufficiency are **rejected by default**" (operationalizes R-29). Status column: "burden of proof reversed".
- [SOURCE] L5 is defined as "executable governance … part of the normal engineering workflow … **one script proves possibility; routine use proves capability**".
- [SOURCE] A platform maturity ladder L1–L6, marked "Class A, not ruled". This is a **fourth** sense of "maturity": the platform, not knowledge items.

## 2. Transition semantics
- **Propose(p, E) → Accept | Reject.** The burden attaches to **the proposal** [SOURCE]. The default is **Reject** when the evidence is absent [SOURCE]. So in this decision rule, absence of evidence yields *non-acceptance*, never a negative truth, consistent with J6.
- **Unfreeze** is gated by a conjunction of three completion conditions, including *real implementation evidence* [SOURCE]. It has the same form as ES-006.2 ("changes only from … evidence").
- **Nowhere in the released corpus is an authorization step described separately from the promotion decision** (ES-006.1–.4, R-37) [SOURCE, by what is stated]. The only two-phase act described anywhere is in the *identifier* domain: reserve, then mint (CAP-001 OE-5).

## 3. H3, the single-act hypothesis
- **[HYP] H3:** for knowledge promotion, the evaluative decision *is* the promotion act (t_a = t_p).
- **[TEST under ASSUME H3]** (`h3_coincidence_check.py`: the frozen `model_g25` imported read-only, plus the filter "AuthEvent ⇔ PromEvent in the same step"; chain3/V/diamond; instance-invariant):
  - TOCTOU becomes NOT_REACHABLE in **all** primary models.
  - MT1-P1: EVENT-FLOOR-SAFETY FAILS → **HOLDS**; REVAL-a REACHABLE → **NOT_REACHABLE**.
  - REVAL-b becomes NOT_REACHABLE in all four.
  - All other discriminating properties are unchanged.
- **[DERIVED]** Under H3, **MT1-P1 ≡ MT2-P1** and **MT1-P0 ≡ MT2-P0** on every discriminating property. The family collapses to **two classes, P0 vs P1**, and the event-time guard T-EVENT becomes vacuous.
- **[DERIVED]** Under H3 plus monotone authorization (T-AM), no once-authorized object can be promoted again (REVAL-b unreachable). If the corpus permits re-qualification after a loss, then **H3 and "authorization as a durable state" cannot both hold**, and authorization must be an attribute of the act.
- **Theoretical consequence:** the state variable `au`, and with it the whole MT1/MT2 axis, is meaningful **only if** the corpus separates authorization from promotion. **EQ-2 therefore becomes the question "is there any two-phase promotion act?", not "when is evidence checked?".**

## 4. Current candidate theory T0 (smallest form surviving all released evidence)
- **Objects:** an identity plus derivation, K (kind, set by an act), L (ladder rung), E⃗ (non-aggregated evidence vector) and S (status). A and M are not re-attested as independent axes.
- **Acts:** Observe · DetermineReusePotential · DetermineArtifactType · Promote · Qualify · Amend-frozen · Propose/Accept/Reject.
  - **Every act that raises standing or changes a frozen object carries an evidence burden at the act** (J1, J2, R-37). The default is non-acceptance.
  - "No" and "not needed" are valid terminals.
- **Unknown, and the only remaining discriminating axis:** what happens to standing when E⃗ later shows contradictions (B2). That is P0 (automatic) vs P1 (explicit act) vs "neither: standing is untouched and only supersession exits".
- **Status:** T0 is a *candidate*. It is deliberately smaller than MK, and it survives the H3 attack by construction because it has no separate authorization state.

## 5. Engine (secondary)
- R-37: 3 cells (EP-02a / EP-13a / EP-13c), all **AMBIGUOUS**. The scope is architectural expansion, and the link to promotion is only ES-006.1's pointer. 2 BAR_CHANGE events.
- **Cumulative, 27 cells: 11 SILENT · 7 AMBIGUOUS · 1 SUPPORTED (EP-12).** No model constraint; no flag.

## 6. OBS-SF-1 clue (provenance, not a resolution)
- The nearest released wording to F0018's "n ≥ 2 / never from a single occurrence" is R-37's L5 definition: "**one script proves possibility; routine use proves capability**".
- It is about platform maturity, not promotion. It is absent from CAP-001 §9 and from ES-006.1. The attribution chain remains unresolved.

## 7. Adequacy, and the next evidence
- **MT family:** still a valid projection for P0/P1. Its MT1/MT2 axis is **conditional on two-phase acts**, which the released knowledge-promotion corpus never describes.
- **Next highest-information evidence:**
  - **(i) B2:** any rule on what contradictions or later evidence *do* to promoted standing. This decides P0 vs P1 vs "supersession-only".
  - **(ii) An H3 test:** any description of authorizing a promotion separately from performing it ("commissioned", "reserved", "authorized to promote").
  - **Method:** a locate-only lexical pass, exact co-occurrence (e.g. `contradiction|counter-evidence` with `promot|standing|demot|revis|retire`), with sentence-level segmentation (RB-1 §11 lesson). **No ML is needed yet:** these are precise lexical targets with no demonstrated recall gap.
