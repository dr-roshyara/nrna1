# human-decision-dossier-d0-d1-d2-d3prime

**Scope(s):** OBJECT · **Row count:** 10 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `D-0`; `D-1`; `D-2`; `D-3′`
**Aliases:** "Human Decision Dossier"; "consolidation D-series"
**Candidate group membership (NOT an identity claim):**
- G0798: links this to `human-normative-decisions-operation-set-authority-determination` — labels share the notation 'D-2'
- G0799: links this to `human-normative-decisions-operation-set-authority-determination` — labels share the notation 'D-1'
- G0800: links this to `canonical-construction-decision-register-d0-d4-d5`, `d0-contradiction-invisible-to-zero-readings`, `o-core-ratification-gap-d0` — labels share the notation 'D-0'

## Sources (how this label entered the ledger)

- OBJECT-INDEX, batch B0045, scope OBJECT: "The consolidation pass's shrunk Human Decision Dossier (three governance-only decisions remaining, down from six, after D-4 was closed by derivation and D-3/D-5 were substantially closed by corpus recovery/folded into D-1): D-0 (ratify O_core or withdraw the ratified claim, four options a-d, recommending b+d = ratify the forced 14-operation lower bound plus three forced-but-unenumerated operations Qualify/Determine/Derive); D-1 (whether Transform/Merge/Split/Reintroduce are mandatory, determining R's shape and whether congruence/global-minimality are provable at all); D-2 (whether to type the free-text humanActRef referent, given two authority acts already collided on one grantId in production, recommending option b = add actId while keeping the free text); and D-3' (residual question of whether Method references a rule instance or rule version, recommending rule version)."

## Candidate births

- CANDIDATE-LEXICAL-BIRTH: [S1852 §"CORRECTION TO MY OWN PRIOR PASS: canonical-construction/21 stated: "INNOVATION is required in exactly one place: a typed AuthorityAct (0 corpus occurrences)." That was wrong. I searched for the string AuthorityAct and concluded the concept was absent. The concept exists, is named Grant, is corpus-de"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-OPERATIONAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-GOVERNANCE-BIRTH: [S1852 §"Verdict: CASE B — implied but incompletely typed ⇒ REFINEMENT. Not Case A (it is not fully present — the act has no identity). Not Case C (it is not absent — Grant is corpus-defined and running)."]

## Lifecycle

last_seen: S1854. Candidate lifecycle: DORMANT.
Evidence: `lifecycle_evidence` is empty (`retracted_by: []`, `superseded_by: []`, `contested_by_own_contradiction_type: false`). DORMANT is a heuristic based on how recently (by source_id) this label was last used (last_seen: S1854), not a confirmed retirement or confirmed ongoing status.

## Completeness roll-up

| Dimension | Status | source_ids |
|---|---|---|
| purpose_rationale | NOT-EVIDENCED-IN-CAPTURE | — |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | NOT-EVIDENCED-IN-CAPTURE | — |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | NOT-EVIDENCED-IN-CAPTURE | — |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1852 |
| examples | PRESENT | S1852 |
| warnings | PRESENT | S1852, S1854 |
| experiments | NOT-EVIDENCED-IN-CAPTURE | — |
| open_questions | NOT-EVIDENCED-IN-CAPTURE | — |

## Rationale

NOT-EVIDENCED-IN-CAPTURE — `rationale_evidence` is empty for this label (no row is typed ARGUMENT/ANALYSIS/EXPLANATION/ALTERNATIVE). `rationale_truncated_count` is 0.

## Assumption register

NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)

- [S1852] types=[CORRECTION, RETRACTION] scope=OBJECT, also labeled `grant-record`, completeness N/A (missing: unspecified) — "Self-correction: an earlier pass (canonical-construction/21) claimed a typed AuthorityAct required pure innovation because a literal string search for 'AuthorityAct' found zero corpus occurrences; this was a lexical error -- the concept exists under the name Grant ('Grant = recorded reference to human act', architecture/Epistemic-Architecture-Investigation.md:265), is corpus-defined, and is implemented with fields grantId/status/authority/humanActRef/registeredBy/scope across 132/132 measured work items -- the same Grant entity the B0002 object 'grant-record' already identifies (BC-7 owns the grant record schema but not the authority content it refers to)." (anchor: "CORRECTION TO MY OWN PRIOR PASS: canonical-construction/21 stated: "INNOVATION is required in exactly one place: a typed AuthorityAct (0 corpus occurrences)." That was wrong. I searched for the string AuthorityAct and concluded the concept was absent. The concept exists, is named Grant, is corpus-de") — lineage claim: SOURCE-CLAIMED-RETRACTION of canonical-construction/21's claim that a typed AuthorityAct requires innovation
- [S1852] types=[EXAMPLE, WARNING] scope=OBJECT, completeness N/A (missing: unspecified) — "Cited production evidence (KOS-AIP-GOV-STATE-DURABILITY-MIGRATION-PLAN-ARCHITECTURE-REVIEW.md:269): two distinct authorized authority acts with different humanActRef and scope values share one grantId, meaning any verification or deduplication keyed on grantId alone would silently treat them as a single act -- a failure already realized, not merely hypothetical." (anchor: "Two distinct authority acts — different humanActRef, different scope, both AUTHORIZED — share one identifier … any verification or dedup keyed on grantId would treat them as one, which is a silent loss of an authority act. The failure has already occurred in production.")
- [S1852] types=[GOVERNANCE] scope=OBJECT, completeness N/A (missing: unspecified) — "Classification verdict: AuthorityAct is Case B (implied but incompletely typed), requiring REFINEMENT rather than Case A (fully present) or Case C (absent, requiring innovation) -- Grant exists and runs, but the human act it references has no identity distinct from grantId." (anchor: "Verdict: CASE B — implied but incompletely typed ⇒ REFINEMENT. Not Case A (it is not fully present — the act has no identity). Not Case C (it is not absent — Grant is corpus-defined and running).")
- [S1852] types=[CONSTRAINT, PRINCIPLE] scope=THEORY-LEVEL, completeness N/A (missing: unspecified) — "Governing boundary preserved across every proposed repair option for typing the humanActRef referent: software records authority but never manufactures it; a status-quo, actId-addition, or grantId-uniqueness fix all preserve this, while a fully structured act record (option c) is flagged as the one option that risks crossing into acting as a constructor of authority." (anchor: "Software records authority; software does not manufacture the authority from which its governance derives. Every option above preserves it. ... Option (c) is where the risk sits: a fully structured act record starts to look like a constructor.")
- [S1854] types=[GOVERNANCE] scope=THEORY-LEVEL, completeness N/A (missing: unspecified) — "Three previously-open decisions are removed from the dossier this pass: D-4 (Sigma stored vs derived) is closed by mathematical derivation leaving no choice, D-3 (conditional determination in scope) is substantially closed by corpus recovery of Step 157.22, and D-5 (restore R's fields) is folded into D-1 since R's shape depends on which relation-operations are declared mandatory." (anchor: "D-4 is Σ stored or derived? CLOSED BY DERIVATION — OR-merge and retract are jointly satisfiable only if Σ is derived (03 §A4). No choice remains. D-3 conditional determination in scope? SUBSTANTIALLY CLOSED BY RECOVERY. D-5 restore ℛ? FOLDED into D-1")
- [S1854] types=[GOVERNANCE] scope=THEORY-LEVEL, also labeled `o-core-ratification-gap-d0`, completeness N/A (missing: unspecified) — "Decision D-0 poses whether to ratify O_core (as-is, as a forced 14-operation lower bound, not at all/mark as HYPOTHESIS, or as the forced lower bound plus three forced-but-omitted operations Qualify/Determine/Derive), recommending options (b)+(d) as the only choice where every included member is entailed rather than merely asserted; separately flags the self-attested HPA authority strings as urgent regardless of which option is chosen." (anchor: "D-0 · Ratify 𝒪_core, or withdraw the claim that it is ratified. ... Options. (a) ratify 𝒪_sem^candidate as-is ... (b) ratify only the 14-operation forced lower bound ... (c) decline to ratify ... (d) ratify with the three forced-but-unenumerated operations added: Qualify, Determine, Derive. Recommen")
- [S1854] types=[GOVERNANCE] scope=THEORY-LEVEL, completeness N/A (missing: unspecified) — "Decision D-1: whether Transform, Merge, Split, Reintroduce are mandatory or optional operations, which determines what the universal quantifier over operations ranges over (hence whether congruence and global minimality are provable) and whether R must recover fields from the corpus's r=(E1,E2,T,R,Q,E,Sigma,tau) 8-tuple; recommends option (c) Transform-only or (d) open-ended-with-mandatory-core, with a caveat that restoring a bare, policy-unparameterized Sigma field into r would be ill-typed." (anchor: "D-1 · The four unforced operations, and with them ℛ's shape. Are Transform, Merge, Split, Reintroduce mandatory or optional? ... Recommendation: (c) or (d).")
- [S1854] types=[GOVERNANCE] scope=THEORY-LEVEL, completeness N/A (missing: unspecified) — "Decision D-2: whether to give the human act referenced by Grant.humanActRef its own identity (status quo; add actId while keeping free text; a fully structured act record; or make grantId globally unique only), recommending option (b) as the smallest change closing an already-realized production failure while extending rather than duplicating the existing Grant mechanism." (anchor: "D-2 · Type the referent of humanActRef? ... Recommendation: (b) — smallest change that closes a failure already on the record, and it extends the existing Grant rather than creating a second mechanism (ES-005.4).")
- [S1854] types=[GOVERNANCE] scope=THEORY-LEVEL, completeness N/A (missing: unspecified) — "Decision D-3' (the sole residue of D-3): whether Step 157.22's Method field references a rule instance or a rule version, recommending rule version since it composes with Policy=(id,version) and makes replay deterministic, flagged as low-stakes and deferrable." (anchor: "D-3′ · Residue only — rule instance or rule version? ... Recommendation: rule version — it composes with Policy=(id,version) and makes replay deterministic. Low stakes; defer if you prefer.")
- [S1854] types=[WARNING] scope=CROSS-OBJECT, completeness N/A (missing: unspecified) — "A standing governance item not owned by this pass: GN-73 flags that the policy-change loop has been closed twice by two different, unreconciled resolutions (ratified I-11+R-1 at v0.2/GN-19, and again by the 2026-08-30 audit), a material collision awaiting human/governance reconciliation." (anchor: "GN-73 records a MATERIAL COLLISION WITH THE RATIFIED LANE, raised by another session and awaiting you: the policy-change loop was closed twice, differently — ratified I-11 + R-1 (v0.2, GN-19) and again by the 2026-08-30 audit — "two unreconciled resolutions of one problem (ES-005.4)".")

## Notes for P3

- No internal tension, unknown-candidate marker, or contested-lifecycle discrepancy was observed in this label's own rows; evidentiary base is straightforward for its row count.
