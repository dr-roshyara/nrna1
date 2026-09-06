# Edition 2 · Production Log (GN-42)

**Findings discovered during production are recorded here and routed to governance — never
repaired into upstream artifacts (DISCOVER → CLASSIFY → TRACE → REPORT → DISPOSITION).**

## PF-1 · 2026-08-28 · Zero's source status-set is richer than the ratified summary
⟦E⟧ 025d §25D.4 defines the satisfaction set 𝒮 = {Satisfied, PartiallySatisfied, Unknown,
Insufficient, Conflicted, Stale, Invalid, Prohibited, NotApplicable} (nine-valued) and Zero as the
per-requirement status VECTOR Zero = {(r_i, Z_i)}; §25D.7 additionally distinguishes Missing from
Unknown. The ratified concept row (v0.1→v0.2) summarizes this as "typed discrepancy
(unknown/conflicting/missing/invalid)". ⟦INT⟧ The ratified wording is a lossy four-arm compression
of a nine-status source. **Classification: FINDING for a future governance disposition** (options:
annotate the FA lineage; or leave — the compression is a synthesis choice, not an error).
**Edition-2 handling (allowed, explanation-only): III.4 teaches the full source set, quotes the
ratified summary verbatim, and states the compression explicitly. v0.2 untouched.**

## PF-2 · 2026-08-28 · "Why exactly eight primitives" upgrades to partially RECONSTRUCTABLE
⟦E⟧ Step 049 carries a genuine reduction argument (§49.2 core-vs-structures; §§49.3–49.29
per-concept reductions, e.g. Evidence = QualifiedObservation, Claim = Proposition, Identity =
EntityIdentityRelation; §49.30 candidate kernel 𝒦=(E,S,T,O,P,R,Π,A); §49.76 freeze). The
plan's derivation inventory had this as NOT ESTABLISHED. **Upgrade (evidence-based): the
reduction argument is RECONSTRUCTABLE; what remains open is only whether the reduction is
COMPLETE/minimal (the source itself says "candidate").** III.3 teaches it at exactly that strength.

## PF-3 · 2026-08-28 · Source-level open item found in the EXP-01 verdict
⟦E⟧ Verdict doc §9: *"What exactly constitutes epistemic equivalence?"* — an unresolved issue the
source itself flags. Not in the FA-6 register (it is corpus-internal, feeding OQ-3's context).
**Edition-2 handling: surfaced in III.5 as a source-level open item, distinguished from the
governed OQ register.**

## PF-4 · 2026-08-28 · EXP-01 design-to-execution property gap
⟦E⟧ The design document (20260827-134514) specifies TEN test criteria A–J (Duplicate invariance,
Independent corroboration, Order invariance, Associativity, Contradiction preservation, Dependency
awareness, Irrelevance, Temporal validity, Context dependence, Policy dependence), plus
**Calibration** as "the most important statistical test" and a conditional-independence caution.
The executed matrix (verdict 20260827-135038 + CSV) records outcomes for SEVEN columns (Duplicate
invariant, Independent corroboration, Dependency-aware, Irrelevance, Order invariant, Stale
retained, Bounded [0,1]) — "Bounded [0,1]" is not among the designed A–J; Associativity (D),
Context dependence (I), Policy dependence (J) and Calibration have NO recorded matrix outcome;
Contradiction preservation (E) is discussed narratively (verdict §5) but is not a matrix column.
⟦INT⟧ The ratified summary "7 properties" describes the executed matrix, not the designed suite.
**Classification: FINDING for future disposition** (candidates: EG-05/OQ-5 scope — the unexecuted
criteria resemble the defined-not-executed suite family). **Edition-2 handling (explanation-only):
III.5 teaches the designed suite AND the executed subset, with each criterion's actual evidentiary
status. No upstream artifact modified.**

## PF-5 · 2026-08-28 · Two further members of the PF-1 compression family (025d → ratified)
⟦E⟧ (a) 025d §25D.3 defines the contract as a PAIR: `EC_G = (R_G, Γ_G)` — required conditions PLUS
sufficiency-judgment rules; the ratified concept row renders EC as "goal-derived requirement set."
(b) 025d §25D.14 distinguishes TWO Zeros — operational `Z_W = Distance(W_t, W*)` (world-distance)
and epistemic `Z_K = Distance(K_t, K*_EC)` — "fundamentally different"; the ratified model carries
only the epistemic one (the operational twin touches the OQ-4 action side). ⟦INT⟧ Both are lossy
compressions of the same kind as PF-1 — synthesis choices, not errors. **Classification: FINDING
for the same future disposition as PF-1. Edition-2 handling: III.4 teaches the source structures
at source strength and quotes the ratified wording verbatim; nothing upstream modified.**
Also verified: 025d's own unresolved questions §25D.39 (ContractDerivation — the source-level
ancestor of η/OQ-1, with its list of requirement origins) and §25D.40 (RequirementGraph —
requirement dependencies), both recorded as source-level watch items, not new OQs.

## PF-6 · 2026-08-28 · The ratified linear ladder compresses a branched, finally multidimensional source model
⟦E⟧ Step 008 §2 defines the chain Candidate→Supported→Accepted→Committed WITH branches
Supported→Contested, Supported→Rejected, Candidate→Unresolved; §9 gives the acceptance function
α_ρ: EA×P×C → 𝒮_A with SIX statuses {Candidate, Supported, Accepted, Rejected, Contested,
Unresolved} and notes the output "is not necessarily a single linear state" (Supported+Contested);
§10 RECOMMENDS a multidimensional status Ω_A = (SupportStatus, AcceptanceStatus, CommitmentStatus,
ContestStatus); §16 boxes NotAccepted ≠ Rejected and Unresolved ≠ Rejected. The ratified row
carries the linear four-name chain (v0.1), re-typed by ruling to the 3-status ladder + Committed
boundary (R-3). ⟦INT⟧ Same compression family as PF-1/PF-5: the ratified ladder is the linear
spine of a branched source system whose own final recommendation was a 4-dimensional status
vector. Two nuances for the record: (a) the source's §11 ("epistemic status and governance status
should not be collapsed") independently SUPPORTS the R-3 boundary re-typing; (b) AF-006's era-split
framing ("the rich vocabulary is the older tradition") needs a nuance — the formal era's own
source also carried Rejected/Contested/Unresolved branches; the archaeology's claim that the two
vocabularies were never MERGED stands. Also noted: §10 reuses the symbol Ω for the status tuple
(vestigial, no relation to historical Ω — naming-register hazard only).
**Classification: FINDING for the same pending disposition family as PF-1/PF-5. Edition-2
handling: III.6 teaches the branched/multidimensional source at source strength, quotes the
ratified wording verbatim, shows the compression, decides nothing.**

## PF-7 · 2026-08-28 · The ratified DC six-tuple is the source's EARLY form; the source refines to seven
⟦E⟧ 042 §42.2 defines `DC(d)=(Pre,Inv,Auth,Post,Temporal,Evidence)` — the form the ratified row
carries verbatim. 042 §42.40 later refines it: `DC(d)=⟨P,I,A,E,Q,T,O⟩` adding **Q = epistemic
sufficiency** as its own component, with admissibility the six-fold conjunction P∧I∧A∧E∧Q∧T
(§42.41) and the boxed no-averaging rule (§42.10). ⟦INT⟧ Compression family: the ratified model
selected the early form; the source's own refinement separates epistemic sufficiency from evidence
requirements. **FINDING for the PF-1-family disposition; III.7 teaches both forms at their exact
strengths; nothing repaired.**

## PF-8 · 2026-08-28 · Two source selector roles compressed into one ratified Proposal
⟦E⟧ 025h §25H.2 defines TWO selector functions with distinct signatures: the epistemic-action
selector `(K,Z,G,C)→A` ("what to do next to improve the state") and the decision selector
`(K,G,D,C,P)→d` ("which decision is justified"), the latter operating over a
`DecisionModel=(Outcomes,Constraints,Preferences,Utilities,Uncertainty,Policy)` and returning an
auditable `DecisionResult` (decision + reason + blocking requirements + alternatives + evidence
basis + decision-model version + authorization note — §25H.11); §25H.1 boxes Decision ≠ Action.
The ratified model carries ONE Proposal selector (025g signature `(K_t,Z_t,G)`) plus DC; the
decision-selection role, the DecisionModel object, and the DecisionResult structure have no
distinct ratified counterparts (fragments live inside DC's components). Also verified: 025z §25Z.10
— Recommended(Action) and Authorized(Action) are INDEPENDENT in both directions; §25Z.11's
`Decision=(Options,Evidence,Constraints,ExpectedUtility,Risk,SelectedAction,Rationale)`; §25Z.1's
closed loop (the ratified action-loop row's source). ⟦INT⟧ Same compression family. **FINDING;
III.7 teaches the two-role source model at source strength, token-free per BA-3 (the roles'
lens-names stay in Part I.5); nothing repaired.**

## PF-9 · 2026-08-28 · The governance algebra's structures have no distinct ratified objects
⟦E⟧ The ratified Governance row is one line ("separate concern (own conflict algebra) whose
invariants cut across" — READ, 025f/042/121). The source algebra (025f) establishes, with formal
statements: authority as a CONTEXT-INDEXED PARTIAL ORDER (`s₁ ⪰_C s₂`; §§25F.14–15, argued against
any universal linear hierarchy); scope and temporal APPLICABILITY as preconditions of conflict
(`Conflict(s₁,s₂,C,t)` only when both Applicable and conclusions differ; §§25F.4–25F.6); a
resolution machinery (hierarchy, supersession, explicit exception; §§25F.7–25F.9); a THREE-OUTCOME
vocabulary Resolved/Unresolved/Invalid (§25F.20); the boxed ESCALATION LAW
`UnresolvedGovernanceConflict → HumanGovernance` with "knowing that something cannot be determined
is itself a valid computed result" (§§25F.18–19); Instruction ≠ AuthorizedDecision (§§25F.11–12);
`AIOutput cannot directly override governance` (§25F.13). Step 121 adds the VERSION-TRANSITION
SCHEMA (a new constitution version must carry change rationale, authority, effective date,
superseded version, verification — §121.47) and the RECURSION `Constitution ⊂
AuthoritativeKnowledge` (the constitution obeys its own provenance/authority/temporal/traceability
disciplines — §121.48, "recursive governance" §121.49). ⟦INT⟧ None of these has a distinct
ratified object; I-10/I-11 + the R-1 loop carry the load-bearing minimum. Same compression family.
**FINDING; III.8 teaches the algebra at source strength; nothing repaired.**

## Cross-reference · 2026-08-28 · Hostile pass identified two unregistered compression-family members
The GN-43 hostile pass found the PF family incomplete: **AF-F-9** (the four-vs-nine compression
also sits inside ratified invariant I-9's own text) and **AF-F-11** (025g's selector context-
argument C dropped between cited source and ratified Proposal row). Recorded in
`analysis/architecture-findings.md`; not duplicated here as PF entries; pending the same
disposition family. No repair.
