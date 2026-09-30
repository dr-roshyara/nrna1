# missingness-taxonomy-abhava

**Scope(s):** OBJECT · **Row count:** 8 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** D_t; Pragabhava/Pradhvamsabhava/Atyantabhava/Anyonyabhava; not assessed / absent / not applicable / unresolved · **Aliases:** Abhava absence typology; Zero-lens six-way taxonomy
**Candidate group membership (NOT an identity claim):**
- G0416: relationship not yet decided (P3). Per `_LABEL-NORMALIZATION.md`, explicit agent-stated uncertainty (batch B0041) that `unknown-no-home-gap` POSSIBLY relates to `missingness-taxonomy-abhava`. Note quoted there: "Unknown fits neither the value space (a category error per Step 264's own 'Nexus Version problem' warning) nor absence-of-assertion (indistinguishable from never-having-asked, destroying the corpus's own Zero concept per 025d); since sigma is not a field of the terminal Assertion type, Unknown currently has nowhere to live."
- G0760: relationship not yet decided (P3). Per `_LABEL-NORMALIZATION.md`, three labels (`d-t-recognised-dimension-set-gap`, `dimension-discovery-uncertainty`, `missingness-taxonomy-abhava`) "share the notation 'D_t'" — a purely mechanical notation-overlap signal, not independently reviewed/confirmed in the normalization document's summary sections. No identity is asserted.
- G1702: relationship not yet decided (P3). Per `_LABEL-NORMALIZATION.md`, `knowledge-state-formalization` and `missingness-taxonomy-abhava` "co-occur in the same contribution's labels[] 2 separate times across the corpus" — a co-occurrence signal (this label's own rows do in fact show two rows dual-labeled with `knowledge-state-formalization`: S1683's second row and S1684's row, both below). No identity is asserted.

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0041, scope OBJECT: "Two independent corpus constructions (a six-way Zero-taxonomy with five non-collapse laws, and a Navya-Nyaya-derived Abhava ontological absence typology with an AbsenceClaim record shape) together covering seven of eight mandate-listed missingness cases; identifies D_t, the recognised-dimension set, as the smallest component missing from K=(A,R) needed to make 'not assessed' decidable."

No `single_candidate_flags` recorded.

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1683 §"The corpus says 'nobody ever asked'. ... Seven of eight are directly covered; the eighth is partial. The audit reported zero of five."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1683 §same anchor as lexical, above]
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S1684 §"VERIFIER RECOMMENDS -- not CORPUS ESTABLISHES. K = (D_t, A, R) ... with id = H(P, e_refs, c, t, Pi) where e_refs projects out the mutable state. ... Nothing here is adopted. This is a proposal, and it is smaller than the three the corpus already contains -- which should be consumed first (ES-005.4)."]

## Lifecycle
last_seen: S1849. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is empty (no retracted_by, no superseded_by, not contested). DORMANT is a recency heuristic based on this label's rows spanning batches B0041/B0042/B0045 — it is not a confirmed retirement of the taxonomy.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S1683 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1683, S1849 |
| type_signature | PRESENT | S1684, S1689 |
| invariants | PRESENT | S1683, S1689 |
| dependencies | PRESENT | S1683 (x2), S1684, S1694 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1683, S1689, S1733, S1742 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | NOT-EVIDENCED-IN-CAPTURE | — |
| experiments | PRESENT | S1742 |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale
One row is classified as rationale evidence: an executed probe (MISSINGNESS-NOT-ASSESSED in THEORY-CONSTRUCTION-TEST.md) shows a never-assessed dimension and an assessed-with-no-result dimension both render identically in K=(A,R) as "no assertion mentioning that dimension," proving them provably indistinguishable; introducing D_t (the set of currently recognised dimensions) as a component of K makes "not assessed" decidable in O(n), and is identified as the smallest missing component needed to close the missingness gap [S1683]. `rationale_truncated_count` is 0, so no further rationale-bearing rows are known to exist beyond this capture.

## Assumption register
NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)
- `[S1683] types=[CORRECTION, FORMALIZATION, DISTINCTION] scope=OBJECT, explicit_date=2026-08-30, completeness=PARTIAL` — "A six-way Zero-lens dimension/value taxonomy (known / unknown Zero-A / not-assessed / absent / not-applicable / unresolved Zero-B) with five non-collapse laws (UNKNOWN≠ABSENT, UNRESOLVED≠INVALID, NOT_ASSESSED≠LOW_CONFIDENCE, NOT_APPLICABLE≠UNKNOWN, NO_EVIDENCE≠INVALID_EVIDENCE) plus a sixth boxed law (UnknownValue(D)≠UnknownDimension(D)), together with an independently-committed Abhava ontological absence typology (Pragabhava/Pradhvamsabhava/Atyantabhava/Anyonyabhava) and an AbsenceClaim record shape, jointly cover seven of the mandate's eight missingness cases directly and one partially — versus the audit's report of zero of five covered." Missing: "Omega's domain declared for case 4 (observation absent)". Invariants: the six non-collapse laws listed above. Dependencies: two named prior documents (zero-lens research synthesis; abhava-absence-structured-knowledge-lens). Lineage claim: SOURCE-CLAIMED-CONTRADICTION of "THEORY-CLOSURE-AUDIT.md previous claim 'not asked' and 'absent' indistinguishable" (quote: "FALSIFIED, decisively").
- `[S1683] types=[ANALYSIS, EXTENSION] scope=OBJECT, explicit_date=2026-08-30, label_confidence=UNCERTAIN` — see Rationale section above for full statement. Dual-labeled with `knowledge-state-formalization` (not among this batch's 20 assigned labels; consistent with the G1702 co-occurrence signal above).
- `[S1684] types=[EXTENSION, GOVERNANCE] scope=OBJECT, label_confidence=UNCERTAIN, completeness=PARTIAL` — "Proposes (explicitly labelled VERIFIER RECOMMENDS, not adopted) a minimal repair K=(D_t,A,R) adding the recognised-dimension set D_t and redefining id=H(P,e_refs,c,t,Pi) to project out the mutable state field, arguing this closes the missingness gap (7/8 cases), removes the identity/mutability contradiction, and makes a dedup operator definable by unioning Pi and e on (P,c,t); explicitly states this does not repair non-identifiability (needs a W,Omega layer beneath K) or uncertainty (needs U(H)), and that the corpus's own existing three constructions should be consumed first per ES-005.4." Missing: "non-identifiability layer (W,Omega)", "uncertainty U(H) integration". Dependency: "ES-005.4 consume-or-extend rule". Dual-labeled with `knowledge-state-formalization`. Type signature: domain "proposed K components", codomain "n/a", arity 3.
- `[S1689] types=[DISTINCTION, HYPOTHESIS] scope=OBJECT, label_confidence=UNCERTAIN, completeness=PARTIAL` — "Argues no-evidence must be distinguished from conflicting-evidence when assigning Sigma=Unknown, and that a missing required measurement (x=?) cannot be inferred as False; proposes a candidate three-valued policy-decision output {Permit, Deny, Undetermined} as a bridge between missingness and policy, explicitly marked as a candidate requiring corpus confirmation, not a final decision." Missing: "corpus confirmation that Undetermined is required as a formal Policy result". Invariants: "Missing ≠ False", "empty-evidence ≠ contradictory-evidence". Type signature: domain "policy evaluation input", codomain "{Permit,Deny,Undetermined}", arity 1.
- `[S1694] types=[CORRECTION, GOVERNANCE] scope=THEORY-LEVEL, explicit_date=2026-08-30, version_ref=v0.2` — "Corrects a prior verdict ('THEORY CLOSED AGAINST THE STATED CRITERIA — NOT COMPLETE IN EVERY RESPECT'...) by showing the corpus does say 'nobody ever asked' (the not-assessed Zero-lens dimension state, committed one day before this programme began) and does have formal, typed constructions for non-identifiability and uncertainty; reclassifies the three exclusions as a discovery failure rather than a mathematical limit, and as a governance finding since these constructions are measured absent (zero grep occurrences of unknown/absent/not assessed/uncertain/identifiab) from the authorized v0.2 model despite existing in the raw corpus." Dual-labeled with `non-identifiability-omega-w`, `uncertainty-typed-object-u-h`, `canonical-architecture-v0.2` (none among this batch's 20 assigned labels). Dependencies: "20260826-105229 committed 2026-08-28", "reviews/synthesis/model/canonical-architecture-v0.2.md". Lineage claim: SOURCE-CLAIMED-CONTRADICTION of "prior verdict 'THEORY CLOSED AGAINST THE STATED CRITERIA'".
- `[S1733] types=[DISTINCTION] scope=OBJECT, completeness=N/A, _batch_id=B0042` — "Three concepts have exactly zero definitional occurrences corpus-wide: Missingness (the concept exists richly under other names — Zero, abhāva, Missing, 'not assessed' — so this is a vocabulary gap masquerading as a theory gap), Admissibility (its two laws are cited constantly, 42.9/42.41, but the term itself is never defined), and AuthorityAct (confirming, by whole-corpus scan rather than targeted grep, that the object the entire governance model turns on has no type)."
- `[S1742] types=[DISTINCTION, EXPERIMENTAL-RESULT] scope=THEORY-LEVEL, _batch_id=B0042` — "8 of 9 mandated missingness distinctions (not-asked, asked-but-no-answer, unknown, absent, no-evidence-found, searched-but-insufficient, deliberately-omitted, deleted; only 'never existed'/Prāgabhāva lacks one) have a named corpus construct across the Zero taxonomy and the Sanskrit abhāva typology, and 4 of them (unknown, absent, insufficient, not-applicable) are executable, tested, passing code in zero_reference.py (8/8 tests); the word 'Missingness' itself has 0 definitional occurrences, so searching for the word finds nothing while the capability exists richly under other names."
- `[S1849] types=[FORMALIZATION, EXTENSION] scope=OBJECT, explicit_date=2026-08-30, completeness=N/A, _batch_id=B0045` — "The Zero model's D_t (recognised-dimension set) plus Z_t=Ω\\Represented(K_t) layer, already present in the corpus, makes 'not assessed' a decidable O(n) predicate (D in D_t and no assertion covers D), and this is proposed as the minimum additional structural component needed — not invented, but recovered from the corpus's own Zero-lens construction." Dual-labeled with `step280-empirical-closure-test-suite-ec-not-achieved` (not among this batch's 20 assigned labels).

## Notes for P3
- This label documents a strong, largely consistent thread across three batches (B0041, B0042, B0045) converging on the same finding: the concept of "missingness" is richly present in the corpus under other names (Zero-lens taxonomy, Abhava/Sanskrit typology) but the literal word "Missingness" has zero definitional occurrences — repeated independently in S1733 and S1742 as a "vocabulary gap masquerading as a theory gap." This is a strong, multiply-corroborated evidentiary base, worth flagging as unusually solid for P3.
- Two rows carry `completeness: N/A` (S1733, S1849) rather than COMPLETE/PARTIAL — transcribed verbatim, flagged as an unusual value.
- The G0760 notation-overlap group (three labels sharing "D_t") and the G1702 co-occurrence group (with `knowledge-state-formalization`) both point toward a close working relationship between this label and the D_t/knowledge-state-formalization family — this label's own rows (S1683's second row, S1684) are themselves dual-labeled with `knowledge-state-formalization`, corroborating the co-occurrence mechanically. P3 should treat this as a priority pairing given the multiple independent signals, though still not an identity claim.
- Several rows explicitly stress that proposed repairs (D_t, id=H(...) redefinition) are NOT adopted — "VERIFIER RECOMMENDS, not CORPUS ESTABLISHES" (S1684) — this qualifier should be preserved by P3 in downstream synthesis rather than treated as settled architecture.
