# EKS — Research Definition and Python Architecture

**Date:** 2026-09-29, consolidated. Supersedes and merges `2026-09-29-KOS-EKS-RESEARCH-DEFINITION.md`
and `2026-09-29-KOS-EKS-PYTHON-ARCHITECTURE.md` (both retained in git history, not deleted from the
record — see traceability). Consolidation and corrections follow the independent adversarial review
`2026-09-29-KOS-EKS-PKS-RESEARCH-REVIEW.md`; every correction below is traced to a specific review
finding, not made silently.

**Tagging discipline unchanged:** `OBSERVED` / `EXISTING DESIGN` / `DERIVED` / `HYPOTHESIS` /
`UNKNOWN`.

---

## 1 · What EKS stands for and what problem it solves

**"Engineering Knowledge System."** `EXISTING DESIGN`, quoted directly from
`docs/knowledgeos/brainstorming/20260821-2033-what-eks-is-today.md`: *"A governed engineering
workflow and knowledge environment in which human-authorized engineering work produces durable
decisions, evidence, observations, and other engineering records, with automated mechanisms
enforcing and verifying selected invariants."*

EKS governs **how engineering work on this repository gets done**, not the business/product domain
(external, per `AIP-14`). Its strongest measured finding: **"the mechanism records authority; it
does not grant authority."**

> **Correction (review finding, `EKS-RD-2b`):** the earlier version of this document stated this
> principle as verified "against 20 real grants... `OBSERVED`, this session." That count is a
> month-old citation of `KOS-ARCH-BASELINE-001` (2026-08-15/17), not a fresh measurement, and the
> `OBSERVED` tag was stretched to cover it. **Re-measured directly, this pass, against the live
> `.claude/runtime/workflow/*.json` ledgers: 144 grants across 22 work-item files, 144/144 carry
> `humanActRef`, 144/144 `registeredBy: governance`.** The principle is not weakened by this
> correction — it is considerably more strongly supported at the larger, current count. The error
> was in citation currency, not in the underlying claim. `OBSERVED`, this pass, dated 2026-09-29.

## 2 · Responsibility and boundary

Not a single declared bounded context. `KOS-ARCH-BASELINE-001` (accepted 2026-08-17): 6 declared
contexts exist on paper; 2 of 8 registered components have no real assets, 2 more are
partial/by-reference. `EXISTING DESIGN`. The same baseline records *"governed session
orchestration"* as a de facto seventh area, explicitly `Inferred`, not decided (`U-3`).
`EXISTING DESIGN`.

**Measured this session:** EKS is at least two structurally separate, real, working systems:

1. **The Engineering Knowledge capability layer** — `scripts/lib/EngineeringKnowledge/Capabilities/`:
   `IdentifierIntegrity` (`CAP-001`), `Cohesion`, `ReferenceIntegrity`, `VocabularyIntegrity`.
   `IdentifierIntegrity/README.md` calls its own `Domain/Application/Infrastructure/Tests` shape
   *"the template for every future Engineering Knowledge capability."* `EXISTING DESIGN`.
2. **The Observation Runtime pipeline** — `scripts/observations/`: `TestPresenceCollector`/
   `Lcom4Collector` → `RecommendationEngine` (rules as YAML data) → `RecommendationInbox` →
   developer decision → `OutcomeRecorder` → `AssessmentService`. Real, TDD-built, verified live.
   `OBSERVED`.

**These two systems are structurally unconnected.** Confirmed by direct grep, re-verified by the
independent review (`grep -rl "Cohesion" scripts/observations/*.php` → exit 1, zero matches).
`OBSERVED`.

> **Correction (review finding, `EKS-PY-1`):** the earlier claim that all four capabilities share
> "the same template, used successfully four separate times" is **imprecise**. Verified: 3 of 4
> (`IdentifierIntegrity`, `ReferenceIntegrity`, `VocabularyIntegrity`) have a capability-local
> `Tests/` directory exactly matching the template. **`Cohesion` does not** — its tests live at the
> top-level `tests/Unit/Cohesion/`, outside the capability's own directory. Cohesion follows
> `Domain/Application/Infrastructure` but is a structural variant on the `Tests` placement, not
> flagged as such previously. This is disclosed here as an open question (§7), not resolved —
> whether it is an intentional exception or unnoticed drift is `UNKNOWN`.

> **Reconciling a related tension (review finding, contradiction #3):** `PKS-Current-Architecture-
> Baseline-Stage-2.md` (2026-08-21) states Cohesion has *"0 dedicated test files."* This is true in
> the narrow sense that Cohesion has no `Capabilities/Cohesion/Tests/` folder (unlike the other
> three capabilities) — it is **not** evidence that Cohesion is untested. `tests/Unit/Cohesion/`
> contains 17+ real PHPUnit test files, some dating to 2026-08-18, and this session's own `D-1`
> work added 6 more, GREEN, 188/188. "No capability-local `Tests/` directory" and "untested" are
> different claims; the earlier baseline's wording invites conflating them, and this document does
> not repeat that conflation.

## 3 · Inputs and outputs

| System | Input | Output |
|---|---|---|
| Engineering Knowledge capabilities | source files, markdown documents | `Verdict` (`PASS`/`FAIL`/`WARN`/`INCONCLUSIVE`), `Assessment` (verdict + evidence) |
| Observation Runtime | git commits / file saves (`ChangeSet`) | observations → recommendations → decisions → outcomes → assessments (`SUPPORTED`/`PARTIALLY_SUPPORTED`/`NOT_SUPPORTED`/`INCONCLUSIVE`) |

`OBSERVED`, both rows.

## 4 · Concepts EKS owns

- **Canonical, language-neutral semantic facts** (Cohesion's `L3`) — real, cross-language-tested.
  `OBSERVED`. Precisely: 11 mechanisms checked, 9/11 byte-identical, 2/11 legitimate spelling
  differences with identical decisional fields — **not** "0 semantic divergences" stated without
  qualification, per the review's own correction (`EKS-PY-2`); the 2/11 difference is real and
  benign, and should be named, not compressed away.
- **A closed verdict vocabulary**, two independently-existing instances (capability layer:
  `PASS`/`FAIL`/`WARN`/`INCONCLUSIVE`; Observation Runtime: `SUPPORTED`/`PARTIALLY_SUPPORTED`/
  `NOT_SUPPORTED`/`INCONCLUSIVE`) — real, both verified to exist verbatim in code. `OBSERVED`.
  **The claim that these were "independently arrived at" is withdrawn** (review finding,
  `EKS-RD-7`): no evidence was found, in this corpus, of the two vocabularies' actual design
  history — only that they now look alike. The defensible claim is narrower: two structurally
  identical, closed, fail-to-a-fourth-state vocabularies exist in both systems. `UNKNOWN` whether
  they were designed independently.
- **Fail-closed discipline** — *"absence of evidence is never `PASS`"* (`AP-8`). `EXISTING DESIGN`.
- **Rules as data, engine as mechanism** — `recommendation-rules.yaml` + `RecommendationEngine.
  evaluate(facts, rules)`, real code. `OBSERVED`.

## 5 · Concepts EKS explicitly does not own

- Business/product domain semantics (external, per `AIP-14`). `EXISTING DESIGN`.
- Knowledge-governance semantics as PKS's own primary domain — see `PKS-Research-Definition.md`.
  Whether EKS's own capabilities are themselves PKS capabilities is genuinely unresolved — see
  §6 and `EKS-PKS-Relationship.md` (the single canonical location for this question, not restated
  here beyond the pointer).
- Process/agent identity — by ratified invariant (`INV-ATTR-1`/`INV-ATTR-2`): self-declared
  identity is evidential, never attestable. `EXISTING DESIGN`.

## 6 · The one load-bearing unresolved question

**Whether EKS and PKS are the same system, overlapping, or genuinely distinct is `OQ-11`, the
corpus's own oldest unresolved open question.** Full treatment, comparison, and the kernel-relation
analysis live in `2026-09-29-KOS-EKS-PKS-Relationship.md` — this is the single canonical statement
of `OQ-11` in this document set; every other document points here rather than restating it.

## 7 · Capabilities EKS actually provides

| Capability | Realized by | Status |
|---|---|---|
| Identifier collision prevention (`PMR-10`) | `IdentifierIntegrity` (`CAP-001`) | `OBSERVED`, real, tested |
| Code cohesion analysis, cross-language | `Cohesion` | `OBSERVED`, real, tested, `D-1` validated this session; structural `Tests`-placement variant noted (§2) |
| Intra-document reference integrity | `ReferenceIntegrity` | `OBSERVED`, real |
| Vocabulary/stale-token/confusable-identifier detection | `VocabularyIntegrity` | `OBSERVED`, real, back-tested against real historical defects |
| Test-presence, cohesion metrics, recommendation, decision, outcome, assessment | Observation Runtime | `OBSERVED`, real, TDD-built, verified live |
| Environment readiness check | `KnowledgeOsDoctor` | `OBSERVED`, real |
| Idempotent bootstrap | `KnowledgeOsInitPlanner` | `OBSERVED`, real |

## 8 · Python architecture

**The template is not proposed here — it is confirmed already in use.** `Domain/Application/
Infrastructure/(Tests)` has been used, with the §2-noted `Tests`-placement exception, across all
four capabilities, and is explicitly documented as the mandatory shape for any new one.

```
CLI  →  Infrastructure  →  Application  →  Domain  →  Shared
⛔ No arrow runs the other way. Shared must not know a specific capability exists.
```
`EXISTING DESIGN`, `IdentifierIntegrity/README.md`.

**What justifies keeping it:** Cohesion's own `Infrastructure/Php`/`Infrastructure/Python` split is
a direct, working proof this shape survives a language boundary without `Domain` changing.
`OBSERVED`.

**Python as the primary implementation language — already decided, not proposed here.**
`PYTHON-FIRST-POLICY.md` (2026-09-28, real): *"From this point forward, Python is the primary
language for new KnowledgeOS development."* `EXISTING DESIGN`. Motivated by real evidence: two of
six real bugs found in Cohesion's cross-language work were symmetric — present in the PHP reference
too, exposed only because an independent Python implementation forced the comparison. `OBSERVED`.
Whether existing PHP capabilities should be ported is `UNKNOWN` — no evidence found either way.

**Do not copy PHP structure merely because it exists — already tested, not just stated.** Cohesion's
Python adapter (`extract_facts.py`) is deliberately not a transliteration of the PHP extractor — it
uses a different parsing strategy (`ast` module vs. `nikic/php-parser`) specifically so agreement
between the two is evidence about the *contract*, not a shared library. `OBSERVED`, verified
directly against the source file's own stated rationale.

**Ports, adapters, testing — from what already exists, not invented:**

| Concern | Existing, real pattern |
|---|---|
| Port | `Application/Ports/SemanticFactProvider.php` (`extract(string): FactSet`) |
| Adapter | one per language, each returning the same `Domain` types |
| Testing | golden fixtures + `expected.json`, RED→GREEN, negative discrimination (deliberately reintroduce a known bug, confirm it's caught) |
| Conformance | dump raw `L3` facts for both languages, diff field-by-field — never trust the final metric alone |

**What remains genuinely undecided:** whether the Observation Runtime should ever be unified with
the capability-layer template, or remain structurally distinct. `UNKNOWN`.

---

## Status table

| Question | Status |
|---|---|
| What EKS stands for | `EXISTING DESIGN` |
| EKS's problem/responsibility | `EXISTING DESIGN` + `OBSERVED` |
| EKS's boundary | `DERIVED` — two real, unconnected systems |
| Authority-recording principle | `OBSERVED`, corrected and strengthened this pass (144 grants, not 20) |
| Verdict-vocabulary "independence" | `UNKNOWN` — withdrawn as a claim this pass |
| Cohesion's template conformance | `DERIVED` — a real structural exception (`Tests` placement), not previously flagged |
| Relationship to PKS | pointer to `EKS-PKS-Relationship.md` — `UNKNOWN`/`OQ-11` |
| EKS's Python architecture | `EXISTING DESIGN` — already proven, not proposed |

**Traceability:** `20260821-2033-what-eks-is-today.md` · `KOS-ARCH-BASELINE-001` accepted baseline
(2026-08-15/17) · `IdentifierIntegrity/README.md` · `PKS-Current-Architecture-Baseline-Stage-2.md` ·
this session's own `D-1`/Cohesion/Observation-Runtime work · `2026-09-28-KOS-PYTHON-FIRST-POLICY.md` ·
`2026-09-29-KOS-EKS-PKS-RESEARCH-REVIEW.md` (source of every correction in this document) ·
superseded originals: `2026-09-29-KOS-EKS-RESEARCH-DEFINITION.md`,
`2026-09-29-KOS-EKS-PYTHON-ARCHITECTURE.md` (git history, commit `19d3cb7fa`).
