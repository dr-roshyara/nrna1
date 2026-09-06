# II.4 · Conformance and the Living Repository

> **Edition 1 abstract** *(frozen baseline, verbatim opening)*: With an authorized model in hand,
> the programme asked the opposite question: does the *actual* repository — constitution,
> governance records, running code — conform to it? — *Full Edition-1 text:
> `../../../book/part-2-reconstruction/02-04-conformance-and-the-living-repository/chapter.md`.*

## 1 · The opposite question, and the rules that made it safe — *status: method [M]; rules currently ratified [R]*

[M] Falsification (II.3) attacked the model with its own materials. Conformance asked something
harder to keep honest: whether a *living repository* — a constitution, governance acts, running
code, all still growing — corresponds to the ruled model. The pass (3C) could not begin until a
scope-and-evidence plan was approved as its own governance act, and it ran under ruled
constraints: [R] a five-verdict vocabulary and nothing else (CONFORMANT · PARTIALLY CONFORMANT ·
NON-CONFORMANT · NOT ESTABLISHED · OUT OF SCOPE), exactly one verdict per tested relation; **no
silent upgrade of NOT ESTABLISHED**; evidence ⟦E⟧ separated from interpretation ⟦INT⟧, verdicts
resting on evidence alone; timestamped counts; an honest sampling residue; and the cardinal rule —
**3C tests the model and repairs nothing**. [M] The stance in one sentence: *difference is a
finding, not a defect* — a divergent repository falsifies neither itself nor the model until a
ruling says which, if either, must move.

## 2 · The first discovery was about words — *status: explicitly recorded [E], dated*

[E] Before any correspondence could be judged, the pass hit a vocabulary wall (CF-004): a grep
over the in-scope governed repository (2026-08-28) found the model's formal terms **absent** —
`Zero(` in 0 files, `EpistemicContract` 0, `AcceptancePolicy` 0, `IdealState` 0, `Decision
Contract` 0, the ladder wording 0. What is present — `Knower` (17 files), `Determination` (55),
`Committed` (62) — carries mostly other senses. [E] "Zero" itself turned out to hold a *third*
sense on the repository side (CF-005): Z-KOS-001's foundational meta-principle — *"Zero ≠
Component · Zero = Property of the boundaries"* — explicitly refused as a kernel article and as an
implementation object, incompatible with both the historical absence-lens and the model's goal-gap
function. [M] Consequence, stated by the register itself: **every conformance verdict below is
conceptual correspondence, never shared vocabulary** — and the register's Zero finding fed the
three-sense terminology ruling the reader met in Part III.

## 3 · Where the repository conforms — the boundaries — *status: repository acts explicitly recorded [E]; the correspondence readings are the register's ⟦INT⟧, never repository self-description*

[E] The model's authority boundaries turned out to be the repository's daily practice:

- **A6 in force (CF-007).** The Constitution's Article 3 — *"Authority SHALL be **assigned** — a
  recorded reference to a human act — never intrinsic, never emergent from content"* — plus a
  recorded pair of *separate human acts* for adoption versus authorization ("Recording is not
  deciding"), plus a running bootstrap script whose banner is a six-way inequality: *"IDENTITY ≠
  ROLE ≠ ELIGIBILITY ≠ AUTHORIZATION ≠ OWNERSHIP ≠ CONTINUATION."* Three independent carriers of
  the same boundary the falsification pass had just re-typed.
- **No-skip enacted (CF-008).** A subsystem sat implemented and fully GREEN (25 tests, 270
  assertions) yet held *"NOT ADOPTED · NOT VERIFIED"* because independent verification had not
  occurred — lifecycle states *"reached in order, by separate acts."* Behavior the register reads ⟦INT⟧ as the covering-relation
  discipline in operation, before anyone wrote it as an axiom; **as stated text, the axiom exists
  nowhere in the repository** — practice-conformant, axiom NOT ESTABLISHED.
- **Stratification in constitutional form (CF-011).** Engineering evolution free; constitutional
  amendment only by ratification, bounded, per a named discipline — policy-as-content versus
  policy-in-force, in the repository's own words.
- **The admission gate (CF-013), the action boundary (CF-014)** — a Verification Gate as *"the
  only admission path into knowledge"* with engines that may propose but never admit; and
  Article 4: *"Knowledge SHALL inform action; it SHALL not execute it."*
- **The no-scalar convergence (CF-016).** Article 2 forbids a knowledge-quality scalar as a
  surrogate; the experiment record had reached the same prohibition by a different derivation.
  Two roads, one wall.

## 4 · Where correspondence is not established — the formal layer — *status: explicitly recorded [E]; conclusion currently ratified [R]*

[E] The executable footprint in scope is a handful of read-only and diagnostic tools — the
largest 703 lines, fail-closed; a doctor that *"checks and reports — it never installs, never
repairs"* (CF-015). **No executable counterpart exists for any formal object of the model** — not
Zero, not EC, not η, not the ladder, not the Decision Contract; the DC six-tuple structures no
repository artifact (CF-014). The Knower conforms as first-class identity and final human
authority, but the model's stronger claim — Knower ownership of problem, purpose, IdealState — is
not evidenced in scope (CF-012, partial). [R] The ruled conclusion holds both halves at exact
strength: *the repository provides substantial evidence for the architectural boundaries and
governance principles, but does not establish implementation-level correspondence for several
formal objects* — with the methodological rider ruled in the same act: the thin footprint does
**not** license "therefore the model is wrong." NOT ESTABLISHED is a state, not a verdict of
failure — the pass's most repeated lesson.

## 5 · The seams, and how each was ruled — *status: explicitly recorded [E]; dispositions currently ratified [R]*

[E/R] Sixteen findings (CF-001…CF-016) entered a disposition gate; none was repaired in-line.
Four seams carried the weight:

- **The diverged branch (CF-001 → RESOLVED).** A parallel tree held a Reference Architecture v1.1
  the main tree lacked. The ruling's shape matters more than the instance: the branch is a
  *parallel authorized lane*, its artifacts holding the authority of their own lane's acts; the
  programme's authoritative tree is the one carrying its ruled record; and the rival artifact
  became a **named future input** — *"it may not be absorbed silently, and it may not be ignored
  silently."* Authority resolved per-lane, not by declaring a winner.
- **The stale banner (CF-003 → RESOLVED).** The repository's own constitution said "PROPOSED"
  while downstream records said "ratified" — the fourth sighting of II.3's failure family, on the
  implementation side. Resolution by evidence: a same-day record *"recorded as a retrospective
  ratification, not a re-commission"* covers the constitution-freeze step by name; the defect
  narrowed to status-synchronization only — the family's mildest form, exactly as II.3 reported.
- **The contradicting export (CF-010 → ADJUDICATED).** A repository CSV disagreed in three cells
  with the experiment's own prose record. The adjudication worked cell-by-cell and ruled on
  internal-consistency grounds, not preference: the prose prevails (self-consistent under its own
  footnote); the CSV — internally inconsistent under any single reading, carrying no generator
  and no provenance — was graded **UNRELIABLE-WITHOUT-ITS-GENERATOR**; the model-side registry
  was exonerated as a faithful transcription; the central negative verdict survives under either
  source; **neither artifact was modified**.
- **The richer state model (CF-006/CF-009 → FEED-INTO).** The repository operates first-class
  states the lean ladder lacks — REJECTED preserved, CONFLICTED coexisting until governed
  resolution, Unknown ≠ Absent ≠ False — and its own ladders correspond to the model's only
  structurally, with the mapping stated nowhere. Ruled not as a defect of either side but as an
  explicit *input* to the next architecture act — the disposition whose descendants the reader
  has already met as III.6's layered states.

## 6 · The residue kept honest — *status: explicitly recorded [E]; method [M]*

[E] The register ends by counting what it did **not** read: of 285 review-root files, ~12 read
and quoted, all grep-swept; most Tier-3 documents unread; every verdict marked NOT ESTABLISHED
*"wherever the unread residue could plausibly change"* it. [M] This is II.2's absence-claim
discipline load-bearing in the field: a conformance verdict over a partially read repository is
an absence-class claim, worth exactly the search that produced it — so the search itself is part
of the record.

## 7 · The pattern for reuse — *status: method [M]*

[M] For any programme testing a model against a living system: (1) approve scope before testing;
(2) fix a closed verdict vocabulary, one verdict per relation, and forbid silent upgrades of the
weakest verdict; (3) separate evidence from interpretation and let only evidence carry verdicts;
(4) treat difference as a finding for a ruling, never as an in-line repair; (5) route every seam
through an explicit disposition — resolve, adjudicate, feed-into, or hold; (6) publish the
sampling residue with the verdicts. **Relation-status ledger (BA-ED2-11):**

| Relation | Source | Ratified? | Formal status | Computable? | Tested? | Open issue |
|---|---|---|---|---|---|---|
| repository ↔ model (per-relation verdicts) | 3C register CF-001…016 | verdicts accepted; five dispositioned by ruling | five-verdict vocabulary, ⟦E⟧-carried | partly (greps re-runnable; counts dated 2026-08-28) | no (documentary + operational evidence) | vocabulary disjunction (CF-004) |
| boundary conformance (A6 · no-skip · stratification · gate) | CF-007/008/011/013/014 | evidence accepted | conceptual correspondence; mapping unstated in repository | partly | enacted-in-L5-practice | correspondence stated by the synthesis only |
| formal-object correspondence (Zero, EC, η, ladder, DC) | CF-015/014 | ruled NOT ESTABLISHED | counterparts absent | — | no | implementation lane open; not a defect verdict |

## 8 · Limitations — *status: register [U]*

No governed OQ owned. Standing: the verdicts are as-of dated (2026-08-28) against a repository
that keeps moving; the pass sampled where it could not read exhaustively, and says so; the
correspondence mapping between repository ladders and the model's is the synthesis's construction,
stated in no repository document; and the deferred-intake artifact remains exactly that —
deferred, its content untested by this programme and untouched by this book.

## 9 · Conclusion — Part II closes — *status: method [M], summary*

[M] Part II began in a corpus that refused premature architecture, watched a grading discipline
grow from that refusal, saw the ruled model survive its own attack through one preserved breach,
and ends here with the model held against the living repository — conforming where authority
crosses boundaries, unestablished where formality awaits implementation, and honest about which
is which. That last distinction is the part a reader should keep: the programme's strongest
results are not the correspondences it found but the exactness with which it recorded where
correspondence ends. In this book's order, Part III taught the architecture first and Part II has
now shown the ground it stands on; in history it ran the other way — the ground came first, and
the architecture was built on it.
