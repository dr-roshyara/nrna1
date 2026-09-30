# sufficient-f-o-i-criterion-adoption

**Scope(s):** OBJECT · **Row count:** 7 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Congruent(F,O)`; `Expressive(F,I)`; `G-56`; `G-67`; `Sufficient(F,O,I)`
**Aliases:** "congruence plus expressibility criterion"; "sufficiency adoption"
**Candidate group membership (NOT an identity claim):**
- G0427: co-occurs with `step273-knowledge-state-sufficiency-minimality-mandate` — explicit agent-stated uncertainty: 'sufficient-f-o-i-criterion-adoption' POSSIBLY relates to 'step273-knowledge-state-sufficiency-minimality-mandate' (batch B0046). Note: The formally adopted two-conjunct sufficiency criterion Sufficient(F,O,I) := Congruent(F,O) AND Expressive(F,I), where Expressive is defined as congruence for the 0-ary (predicate) case, executed as closing gap G-56 (congruence != sufficiency). The two conjuncts are shown independent by executed witnesses, and the criterion is shown to retrodict Step 281's Repair B selection a priori (K alone is Congruent but not Expressive once 'was p asked?' is a mandated predicate). Opens new gap G-67: the invariant/predicate set I has never been enumerated.
- G0810: co-occurs with `step288-equality-register-nine-registers-g67` — labels share the notation 'G-67'
- G1723: co-occurs with `step281-missingness-repair-inquiry-register-selection` — labels co-occur in the same contribution's labels[] 2 separate times across the corpus

## Sources (how this label entered the ledger)

- PROPOSAL, batch B0046, scope OBJECT: "The formally adopted two-conjunct sufficiency criterion Sufficient(F,O,I) := Congruent(F,O) AND Expressive(F,I), where Expressive is defined as congruence for the 0-ary (predicate) case, executed as closing gap G-56 (congruence != sufficiency). The two conjuncts are shown independent by executed witnesses, and the criterion is shown to retrodict Step 281's Repair B selection a priori (K alone is Congruent but not Expressive once 'was p asked?' is a mandated predicate). Opens new gap G-67: the invariant/predicate set I has never been enumerated." (relation_to_existing: POSSIBLY:step273-knowledge-state-sufficiency-minimality-mandate)

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S1889 §"after Repair B: system state = (K,\ Q_t,\ H) \qquad\text{not}\qquad K ... the sufficiency question re-opens **one level up**"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1905 §"def expressive(F, predicates, space=SPACE):
    """Expressive(F, I): no two F-identified states disagree on any predicate.""""]
- CANDIDATE-OPERATIONAL-BIRTH: [S1905 §"def expressive(F, predicates, space=SPACE):
    """Expressive(F, I): no two F-identified states disagree on any predicate.""""]
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S1912. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is empty (retracted_by: [], superseded_by: [], contested_by_own_contradiction_type: false). DORMANT is a recency heuristic based on last use (S1912), not a confirmed retirement or a confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | PRESENT | S1889, S1912 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1905, S1912 |
| type_signature | PRESENT | S1912 |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S1912 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S1912 |
| experiments | PRESENT | S1912 |
| open_questions | PRESENT | S1912 |

## Rationale

Mathematician's-lens argument that Repair B, though invariant-preserving over K, silently changes what 'the state' means to (K,Q_t,H); K alone can no longer distinguish M1 from M2, so the sufficiency question reopens one level up: is (K,Q_t) sufficient and minimal? Names G-56 (Sufficient(K,O,I)) as exactly the needed instrument, noting it remains unadopted as of this document [S1889]. Additionally, The decisive adoption argument: modeling the Step 280 defect (proposition p, inquiry recorded or not) shows K alone is CONGRUENT (the only operations are Assert and Ask; Ask changes only the inquiry register, which K does not project, so K-indistinguishability is preserved by every K-transformation) but NOT EXPRESSIVE the moment 'was p asked?' is admitted as a mandated predicate -- so Sufficient(K,O,I) would have located the exact Step 280 defect, and named the repair site (the smallest carrier making I_was_asked expressible), BEFORE the empirical test ran, i.e. a priori rather than by post-failure comparison [S1912].

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

- [S1889] types=[ANALYSIS, EXTENSION] scope=OBJECT — label_confidence UNCERTAIN, also labeled `step281-missingness-repair-inquiry-register-selection` — "Mathematician's-lens argument that Repair B, though invariant-preserving over K, silently changes what 'the state' means to (K,Q_t,H); K alone can no longer distinguish M1 from M2, so the sufficiency question reopens one level up: is (K,Q_t) sufficient and minimal? Names G-56 (Sufficient(K,O,I)) as exactly the needed instrument, noting it remains unadopted as of this document." (anchor: "after Repair B: system state = (K,\ Q_t,\ H) \qquad\text{not}\qquad K ... the sufficiency question re-opens **one level up**")
- [S1905] types=[IMPLEMENTATION, FORMALIZATION] scope=OBJECT — "Ground-truth executable definition of Expressive(F,I): for a given finite enumerated space of states, bucket states by their F-image, then check that no two states sharing an F-image disagree on any predicate in I; returns (bool, list of witnessing disagreements). This is the operational form of the Expressive conjunct used throughout the Sufficient(F,O,I) adoption argument." (anchor: "def expressive(F, predicates, space=SPACE):
    """Expressive(F, I): no two F-identified states disagree on any predicate."""")
- [S1912] types=[FORMALIZATION, DEFINITION] scope=OBJECT — "Formal definition proposed for adoption: for a state abstraction F with s1~F s2 iff F(s1)=F(s2), Congruent(F,O) requires every state-transforming operation in O to respect F-indistinguishability, Expressive(F,I) requires every mandated predicate/invariant in I to respect F-indistinguishability (i.e. Expressive is Congruence for the 0-ary/predicate case), and Sufficient(F,O,I) is their conjunction. Closes G-56 (previously open since the second-order pass, at 0 adoption)." (anchor: "\mathrm{Sufficient}(F,\mathcal O,\mathcal I)\;&:\Longleftrightarrow\; \mathrm{Congruent}(F,\mathcal O)\ \wedge\ \mathrm{Expressive}(F,\mathcal I)")
- [S1912] types=[EXPERIMENTAL-RESULT, VALIDATION] scope=OBJECT — "Executed independence proof that Congruent and Expressive are neither redundant: witness (a) F4=K=(A,R) is Congruent=True but Expressive(I_merge_prov)=False against the section-265.11 merge-provenance invariant; witness (b) F2=content+status is Congruent=False (fails on Remove/Revise/Transform) but Expressive(I_any_accepted)=True. Both directions realized, so the conjunction Sufficient=Congruent AND Expressive is a proper (non-redundant) conjunction." (anchor: "Congruent ∧ ¬Expressive | `F₄ = K=(𝒜,ℛ)` ... Expressive ∧ ¬Congruent | `F₂ = content+status`")
- [S1912] types=[ARGUMENT, VALIDATION] scope=OBJECT — also labeled `step281-missingness-repair-inquiry-register-selection` — "The decisive adoption argument: modeling the Step 280 defect (proposition p, inquiry recorded or not) shows K alone is CONGRUENT (the only operations are Assert and Ask; Ask changes only the inquiry register, which K does not project, so K-indistinguishability is preserved by every K-transformation) but NOT EXPRESSIVE the moment 'was p asked?' is admitted as a mandated predicate -- so Sufficient(K,O,I) would have located the exact Step 280 defect, and named the repair site (the smallest carrier making I_was_asked expressible), BEFORE the empirical test ran, i.e. a priori rather than by post-failure comparison." (anchor: "Sufficient(K,\mathcal O,\mathcal I)\ \text{FAILS on the EXPRESSIBILITY conjunct the moment } \textit{“was }p\textit{ asked?”}\ \in \mathcal I")
- [S1912] types=[LIMITATION, OPEN-QUESTION] scope=OBJECT — "Adoption obligation stated: while the mandatory-operation set O is usable now (15 named, 5-class partition, 14-element lower bound forced, 4 operations' mandatory status open as D-1), the invariant/predicate set I has NEVER been enumerated -- opened as new gap G-67, a class-(1) actual theoretical hole inheriting the load-bearing role O held before Step 272A; recommends I become a maintained register with the same citation discipline O received, seeded with the seven known members (one of which, section 265.11's merge-provenance invariant, is currently inexpressible in K=(A,R))." (anchor: "`ℐ` now carries the weight `𝒪` used to. ... **There is no invariant register.**")
- [S1912] types=[LIMITATION, WARNING] scope=METHODOLOGICAL — "Explicit epistemic-status caveat on the adoption document's own claim: Sufficient(F,O,I) is demonstrated on a bounded 3-state model and derived from two pre-existing corpus criteria (congruence, and the corpus's own restriction noted at 259.8), not proven as a general theorem, and must not be described as a theorem until the invariant set I is fully enumerated (G-67)." (anchor: "`Sufficient` is *demonstrated* on a bounded model and *derived* from the corpus's own two criteria. It is **`DERIVED`, not proven**, and it should not be described as a theorem until `ℐ` is closed.")

## Notes for P3

- This is my own observation: this label participates in 3 candidate groups (G0427, G0810, G1723) — a comparatively dense cross-linkage that may be worth prioritizing in P3 reconciliation.
