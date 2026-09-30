# sigma-gamma-reconstruction-after-policy

**Scope(s):** THEORY-LEVEL · **Row count:** 18 ·
**Lifecycle (candidate):** DORMANT · **Layer (provisional):** LAYER-UNRESOLVED
**Notations:** `Gamma:Assertion x GovCtx->GovState`, `Sigma:Assertion->(dir,str)`, `conflictsWith(a)`, `contests(b,a) in R`
**Aliases:** "Artifact 7 (mandate 20260830_1931)"
**Candidate group membership (NOT an identity claim):**
- G1684: co-occurs with `final-theory-gap-register` in the same contribution's labels[] 4 separate times across the corpus — relationship not yet decided (P3).
- G1685: co-occurs with `canonical-policy-model` in the same contribution's labels[] 5 separate times across the corpus — relationship not yet decided (P3).

## Sources (how this label entered the ledger)
- OBJECT-INDEX, batch B0040, scope THEORY-LEVEL: "The direct, executed resolution of gap G-1 (Sigma's shape), made possible by Policy's prior reconstruction (G-2): Sigma (epistemic, evidence-derived via Assessment(.,Policy)) and Gamma (governance, authority-act-derived) as two orthogonal dimensions whose pair strictly covers all five corpus assertion states plus the previously-inexpressible PF-6 residue; resolves where 'contest' belongs (a governance act, not evidence-derived)."

## Candidate births
- CANDIDATE-LEXICAL-BIRTH: [S1657 §"Expressibility probe — Omega_A = (Support, Acceptance, Commitment, Contest) target: Acceptance=Accepted AND Contest=Active (008 §11, board case) ... NO single state carries both. -> PF-6 residue CONFIRMED"]
- CANDIDATE-CONCEPTUAL-BIRTH: NOT-EVIDENCED-IN-CAPTURE
- CANDIDATE-FORMAL-BIRTH: [S1657 §"Σ : Assertion → (dir, str) EPISTEMIC — via Assessment(·, Policy) ... Γ : Assertion × GovCtx → GovState GOVERNANCE — via authority acts ... PF-6 dissolves..."]
- CANDIDATE-OPERATIONAL-BIRTH: [S1657 §"a value of Σ REFUTED — PF-6: it destroys the acceptance value it must coexist with ... a relation in ℛ VIABLE ... a governance state in Γ VIABLE ... Can a contest exist with NO contradicting evidence? YES..."]
- CANDIDATE-GOVERNANCE-BIRTH: [S1657 §"Is EpistemicStrength ≠ GovernanceStatus one dimension, two orthogonal dimensions, derived values, or context-specific assessments? TWO ORTHOGONAL DIMENSIONS, and BOTH ARE DERIVED..."]

## Lifecycle
last_seen: S1846 (2026-08-30). Candidate lifecycle: DORMANT. Evidence: no retraction, supersession, or self-contradiction recorded — heuristic based on recency of last use, not a confirmed retirement. All 18 rows are dated 2026-08-30 (a single dense research day), spread across 10 different source files.

## Completeness roll-up

| Dimension | Status | Source IDs |
|---|---|---|
| purpose_rationale | PRESENT | S1657, S1669 |
| informal_meaning | NOT-EVIDENCED-IN-CAPTURE | — |
| formal_definition | PRESENT | S1657 |
| type_signature | NOT-EVIDENCED-IN-CAPTURE | — |
| invariants | NOT-EVIDENCED-IN-CAPTURE | — |
| dependencies | PRESENT | S1657 ×4, S1658, S1659, S1663, S1664, S1668, S1669 ×2, S1671, S1673 |
| assumptions | NOT-EVIDENCED-IN-CAPTURE | — |
| semantics | PRESENT | S1657 ×2, S1659, S1664, S1669 ×2, S1673 |
| examples | NOT-EVIDENCED-IN-CAPTURE | — |
| warnings | PRESENT | S1663 |
| experiments | PRESENT | S1657 ×4, S1658, S1663, S1668, S1671, S1846 |
| open_questions | PRESENT | S1663 |

## Rationale
Two rows are classified rationale-bearing (both ARGUMENT type). The core problem: an earlier three/five-state epistemic-status model could not represent a real corpus case — "accepted AND actively contested" (the 008 §11 board case, "PF-6 residue") — because no single status value could carry both facts simultaneously [S1657]. The resolution required Policy to be reconstructed first: Sigma is literally the codomain of `Assessment`, and `Assessment` takes Policy as an argument, so Sigma's codomain was *undetermined* (not merely unknown) while Policy remained undefined; the same evidence under different policies yields different Sigma values, proving Sigma is a property of an assertion *under a policy*, not of the assertion alone — confirming the mandate's Policy-before-Sigma dependency ordering was correct [S1657]. A second, independent derivation (S1669) reaches the same non-derivability-from-assertion-alone conclusion via a different worked example (`Assess(A2,default)` vs `Assess(A2,strict-provenance)`), but is careful to note this does *not* prove Policy belongs inside K — K remains knowledge content, Policy remains an external condition governing interpretation [S1669]. The alternative this replaces is treating epistemic strength and governance status as a single collapsed vocabulary (the corpus's competing eleven epistemic-status vocabularies, S1663) — the Sigma/Gamma split is proposed specifically to dissolve that collapse cleanly, gaining strictly more expressiveness than the original five-state model by covering the PF-6 residue as an extra reachable coordinate pair rather than a lost case [S1657].

## Assumption register
NOT-EVIDENCED-IN-CAPTURE.

## All rows (source_id order)
- [S1657] types=[EXPERIMENTAL-RESULT, VALIDATION] — "The corpus's own executable reference file (ladder_dc_reference.py, dated 2026-08-28, contamination-free) independently confirms the exact same PF-6 defect the verifier found ... two independent derivations converging on one conclusion: a single epistemic value is insufficient." Also labeled `final-theory-gap-register`. (file `SIGMA-RECONSTRUCTION-AFTER-POLICY.md`, 2026-08-30)
- [S1657] types=[VALIDATION, EXPERIMENTAL-RESULT] — "Sigma-⊥-Gamma orthogonality is now proven via three mutually independent routes ... 10^6 units of evidence with no authority act still fails to commit, while a single authority act does commit — evidence and authority are non-substitutable at ANY magnitude."
- [S1657] types=[EXPERIMENT, DISTINCTION] — "Five candidate placements for 'contest' are tested ... a board member can contest an accepted claim on purely procedural grounds, offering zero counter-evidence ... proving contest is NOT evidence-derived but a GOVERNANCE ACT." Carries an embedded experiment: hypothesis "'contest' can be fully derived from evidence alone"; result "conflictsWith(e)=False while contested=True"; conclusion "contest is a governance act, not evidence-derived."
- [S1657] types=[FORMALIZATION] — "The derived resolution: Σ:Assertion→(dir,str) is epistemic ... Γ:Assertion×GovCtx→GovState is governance ... conflictsWith(a) is derived from evidence (self-conflict) while contests(b,a) is a relation in R representing third-party contest ... PF-6 dissolves cleanly into (Σ=(Supporting,Strong), Γ=Contested)." Also labeled `canonical-policy-model`. Dependency: `canonical-policy-model`.
- [S1657] types=[EXPERIMENTAL-RESULT, VALIDATION] — "Enumerated coverage check: the (Σ,Γ) pair represents all five of the corpus's original states ... PLUS the PF-6 residue ... strictly more expressive, by exactly the one case the corpus itself had identified as lost."
- [S1657] types=[RESTATEMENT, GOVERNANCE] — "Direct answer to mandate §10's question: Σ and Γ are TWO ORTHOGONAL DIMENSIONS, and both are derived, but from different sources ... resolving gap G-1." Also labeled `final-theory-gap-register`. Dependency: `final-theory-gap-register`.
- [S1657] types=[ARGUMENT] — "Explains why Sigma could not have been resolved before Policy ... Σ is a property of the assertion UNDER A POLICY, not of the assertion alone." Also labeled `canonical-policy-model`. Dependency: `canonical-policy-model`.
- [S1658] types=[EXPERIMENT, EXPERIMENTAL-RESULT] scope=METHODOLOGICAL — "A sixteen-attack adversarial falsification pass (per mandate §13) is run against the closed theory ... five genuine successes total, three fixed within this phase and two left open ... 'a falsification pass in which nothing succeeds has not been run properly.'" Also labeled `foundational-dependency-closure`, `final-theory-gap-register`. Dependencies: `sigma-gamma-reconstruction-after-policy` (self), `final-theory-gap-register`. (file `FOUNDATIONAL-DEPENDENCY-CLOSURE.md`)
- [S1659] types=[GOVERNANCE, RESTATEMENT] — "Five previously open gaps are confirmed CLOSED as of this mandate: Policy undefined ... Sigma underdetermined (closed via (dir,str)+Gamma, PF-6 dissolved) ..." Also labeled `policy-provenance-reflexivity-gap`, `assurance-concept-refutation-and-split`, `proposition-assertion-knowledge-hierarchy`. (file `NEXT-FOUNDATIONAL-GAP-AUDIT.md`)
- [S1663] types=[OPEN-QUESTION, CONSTRAINT] scope=OBJECT — "Requires investigating eleven competing corpus epistemic-status vocabularies through five possible resolutions ... before any vocabulary choice is escalated to the user." (file `prompts/20260830_1953_prompts.md`)
- [S1663] types=[EXPERIMENT, WARNING] — "Specifies the exact four-quadrant Sigma x Gamma counterexample-construction test ... with an explicit warning against inferring logical impossibility merely from a quadrant's absence in the current EKP dataset." Dependency: self.
- [S1664] types=[DISTINCTION] — "Two of the audit's sharpest distinctions: (1) structural validation is computable while Truth is not ... (2) TransitionValidity ≠ StatusAssignment ... one of the most important hidden boundaries in the whole theory." Also labeled `computability-audit-step266`. Dependency: self. (file `step_266_computability-audit.md`)
- [S1668] types=[EXPERIMENT] scope=OBJECT — "Sigma's implementation correspondence is undetermined, and a discriminating Test 12 is specified to resolve it empirically: whether the system stores Sigma ... or purely derives it from Assessment on demand." Also labeled `empirical-bridge-theory-implementation-correspondence`, `final-theory-gap-register`. Dependencies: self, `final-theory-gap-register`. (file `step_267_empirical-bridge-to-knowledgeos.md`)
- [S1669] types=[ARGUMENT, RESTATEMENT] scope=OBJECT — "Re-derives ... that Sigma cannot be derived from the Assertion alone ... K remains knowledge content, Policy remains an external condition." Also labeled `canonical-policy-model`. Dependency: self. (file `step_269_policy-semantics-and-the-final-formal-blocker.md`)
- [S1669] types=[PRINCIPLE, GOVERNANCE] — "CB-5:Σ and CB-6:Policy are not independent. They form: Policy → Assessment → Σ ... a major sequencing improvement." Also labeled `canonical-policy-model`. Dependency: self.
- [S1671] types=[EXPERIMENT, CONSTRAINT] scope=OBJECT — "A precise test for Sigma's policy-relativity is specified ... the theory must explicitly notate Sigma as Σ_π (policy-indexed) rather than pretending a policy-free epistemic status exists." Also labeled `canonical-policy-model`. Dependency: self. (file `step_270_adversarial-policy-evidence-assessment-closure-audit.md`)
- [S1673] types=[RESTATEMENT, LIMITATION] scope=OBJECT — "ARC D: the Epistemic-Status/Governance-Status orthogonality is confirmed CORPUS ESTABLISHES ... but the actual vocabulary membership remains UNRESOLVED." Also labeled `theory-evolution-map-independent-reconstruction`. Dependency: self. (file `01-THEORY-EVOLUTION-MAP.md`)
- [S1846] types=[EXPERIMENTAL-RESULT] scope=OBJECT, batch B0045 — "A5: ten constructed (Σ,Γ) cells are all independently meaningful ... requires Γ (governance) to sit outside Σ (epistemic) ... independently agreeing with 272B's Σ⊥Λ⊥Γ orthogonality claim." Also labeled `sigma0-minimal-four-state-derivation-d4-closure`. (file `consolidation/exec/sigma.py`)

## Notes for P3
This label documents a single, well-instrumented research episode (2026-08-30, "Artifact 7", mandate 20260830_1931) with unusually strong internal cross-validation: three independent routes reach the same Sigma⊥Gamma orthogonality conclusion (verifier's four-quadrant construction, the running EKP's `authorities.yaml`, and an executed 10^6-evidence corpus experiment), and a later, differently-dated executable file (S1846, batch B0045) independently reaches the same conclusion again via a distinct D4-closure derivation. This is one of the more evidentially strong labels among the 19 assigned here. Several dependency edges point to labels outside this family (`final-theory-gap-register`, `canonical-policy-model`, `foundational-dependency-closure`, `policy-provenance-reflexivity-gap`, `assurance-concept-refutation-and-split`, `proposition-assertion-knowledge-hierarchy`, `computability-audit-step266`, `empirical-bridge-theory-implementation-correspondence`, `theory-evolution-map-independent-reconstruction`, `sigma0-minimal-four-state-derivation-d4-closure`) — this label functions as a hub concept for the entire 2026-08-30 verification day and P3 may want to treat it as a load-bearing node when reconciling that day's work. `files_touching` also lists S1660 and S1667, not present in `family.rows` — additional context may exist there beyond what was captured under this working_label.
