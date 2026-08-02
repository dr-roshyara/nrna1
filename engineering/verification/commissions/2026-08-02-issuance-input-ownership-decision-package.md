# ARB Decision Package — Ownership of Three Issuance Inputs

**Date:** 2026-08-02 · **Prepared by:** Recording Architect
**Purpose:** enable ARB deliberation on **bounded-context ownership**. **Not to redesign the system.**
**Status:** **CONVENED — no recommendation is made and no ruling is issued here.**

> **The engineering investigation has completed its mandate** (`engineering/verification/reports/2026-08-02-wp4b-issuance-input-ownership-investigation.md`). **This package reframes its evidence as a decision, and adds one thing engineering did not supply: which issued decisions each option would touch.**

---

## 1. The decisions before the Board

> ### For each of three values — who **owns** the concept, by what **path** does it reach issuance, and should the field **exist at all**?
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

## 3. Three dimensions, kept apart

**The earlier draft collapsed three different questions into one table of "candidate owners". They are not the same question, and conflating them would have put a non-answer beside two answers:**

| Dimension | The question | Who decides |
|---|---|---|
| **Ownership** | **which bounded context is the authoritative owner of the concept?** | **ARB — strategic** |
| **Transport / source** | **by what path does the value reach issuance?** | **ARB or architecture — tactical, and constrained by TP-1 / ADR-T5 / ADR-T16** |
| **Model simplification** | **should the field exist at all?** | **ARB — it touches frozen artifacts** |

> **Ownership and transport are compatible, not competing. Contestation may *own* `ContestedOutcomeRef` while the authority's decision *carries* it. Evidence may *own* `EvidenceEnvelopeRef` while Adjudication *receives* it by integration event.** **Deciding one does not decide the other.**

## 4. Dimension 1 — Ownership candidates

| Value | Candidate owners | Evidence bearing on ownership |
|---|---|---|
| **`Jurisdiction`** | **the deciding authority** (Governance-delegated, via Q-1) · **Membership** · **Adjudication itself** | Adjudication holds only a **bare string with no producer**. **Membership holds a structurally richer, same-named model** with **no recorded relationship**. ADR-T23/K1 place what the authority *decides* with the authority |
| **`EvidenceEnvelopeRef`** | **the Evidence context** — *"owned by the Evidence context"*, per the VO's own docblock | **Not genuinely contested.** The documented owner is explicit; **the difficulty is that `app/Contexts/` has no `Evidence/` directory** |
| **`ContestedOutcomeRef`** | **Contestation** | **Not genuinely contested either.** ADR-UL-01 defines the term, Contestation's `Challenge` holds it, and Adjudication's copy is documented as a **local reconstruction** under ADR-T16 |

> ### ⚠️ Separating the dimensions changes what the Board is actually being asked
>
> **Only `Jurisdiction` presents a real ownership question.** **For the other two, ownership is already settled by issued decisions and by the code's own documentation — what is unresolved is *transport*.**
>
> **That is a materially smaller decision than the earlier draft implied.**

| Value | Ownership | Transport |
|---|---|---|
| `Jurisdiction` | ⚠️ **open** | ⚠️ open — and dependent on the ownership answer |
| `EvidenceEnvelopeRef` | ✅ documented (Evidence) | ⚠️ **open — and the owner is unbuilt** |
| `ContestedOutcomeRef` | ✅ documented (Contestation) | ⚠️ **open — a path is missing, nothing else** |

## 5. Dimension 2 — Transport / source options, with consequences

### 5.1 `Jurisdiction`

| Path | Consequence | Issued decisions affected |
|---|---|---|
| **Carried on the authority's decision** | widens `receiveRulingDecision()` | **ADR-T23 · K1 · Q-1** — *consistent with* "the authority decides, the manager receives"; **defines part of WP-4D's intake port** |
| **Supplied by Membership over the wire** | **a new context crossing**; Adjudication reconstructs locally from primitives | **ADR-T16 · TP-1** — **no jurisdiction event exists in the Canonical Event Catalog, so one must be created**; **the context map changes** |
| **Resolved locally from `IssuedByAuthority`** | Adjudication would *derive* a fact about authority | **ADR-T23 · K1** — **exactly the line ADR-T23 draws**; `IssuedByAuthority` is itself a bare string with no lookup |

### 5.2 `EvidenceEnvelopeRef`

| Path | Consequence | Issued decisions affected |
|---|---|---|
| **An Evidence integration event** | **the documented owner has no code home** — the real question becomes *when Evidence is built, or what stands in until then* | **Round50-05 · the Canonical Event Catalog** (both specify `EvidenceRecorded`/`envelopeHash`) · **TP-1** (reference only) |
| **Carried on the authority's decision** | the envelope becomes part of *what was decided upon* rather than *what was assembled* | **ADR-T23 · EPIC-004K §11** — **a different reading of where fixation is seated** |
| **Carried forward from the PM's admitted evidence** | must resolve the **plural-vs-singular** mismatch | **EPIC-004K §11 / R-4-expanded · ADR-T22 · INV-4** |

### 5.3 `ContestedOutcomeRef`

| Path | Consequence | Issued decisions affected |
|---|---|---|
| **Additive field on `ChallengeRouted`** | `schema_version` bump; hydrator window vCurrent+vPrevious | **ADR-T5 · ADR-T21** — **and it reopens the payload of the published language R-67 accepted hours ago** |
| **Publish `ChallengeRaised`** | a **new published event**; the outbox maps only Routed/Adjudicated/Resolved today | **ADR-T5 · the Canonical Event Catalog** (`ChallengeRaised` is listed, producer Contestation) · **Round50-05 §2** |
| **Carried on the authority's decision** | the authority carries a fact **Contestation owns** — **permissible, since ownership and transport are separate** | **ADR-T23 · ADR-UL-01** |
| **Queried synchronously from Contestation** | ⛔ **contradicts a standing rule** | **TP-1** — listed for completeness, **not viability** |

## 6. Dimension 3 — Model simplification hypotheses

**These are not ownership candidates. They are the hypothesis that a field should not be sourced because it should not exist.** **Separated so that "nobody owns it" is never mistaken for an ownership answer.**

| # | Hypothesis | What would have to be true | Issued decisions affected |
|---|---|---|---|
| **H1** | **`Jurisdiction` is a residue of 50-05** | a determination needs no jurisdiction distinct from its authority | **Round50-05 §1 · Canonical Event Catalog v1.0 (🧊 FROZEN) · ADR-T5** — *"version, never mutate"*: **a v1.1/v2 act, not a refactor** |
| **H2** | **`EvidenceEnvelopeRef` is redundant with `EvidenceSet`** | the considered set already fixes the basis; **both are in the command and no artifact states how they relate** | **INV-4** (single fixation) · **ADR-T22 · EPIC-004K §11** · the frozen catalog |
| **H3** | **`EvidenceEnvelopeRef` is a derived projection over `EvidenceSet`** | the singular is computable from the plural | **ADR-T22 · INV-4** — and it would make the field **derivable, not sourced** |
| **H4** | **`IssueDeterminationCommand` is oversized** | one or more inputs belong to a later act, not to issuance | **Round50-05 · the frozen catalog · INV-4 · EPIC-004K §11** |

> **No simplification hypothesis is identified for `ContestedOutcomeRef`.** **Its concept is defined by ADR-UL-01, held by Contestation and reconstructed locally by Adjudication under ADR-T16 — there is no evidence the field is spurious.** **For that value, transport is the whole of the open question.**

## 7. What is common across the options

**Two cross-cutting consequences the Board should weigh once, not three times:**

1. **Every authority-carried *transport* option widens `receiveRulingDecision()`** — today six parameters, and the whole of what the APM accepts from an authority. **How wide that signature should be is the same question WP-4D must answer when it defines the intake port.** **Deciding these three inputs partly decides WP-4D.**
2. **Two transport options change a context boundary** — consuming Membership's jurisdiction model, and extending Contestation's published payload. **Both are strategic, not tactical, and neither is engineering's to take.**

## 8. Scope of this package

**Included:** repository-supported observations · **ownership candidates** · **transport options** · **model-simplification hypotheses**, kept as three distinct dimensions · architectural consequences · the issued decisions each option would affect.

**Deliberately excluded:** **any preferred solution · any model change · any implementation guidance · any ruling.** **No option in §4, §5 or §6 is marked as favoured, and the ordering within each table carries no preference.**

**Not in this package:** WP-4B's authorization · WP-4C · WP-4D's definition · WP-8 · the register-recording gap on WP-3A/WP-4A · contract R-2's missing ADR home.

---

**Traceability:** `engineering/verification/reports/2026-08-02-wp4b-issuance-input-ownership-investigation.md` (the evidence) · `…-wp4b-engineering-discovery.md` · **ADR-T5 · ADR-T16 · ADR-T21 · ADR-T22 · ADR-T23 · ADR-UL-01 · TP-1 · K1 · Q-1 · INV-4** · `docs/architecture/design/Round50-05_Event_Catalogue.md` · `docs/implementation/Canonical_Event_Catalog_v1.0.md` (🧊 frozen) · `docs/implementation/EPIC-004K_*.md` §9 · §10 · §11 · **R-67 · R-68 · R-69**. **Framing only — no observation extended, no option recommended, no model changed, no ruling issued.**
