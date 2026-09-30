# PKS — Research Definition and Python Architecture

**Date:** 2026-09-29, consolidated. Supersedes and merges `2026-09-29-KOS-PKS-RESEARCH-DEFINITION.md`
and `2026-09-29-KOS-PKS-PYTHON-ARCHITECTURE.md` (retained in git history). Follows the independent
adversarial review `2026-09-29-KOS-EKS-PKS-RESEARCH-REVIEW.md`; no corrections were required to
this document's own content — the review found no overclaims here and specifically credited it as
*"a model of how to disclose an unresolved tension rather than paper over it."* Consolidated for
structural reasons only (§8 of the review: redundancy).

---

## 1 · What PKS stands for and its core definition

**"Product Knowledge System."** `EXISTING DESIGN`, quoted verbatim from
`docs/knowledge_tranfer/20260728_1710_what_is_pks_v1.md`: *"The PKS is a knowledge system that
models engineering knowledge, governance, evidence, and their relationships. One of its primary
capabilities is providing AI with deterministic guidance about which engineering artifacts to
produce, when, where, how to structure them, and how to verify them."*

**Explicitly not documentation, not software:** *"The PKS is knowledge specifications (YAML +
Markdown). It does not require PHP, Python, or any other programming language. AI tools consume the
specifications directly."* `EXISTING DESIGN`, same source, verified verbatim.

## 2 · Responsibility and boundary

Three declared layers: **Knowledge Model** → **Representation** (YAML/JSON) → **Presentation**
(documents as projections — `ADR ← Decision`, `Session Log ← Observations`). `EXISTING DESIGN`.

**17 named knowledge concepts** (`G-1`…`G-17`, verified all present and matching exactly against
source §3.1): `Decision`, `Rule`, `Invariant`, `Ruling`, `Finding`, `Question`, `Term`, `Contract`,
`Model Element`, `Observation`, `Risk`, `Candidate`, `Work Item`, `Guide Step`, `Verdict`,
`Exception Record`, `Charter Grant`.

**6 Engineering Capabilities**, governed by a Capability Lifecycle: `Candidate → Designed →
Realized`, with `Deferred`/`Rejected` as terminal alternatives — **"Active" deliberately excluded**,
since nothing is currently authorized to operate. `EXISTING DESIGN`.

## 3 · What PKS actually computes — the central, disclosed tension

**PKS's own definition says it is "not software," yet its measured reality is executing PHP code**
identical in shape to EKS's capability layer (`IdentifierIntegrity`, `ReferenceIntegrity`,
`VocabularyIntegrity`). `OBSERVED` at the code-identity level, both directories confirmed identical
by direct file-system check. `PKS-Current-Architecture-Baseline-Stage-2.md`: *"PKS has no server, no
database, no daemon — knowledge specifications (YAML+Markdown) plus a set of command-line validation
programs."*

**This document does not resolve the tension. It is the central, genuine open question this whole
document exists to record.** See `EKS-PKS-Relationship.md` for how this same fact is used, from two
different angles, elsewhere in the research set — flagged there, not here, since this document's
job is to state the tension plainly, not adjudicate its downstream use.

**Concrete, real operations, measured:**
- `CAP-001` — identifier-collision prevention before minting (`PMR-10`). `OBSERVED`.
- `CAP-003` — stale-token/confusable-identifier detection, back-tested against 4 real historical
  defect classes at real historical commits, confirmed silent on the repaired commit. `OBSERVED`.
- `CAP-002`/`CAP-004` — `Deferred`, blocked on a named gap (`G-6`). `EXISTING DESIGN`.

**Not confirmed as real PKS operations:** semantic interpretation, relation discovery, graph
construction, reasoning, querying. `UNKNOWN` whether these are intended future capabilities or
outside PKS's actual scope.

## 4 · PKS's Python architecture — a question that may not be well-posed

Given §3's tension, a "Python architecture for PKS" is close to a category error — you do not
architect a specification format the way you architect a service.

**Reading A — PKS as specifications (its declared identity):** architecture = a YAML schema per
knowledge concept + a Markdown authoring convention; Python's role would be a schema validator, not
a domain/application/infrastructure architecture. `DERIVED`, not `OBSERVED` — no such validator was
found built in Python this session.

**Reading B — PKS as its measured capability layer:** identical question to EKS's — see
`EKS-Research-Definition.md` §8 — because it is, today, literally the same code template.

**This document takes no side between A and B.** Forcing one would resolve `OQ-11` by architectural
fiat rather than the governance ruling the corpus itself requires. `UNKNOWN`.

**What holds regardless of which reading is correct:**
- Whatever is built must not violate *"Governance Before Implementation — No decision is final
  without human approval. AI surfaces governance questions; it does not resolve them."* `EXISTING
  DESIGN`, verified verbatim.
- If Reading A is built, it should follow the same fail-closed discipline already proven
  elsewhere (`AP-8`), not invent a new one.
- No production architecture decision should precede an `OQ-11` ruling.

## 5 · Concepts belonging vs. not belonging to PKS

**Belongs:** the 17 knowledge concepts; the 6-capability lifecycle; governance-before-
implementation. `EXISTING DESIGN`.

**Does not belong, per PKS's own definition:** "AI guidance is one application of PKS, not PKS
itself"; documentation instructions are "one capability... not the PKS itself." `EXISTING DESIGN`.

**Ambiguous, not decided here:** whether the 4 real capability implementations are PKS's own
realized capabilities, EKS's, or shared — `OQ-11` restated at the implementation level, canonical
treatment in `EKS-PKS-Relationship.md`.

## 6 · PKS's own maturity, honestly stated

Phase I Strategic Discovery complete (2026-07-27); Phase II.A Strategic Modeling **in progress**
(`M0` complete, `M1` in progress as of its own writing, `M2`–`M8` planned). `EXISTING DESIGN`. This
document's own architectural interpretation is inherited as provisional from its source, not
overridden.

---

## Status table

| Question | Status |
|---|---|
| What PKS stands for | `EXISTING DESIGN` |
| PKS's core definition | `EXISTING DESIGN`, quoted directly |
| Spec-only vs. measured-code tension | `OBSERVED`, genuinely unresolved |
| PKS's Python architecture | `UNKNOWN` — the question itself may not cleanly apply |
| Relationship to EKS | pointer to `EKS-PKS-Relationship.md` — `UNKNOWN`/`OQ-11` |
| PKS's own maturity | `EXISTING DESIGN` — self-declared provisional |

**Traceability:** `docs/knowledge_tranfer/20260728_1710_what_is_pks_v1.md` · `docs/pks/README.md` ·
`PKS_Phase_III_Capability_Catalog.md` · `PKS-Current-Architecture-Baseline-Stage-2.md` · the
2026-08-21 Track-2 Phase-0 back-test plan (`CAP-003`) · `2026-09-29-KOS-EKS-PKS-RESEARCH-REVIEW.md` ·
superseded originals: `2026-09-29-KOS-PKS-RESEARCH-DEFINITION.md`,
`2026-09-29-KOS-PKS-PYTHON-ARCHITECTURE.md` (git history, commit `19d3cb7fa`).
