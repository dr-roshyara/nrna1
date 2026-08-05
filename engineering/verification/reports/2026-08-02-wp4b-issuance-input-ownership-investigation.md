# WP-4B — Issuance Input Ownership Investigation

*(Opened as the “dependency investigation”, then briefly the “provenance investigation”. **Retitled again 2026-08-02: even “provenance” presupposes that provenance is the root cause.** The neutral question is simply where the inputs come from — and whether they should be there at all.)*

**Date:** 2026-08-02 · **Prepared by:** Principal Engineer
**Question as opened:** **is the authority-decision intake a true implementation prerequisite for the conclude→issue seam, or only its production trigger?**
**Question as it turned out to be:** **where do `IssueDeterminationCommand`'s unsourced inputs come from — and is each of them rightly there?**
**Status:** investigation only. **No redesign · no governance proposal · no authorization recommendation.**

---

> # ANSWER — **the sequencing question is not answerable on its own, because a second gap sits underneath it.**
>
> **The sequencing debate alone does not explain the observed implementation gap. Repository evidence indicates an additional modelling question regarding the provenance of three issuance inputs.**
>
> **Three of the ten inputs `IssueDeterminationCommand` requires have no source in the concluded process record, and no source in the PM's decision-receiving signature either. That is the observation. What it *means* is §5's question, and it is not settled here.**

---

## 1. Repository evidence

### What the issuance command requires — ten inputs

`app/Contexts/Adjudication/Application/Command/IssueDeterminationCommand.php`:

```php
public ChallengeRef $challengeRef,  public DeterminationOutcome $outcome,
public Legitimacy $legitimacy,      public Reason $reason,
public IssuedByAuthority $issuedByAuthority,
public Jurisdiction $jurisdiction,
public EvidenceEnvelopeRef $evidenceEnvelopeRef,
public ContestedOutcomeRef $contestedOutcome,
public EvidenceSet $evidenceSet,    public DateTimeImmutable $occurredAt,
```

### What the concluded process record carries

`AdjudicationProcessState` — constructor fields and accessors:

| Command input | Available on the concluded state? | Source |
|---|---|---|
| `challengeRef` | ✅ | `challengeRef()` |
| `outcome` | ✅ | `outcome()` |
| `legitimacy` | ✅ | `legitimacy()` |
| `reason` | ✅ | `reason()` |
| `issuedByAuthority` | ✅ | `concludedByAuthority()` |
| `evidenceSet` | ✅ | `consideredEvidence()` |
| `occurredAt` | ✅ | `concludedAt()` |
| **`jurisdiction`** | ⛔ **absent** | — |
| **`evidenceEnvelopeRef`** | ⛔ **absent** | — |
| **`contestedOutcome`** | ⛔ **absent** | — |

### And the PM's decision-receiving signature does not supply them either

```php
public function receiveRulingDecision(
    ChallengeRef $challenge, DeterminationOutcome $outcome, Legitimacy $legitimacy,
    Reason $reason, IssuedByAuthority $authority, EvidenceSet $consideredEvidence,
): void
```

**Six parameters. None of them is `Jurisdiction`, `EvidenceEnvelopeRef` or `ContestedOutcomeRef`.**

> **So even a fully built authority-decision intake, calling this method exactly as written, would leave the same three inputs unsupplied.**

## 2. Dependency analysis

**The original question offered two readings. The evidence supports a third.**

| Reading | Verdict on the evidence |
|---|---|
| **A — WP-4D is a true implementation prerequisite** | ⛔ **not supported.** The intake would feed `receiveRulingDecision()`, whose signature carries none of the three missing inputs. **Building WP-4D changes nothing about the gap** |
| **B — WP-4D is only the production trigger, so WP-4B is buildable now** | ⛔ **not supported either.** The seam cannot construct its command from the concluded record, with or without a trigger |
| **C — an unexplained gap exists that neither reading accounts for** | ✅ **what the evidence shows.** **That the gap is a *provenance* problem is the leading reading, not a proven one — see §5** |

**Candidate sources exist in the repository for two of the three, and identifying them is not the same as choosing them:**

- **`ContestedOutcomeRef`** exists in **both** contexts — `Contestation/Domain/Challenge/ContestedOutcomeRef.php` and `Adjudication/Domain/Determination/ContestedOutcomeRef.php` — and Contestation's `ChallengeRaised` carries it. **But `ChallengeRouted`'s published payload is `challengeId · routedTo · occurredAt` only**, so Adjudication does not receive it over the wire today.
- **`EvidenceEnvelopeRef`** is Evidence-side language (`EvidenceRecorded` carries `envelopeHash`). The PM does admit evidence references — `admitEvidence(string $reference)` — but the concluded record exposes them as `admittedEvidence(): array` and `consideredEvidence(): ?EvidenceSet`, **neither of which is an `EvidenceEnvelopeRef`.**
- **`Jurisdiction`** has **no candidate source in the Adjudication context at all.** It appears only in the command, the aggregate, `DeterminationIssued`, the hydrator and the mapper — **every one of them a consumer, none a producer.**

**Which source is correct for each is a modelling question the repository does not settle. Naming the gap is this investigation's scope; closing it is not.**

## 3. Conclusion

> **The authority-decision intake is not established as a true implementation prerequisite for the conclude→issue seam, and it is not established as merely the production trigger either — because an unexplained gap sits beneath both readings.**
>
> **`IssueDeterminationCommand` requires three inputs — `Jurisdiction`, `EvidenceEnvelopeRef`, `ContestedOutcomeRef` — that the concluded process record does not hold and the PM's decision entry point does not accept.**

### The strongest statement the evidence supports

> **The repository does not currently identify an authoritative producer for these three inputs at determination issuance.**

**Nothing stronger holds.** It does **not** establish that the correct producer is any particular context, and it does **not** establish that the model is wrong. **Even calling this a *provenance* problem is one level of interpretation** — the three values might be supplied upstream, **derived**, **loaded**, **resolved through a service**, **owned by another aggregate**, or **the command may simply be oversized**. **None of those is ruled out.** §5 enumerates candidates without choosing among them.

## 4. Effect on WP-4B sequencing

| | |
|---|---|
| **WP-4D before WP-4B?** | **Not on this evidence.** The recorded sequencing assumption is **not confirmed** |
| **WP-4B buildable now?** | **No.** The alternative reading is **not confirmed either** |
| **What actually gates WP-4B** | **An unexplained gap in the issuance inputs** — whatever its cause, it sits *upstream* of both slices |
| **Effect on the discovery package** | **§3 dependency 6 is superseded by this investigation.** §6's verdict — *readiness cannot yet be determined* — **is unchanged, and now rests on a sharper reason** |
| **Effect on §7's next step** | **Answered. It should not be repeated** |

**One consequence worth stating plainly, because it is the useful part:** **ordering 4D before 4B, or 4B before 4D, does not on its own close the gap** — the three inputs are absent in both orders. **Sequencing is therefore not sufficient to explain or resolve what was observed.**


## 5. Ownership enumeration — candidates only, no recommendation

**Scope of this section: for each of the three values, enumerate every plausible producer visible in the repository, name the bounded context that owns each candidate today, and state the architectural consequence of that option.** **No option is selected. Selecting one is a model-ownership decision, and engineering does not take it.**

### 5.1 `Jurisdiction`

**What it is in Adjudication:** `final readonly class Jurisdiction` wrapping a **bare non-empty string** — *"the jurisdiction under which the determination is issued (50-05 `jurisdiction`)"*. **No structure, no link to any other concept.**

| Candidate producer | Owning context today | Architectural consequence |
|---|---|---|
| **Arrives with the authority's decision** | Governance-delegated authority, via the Q-1 crossing | Extends `receiveRulingDecision()`'s signature; **puts jurisdiction inside what K1 says the authority *decides*** — consistent with ADR-T23, *"the authority decides; the manager receives"* |
| **Resolved from `IssuedByAuthority`** | Adjudication | ⚠️ **`IssuedByAuthority` is also a bare string.** A resolution step would need a lookup Adjudication does not have, and would make Adjudication *derive* a fact about authority — **near the K1 line ADR-T23 draws** |
| **Supplied by Membership's existing jurisdiction model** | **Membership** — `Domain/Committee/ValueObjects/Jurisdiction.php` · `Constitutional/Jurisdiction/GovernanceJurisdictionId.php` · `Geo/Graph/JurisdictionNode.php` · `Ports/GeographicJurisdiction.php` | **A same-named concept already exists in another context, with far richer structure and no recorded relationship to Adjudication's string.** Consuming it is a **new context crossing**; ADR-T16 would require Adjudication to reconstruct locally from primitives |
| **The command is oversized** | — | If a determination does not in fact need a jurisdiction distinct from its authority, the field is a **modelling residue from 50-05**. **Round50-05 lists `jurisdiction` in `DeterminationIssued`'s payload, so removing it is a frozen-artifact question, not a refactor** |

> **Decisive observation: in Adjudication, `Jurisdiction` appears in the command, the aggregate, `DeterminationIssued`, the hydrator and the mapper — every one a consumer. There is no producer anywhere in the context.**

### 5.2 `EvidenceEnvelopeRef`

**What it is:** an opaque string reference — its own docblock says *"the EvidenceEnvelope's hash, **owned by the Evidence context**. Reference only (TP-1)."*

| Candidate producer | Owning context today | Architectural consequence |
|---|---|---|
| **The Evidence context, over the wire** | ⛔ **`app/Contexts/` contains no `Evidence/` directory.** The contexts present are Adjudication · Committee · Contestation · Election · Elections · Finance · Geography · Governance · Membership · Shared · Trust | **The owner the architecture names has no code home.** Round50-05 specifies `EvidenceRecorded` with `envelopeHash`, and the canonical catalog lists the event — **the context is specified and unbuilt** |
| **Derived from the admitted evidence the PM already holds** | Adjudication | ⚠️ **shape mismatch:** the PM holds `admittedEvidence(): array` and `consideredEvidence(): ?EvidenceSet`, and **`EvidenceSet` is a `non-empty-list<string>`** — **plural**. `EvidenceEnvelopeRef` is **singular**. **Which member, or whether the envelope is a distinct artifact over the set, is recorded nowhere** |
| **Arrives with the authority's decision** | the deciding authority | Makes the envelope part of *what was decided upon* rather than *what was assembled* — **a different reading of R-4-expanded's considered-set seating**, which EPIC-004K §11 places at the aggregate |
| **The command is oversized** | — | If `EvidenceSet` already fixes the considered basis, a separate envelope reference may be **redundant with it**. **Both appear in the command, and no artifact states how they relate** |

### 5.3 `ContestedOutcomeRef`

**What it is:** a **structured** VO — `ElectionId` · `TargetType` · `TargetId` — and **the only one of the three already modelled in two contexts by design**: `Contestation/Domain/Challenge/ContestedOutcomeRef.php` and `Adjudication/Domain/Determination/ContestedOutcomeRef.php`, the latter documented as *"Adjudication does NOT import Contestation's VO; duplicate concept, own reconstruction"* (ADR-T16 · ADR-UL-01).

| Candidate producer | Owning context today | Architectural consequence |
|---|---|---|
| **Carried on `ChallengeRouted`'s payload** | Contestation | ⛔ **Today the payload is `challengeId · routedTo · occurredAt` only.** Adding a field is an **additive `schema_version` bump under ADR-T5**, and it extends the **published language WP-3A just closed under R-67** |
| **Carried on `ChallengeRaised`** | Contestation | **`ChallengeRaised` already carries `ContestedOutcomeRef` in its constructor** — but **`ChallengeOutboxAdapter` maps only `ChallengeRouted`, `ChallengeAdjudicated` and `ChallengeResolvedIntegration`. `ChallengeRaised` is not published**, so Adjudication cannot consume it today |
| **Queried back from Contestation** | Contestation | **A synchronous cross-context read. TP-1 states contexts collaborate only via events** — listed because enumeration is the task, **not because it is viable** |
| **Arrives with the authority's decision** | the deciding authority | Places the identity of *what is being ruled on* inside the ruling rather than the routing — **coherent, but it makes the authority the source of a fact Contestation already owns** |

> **This value is the best-evidenced of the three: it exists, it is structured, it is already modelled in both contexts, and Contestation demonstrably holds it. What is missing is only a path.**

## 6. What the enumeration establishes — and what it does not

| | |
|---|---|
| **Established** | Three inputs have **no demonstrated provenance**. For **`ContestedOutcomeRef`** a holder exists (Contestation) but no path. For **`EvidenceEnvelopeRef`** the named owner — **Evidence** — has **no code home**. For **`Jurisdiction`** there is **no producer in Adjudication at all**, and a **same-named, richer concept in Membership with no recorded relationship** |
| **Not established** | **Which candidate is correct for any of the three.** Each implies a different owner, and two of them — extending `ChallengeRouted`'s published payload, or consuming Membership's jurisdiction model — **would change a context boundary** |
| **Not attempted** | Any recommendation. **The trade-offs are presented for ARB deliberation; engineering stops at the evidence** |

**One cross-cutting observation, recorded because it bears on all three:** **every candidate that routes a value through the authority's decision would extend `receiveRulingDecision()`, whose six parameters are today the whole of what the APM accepts from an authority.** **How wide that signature should be is itself a modelling question — and it is the same question WP-4D must answer when it defines the intake port.**

---

**Traceability:** `app/Contexts/Adjudication/Domain/Determination/{Jurisdiction,EvidenceEnvelopeRef,ContestedOutcomeRef,EvidenceSet,IssuedByAuthority}.php` · `app/Contexts/Membership/Domain/Committee/ValueObjects/Jurisdiction.php` · `.../Constitutional/Jurisdiction/GovernanceJurisdictionId.php` · `app/Contexts/` (**no `Evidence/` directory**) · `docs/architecture/design/Round50-05_Event_Catalogue.md` §1 · **ADR-T5 · ADR-T16 · ADR-T23 · ADR-UL-01 · TP-1 · K1** · `app/Contexts/Adjudication/Application/Command/IssueDeterminationCommand.php` · `.../Process/AdjudicationProcessState.php` · `.../Process/AdjudicationProcessManager.php::receiveRulingDecision` · `.../Domain/Determination/{Jurisdiction,EvidenceEnvelopeRef,ContestedOutcomeRef}.php` · `app/Contexts/Contestation/Domain/Challenge/ContestedOutcomeRef.php` · `.../Domain/Events/ChallengeRaised.php` · `app/Contexts/Contestation/Infrastructure/Outbox/ChallengeOutboxAdapter.php` (the `ChallengeRouted` payload) · `engineering/verification/reports/2026-08-02-wp4b-engineering-discovery.md` §3 · §6 · §7 · roadmap §WP-4. **Investigation only — nothing designed, nothing implemented, nothing ruled.**
