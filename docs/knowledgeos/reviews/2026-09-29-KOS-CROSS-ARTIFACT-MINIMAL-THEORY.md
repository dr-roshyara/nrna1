# Cross-Artifact Concept Compression — Toward a Minimal Governed-Evidence Theory

**Date:** 2026-09-29. **Every artifact analyzed below shares one author** (`git log --format=%an`,
checked directly for each: `ADR-AIP-LOG-Platform-Rulings.md`, `PKS_Phase_IIC_C4_Architecture_Views.md`,
`docs/ARCHITECTURE.md`, plus the KOS research itself) — stated up front this time, not discovered
after an overclaim. **Independently reviewed (`kos-theory-reviewer`) — verdict `FAIL`**: real
counterexamples were found that the original counterexample search missed, the `RULE-H6-03`
"code-enforced" claim was overstated, a `Corruption Recovery` passage was misread, one quote was
misattributed, and — most seriously — the core abstraction as originally stated was shown to be
unfalsifiable (the word "automatic" absorbs every counterexample by redefinition). **All corrected
below, disclosed, not silently fixed.** This document does not claim domain-independent proof, and
after correction it claims considerably less than the first version did.

## Research question

> Is there a minimal common abstraction from which the recurring source/representation/derivation/
> authority separations follow, and does it survive active counterexample search within this
> (single-author) corpus?

## Corpus

KOS evidence-determination research (this session) · WP ruling log
(`engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md`, `R-30`–`R-56` read for the main
analysis, `R-61` additionally checked during this correction round) · PKS Phase
II.C C4 Architecture Views + referenced AD-1 principles (`docs/implementation/PKS_Phase_IIC_C4_Architecture_Views.md`)
· `docs/ARCHITECTURE.md` (real, production, projection-consistency rules `RULE-H6-02` through `H6-10`,
though only `H6-04`/`H6-06` are actually test-enforced) · Gate D's 5 persisted blind-review reports
across 3 cases (same-model reviewers, told upfront about shared authorship — **corrected: reading the
raw primary source, not "given no vocabulary"**, since the source text itself states the relevant
distinctions plainly).

## Recurring separations, with concrete source quotes (not paraphrased)

| Separation | KOS | WP (ruling log) | PKS (C4/AD-1) | `ARCHITECTURE.md` (H6) |
|---|---|---|---|---|
| Record ≠ substance/act | `GOLD-001`: grant recorded, act not covered | `R-53`: *"does not determine whether the gate was ACTUALLY EXECUTED"* | — | — |
| Authorization ≠ execution | `H4`/`H5` findings | `R-51`: *"authorization permits execution... it does not itself perform it"* (corrected attribution — misattributed to `R-47` in the original version) | AC-1 *"cannot self-issue"*, issuance trigger external | — |
| Representation/projection ≠ authority/source of truth | `H2` correction (citation ≠ authorization) | — | `AP-2`: *"nothing may cite a view as authority"*; AC-2 *"Holds NO source of truth · NO authority"* | `RULE-H6-03`: *"Projection tables are not sources of truth... disposable, rebuildable"* |
| Assessment/evidence ≠ authority | `INV-ATTR-1/2` | — | `AP-1`: *"Knowledge feeds authority; it never holds authority"* | — |
| Undefined ≠ permission to invent | — | — | `DP-3`/`DP-4`: *"Undefined remains undefined... Silence over invention"* | — |
| Derived artifact → provenance required | — | citation discipline throughout | `DP-5`: *"every graphical element shall have an architectural provenance"* | — |
| Deterministic ≠ authoritative/correct | — | — | — | `RULE-H6-06` (determinism, test-enforced) and `RULE-H6-03` (non-authoritative, prose-only) are two unconnected sections that never reference each other — the reading below is *consistent with* the document, not stated *by* it |
| Disclosed inconsistency > silent repair | this session's own erratum discipline | `R-52`: *"FLAGGED NOT CORRECTED... recorded rather than silently normalised"* | — | — |

## Candidate minimal abstraction (hypothesis, not conclusion)

> *A derived, recorded, projected, represented, or reconstructed artifact does not automatically
> inherit the semantic authority or epistemic status of the source from which it derives — and this
> holds independently of how faithfully, deterministically, or verifiably the derivation was
> performed.*

## Dependency / compression analysis

**Corrected caveat, per independent review**: the "Consequence" column below was built by classifying
each candidate as a derivation instance once "derived" was defined broadly enough to cover it — the
review correctly identifies this as assertion, not formal proof; no independent test separates what
counts as a "derivation" from what doesn't before the classification is made. Read the table as a
**working categorization hypothesis**, not a demonstrated logical reduction.

| Candidate (from the prior redirect's I1–I14) | Independent, or consequence of the minimal abstraction? |
|---|---|
| I1/I2 `AuthorityAct ≠ Record` | **Consequence** — record is a representation of the act |
| I8/I9 `Representation ≠ SourceOfTruth`/`≠ Authority` | **Consequence** — direct instance |
| I5 `ExecutionRecord ≠ IndependentExecutionEvidence` | **Consequence** — the record of execution is itself a derived artifact |
| I6/I7 `Evidence ≠ Assessment ≠ Authority` | **Consequence**, with an added premise: assessment is *also* a derivation step, from evidence |
| I12 `DerivedArtifact → ProvenanceRequired` | **Not a consequence — an independent, additional rule.** The minimal abstraction says a derived artifact lacks inherited authority; it says nothing about *requiring* provenance to be recorded. This is a real second, separate rule, confirmed in both `DP-5` (PKS) and this whole research line's own citation discipline. |
| I11 `Undefined ≠ PermissionToInvent` | **Independent.** This is about *absence* of a source, not about a derived artifact's relationship to a present source — a different logical shape, not a special case of the minimal abstraction. |
| I3 `Decision ≠ DecisionState` | **Partially independent.** `R-53`'s case (decision stands, evidentiary state undetermined) fits the minimal abstraction (the *state* claim is a derived/asserted artifact that doesn't inherit the decision's settledness). But "state" here also carries a temporal-tracking function the abstraction doesn't cover. |
| I13 (determinism ≠ authority) | **Candidate consequence** — corrected: `RULE-H6-03` and `RULE-H6-06` are two separate, unconnected sections of the same document; neither references the other. This reading is *consistent with* the document, not demonstrated *by construction* as the original version claimed. |
| I14 `GovernedTransition → ObservableGate/EvidenceRequirement` | **Independent** — a requirement about *how* governance must be structured (gates must be observable), not a claim about inherited authority. |

**Compression result, corrected and precisely counted**: **8 of the 14** originally-listed candidates
(`I1`, `I2`, `I5`, `I6`, `I7`, `I8`, `I9`, `I13`) were classified as instances of one minimal
abstraction — but that abstraction itself was just shown to be unfalsifiable as stated (see below), so
this compression is a **working hypothesis about how to group the candidates**, not a demonstrated
reduction. **3 (`I11`, `I12`, `I14`)** remain defensible as real, separate rules regardless of how the
central question resolves. **`I3` is partial** (fits in one respect, carries an uncovered
temporal-tracking function in another). **`I4` and `I10` were never addressed by this table at all** —
an honest gap, disclosed rather than silently dropped.

## Counterexample search — corrected: real counterexamples exist, and the original search stopped too early

**The first version of this search found nothing and treated that as confirmation. An independent
review found real cases in the same four documents by continuing to look — two genuine
counterexamples, and one case that, once fully quoted, turns out to weaken rather than support a
counterexample reading.** Verified directly against the repository before writing this correction
(not taken on the reviewer's word alone):

- **`R-61`** (`ADR-AIP-LOG-Platform-Rulings.md`, checked verbatim): *"The Governance Meta-Model
  Operational Validation is **accepted as the authoritative assessment** of the current governance
  model."* This is a direct, real case of an *assessment* — the exact category `AP-1` and `I6/I7`
  claim never holds authority — being explicitly granted authoritative status. **A genuine
  counterexample to an unqualified `assessment ≠ authority` reading**, missed by the original search
  (which only read `R-30`–`R-56`; `R-61` falls just past that window).
- **The C4 view's own promotion — corrected to a weaker, boundary case, not a clean counterexample.**
  The same status cell containing *"Now governing — no longer freely editable; further change runs
  through change control"* **also states, in the same breath**: *"A promoted view still holds NO
  AUTHORITY: DP-7 and AP-2 continue to govern — nothing may cite a view as authority... **C4-1 did
  not acquire authority because its source did.**"* Quoting "Now governing" alone, as the original
  correction did, was itself a selective quote. The honest reading: promotion changes the *change-
  control process* around the artifact (who may edit it, and how) without changing its *semantic
  authority* (what it may be cited for) — these are different senses of "governing," and the document
  itself keeps them apart. **This is a boundary case that weakens, not strengthens, the original
  counterexample claim** — recorded to show the search is now being applied honestly, not to inflate
  the count of real counterexamples.
- **`RULE-H6-04`'s practical shape**: `legitimacy` and `can_act` are served to consumers *only* from
  the projection table — in ordinary operation, the projection is the only thing any consumer ever
  actually sees or acts on, whatever `RULE-H6-03` declares about the domain being the "real" source
  of truth.

**Both genuine counterexamples (`R-61`, `RULE-H6-04`'s practical reliance) survive only through the
qualifier "automatic."** `R-61`'s assessment gains authority through an explicit ARB acceptance act,
not by inheriting it as a matter of course. **The C4 case is different in kind, not degree** — it is
not absorbed by "automatic" at all, because the document itself explicitly denies the view acquired
authority ("C4-1 did not acquire authority because its source did"); it was never a counterexample to
begin with, once read in full, only a same-word ("governing") coincidence between a process claim and
an authority claim. This is real, but it exposes the actual problem with the two genuine
counterexamples: **as originally worded, the abstraction is unfalsifiable** — any case where a derived
artifact *does* carry authority can always be re-described as "conferred by an
explicit act, not inherited automatically," with no independent test for which is which. The
original search's `NO COUNTEREXAMPLE FOUND` conclusions are withdrawn as premature, not because the
underlying observations were fabricated, but because the search stopped as soon as a comfortable
answer appeared rather than continuing past the initially-read range.

**Also corrected**: `RULE-H6-03` is **not** code-enforced — checked directly, only `RULE-H6-04`
(`test_query_path_does_not_import_domain_policies()`) and `RULE-H6-06`
(`test_same_event_stream_produces_deterministic_projection()`) have real automated tests; `H6-02`'s
"NOT authoritative domain truth" is a source-code *comment*, and `H6-03` is prose/YAML/a CLI command
only. The "single most explicit, code-enforced denial" claim is withdrawn. **The `Corruption
Recovery` reading is also corrected**: orphans are quarantined *because* they are structurally
invalid (a `parentId` pointing at nothing) — the document never separates structural integrity from
domain correctness the way the original version claimed; that was a misreading, not a real finding.

## Gate D reliability evidence (corrected: now persisted and checkable, and the framing narrowed from "corroboration")

**Correction**: the original version of this section quoted three Gate D reviewer excerpts that
existed only in conversation history, not in any file — an independent review correctly flagged that
these could not be verified. They are now persisted verbatim in
`2026-09-29-KOS-GATE-D-BLIND-REVIEW-TRANSCRIPTS.md`, checkable against that file.

**Correction to the claim itself**: the review also correctly notes that describing this as reviewers
recovering the distinction with "no vocabulary" overstates it — the raw ruling text itself uses this
vocabulary plainly (`R-53`: *"does not determine whether the gate was ACTUALLY EXECUTED"*), so a
reviewer restating it is real reading comprehension of a real primary source, not evidence of the
distinction being independently *discoverable* from weaker signals. **Corrected count**: 5 transcripts
exist, not 6 — two cases (`R-43`/`R-49`/`R-50`/`R-53` and `R-46`/`R-47`/`R-48`) each have two
independent readers in different reading orders; the third case (`R-44`) has only one reader so far,
the second having been lost to a rate-limit interruption and not yet re-run. What the 5 transcripts do
show, narrowly and accurately: for the 2 cases with two readers each, compatible, non-contradictory
characterizations of what the records say and don't say — a real, if modest, form of reliability
evidence for those 2 cases specifically, not corroboration of a domain-independent theory, and not
yet established for the third case.

## Minimal theory (substantially downgraded after correction)

```
CORE ABSTRACTION — STATUS DOWNGRADED FROM "SUPPORTED" TO "HYPOTHESIS, NOT YET FALSIFIABLE
AS STATED":
  Derivation(x, s) → ¬AutomaticInheritance(Authority(s), x)

  Real problem, found by independent review and confirmed directly against the repository:
  "Automatic" is undefined, and the two genuine located counterexamples (R-61's assessment
  gaining authority; H6-04's practical sole-reliance on projections) can be, and were,
  explained away as "conferred by an explicit act, not inherited automatically" -- with no
  independent test that could ever distinguish an "automatic" inheritance from a "conferred"
  one. (The C4 case, on closer reading, is not a counterexample at all -- see above -- but
  the unfalsifiability problem stands on the other two alone.) A hypothesis that absorbs
  every counterexample by construction has not yet been stated precisely enough to test.

WHAT SURVIVES, STATED NARROWLY (the reviewer's own suggested reformulation, adopted here):
  DESCRIPTIVE REGULARITY, not a theory: within this author's corpus, derived artifacts
  (records, projections, views, assessments) are repeatedly and explicitly DECLARED
  non-authoritative in their own governing documents, and where one is later treated as
  authoritative (R-61's assessment), that status is traceable to a separate, explicit,
  named act (an ARB acceptance) -- not to the act of derivation itself. This is real and
  checkable. It is not yet a falsifiable law, because "explicit act" has not been defined
  precisely enough to say what would count against it either.

INDEPENDENT ADDITIONAL RULES (status unchanged, not swept up in the above correction):
  DerivedArtifact(x) → ProvenanceRequired(x)
  Undefined(s) ↛ InventedStructure(s)
  GovernedTransition(t) → ObservableGate(t)

DETERMINISM/AUTHORITY CHAIN — corrected: NOT "code-enforced by construction" as originally
claimed. RULE-H6-06 (determinism) is test-enforced; RULE-H6-03 (non-authoritative) is prose
only; the document never states they are related, and this analysis's claim that it does was
a misreading of two adjacent-but-unconnected sections:
  Deterministic(x) ↛ Correct(x) ↛ Authorized(x) ↛ True(x)
  -- remains a defensible READING of the corpus, downgraded from "clean, real confirmation"
  to "consistent with, not demonstrated by, RULE-H6-06/H6-03."
```

## Kernel implications

**None.** Per standing instruction, no concept above is promoted to `KERNEL CANDIDATE`. **Corrected**:
8 of the 14 original candidates were *classified as* restatements of one idea under
a working categorization (§Dependency/compression analysis) — not *shown to be* such, since that
categorization was itself flagged as assertion rather than proof, and the idea they'd be restating is
the same abstraction just downgraded to unfalsifiable-as-stated. If the abstraction is later
reformulated precisely enough to be tested and survives, this compression would become a real,
disclosed finding for kernel work; as it stands, it is a hypothesis about how to group hypotheses.

## UNKNOWN — the real remaining gap, unchanged from the last redirect's own diagnosis

Everything above is same-author evidence, gathered across four artifact types, with 5 blind-reviewed
readings across 2 of 3 sampled cases providing modest reliability evidence (not corroboration) that
the raw text supports the readings given. This is still not the threshold the user's own Phase 4
named: a genuinely different author. That remains untested and is the actual next research frontier,
not a Kernel decision.

## Next smallest decisive experiment (corrected — a prerequisite step was missing)

**Before** searching for a different-author artifact, the actually smallest next step is to fix the
unfalsifiability problem this correction round surfaced: define "automatic" precisely enough that a
real counterexample (like `R-61`) and a real "conferred by explicit act" case can be told apart in
advance, not just after the fact. Without that definition, a different-author test would face the
same absorb-everything problem `R-61`/`H6-04` just exposed — it would not be a decisive
test yet, only a repeat of the same open question in new material. This is smaller, cheaper, and
more honest than jumping straight to the cross-author search.

---

**Traceability:** `2026-09-29-KOS-WP-GOVERNANCE-MECHANISM-RECONSTRUCTION.md` (source of the
same-author finding this document is built on, not repeating its mistake) · `docs/implementation/PKS_Phase_IIC_C4_Architecture_Views.md`,
`docs/implementation/PKS_Phase_IIB_Architecture_Definition.md` · `docs/ARCHITECTURE.md` (`RULE-H6-02`–`H6-10`,
`Corruption Recovery`) · `engineering/architecture/adr/ADR-AIP-LOG-Platform-Rulings.md` (`R-30`–`R-56` read for the
main analysis, `R-61` additionally checked in this
correction round, `R-61` specifically the counterexample that broke the original search)
· `2026-09-29-KOS-GATE-D-BLIND-REVIEW-TRANSCRIPTS.md` (persisted, checkable, replaces conversation-only
citations) · `2026-09-29-KOS-EVIDENCE-PROPOSITION-MATRIX.md` (source of the `H2` string-match-≠-semantics
discipline applied throughout).
