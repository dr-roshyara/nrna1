# ARB Decision Package — Ownership of Three Issuance Inputs

**Date:** 2026-08-02 · **Prepared by:** Recording Architect
**Purpose:** enable ARB deliberation on **bounded-context ownership**. **Not to redesign the system.**
**Status:** **CONVENED — no recommendation is made and no ruling is issued here.**

> **The engineering investigation has completed its mandate** (`engineering/verification/reports/2026-08-02-wp4b-issuance-input-ownership-investigation.md`). **This package reframes its evidence as a decision, and adds one thing engineering did not supply: which issued decisions each option would touch.**

---

## 1. The decision before the Board

> ### Which bounded context is the authoritative owner of each of these three values at determination issuance?
>
> **`Jurisdiction` · `EvidenceEnvelopeRef` · `ContestedOutcomeRef`**

**Three separate decisions, not one.** The evidence differs sharply per value, and nothing requires them to resolve the same way.

## 2. Repository-supported observations — the whole factual basis

| # | Observation | How it was established |
|---|---|---|
| 1 | `IssueDeterminationCommand` requires **ten** inputs | the constructor |
| 2 | The concluded `AdjudicationProcessState` supplies **seven** — `challengeRef` · `outcome` · `legitimacy` · `reason` · `concludedByAuthority` · `consideredEvidence` · `concludedAt` | its fields and accessors |
| 3 | **Three are unsourced:** `Jurisdiction` · `EvidenceEnvelopeRef` · `ContestedOutcomeRef` | 1 minus 2 |
| 4 | `receiveRulingDecision()` takes **six** parameters and **none of them is one of the three** | the signature |
| 5 | **No production caller connects a concluded process to issuance** | `grep -rn "issueDetermination" app/` |
| 6 | In Adjudication, `Jurisdiction` appears only in the command, the aggregate, `DeterminationIssued`, the hydrator and the mapper — **all consumers, no producer** | repository-wide grep |
| 7 | A **same-named, structurally richer** `Jurisdiction` model exists in **Membership**, with **no recorded relationship** to Adjudication's string | `Membership/Domain/Committee/...` |
| 8 | `EvidenceEnvelopeRef`'s docblock names the **Evidence context** as owner; **`app/Contexts/` has no `Evidence/` directory** | the docblock · the directory listing |
| 9 | `EvidenceSet` is a `non-empty-list<string>` — **plural**; `EvidenceEnvelopeRef` is **singular**; **no artifact records how they relate** | the VOs |
| 10 | `ContestedOutcomeRef` is **modelled in both contexts by design** and **carried by `ChallengeRaised`** — but **`ChallengeRaised` is not published**, and `ChallengeRouted`'s payload is `challengeId · routedTo · occurredAt` | the VOs · the event · `ChallengeOutboxAdapter` |

> **The strongest statement these ten support:** **the repository does not currently identify an authoritative producer for these three inputs at determination issuance.** **They do not establish that any particular context is the correct owner, and they do not establish that the model is wrong.**

## 3. Candidate owners and their consequences

### 3.1 `Jurisdiction`

| Candidate owner | Consequence | Issued decisions affected |
|---|---|---|
| **The deciding authority** (Governance-delegated, via the Q-1 crossing) | widens `receiveRulingDecision()`; places jurisdiction inside what the authority *decides* | **ADR-T23 · K1 · Q-1** — *consistent with* "the authority decides, the manager receives"; **defines part of WP-4D's intake port** |
| **Membership** (its existing jurisdiction model) | **a new context crossing.** Adjudication would reconstruct locally from primitives | **ADR-T16** (identity crosses as strings) · **TP-1** (contexts collaborate only via events) — **and no jurisdiction event exists in the Canonical Event Catalog, so one would have to be created**; **the context map changes** |
| **Adjudication, resolved from `IssuedByAuthority`** | Adjudication would *derive* a fact about authority | **ADR-T23 · K1** — **the line ADR-T23 draws is exactly this**; `IssuedByAuthority` is itself a bare string with no lookup |
| **Nobody — the field is a residue** | remove it from the command and the event | **Round50-05 §1 · Canonical Event Catalog v1.0 (🧊 FROZEN) · ADR-T5** (*"version, never mutate"*) — **a v1.1/v2 act, not a refactor** |

### 3.2 `EvidenceEnvelopeRef`

| Candidate owner | Consequence | Issued decisions affected |
|---|---|---|
| **The Evidence context** | **the specified owner has no code home** — the decision is really *when Evidence gets built*, or what stands in until it does | **Round50-05 · the Canonical Event Catalog** (both specify `EvidenceRecorded`/`envelopeHash`) · **TP-1** (reference only) |
| **Adjudication, derived from admitted evidence** | must resolve the **plural-vs-singular** mismatch: which member of `EvidenceSet`, or a distinct artifact over it? | **EPIC-004K §11 / R-4-expanded** (the considered set is seated at the aggregate) · **ADR-T22** (WP-1's `EvidenceSet`) · **INV-4** (single fixation) |
| **The deciding authority** | the envelope becomes part of *what was decided upon* rather than *what was assembled* | **ADR-T23** · **EPIC-004K §11** — **a different reading of where fixation is seated** |
| **Nobody — redundant with `EvidenceSet`** | remove it | **Round50-05 · the frozen catalog · ADR-T5 · INV-4** |

### 3.3 `ContestedOutcomeRef`

| Candidate owner | Consequence | Issued decisions affected |
|---|---|---|
| **Contestation, via `ChallengeRouted`'s payload** | additive field, `schema_version` bump | **ADR-T5** (additive vN+1) · **ADR-T21** (published language) · **R-67 — the published language accepted hours ago; this reopens its payload** · hydrator window vCurrent+vPrevious |
| **Contestation, via publishing `ChallengeRaised`** | a **new published event**; the outbox maps only Routed/Adjudicated/Resolved today | **ADR-T5 · the Canonical Event Catalog** (`ChallengeRaised` is listed, producer Contestation) · **Round50-05 §2** (its consumer set) |
| **Contestation, queried synchronously** | ⛔ **contradicts a standing rule** | **TP-1** — listed for completeness, not viability |
| **The deciding authority** | the authority becomes the source of a fact **Contestation already owns** | **ADR-T23 · ADR-UL-01** (the term's definition) |

## 4. What is common to every option

**Two cross-cutting consequences the Board should weigh once, not three times:**

1. **Every "authority" option widens `receiveRulingDecision()`** — today six parameters, and the whole of what the APM accepts from an authority. **How wide that signature should be is the same question WP-4D must answer when it defines the intake port.** **Deciding these three inputs partly decides WP-4D.**
2. **Two options change a context boundary** — consuming Membership's jurisdiction model, and extending Contestation's published payload. **Both are strategic, not tactical, and neither is engineering's to take.**

## 5. Scope of this package

**Included:** repository-supported observations · candidate owners already identified · architectural consequences · the issued decisions each option would affect.

**Deliberately excluded:** **any preferred solution · any model change · any implementation guidance · any ruling.** **No option in §3 is marked as favoured, and the ordering within each table carries no preference.**

**Not in this package:** WP-4B's authorization · WP-4C · WP-4D's definition · WP-8 · the register-recording gap on WP-3A/WP-4A · contract R-2's missing ADR home.

---

**Traceability:** `engineering/verification/reports/2026-08-02-wp4b-issuance-input-ownership-investigation.md` (the evidence) · `…-wp4b-engineering-discovery.md` · **ADR-T5 · ADR-T16 · ADR-T21 · ADR-T22 · ADR-T23 · ADR-UL-01 · TP-1 · K1 · Q-1 · INV-4** · `docs/architecture/design/Round50-05_Event_Catalogue.md` · `docs/implementation/Canonical_Event_Catalog_v1.0.md` (🧊 frozen) · `docs/implementation/EPIC-004K_*.md` §9 · §10 · §11 · **R-67 · R-68 · R-69**. **Framing only — no observation extended, no option recommended, no model changed, no ruling issued.**
