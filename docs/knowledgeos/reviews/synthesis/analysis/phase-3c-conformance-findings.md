# PHASE 3C · Conformance Findings Registry (D-2)

**Authority:** GN-22 (execution) under GN-21 (plan approval). **Date of all counts/verdicts: 2026-08-28.**
**Discipline:** ⟦E⟧ repository evidence · ⟦INT⟧ interpretation · findings only, no repairs. v0.2 unmodified.
**Gate A dispositions (GN-24, 2026-08-28)** are marked ▶ on CF-001, CF-003, CF-006, CF-009, CF-010;
all other findings retained as evidence/open questions. Dispositions are governance metadata —
original evidence and verdicts below are unmodified.

---

## CF-001 · Worktree divergence (triage, T-1 check of the plan)
**▶ DISPOSITION (GN-24): RESOLVE-BEFORE-FINAL-ARCHITECTURE · ▶ RESOLVED (GN-27, 2026-08-28)** — parallel authorized branch; programme tree = main; RA v1.1 = named Final-Architecture intake input. See phase-3c-dispositions-resolution.md.
⟦E⟧ `.claude/worktrees/kos-v11-ddd/docs/knowledgeos/` holds 340 files vs main tree 1,119; 881
divergent file-list lines. The worktree contains, absent from the main tree:
`20260822-1402-KOS-EP01-Reference-Architecture-v1.1-DDD-Bounded-Context-and-Core-Domain-Model.md`
(75,851 bytes), `20260822-1559-KOS-Expression-Meaning-Port-Contract.md`,
`20260823-2229-KOS-ADR-KnowledgeOS-Core-Today-and-Future-Epistemic-Brain-Vision.md`. The main tree
holds EKS-05/07/09/10/11 backlog items the worktree lacks. One worktree "file" is a pasted status
sentence (`architecture/Stopped and waiting. Nothing in flight;`).
⟦INT⟧ A **Reference Architecture v1.1** exists only in a diverged worktree while the main tree's
head is v1.0 — which tree is authoritative for the KnowledgeOS reference architecture is
**not established by any record found in scope**. Content untested (OUT OF SCOPE per plan).
**Kind: scope/authority ambiguity. No repair proposed.**

## CF-002 · `docs/eks/` triage
⟦E⟧ Contains exactly one file whose filename is a truncated chat sentence ("Yes — **we have worked
substantially on"), 19,451 bytes, mtime 2026-08-24. ⟦INT⟧ Session paste residue, not a governed
artifact. **Triaged OUT OF SCOPE.**

## CF-003 · Constitution v1.0 in-force status is ambiguous
**▶ DISPOSITION (GN-24): RESOLVE-BEFORE-FINAL-ARCHITECTURE · ▶ RESOLVED (GN-27, 2026-08-28)** — ratification act located (retrospective ratification 20260822-1028); defect = stale banner only. See phase-3c-dispositions-resolution.md.
⟦E⟧ The Constitution (`architecture/20260822-0951-…`) declares itself *"PROPOSED · EVIDENCE-BASED ·
NON-AUTHORITATIVE — pending HPA ratification."* The Reference Architecture v1.0 traceability says
*"after Constitution v1.0 **(ratified)**"*. The Zero synthesis record (`reviews/20260822-1130-…`)
states *"**Constitution:** RATIFIED (no change from Zero)"*. **No dedicated ratification act for the
Constitution itself was found in scope** (ratification acts exist for research-phase closure
`20260822-1028` and Z-KOS-001 `20260822-1115`).
⟦INT⟧ The in-force status of the repository's own constitutional policy is asserted downstream but
not carried by a locatable governed act — the same failure family v0.2's F-1/I-11 guards against
(corpus sightings: Step 121, CON-06, F-1; this is a **fourth, implementation-side sighting**).
**Kind: governance-record gap. Finding only.**

## CF-004 · Vocabulary disjunction — v0.2's formal terms are absent from the repository
⟦E⟧ grep over in-scope `docs/knowledgeos/` (excl. brainstorming, synthesis, kernel), 2026-08-28:
`Zero(` 0 files · `EpistemicContract`/`Epistemic Contract` 0 · `AcceptancePolicy` 0 · `IdealState` 0 ·
`Decision Contract` 0 · `SufficientKnowledge` 0 · ladder wording (`Candidate → Supported`,
`Supported → Accepted`) 0. Present: `Knower` 17 files · `Determination` 55 · `Committed` 62 (senses
vary; sampled senses are mostly non-technical or other-technical).
⟦INT⟧ Every conformance verdict below is **conceptual correspondence**, never shared vocabulary.
**Kind: nomenclature gap between synthesis model and repository.**

## CF-005 · "Zero" naming collision — a third sense
⟦E⟧ Repository Zero (Z-KOS-001, `reviews/20260822-1100/1115/1130`): a **foundational
meta-principle** — *"not a new kernel concept … Zero is the name for that prevention"*, *"Zero ≠
Component · Zero = Property of the boundaries"*, explicitly **refused as kernel article and as
implementation object**. v0.2 Zero: the computable goal-gap function `Zero(K, EC)`.
⟦INT⟧ Same term, incompatible senses — extends lineage CON-01 (absence-lens → goal-gap) with an
implementation-side third sense (neutral-reference/anti-collapse). **No repository counterpart of
the goal-gap function exists.** Also bears on T-9: no evidence for any G-residual inside a
Zero-like object — the η-totality question **stays open, unchanged**.

## CF-006 · Status-ladder correspondence is structural, not nominal
**▶ DISPOSITION (GN-24, HPA, 2026-08-28): FEED-INTO-FINAL-ARCHITECTURE** (jointly with CF-009) — explicit Final-Architecture input; NOT an authorization to modify v0.2 now.
⟦E⟧ Repository ladders actually operated: research rows `Candidate → Established/SELECTED →
PROPOSED → RATIFIED` (P4/P5/step-⑤ chain; register "25 candidates + 4 Established"); asset
lifecycle `implemented → independently verified → ADOPTED → AUTHORIZED for future use` (§38 "four
states … reached in order, by separate acts"). Expression strength is status-gated: ⟦E⟧ *"As a
**Candidate**, the P4-map voice is **'should'** — the final SHALL is earned by the P5
domain-independence test"* (Character_Definition §15).
⟦INT⟧ Structurally corresponds to `Candidate → Supported → Accepted` + Committed-as-boundary; no
repository document states the mapping. **Kind: correspondence unstated; vocabularies disjoint.**

## CF-007 · Authority boundary (A6) — conformant, multiply evidenced
⟦E⟧ Constitution Art. 3: *"Authority SHALL be **assigned** — a recorded reference to a human act —
never intrinsic, never emergent from content"*; adoption vs authorization decided by **separate
human acts** (`2026-08-23` pair; *"§38 forbids collapsing the two"*, *"Recording is not
deciding"*); `session-bootstrap.php`: *"IDENTITY ≠ ROLE ≠ ELIGIBILITY ≠ AUTHORIZATION ≠ OWNERSHIP ≠
CONTINUATION"*, identity *"evidence-only … reported, never a grant"*, G-3 requires a **human START
act**. ⟦INT⟧ This is v0.2's A6 and decision-boundary re-typing (R-3) operating in practice.

## CF-008 · No-skip (I-12) — conformant in practice, absent as stated axiom
⟦E⟧ AST-019: implemented and GREEN (25 tests/270 assertions) yet held *"NOT ADOPTED · NOT
VERIFIED"* because independent verification had not occurred; the three adopted layers *"reached in
order, by separate acts, with independent verification between implementation and adoption."*
⟦INT⟧ Covering-relation behavior enforced operationally. No repository document states a general
no-skip axiom. **Kind: practice-conformant; axiom NOT ESTABLISHED as text.**

## CF-009 · Repository's epistemic state model is richer than v0.2's ladder
**▶ DISPOSITION (GN-24, HPA, 2026-08-28): FEED-INTO-FINAL-ARCHITECTURE** (jointly with CF-006) — explicit Final-Architecture input; NOT an authorization to modify v0.2 now.
⟦E⟧ Articles 7–9 + Reference Architecture §5: first-class states `UNKNOWN · ABSENT · FALSE ·
VALIDATED · QUESTIONABLE · REJECTED · CONFLICTED`; REJECTED preserved (Art. 7), CONFLICTED coexists
until governed resolution (Art. 8), Unknown ≠ Absent ≠ False (Art. 9).
⟦INT⟧ v0.2's three-status ladder + Committed boundary has **no counterparts for failure
(REJECTED), conflict (CONFLICTED), or unknown/absence states** (v0.2 carries absence only in Zero
lineage). **Kind: possible v0.2 coverage weakness — recorded, no repair proposed.**

## CF-010 · EXP-01 cross-check — cell-level discrepancies
**▶ DISPOSITION (GN-24): ADJUDICATE · ▶ ADJUDICATED (GN-27, 2026-08-28)** — prose verdict doc prevails; CSV unreliable standalone; registry exonerated; neither source modified. See phase-3c-dispositions-resolution.md.
⟦E⟧ `tests/experiments/knowledgeos_evidence_calculus_property_tests.csv`: BAYES_LIKE row has
`Irrelevance = False`; WEIGHTED_MEAN row has `Duplicate invariant = True`. Model-side registry
(EXP-01) records *"Bayesian-like — passes all 7"* and *"Weighted Mean — fails duplicates,
corroboration, dependency"*. SATURATING passes all 7 in both.
⟦INT⟧ At least two cells conflict between the repository CSV and the model-side record (whether the
registry misread the corpus verdict doc, or the corpus prose disagreed with its own CSV export, is
**not adjudicated here**). Note also: the CSV **alone** does not entail the corpus verdict *"no
simple scalar operator is sufficient"* (SATURATING passes all seven tested properties); the verdict
rested additionally on the dependency-first argument. v0.2's **non-claim** (no operator selected)
is itself conformant — neither side selects one. **Kind: evidence discrepancy. Finding only.**

## CF-011 · Policy stratification & versioning (R-1/I-11) — conformant in content
⟦E⟧ Constitution Ch. V: engineering evolution free (V.1) vs constitutional amendment *"ratified by
the HPA"* required (V.2), amendments bounded (V.3), ratification per the step-⑤ discipline (V.4).
⟦INT⟧ Exactly v0.2's policy-as-content vs policy-in-force stratification in constitutional form.
Caveat: CF-003 shows the in-force **tracking** of that very policy is ambiguous in practice.

## CF-012 · Knower (I-1) — partially conformant
⟦E⟧ Art. 10 (*"Who knows?" SHALL always be answerable*); H-KOS-Agent-001: Knower's *"identity,
standpoint, and epistemic authority … constitutionally protected and inseparable from the knowledge
object"* (Kṣetrajña lineage explicit). HPA holds final authority throughout the record.
⟦INT⟧ Knower-as-first-class and final-human-authority conform. v0.2's I-1 additionally asserts the
Knower **owns problem, purpose, and IdealState** — goal-ownership is **not evidenced** in scope.

## CF-013 · Admission gate (Determination/AcceptancePolicy) — conceptually conformant
⟦E⟧ Verification Gate = *"the **only admission path** into knowledge"*; engines *"may propose — the
Verification Gate decides admission"*; *"Generation SHALL NOT be justification"* (Art. 6).
⟦INT⟧ Corresponds to v0.2's governed admission (Determination under AcceptancePolicy) and the
evidence ≠ acceptance distinction. The repository's gate vocabulary (VALIDATED/QUESTIONABLE/
REJECTED) is engine-verdict, not epistemic-ladder — see CF-006/CF-009.

## CF-014 · Decision boundary & action (DC / 042 triple) — boundary conformant; DC object absent
⟦E⟧ Art. 4: *"Knowledge SHALL inform action; it SHALL **not execute** it. The boundary … SHALL be
governed, never implicit."* Decision Interlock *"terminates knowledge flows at recommendation."*
⟦INT⟧ Conforms to `SufficientKnowledge ≠ ValidDecision ≠ AuthorizedAction` and to v0.2's governed
crossing. The **formal DC(d) 6-tuple has no repository counterpart** (no artifact structures
decisions as Pre/Inv/Auth/Post/Temporal/Evidence). v0.2's open action/execution question gains
boundary-side support (execution is outside knowledge scope) but remains open.

## CF-015 · Executable footprint is minimal relative to the model
⟦E⟧ Executable KnowledgeOS code in scope: `KnowledgeOsDoctor.php` (126 lines, observation-pipeline
diagnostics; *"It checks and reports — it never installs, never repairs"*), `KnowledgeOsInitPlanner.php`
(36), `operating-model.php` (read-only presenter, ADOPTED·AUTHORIZED), `session-bootstrap.php` (703,
read-only, fail-closed), their tests.
⟦INT⟧ No executable counterpart exists for any v0.2 formal object (Zero, EC, η, ladder, DC).
Implementation-level conformance for the formal layer is **NOT ESTABLISHED** — the conformant
material is documentary (constitution/architecture) and operational (governance acts, bootstrap).

## CF-016 · No-scalar convergence (Article 2 ↔ EXP-01 consequence)
⟦E⟧ Art. 2: *"no single score SHALL replace epistemic structure (a 'knowledge quality' scalar is a
forbidden surrogate)"*; failure signature *"Knowledge Quality = 0.87"*.
⟦INT⟧ Independently converges with v0.2's recorded EXP-01 outcome (no simple scalar suffices) —
two different derivations, one prohibition. **Conformant.**

---

**Sampling residue (§4 rule, honest):** reviews-root 285 files — read/quoted ~12, grep-swept all;
architecture/ 31 — read 5 core + structural skims, grep-swept all; Tier 3 root corpus ~58 — read
Character_Definition §15 + Meta_Model structure + grep sweeps; NOT read: the remaining reviews-root
chains, most Tier-3 docs, all puml diagrams' content. Verdicts marked NOT ESTABLISHED wherever the
unread residue could plausibly change them.
