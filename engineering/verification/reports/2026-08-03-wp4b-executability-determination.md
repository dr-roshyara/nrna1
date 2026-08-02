# WP-4B — Executability Determination under the Adopted Governing Model

**Date:** 2026-08-03 · **Prepared by:** Recording Architect
**Basis:** **R-73 · R-74 · R-75 · R-76** (ARB, 2026-08-03) and **R-72** (authorization).
**Question the Board set:** *after the four decisions are recorded, has WP-4B become executable?*

---

> # ⛔ **NO — and the reason has changed in kind.**
>
> **Before the session, WP-4B was blocked by AMBIGUITY: three inputs with no decided source.** **After it, WP-4B is blocked by UNBUILT WORK: three inputs whose sources are now decided and do not yet exist.**
>
> **That is real progress and it is not executability.** The Board asked to leave with one of two outcomes; **this is the second — the remaining blockers, explicitly identified.**

---

## 1. The adopted governing model

| Value | Authoritative source | Path |
|---|---|---|
| `Jurisdiction` | the **deciding authority** | carried on the authority's decision (R-73) |
| `EvidenceEnvelopeRef` | the **Evidence** bounded context | an integration event (R-74) |
| `ContestedOutcomeRef` | **Contestation** | a published `ChallengeRaised` (R-75) |
| **WP-4B scope** | — | **request path only**; confirmation excluded (R-76) |

**The model is internally consistent: every value has exactly one owner, every owner is the context the ubiquitous language already assigns it to, and no value is derived by a context that does not own it.**

## 2. What each decision requires before RED can reach GREEN

| # | Prerequisite created by the decision | Exists? |
|---|---|---|
| **P1** | **`receiveRulingDecision()` widens** to accept `Jurisdiction` from the authority — and that signature **is** the authority-decision intake shape | ⛔ **no.** That shape is **WP-4D**, unbuilt and unauthorized |
| **P2** | **The Evidence bounded context exists** and publishes an event carrying `envelopeHash` | ⛔ **no.** `app/Contexts/` has **no `Evidence/` directory.** Evidence is **not one of the eight roadmap work packages** |
| **P3** | **Contestation publishes `ChallengeRaised`** — outbox mapping · hydrator · catalog visibility · an Adjudication-side consumer that retains the value | ⛔ **no.** `ChallengeOutboxAdapter` maps only Routed, Adjudicated and Resolved |
| **P4** | WP-4B's scope is unambiguous | ✅ **yes — R-76 closed it.** RED carries the roadmap keystone and four keystones, not six |

**P4 was one of the four blockers and is now removed. P1–P3 replace three open questions with three concrete builds.**

## 3. What this means for sequencing — recorded, not proposed

**R-73 places WP-4D upstream of WP-4B.** The intake port was previously described as a *recorded external*; **the adopted model makes it a prerequisite**, because the value arrives through a signature only WP-4D defines.

**R-74 introduces a dependency outside the roadmap's eight work packages.** **Evidence is specified in Round50-05 and the canonical catalog, and has never been built.** **Nothing in §WP-4 covers it.**

**R-75 creates Contestation-side work allocated to no current work package** — and it is the counterpart of the publication half WP-3A delivered, on a different event.

> **Three prerequisites, and none of them is inside WP-4B. Under the adopted model WP-4B is the LAST of four builds, not the next one.**

**Architecture proposes no re-sequencing, no new work packages, and no interim substitutes. Those are governance acts, and the Board has not been asked for them.**

## 4. What engineering may do now

**Nothing on WP-4B.** RED can be written; **it cannot reach GREEN**, because the command cannot be constructed from sources that do not exist. **Writing RED against three absent producers would produce a boundary that must be discarded when they arrive.**

**Engineering is idle on this path by consequence of the adopted model, not by disagreement with it.** **R-76's answer would have been enough on its own; R-73–R-75 each added a build.**

## 5. Recorded, not raised for decision

**H1–H4 are settled by implication:** R-74 rejects H2 and H3 (`EvidenceEnvelopeRef` is neither redundant with `EvidenceSet` nor a projection over it); R-73 rejects H1 (`Jurisdiction` is not a residue — it has an owner). **H4 is untouched.**

**Still unallocated after this session:** PM-6's issuance-confirmation responsibility (R-76 excludes it from WP-4B and assigns it to no successor) · the Contestation-side publication work (R-75) · the Evidence context itself (R-74).

---

**Traceability:** **R-72 · R-73 · R-74 · R-75 · R-76** · `engineering/verification/commissions/2026-08-02-issuance-input-ownership-decision-package.md` · `engineering/verification/reports/2026-08-02-wp4b-engineering-discovery.md` §2 · §6 · `app/Contexts/Adjudication/Application/Command/IssueDeterminationCommand.php` · `.../Process/AdjudicationProcessManager.php::receiveRulingDecision` · `app/Contexts/Contestation/Infrastructure/Outbox/ChallengeOutboxAdapter.php` · `app/Contexts/` (no `Evidence/`) · `docs/architecture/design/Round50-05_Event_Catalogue.md` · `docs/implementation/Canonical_Event_Catalog_v1.0.md`. **Determination only — no architecture proposed, no re-sequencing recommended, no work package created, no code written.**
