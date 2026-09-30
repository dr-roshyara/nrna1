# EKS/PKS Research Review — Adversarial Quality-Control Pass

**Date:** 2026-09-29. **Reviewer stance:** independent, read-only, adversarial-by-design. This
review re-verifies cited evidence at its source (files, `git log`, JSON ledgers, arithmetic) rather
than trusting the seven documents' own citations. The corpus under review is evidence of what a
prior session concluded — not authority. Nothing here modifies the seven original documents.

**Documents reviewed** (all `docs/knowledgeos/reviews/`, committed together at 2026-09-29
09:25:05+02:00, commit `19d3cb7fa`):

1. `2026-09-29-KOS-EKS-RESEARCH-DEFINITION.md` (127 lines) — **EKS-RD**
2. `2026-09-29-KOS-PKS-RESEARCH-DEFINITION.md` (116 lines) — **PKS-RD**
3. `2026-09-29-KOS-EKS-PYTHON-ARCHITECTURE.md` (90 lines) — **EKS-PY**
4. `2026-09-29-KOS-PKS-PYTHON-ARCHITECTURE.md` (71 lines) — **PKS-PY**
5. `2026-09-29-KOS-EKS-PKS-BOUNDARY.md` (51 lines) — **BOUND**
6. `2026-09-29-KOS-EKS-PKS-KERNEL-RELATION.md` (132 lines) — **KERN**
7. `2026-09-29-KOS-EKS-PKS-EVIDENCE-MATRIX.md` (41 lines) — **MATRIX**

628 lines total, 7 files, all produced in one commit.

---

## 1 · Executive findings

1. **The tagging discipline (`OBSERVED`/`EXISTING DESIGN`/`DERIVED`/`HYPOTHESIS`/`UNKNOWN`) is
   applied more carefully than most research documents of this kind** — genuinely open items
   (`OQ-11`, the PKS Python-architecture question, kernel Question E) are left open rather than
   resolved by narrative momentum, and the `K5=(S,A,R)` out-of-scope disclosure in `KERN` is honest
   (verified: that notation appears nowhere else in the repository). This is worth crediting, not
   just auditing.

2. **However, the single most load-bearing empirical claim in the whole set — the `Fuse`
   evidence-fusion 6-commit result cited in `KERN` Question G and `MATRIX` #15 — is already stale
   as of the moment this review was written**, superseded by same-day, later work
   (`2026-09-29-KOS-evidence-fusion-model.md` §11, and the wholly separate
   `2026-09-29-KOS-PROTECTED-ARTIFACT-SWEEP.md`) that the seven reviewed documents could not have
   seen (git timestamps confirm the correction postdates the reviewed documents' commit). The
   corrected finding is materially different from what is cited: `Fuse`, run correctly, would have
   **missed** the `9f83a369c` violation (`UNCITED`, not `CONTRADICTED`), and the "Observation"
   kernel candidate that `KERN` reports as `OBSERVED`/confirmed has since been explicitly
   **withdrawn** as an empirical kernel finding by the same research line. This is not a defect in
   how `KERN`/`MATRIX` were written — it predates the correction — but it means both documents
   **currently assert something the corpus's own later evidence contradicts**, and a reader who
   trusts only the seven reviewed documents will be misled.

3. **The "third independent witness" claim for the Authorization/commitment-gate kernel candidate
   (`KERN` Question F) does not survive reading its own cited source.** `KERN` states the 2026-08-21
   Epistemic Investigation "used **neither** EKS's nor PKS's vocabulary." Read directly, that
   document's headline finding (`Confidence ≠ Authority`) is derived explicitly from **EKS's own
   `AP-1`** and **PKS's own `AP-1`/`MCR-5`** — the identical governing texts already counted as
   evidence for the same kernel candidate elsewhere in this document set. This is not independent
   confirmation; it is the same principle cited under a third label. See §7 for the exact quotes
   and the circularity this produces.

4. **The `EKS-RD` "20 real grants... OBSERVED" claim is stale, and the `OBSERVED` tag is stretched
   to cover it.** Re-counted directly against the live `.claude/runtime/workflow/*.json` ledgers
   (22 work-item files, not 9): **144 grants exist today, all 144 with `humanActRef`, all
   `registeredBy: governance`.** The underlying *principle* ("the mechanism records authority; it
   does not grant authority") is, if anything, **more strongly supported now** than the "20/20"
   figure the document cites — but the specific count and its "verified directly... `OBSERVED`"
   framing are a citation from a different document dated a month earlier (`KOS-ARCH-BASELINE-001`,
   2026-08-15/17), not a fresh measurement "this session," despite the tagging legend's "measured
   directly, this session or earlier this session" wording being used to justify exactly this kind
   of cross-session reuse. See §5 and Claim Inventory row EKS-RD-2b.

5. **The seven-document set is internally well cross-referenced but materially redundant.** `MATRIX`
   is, by its own stated purpose, a re-derivation-free index of the other six documents' claims —
   real traceability value, but near-total content overlap. `PKS-PY` adds almost nothing beyond
   "if Reading B, see `EKS-PY`" and a restatement of PKS's own governance principle already quoted
   in `PKS-RD`. `BOUND` and `KERN` both re-state "`OQ-11` remains open" and both cite the same
   underlying evidence from different angles without materially disagreeing. A minimum document set
   preserving all real content is roughly half the current count (§8/§9).

6. **No fabrication was found.** Every direct quotation checked against its cited primary source
   matched (word-for-word or near-verbatim) in every case tested (EKS/PKS definitional quotes,
   README template language, `AP-8`, `PYTHON-FIRST-POLICY`, grant-ledger figures for their stated
   date). The problems found are about **evidence currency, tag discipline, and unacknowledged
   circularity/dependency chains** — not invention.

---

## 2 · Claim inventory (Task 1)

Classification is my own re-derivation, not copied from the documents' own tags. Where my
classification matches the document's own tag, I say so; where it differs, I explain why.

| ID | Document | Claim | Evidence cited | Classification (verified) | Confidence |
|---|---|---|---|---|---|
| EKS-RD-1 | EKS-RD | "Engineering Knowledge System" definition quote | `20260821-2033-what-eks-is-today.md` | `EXISTING DESIGN` — quote verified verbatim in source, line 47 | High |
| EKS-RD-2a | EKS-RD | "The mechanism records authority; it does not grant authority" (the principle) | 20 grants, all `humanActRef` (as of 2026-08-17) | `DERIVED` — re-verified by me against the **current** full ledger (144/144 grants carry `humanActRef`, `registeredBy=governance` 144/144); principle holds and is now better-supported than cited | High |
| EKS-RD-2b | EKS-RD | "verified directly against 20 real grants... `OBSERVED`" (the specific count, framed as a direct/current measurement) | same as above | **`CONTRADICTED`** — live count is 144 grants across 22 work-item files, not 20 across 9; the "20" figure is a citation of `KOS-ARCH-BASELINE-001` (2026-08-15/17), not a fresh count; the `OBSERVED` tag is used to cover a month-old, external, unre-verified figure | High |
| EKS-RD-3 | EKS-RD | 6 declared bounded contexts on paper; 2 of 8 registered components have no assets, 2 more partial/by-reference | `KOS-ARCH-BASELINE-001` v1.1 (accepted 2026-08-17) | `EXISTING DESIGN` — verified near-verbatim against `docs/publicdigit/reviews/2026-08-15-KOS-ARCH-BASELINE-001-phase-a-current-architecture-baseline.md` v1.1 banner and §1/§6.4 (`CMP-003`, `CMP-006` no assets; `CMP-005`, `CMP-007` partial) | High |
| EKS-RD-4 | EKS-RD | "governed session orchestration" is a de facto 7th area, classified `Inferred`, not decided | same baseline, §3.2/§10 (U-3) | `EXISTING DESIGN` — verified verbatim ("both readings defensible... Phase A does not choose") | High |
| EKS-RD-5 | EKS-RD | EKS is "at least two structurally separate, real, working systems" (capability layer vs. Observation Runtime), confirmed by `grep -rl "Cohesion" scripts/observations/*.php` returning empty | direct grep | `OBSERVED` — re-ran the grep myself: exit code 1, zero matches. Confirmed independently | High |
| EKS-RD-6 | EKS-RD | `IdentifierIntegrity/README.md` calls itself "the template for every future Engineering Knowledge capability" | README quote | `EXISTING DESIGN` — verified verbatim, README.md line 3 | High |
| EKS-RD-7 | EKS-RD | Closed verdict vocabularies (`PASS/FAIL/WARN/INCONCLUSIVE` and `SUPPORTED/PARTIALLY_SUPPORTED/NOT_SUPPORTED/INCONCLUSIVE`) are "two independently-arrived-at but structurally identical" vocabularies | code | `OBSERVED` for existence (verified both vocabularies exist verbatim in `AssessmentService.php` and capability code) — **`UNKNOWN`/unverified for "independently arrived at"**: no evidence in the corpus was found, by this review or by the source document, that actually establishes the two vocabularies were designed without cross-influence, only that they now look alike. The document asserts independence it did not check. |
| EKS-RD-8 | EKS-RD | `AP-8` fail-closed discipline quote | README | `EXISTING DESIGN` — verified verbatim ("absence of evidence is never `PASS`") | High |
| PKS-RD-1 | PKS-RD | PKS core definition quote | `20260728_1710_what_is_pks_v1.md` | `EXISTING DESIGN` — verified verbatim, source §1.1 / Executive Summary | High |
| PKS-RD-2 | PKS-RD | "Not documentation, not software... does not require PHP, Python..." | same source | `EXISTING DESIGN` — verified verbatim, §5.1 | High |
| PKS-RD-3 | PKS-RD | 17 named knowledge concepts (`G-1`…`G-17`) | same source | `EXISTING DESIGN` — verified: all 17 rows present and match exactly, §3.1 | High |
| PKS-RD-4 | PKS-RD | PKS's measured reality is "executing PHP code... identical to EKS's capability layer" | `PKS-Current-Architecture-Baseline-Stage-2.md` | `OBSERVED` at the code-identity level (same directory, verified) — but see §7: this same fact is later used, without disclosure of the tension, both as evidence *for* PKS/EKS being independent validation domains (`KERN` Q.C) and as evidence they might be the *same system* (`BOUND`). The underlying fact is solid; its double duty across documents is not disclosed anywhere in the set. | Medium (fact solid, framing consistency weak) |
| PKS-RD-5 | PKS-RD | Governance-before-implementation: "No decision is final without human approval. AI surfaces governance questions; it does not resolve them." | same source | `EXISTING DESIGN` — verified verbatim, §7.3 | High |
| PKS-RD-6 | PKS-RD | PKS's own maturity is self-declared provisional (Phase II.A, M0 complete, M1 in progress) | same source | `EXISTING DESIGN` — verified verbatim, §6.1/§6.2 | High |
| EKS-PY-1 | EKS-PY | `Domain/Application/Infrastructure/Tests` used successfully 4 separate times, documented as mandatory template | README | `EXISTING DESIGN` — directory layout verified for `IdentifierIntegrity`, `ReferenceIntegrity`, `VocabularyIntegrity` (all have `Domain/Application/Infrastructure/Tests`); **`Cohesion` does not** — it has `Domain/Application/Infrastructure` only, with its tests located separately at `tests/Unit/Cohesion/` (top-level, not capability-local). The "4 separate times, same template" claim is therefore **imprecise**: 3 of 4 match the template exactly; Cohesion is a structural variant, not flagged as such in `EKS-PY` | Medium — the underlying quote is real, but the "4 times, same template" generalization overstates uniformity |
| EKS-PY-2 | EKS-PY | Cohesion's `Infrastructure/Php`/`Infrastructure/Python` split is real, cross-language tested, 11 mechanisms, 0 semantic divergences | this session's own D-1 work | `OBSERVED`, partially — directory split confirmed real (`Infrastructure/Php`, `Infrastructure/Python` both exist). The "11 mechanisms / 0 semantic divergences" figure traces to `2026-09-28-KOS-evidence-derived-architecture-research.md` §8, whose actual wording is **"9/11 byte-identical, 2/11 legitimate spelling differences with identical decisional fields"** — i.e. 2 of 11 were *not* identical, only decision-equivalent. `EKS-PY`'s "0 semantic divergences" compresses this correctly in substance but loses the "2/11 differed in spelling" nuance a reader would want | Medium |
| EKS-PY-3 | EKS-PY | `PYTHON-FIRST-POLICY.md` quote, motivated by 2 of 6 symmetric bugs (`R5`, `OWD-5`) | policy doc | `EXISTING DESIGN` — verified verbatim, both the "primary language" sentence and the "symmetric" bug claim, `2026-09-28-KOS-PYTHON-FIRST-POLICY.md` lines 11, 59 | High |
| EKS-PY-4 | EKS-PY | Python adapter (`extract_facts.py`) deliberately not a transliteration — uses `ast` module vs `nikic/php-parser` | source file | `OBSERVED` — verified: `extract_facts.py` line 10 states exactly this rationale | High |
| PKS-PY-1 | PKS-PY | PKS Python architecture "close to a category error" given spec-only identity | reasoning from PKS-RD | `DERIVED` — internally consistent, correctly tagged, no over-claim found | High |
| PKS-PY-2 | PKS-PY | "This document takes no side" between Reading A/B | self-declared | `EXISTING DESIGN`-equivalent (an honest non-conclusion) — verified: the document genuinely does not force a resolution | High |
| BOUND-1 | BOUND | Comparison table (EKS vs. PKS dimensions) | derived from EKS-RD/PKS-RD | `DERIVED` — spot-checked several cells (Input, Output, State, Persistence rows) against the two research-definition docs; consistent, no fabricated cells found | High |
| BOUND-2 | BOUND | "They may be different views of one underlying capability substrate" — `HYPOTHESIS`, not `ESTABLISHED` | reasoning | `HYPOTHESIS` — correctly tagged as such by the document itself; this review agrees with the classification | High |
| KERN-1 | KERN | Scope disclosure: `K5=(S,A,R)` cannot be evaluated, no source material found | — | `EXISTING DESIGN`-equivalent (an honest scope limit) — verified: `grep` for `K5=(S,A,R)` across the repository returns matches **only inside `KERN` and `MATRIX` themselves** — confirming no external definition exists to evaluate against. Honest disclosure, correctly tagged | High |
| KERN-2 | KERN | Question F: "third independent witness... used **neither** EKS's nor PKS's vocabulary" | `KnowledgeOS-Epistemic-Architecture-Investigation.md` | **`CONTRADICTED`** — see §7. The cited document's own headline finding is explicitly derived by citing "EKS (`AP-1`...)" and "PKS (`AP-1`... `MCR-5`...)" directly (its own §01, finding 2, and its own evidence-hierarchy table §03.1 marks EKS/PKS baselines as PRIMARY evidence, the external frameworks as SECONDARY/never-architecture). This is the opposite of vocabulary-independence | High |
| KERN-3 | KERN | Question G: `Fuse` "tested against a complete real commit population (6 commits, 3 `CONFIRMED`/2 `UNCITED`/1 `CONTRADICTED`)... correctly reproduced an independently-verified real anomaly" | `2026-09-29-KOS-evidence-fusion-model.md` §8/§10 | **`CONTRADICTED` by later evidence in the same source chain** — `evidence-fusion-model.md` §11 (added after `KERN` was committed; confirmed via `git log`, the correction is on an uncommitted working-tree edit postdating commit `19d3cb7fa`) discloses that the `9f83a369c` citation was misread, and that `Fuse` **as specified would have returned `UNCITED`, missing the violation entirely** — the opposite of "correctly reproduced." See §5 | High |
| KERN-4 | KERN | Question C: "All seven [kernel candidates], per the cross-domain validation already run this session (§19–20)" | evidence-derived-architecture-research.md §19-20 | `DERIVED`, but **incomplete** — the same source document's own §21 (same file, same date, a later pass) discloses that the Cohesion/PKS-capability-layer "independent convergence" framing underlying part of this claim is **weaker than claimed** ("shared lineage, not independent discovery"). `KERN` cites §§6,13,19-21 in its traceability line but does not carry the §21 caveat into its own Question C/E reasoning. See §7 | Medium — the underlying seven-item table is real, but its "confirmed independently in both domains" framing needs the §21 qualifier restated, not just footnoted |
| MATRIX-1 | MATRIX | #2: "mechanism records authority, does not grant it" — `OBSERVED`, "20 real grants" | same as EKS-RD-2 | See EKS-RD-2a/2b — same stale-count issue, repeated here without correction | High (of the problem) |
| MATRIX-2 | MATRIX | #13: seven-concept kernel candidate set "confirmed in both EKS and PKS" `OBSERVED` | evidence-derived-architecture-research.md §§6,19-20 | Same issue as KERN-4 — §21's correction is not surfaced | Medium |
| MATRIX-3 | MATRIX | #15: `Fuse` "real, falsifiable... already run against EKS data" `OBSERVED`, "complete, 6-commit population" | evidence-fusion-model.md | **Stale** — "complete" was true of the 6-commit Cohesion-file population as of §10, but §11.3 of the *same* source document later runs a 379-commit population, and §11 corrects the 6-commit result's headline finding. "Complete, 6-commit" is no longer the most complete or most correct statement available in the same source | Medium |
| MATRIX-4 | MATRIX | #7: `PKS-Current-Architecture-Baseline-Stage-2.md` on PKS's measured PHP reality | source doc | `OBSERVED` — verified: source states "no server, no database, no daemon... command-line validation programs" verbatim, line 83 | High |

**Rows not independently re-verified in full** (scope/time-bounded, flagged rather than silently
skipped): the exact byte-count arithmetic behind "9/11 byte-identical" in the underlying
architecture-research document; the complete 22-work-item negative-scope extraction claimed in
`PROTECTED-ARTIFACT-SWEEP.md` §4-6 (spot-checked structurally, not re-derived line by line); the
full 379-commit `git log --grep="KOS-"` census (order-of-magnitude checked, not recomputed exactly).

---

## 3 · Evidence assessment

- **Strongest evidence class in the set:** the workflow-ledger/grant provenance principle
  (`humanActRef` on every grant) and the EKS "two unconnected systems" finding (§Task1 EKS-RD-5) —
  both are population-level, both were independently re-derivable by this review from raw data, and
  both held up (in the grant case, held up *more* strongly at larger n).
- **Weakest evidence class:** anything resting on the seven-candidate kernel set's "cross-domain
  independence." On inspection, the PKS side of that validation (§19 of the architecture-research
  doc) is genuinely textual/specification-level and does constitute a different evidence class from
  Cohesion's code — that part is legitimate. What is not legitimate is treating the
  Authorization/commitment-gate candidate's "third witness" as vocabulary-independent when its own
  source document explicitly built its finding from EKS's and PKS's `AP-1`.
- **Currency is a systemic weak point, not isolated to one document.** Two separate instances found
  (grant count; `Fuse` result) where a document states a figure as if freshly and currently true
  when it is either a month-old citation or has since been superseded by same-day later work. The
  tagging legend's "measured directly, this session **or earlier this session**" wording is loose
  enough to license both.

---

## 4 · Contradictions found

1. **`EKS-RD-2b` vs. live ledger data.** "20 real grants" (claimed, `OBSERVED`) vs. 144 grants
   measured directly by this review against the current `.claude/runtime/workflow/*.json` (22
   files). Not a fabrication — a stale citation mislabeled as a fresh observation.
2. **`KERN-3`/`MATRIX-3` vs. `evidence-fusion-model.md` §11.** "Correctly reproduced" vs. the
   corrected finding that `Fuse` "would have missed this real violation entirely" once the citation
   misread is fixed. Confirmed by direct reading of §11.1-11.2 of the cited source itself.
3. **PKS baseline's "0 dedicated test files" for Cohesion (cited approvingly in `EKS-RD` §6) vs.
   `tests/Unit/Cohesion/` containing 17+ real PHPUnit test files, some dating to 2026-08-18** (three
   days *before* the PKS baseline's 2026-08-21 claim). This is not necessarily wrong on its own
   terms — "dedicated" plausibly means "inside `Capabilities/Cohesion/Tests/`," which Cohesion
   genuinely lacks (unlike the other three capabilities) — but neither `EKS-RD` (which separately
   calls Cohesion "real, tested" in the very same document) nor the PKS baseline it quotes
   reconciles the two statements. A reader assembling both documents would reasonably conclude
   Cohesion is untested, which is false.
4. **`PKS-RD-4`/`BOUND` tension (not a hard contradiction, but an unacknowledged one):** the same
   underlying fact (Cohesion and PKS's capability layer share a directory/template) is used in
   `KERN` to support treating EKS and PKS as validly *independent* validation domains, and in
   `BOUND` as evidence they might be the *same system* under two names. Both uses are individually
   defensible; the set never states that it is using one fact for two different, load-bearing, and
   somewhat opposed arguments.

---

## 5 · Unsupported claims

- **EKS-RD-7** ("two independently-arrived-at" verdict vocabularies): "independently arrived at" is
  asserted, not evidenced. Nothing in the corpus (checked: git history of both vocabularies, commit
  authorship, design docs) was found establishing the two vocabularies were designed without
  cross-influence. The more defensible claim — "two structurally identical, closed, fail-to-a-
  fourth-state vocabularies exist in both systems" — is fully supported; the causal/historical
  "independently" is not.
- **`EKS-RD-2b`/`MATRIX-1`** ("verified directly," "20 real grants," `OBSERVED`): unsupported as a
  *current* measurement; supported only as a citation of an external, month-old document. See §2,
  §4.
- **`KERN-3`/`MATRIX-3`** (Fuse "correctly reproduced" the anomaly): unsupported as of this review's
  writing — actively contradicted by the same research line's own later correction.

---

## 6 · Overclaims

**EKS's identity/boundary.** `EKS-RD`'s "two structurally separate, real, working systems" claim
(§Task1 EKS-RD-5) is well-supported and appropriately scoped — this is the strongest, most
falsifiable claim in the set and it survives scrutiny. No overclaim found here.

**PKS's identity / "specification vs. measured code" tension.** `PKS-RD` handles this honestly —
it states the tension and refuses to resolve it (§3, §6). No overclaim found; this is a model of
how to disclose an unresolved tension rather than paper over it.

**The EKS/PKS relationship, generally.** `BOUND` and `KERN` are careful to tag the "same substrate"
reading as `HYPOTHESIS`. The overclaim is narrower and specific: `KERN` Question F's "used neither
EKS's nor PKS's vocabulary" (§2 KERN-2, §7) and, more diffusely, the failure of `KERN` Question C
and `MATRIX` #13 to carry forward their own cited source's §21 self-correction about shared
lineage weakening the "independent convergence" framing.

**Every one of the seven kernel candidates — re-assessed here, not copied from the source documents
— is in §10 below.** The short version: two of the seven (Authorization/commitment-gate;
Uncertainty/indeterminacy) have real, population-level, cross-domain support that survives this
review's scrutiny. The rest range from "supported in one domain, asserted in the other" to
"withdrawn by the corpus's own later work" (Observation).

---

## 7 · Circular reasoning found (quoted)

### 7.1 — The "third independent witness" is not independent of what it is asked to confirm

`KERN` Question F states:

> "the third independent witness for candidate (3) (Authorization/commitment-gate) came from a
> 2026-08-21 investigation that used **neither** EKS's nor PKS's vocabulary — it tested general
> Bayesian/calibrated-confidence frameworks against evidence and reached the same substance...
> independently."

Read directly, `KnowledgeOS-Epistemic-Architecture-Investigation.md` (the cited source) states, in
its own executive summary, finding 2:

> "CONFIDENCE ≠ AUTHORITY is the strongest-supported external principle. **EKS (AP-1: "knowledge
> feeds authority, it never holds it"...); PKS (AP-1; promotion is a distinct Authority act;
> MCR-5 confidence ceiling is separate from authority acts)**... all implement or imply the
> separation of analytical confidence from organizational authority."

And its own evidence-hierarchy rule (§03):

> "repository evidence (EKS/PKS/AIP baselines...) is **PRIMARY**. The CAPPI / political-performance
> discussions are **SECONDARY** research input that generates hypotheses; they are **NEVER treated
> as architectural evidence**."

The loop: EKS/PKS's own `AP-1` principle is used, elsewhere in this document set, as evidence for
the Authorization/commitment-gate kernel candidate. The 2026-08-21 investigation is then cited as a
*third, independent* confirmation of that same candidate — but that investigation's own finding is
explicitly built by re-citing EKS's `AP-1` and PKS's `AP-1` as its primary evidence, and explicitly
treats the *actually-external* frameworks (the ones that could have supplied real independence) as
secondary, non-architectural, and in fact largely **rejected** (finding 3: "CAPPI's numeric
epistemic stack... has ZERO current-architecture support"). Citing this document as an independent
witness to `AP-1` is citing `AP-1` a third time and calling it a second confirmation.

### 7.2 — A disclosed self-correction not carried forward

`2026-09-28-KOS-evidence-derived-architecture-research.md` §21 (cited in `KERN`'s own traceability
line) states, self-critically:

> "Where §19 credited Cohesion and PKS's capability layer as two unrelated domains converging by
> coincidence, that specific piece of evidence is **weaker than claimed** — shared lineage, not
> independent discovery."

`KERN` Question C nonetheless states, without qualification:

> "All seven, per the cross-domain validation already run this session (§19–20 of the
> evidence-derived architecture research). `OBSERVED`."

This is not fabrication — §19/§20's *textual* PKS confirmations (Concept Register, Capability
Lifecycle) are a different evidence class from the capability-layer code and are not directly
touched by §21's correction. But `KERN` cites §21 in its traceability without ever surfacing what
§21 actually says, which is a real, disclosed weakening of part of the evidence it leans on. A
reader of `KERN` alone would not learn this.

### 7.3 — Not found, disclosed as not found

No instance was found of the more severe pattern the task brief asks to watch for — "observe
architecture X → declare X fundamental → put X in the kernel candidate set → cite X's presence in
the candidate set as further evidence X is fundamental" — operating *within* the seven reviewed
documents themselves. That pattern is closer to describing how the *upstream* `evidence-derived-
architecture-research.md` built its candidate set across its own §§6→19→20→21 (candidate set →
tested against PKS → cited as confirmed → self-corrected), which the seven reviewed documents
inherit rather than originate. Flagged as inherited risk, not invented here.

---

## 8 · Redundancy analysis (Task 6)

| Pair/group | Overlap | Verdict |
|---|---|---|
| `MATRIX` vs. all six others | `MATRIX` is explicitly a re-indexing of the other six documents' own conclusions (its own header states this). 16 of 16 rows trace 1:1 to a claim already stated in another document. | **Near-total duplication by design.** Real traceability value (single click-through table), but zero independent content. |
| `PKS-PY` vs. `PKS-RD` + `EKS-PY` | `PKS-PY`'s §3 ("what must hold regardless") restates PKS's governance-before-implementation principle already quoted verbatim in `PKS-RD` §5. `PKS-PY`'s "Reading B" is explicitly "identical to `EKS-PY`." | **Substantial duplication.** Net new content: roughly one paragraph (the "category error" framing and the two-reading split). |
| `BOUND` vs. `EKS-RD` + `PKS-RD` | `BOUND`'s comparison table recombines fields already stated in the two research-definition docs; ~10 of 13 table rows are direct restatements. | **Moderate duplication**, but the tabular side-by-side format has genuine comparative value the two source docs don't offer individually. |
| `KERN` vs. `evidence-derived-architecture-research.md` (external, not one of the 7) | `KERN`'s §6 candidate table and Question A-G restate that document's §§6/19/20 findings, reformatted as Q&A. | Not "one of the 7 vs. another of the 7," but worth noting: `KERN` is largely a re-presentation layer over external prior research, adding the Question-A-G structure and the third-witness/`Fuse` material, not new evidence of its own for the shared parts. |
| `OQ-11` restated | Appears as a load-bearing "still open" finding in `EKS-RD` §6, `PKS-RD` §4/§5, `PKS-PY` §1, `BOUND` (twice), `KERN` (implicitly, via Question E) | Same fact, same two source citations (AIP reconstruction; PKS baseline), restated 5-6 times across the set with no single canonical statement pointed to by the others. |

**Minimum non-duplicative content, by rough accounting:** `EKS-RD` and `PKS-RD` carry essentially
all first-order evidence; `EKS-PY` adds real, non-duplicated content (the template-reuse argument,
the cross-language testing discipline); `PKS-PY` adds perhaps one paragraph beyond what `EKS-PY`
and `PKS-RD` already establish; `BOUND` adds the comparison-table format (real, if derivative)
value; `KERN` adds the Question-A-G structure, the `Fuse`/third-witness material (which needs
correcting per §4/§7), and the concept×domain matrix (itself sourced externally); `MATRIX` adds a
traceability index with no independent content.

---

## 9 · Recommended document structure (Task 7)

Derived from the above, not a predetermined target shape:

1. **`EKS-Research-Definition.md`** — merge current `EKS-RD` + `EKS-PY`. Rationale: `EKS-PY`'s
   content is a direct, evidence-tight continuation of `EKS-RD` §7 (capabilities) — there is no
   natural seam between "what EKS is" and "how EKS is built," and keeping them separate has already
   produced the EKS-PY-1 imprecision (Cohesion's structural variance from the "4-times-identical"
   template claim) going unnoticed because the python-architecture document didn't cross-check
   against the boundary document's own capability inventory.
2. **`PKS-Research-Definition.md`** — merge current `PKS-RD` + `PKS-PY`. Same rationale; `PKS-PY`'s
   real content (the two-reading split, the category-error framing) fits as a subsection ("§7 —
   Python architecture: an open question, not yet forced") rather than a standalone file whose main
   content is "see the other document."
3. **`EKS-PKS-Relationship.md`** — merge current `BOUND` + `KERN`, as two sections of one document
   ("Boundary comparison" / "Kernel-candidate relationship"). Both are answering the same underlying
   question (are these one system, two, or something in between) from two angles that inform each
   other; keeping them apart has already let one document's caveat (`KERN`'s citation of §21) fail
   to reach the other's parallel claim (`PKS-RD-4`/`BOUND`'s double-duty use of the shared-directory
   fact, §4 item 4). This merged document should carry the §7.1/§7.2 corrections from this review
   as an explicit erratum section, and should either drop or heavily qualify the `Fuse`
   third-witness argument pending the corrections in `evidence-fusion-model.md` §11 and
   `PROTECTED-ARTIFACT-SWEEP.md`.
4. **Evidence appendix** — keep `MATRIX`'s function (a single claim→source table) but as the *last
   section* of document 3, not a separate file, since its entire content is a derivative index of
   the other documents. If the project's convention requires load-bearing conclusions to have a
   standalone, independently-linkable evidence table, keep it separate, but then treat it as the
   single canonical place `OBSERVED`/`DERIVED`/etc. tags live, and have documents 1–3 link to rows
   in it rather than re-asserting their own tags.

**Net: 7 files → 3 files (+ optionally the evidence table as a 4th)**, with every piece of real,
non-duplicated content preserved and the `OQ-11`/shared-lineage/`Fuse`-currency caveats each stated
exactly once instead of 3-6 times inconsistently.

---

## 10 · Kernel-candidate assessment (re-derived, Task 2/Task 3 combined)

For each of the seven candidates from `evidence-derived-architecture-research.md` §20 (which `KERN`
adopts): what observation actually requires the concept, and what would explain the same
observation without invoking it as "kernel-level."

| Candidate | What's actually observed | Necessary, or could a simpler explanation account for it? | This review's status |
|---|---|---|---|
| **Observation** | Both systems read source material before acting (token stream in Cohesion; `Observed` as an epistemic class in PKS). | Trivially true of *any* system that processes input — "something is observed before it is classified" is not a distinguishing architectural claim, it is a precondition of computation. The corpus's own later work (`PROTECTED-ARTIFACT-SWEEP.md` §11) reaches the same conclusion and withdraws this candidate as an empirical finding. | **Not kernel-level — a logical precondition, not a discovered principle.** Agrees with, and independently confirms, the corpus's own later withdrawal. |
| **Rule/classification (never reasoning)** | `EdgeRules` (Cohesion) and `Rule` (PKS Concept Register) both exist and both classify rather than infer. | A simpler explanation: both systems were built under the same "no AI black-box in the verdict path" constraint stated independently in each system's own governance text (`AP-8`, `INV-ATTR`, PKS's governance-before-implementation) — the *shared constraint*, not a shared "kernel," could produce this pattern in any two rule-governed systems. | **Plausible candidate, weaker than claimed.** Real in both domains; "kernel" framing not required to explain it — "both were built under a similar governance mandate" explains it equally well. |
| **Authorization/commitment-gate** | `G-3` mandatory human `START` (EKS, real code, tested); PKS's "two separate acts before activation" (declared); reinforced by `AP-1` in both. | This is the strongest candidate: it is enforced by *running, tested code* in EKS (not merely declared), and independently declared with teeth (two-act requirement) in PKS. A simpler explanation — "both are Anthropic/human-in-the-loop governance systems, so of course both gate on human authorization" — is plausible but doesn't reduce the finding to nothing; gating specifically on an *external, unautomatable* act (not merely a review step) is a real, non-trivial, repeated design choice. | **Supported**, but the "third independent witness" (§7.1) does not add anything beyond what EKS's own code and PKS's own declared rule already establish — remove it, the candidate still stands on two real sources, not three. |
| **Canonical representation (vs. projection)** | `L3` facts (EKS, real, cross-language tested) vs. PKS's Knowledge→Representation→Presentation layering (declared, not code-enforced). | The EKS side is genuinely demonstrated (two independent language adapters converge on one internal form). The PKS side is a documented intention, not a measured property — nothing found tests whether PKS's YAML "representation" and Markdown "presentation" actually stay synchronized in practice (no synchronization-drift test was found in either the reviewed documents or their sources). | **Supported in EKS, asserted (not tested) in PKS.** The "CONFIRMED" status in the source matrix conflates a tested property with a declared intention. |
| **Provenance** | `humanActRef` on every grant (EKS, real, 144/144 as re-measured); `origin` field on every PKS concept (declared). | Real and population-level on the EKS side. On the PKS side, "every concept carries an origin field" is a schema requirement, not evidence the field is filled correctly or checked for drift — no PKS provenance-verification code was found by this review. | **Supported in EKS (strong), declared-only in PKS.** Every downstream document treating this as "confirmed in both" should say so with that asymmetry stated, not implied equal. |
| **Uncertainty/indeterminacy as distinct, non-defaulted state** | `IndeterminateBehaviourReference` (EKS, real code, `D-1`); `INCONCLUSIVE`/`G-6 Question` (PKS, declared); reconfirmed independently by `PROTECTED-ARTIFACT-SWEEP.md`'s own `NOT_APPLICABLE` state, which was *required* to correctly classify real cases. | This is the one candidate with genuine, repeated, population-level, code-level support in **two independent lines of work within EKS alone** (Cohesion's `D-1`, and the wholly separate `Fuse`/sweep work), plus a declared analog in PKS. | **The strongest candidate in the set** — stronger, on this review's evidence, than Authorization/commitment-gate, because it has *three* independent code-level confirmations (Cohesion, Observation Runtime's four-state verdicts, and the sweep's `NOT_APPLICABLE`), not two. |
| **Governed lifecycle (state transition)** | `REGISTER→HANDOFF→START` (EKS, real, coarse); PKS's `Candidate→Designed→Realized`/`Deferred`/`Rejected` (declared, "Active" deliberately excluded). | Real in EKS. The PKS lifecycle is declared and its terminal-state discipline ("Active" excluded because "nothing is currently authorized to operate") is a genuinely specific, non-generic design choice — harder to explain away as "any system has states." | **Supported**, with the same EKS-code/PKS-declaration asymmetry as Provenance and Canonical representation above. |

**Overall re-derived verdict:** of seven candidates, one (Uncertainty/indeterminacy) is
genuinely strong and even under-credited by the source documents; three (Authorization,
Canonical representation, Governed lifecycle, Provenance) are real but asymmetric across the two
domains in a way none of the seven reviewed documents states plainly; one (Rule/classification) is
explainable by a shared governance mandate rather than a shared kernel; one (Observation) should be
dropped, and the corpus's own later work already agrees.

---

## 11 · Evidence-fusion model assessment (Task 5)

- **Genuinely population-level:** the 379-commit `git log --grep="KOS-"` census
  (`evidence-fusion-model.md` §11.3) and the entropy comparison in `PROTECTED-ARTIFACT-SWEEP.md` §9
  are real population statistics with disclosed, non-cherry-picked scope. These are the strongest
  quantitative results in the whole research arc reviewed here.
- **Resting on a single example:** the entire "blind spot is real" finding rests on exactly **one**
  independently-verified ground-truth case (`9f83a369c`/`6b983bc12`) — and `PROTECTED-ARTIFACT-
  SWEEP.md` §8 says this explicitly and correctly: `"SUPPORTED BY ONE INDEPENDENT CASE — INSUFFICIENT
  FOR GENERALIZATION."` This honesty is a model for how the rest of the corpus should handle
  single-example findings, and it is not consistently matched elsewhere (compare Task 6/7.1's
  three-witness inflation).
- **Which kernel conclusions depend on the fusion model being correct:** none of the seven kernel
  candidates in §20 of the architecture-research document structurally depend on `Fuse` being
  correct — `Fuse` is offered in `KERN` Question G only as a demonstration that EKS data can
  *empirically test* kernel candidates, not as the source of any candidate's status. This means the
  `Fuse` staleness problem (§4/§7 of this review) damages **`KERN`'s illustrative example**, not the
  candidate-set conclusions themselves. That is a real distinction worth preserving when this is
  corrected — the fix is narrow (rewrite Question G's example), not a re-derivation of §10's table.
- **Would the kernel conclusions survive if the fusion model turned out wrong?** Yes, for six of
  seven candidates (their evidence sources are Cohesion, the governance engine, and PKS's own
  documents — none of which depend on `Fuse`). The exception is `Uncertainty/indeterminacy`, which
  gains one of its three confirmations from the sweep's `NOT_APPLICABLE` state — if the sweep
  methodology were wrong, that candidate would fall back to two confirmations (Cohesion, Observation
  Runtime), still enough to remain the strongest candidate in the set.
- **The `9f83a369c` blind spot as a data point, not a bug to quietly patch:** treated correctly by
  its own source documents (disclosed, not smoothed over, refinement proposed and explicitly
  **not yet adopted**). The failure in this review's scope is purely one of **propagation** — the
  seven documents under review cite the pre-correction state and have not been updated to reflect
  it, through no fault of sequencing (the correction postdates their commit) but a real fault of
  currency as of today.

---

## 12 · Open questions that genuinely remain

- **`OQ-11`** (EKS/PKS boundary) — correctly identified throughout the set as the master open
  question; nothing in this review's independent verification resolves it, and nothing in the
  corpus claims to.
- **Whether the Authorization/commitment-gate and Canonical-representation candidates would survive
  being tested against a genuinely third, unrelated domain** (not EKS, not PKS, not a document that
  cites EKS's or PKS's own `AP-1`) — not yet attempted anywhere in the corpus this review located.
- **Whether Cohesion's structural deviation from the "4-times-identical template" claim (no
  capability-local `Tests/` directory) reflects an intentional exception or an unnoticed drift** —
  not addressed anywhere in the seven documents or their sources.
- **Whether the `CITED_NO_GRANT` bucket (161 of 379 commits, per `evidence-fusion-model.md` §11.3)
  and the 21-of-22 work items with "no extractable negative-scope rule" (`PROTECTED-ARTIFACT-
  SWEEP.md` §13) represent a real corpus property or a method limitation** — both source documents
  say this explicitly and correctly; unresolved.
- **Whether the "20 grants" framing was an isolated slip or a symptom of how "this session" is used
  loosely across a multi-week, multi-date research arc** (`EKS-RD`'s own tagging legend permits
  "earlier this session" without bounding what counts as the same session) — this review flags the
  ambiguity but does not have grounds to generalize beyond the one confirmed instance.

---

## 13 · Recommended rewrite plan (for the main session to execute)

1. **Correct `KERN` Question F and `MATRIX` #14 first — highest severity, cheapest fix.** Remove or
   sharply requalify the "used neither EKS's nor PKS's vocabulary... independently" framing (§7.1).
   The Authorization/commitment-gate candidate does not need this witness; it is supported by EKS
   code + PKS's declared two-act rule alone. State that plainly instead.
2. **Correct `KERN` Question G and `MATRIX` #15 — same severity.** Replace the 6-commit `Fuse`
   illustration with either (a) a pointer to the corrected §11 finding in `evidence-fusion-model.md`
   plus `PROTECTED-ARTIFACT-SWEEP.md`, stated as "the model initially appeared to catch this
   violation; a same-day correction showed it would have missed it via the citation path alone, and
   a citation-independent sweep was required" — which is a *more* interesting and honest empirical
   result than the original claim — or (b) drop the specific 6-commit example and cite only the
   379-commit / entropy-reduction results, which remain valid.
3. **Fix `EKS-RD-2b`/`MATRIX-1`'s stale grant count.** Either re-measure and state the current
   figure (144 grants / 22 work items / 144-144 `humanActRef`, strengthening the claim), or
   explicitly re-tag the "20 grants" figure as a dated citation ("as of 2026-08-17") rather than an
   `OBSERVED`-this-session count. Either fix is cheap; leaving it as-is is the least defensible
   option.
4. **Add the EKS-PY-1 caveat about Cohesion's structural deviation** from the "4 times, same
   template" claim — one sentence, in the merged `EKS-Research-Definition.md` (§9's proposed
   document 1).
5. **Reconcile the "0 dedicated test files" (PKS baseline) vs. "real, tested" (EKS-RD) tension for
   Cohesion** — one clarifying sentence distinguishing "no capability-local `Tests/` folder" from
   "untested," in the same merged document.
6. **Execute the consolidation in §9**: 7 files → 3 (+ optional evidence appendix), carrying forward
   every non-duplicated finding and stating the `OQ-11`/shared-lineage caveats exactly once each.
7. **Re-run this review's Task 1 spot-checks after the rewrite** (not a new full review — a
   targeted diff-check that the six corrections above actually landed and did not introduce new
   unqualified claims) before treating the consolidated set as citable elsewhere in the corpus.

**Do not,** as part of this rewrite, attempt to re-resolve `OQ-11` itself, re-run the `Fuse`/sweep
research programme, or add new kernel candidates — all three are explicitly out of scope for a
correction pass and would repeat the "corpus-to-architecture leakage" this whole research arc has
otherwise been careful to avoid.
