# policy-change-authorisation-humanActRef

**Scope(s):** OBJECT · **Row count:** 7 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `I-11`; `humanActRef`
**Aliases:** "G6"
**Candidate group membership (NOT an identity claim):**
- G0773: links this to `canonical-architecture-v0.2`, `policy-stratification-invariant` — labels share the notation 'I-11'

## Sources (how this label entered the ledger)

- OBJECT-INDEX, batch B0041, scope OBJECT: "Measured 132/132 humanActRef non-empty across the estate is shown to prove only that a free-text field is always filled, not that authority is bound; the ladder_dc_reference.py witness proving Sigma-perp-Gamma is shown to be a tautological null-check (evidence_volume parameter never read); the ratified canonical-architecture-v0.2 (I-11, R-1 stratification) already closes the underlying question by a different, unreconciled mechanism."

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S1679 §"132/132 is evidence that the field is always filled. It is not evidence that authority is bound. ... This is IMPLEMENTATION-ONLY, and specifically a tautology -- not a proof of Sigma perp Gamma."]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: NOT-EVIDENCED-IN-CAPTURE

## Lifecycle

last_seen: S1946. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is empty (`retracted_by: []`, `superseded_by: []`, `contested_by_own_contradiction_type: false`). DORMANT is a heuristic based on how recently (by source_id) this label was last used (last_seen: S1946), not a confirmed retirement or confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | PRESENT | S1679 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | PRESENT | S1679 |
| dependencies | PRESENT | S1679, S1690, S1946 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | NOT-EVIDENCED-IN-CAPTURE | — |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S1767 |
| experiments | PRESENT | S1679, S1690, S1761 |
| open_questions | PRESENT | S1789 |

## Rationale

Re-measuring humanActRef across the whole estate (22 work items, 132 grants, 269 transitions) confirms 132/132 non-empty, stronger than the prior audit's 20/20, but the field is free-text (83 prose, 49 with a document path) with no schema or verification, establishing a discipline not a binding; the estate already contains a failure case (ASD-001 hand-composed append; two distinct authority acts sharing one grantId). Executing ladder_dc_reference.py's commit() shows evidence_volume is passed but never read in the body, making the cited witness a tautological null-check on authority_act rather than a proof that Sigma is independent of Gamma; the ratified canonical-architecture-v0.2 (I-11, R-1 stratification, committed before this programme) already closes the underlying policy-change-authorisation question by stratifying Policy-as-content vs Policy-in-force, which the original audit never consulted, producing a second unreconciled mechanism for one problem. [S1679]

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

- [S1679] types=[EXPERIMENTAL-RESULT, CORRECTION, ARGUMENT] scope=OBJECT, completeness PARTIAL (missing: a typed AuthorityAct/authorization-binding reference) — "Re-measuring humanActRef across the whole estate (22 work items, 132 grants, 269 transitions) confirms 132/132 non-empty, stronger than the prior audit's 20/20, but the field is free-text (83 prose, 49 with a document path) with no schema or verification, establishing a discipline not a binding; the estate already contains a failure case (ASD-001 hand-composed append; two distinct authority acts sharing one grantId). Executing ladder_dc_reference.py's commit() shows evidence_volume is passed but never read in the body, making the cited witness a tautological null-check on authority_act rather than a proof that Sigma is independent of Gamma; the ratified canonical-architecture-v0.2 (I-11, R-1 stratification, committed before this programme) already closes the underlying policy-change-authorisation question by stratifying Policy-as-content vs Policy-in-force, which the original audit never consulted, producing a second unreconciled mechanism for one problem." (anchor: "132/132 is evidence that the field is always filled. It is not evidence that authority is bound. ... This is IMPLEMENTATION-ONLY, and specifically a tautology -- not a proof of Sigma perp Gamma.") — lineage claim: SOURCE-CLAIMED-CONTRADICTION of THEORY-CLOSURE-AUDIT.md G6 'CORPUS ESTABLISHES + IMPLEMENTATION CONFIRMED'
- [S1690] types=[EXPERIMENTAL-RESULT, CORRECTION] scope=OBJECT, label_confidence UNCERTAIN — "Independently corroborates (via direct code reading of ladder_dc_reference.py's commit()) that evidence_volume is passed (10**6) but never read in the function body, so the witness would emit the identical PASS for evidence_volume=0 or if the law under test were false; classifies the witness as IMPLEMENTATION-ONLY and tautological, withdrawing it as one of three claimed independent proofs of Sigma-perp-Gamma, while the mathematical derivation and the authorities.yaml declaration are left standing." (anchor: "This is a null-check on a parameter named authority_act, restating A6/I-4 in Python. ... Classification: IMPLEMENTATION-ONLY, specifically tautological. Withdrawn as one of the three "independent proofs" of Sigma perp Gamma.") — lineage claim: SOURCE-CLAIMED-IDENTITY of INDEPENDENT-CLOSURE-REVERIFICATION.md §6.2 same finding
- [S1761] types=[EXPERIMENTAL-RESULT] scope=OBJECT, completeness N/A (missing: unspecified) — "Executed grant census over .claude/runtime/workflow/*.json (22 work items, 73 lanes): 132 grant objects, 132/132 carry humanActRef, registeredBy='governance' on 132/132 (single value), grant status 130 AUTHORIZED/1 REVOKED/1 CONSUMED, 79 humanAct free-text entries and 0 typed humanAct objects, 29 distinct hex tokens inside humanActRef of which 21 resolve elsewhere in the store. Executed php .claude/scripts/session-bootstrap.php --process-label=verification returns verdict UNRESOLVED, operable=false, 73 candidates -- fail-closed as documented." (anchor: "grant objects 132 ... grants carrying humanActRef 132 / 132   (100 %) ... humanAct entries as TYPED OBJECTS     0")
- [S1761] types=[LIMITATION] scope=OBJECT, completeness N/A (missing: unspecified) — "AB-1: all 79 humanAct entries and humanActRef itself are untyped prose strings (with embedded hex tokens, 21/29 resolving), so the constitutional layer is recorded but not machine-checkable -- the same defect shape as Step 265's untyped provenance reference and a third verifier package's 'AuthorityAct has no type' finding." (anchor: "AB-1 (IMPLEMENTATION OBSERVATION, HIGH) -- humanAct is untyped. 79 humanAct entries exist; all 79 are free-text strings")
- [S1767] types=[VALIDATION, WARNING] scope=METHODOLOGICAL, completeness N/A (missing: unspecified) — "Fingerprint/provenance check across three concurrently-written verification tracks (mandate: agreement is not corroboration): the 132/132 humanActRef measurement is corroborated as an independent computation but its interpretive inference is convergent not independent (both are Claude sessions with mutual file visibility); the ∀T∈T-incompleteness claim is explicitly withdrawn as non-independent (the corpus states it verbatim, s259.18, before the first-order pass); congruence-not-sufficient (SO-2) and the computed congruence matrix (SO-1) are assessed as genuinely new, located nowhere else in the corpus or either verification tree." (anchor: "humanActRef 132/132 | verification/independent/14 (21:19) | Corroborated as a MEASUREMENT ... The inference (...) is convergent, not independent")
- [S1789] types=[OPEN-QUESTION] scope=OBJECT, also labeled `canonical-construction-decision-register-d0-d4-d5`, completeness N/A (missing: unspecified) — "D-2 (carried): whether authority is exogenous-untyped (status quo, but the estate has already lost an authority act to a grantId collision), exogenous-but-typed (a typed AuthorityAct, minimal innovation, fixes revocation/version-drift/collision), or in-system (rejected by the corpus's own strongest rule) -- recommends the typed option, the single place in the register where innovation is judged genuinely necessary." (anchor: "132/132 grants across 22 work items carry humanActRef; zero typed authority acts exist inside the system. ... any typed act is INNOVATION, which mandate §22 permits only for an irreducible requirement. ... Recommendation: (b), and note it is the only place in this entire register where innovation ap")
- [S1946] types=[CORRECTION, RETRACTION] scope=CROSS-OBJECT — "Four items of withdrawn/corrected evidence are catalogued as items the book must not cite as if valid: (1) the evidence_volume tautology already discussed elsewhere (TG-20), withdrawn; (2) a claimed independence 'status perp authority' is instead measured collinear -- 'draft <=> provisional' is a perfect biconditional (13/13 in each direction) over only 6 of 40 occupied cells, so orthogonality is a design property of the EKP schema, not an observed property of its actual content, and the source experiment self-corrected ('That was FALSE -- my own data contradicted my own conclusion') while being retained rather than deleted, noting 'Two of four experiments produced conclusions I had to withdraw'; (3) '132/132 grants carry humanActRef' supersedes an earlier '20/20' figure but is reframed as 'a discipline, not a binding' because the field is free text (83 prose entries, 49 paths); (4) 93 of 132 knowledge files carry no frontmatter, described as 'a real, running instance of not assessed, the state the theory says it cannot express.'" (anchor: "## C · Withdrawn / corrected evidence (recorded because the book must not cite it)\n- **Tautology:** `evidence_volume` is a dead parameter ...\n- **Collinearity:** `status ⊥ authority` declared independent, **measured collinear** ...\n- **132/132 grants carry `humanActRef`** — "a **discipline, not a binding**" ...\n- **93 of 132 knowledge files carry no frontmatter**") — lineage claim: SOURCE-CLAIMED-CORRECTION of an unnamed prior 'Experiment 3' claiming status-authority independence

## Notes for P3

- 1 of this label's 7 rows carry `label_confidence: UNCERTAIN` (S1690) — treat those rows' membership in this label as provisional.
