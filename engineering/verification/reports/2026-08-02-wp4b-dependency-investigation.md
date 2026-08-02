# WP-4B — Dependency Investigation

**Date:** 2026-08-02 · **Prepared by:** Principal Engineer
**Question:** **Is the authority-decision intake a true implementation prerequisite for the conclude→issue seam, or only its production trigger?**
**Status:** investigation only. **No redesign · no governance proposal · no authorization recommendation.**

---

> # ANSWER — **Neither reading is right, and the question has a larger answer than it assumed.**
>
> **The authority-decision intake is *not* established as the seam's prerequisite.** **But the seam is not buildable today either — for a different and previously unrecorded reason.**
>
> **Three of the ten inputs `IssueDeterminationCommand` requires have no source in the concluded process record, and no source in the PM's decision-receiving signature either.**

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
| **C — the prerequisite is the *provenance of three issuance inputs*, and it is orthogonal to the trigger** | ✅ **what the evidence shows** |

**Candidate sources exist in the repository for two of the three, and identifying them is not the same as choosing them:**

- **`ContestedOutcomeRef`** exists in **both** contexts — `Contestation/Domain/Challenge/ContestedOutcomeRef.php` and `Adjudication/Domain/Determination/ContestedOutcomeRef.php` — and Contestation's `ChallengeRaised` carries it. **But `ChallengeRouted`'s published payload is `challengeId · routedTo · occurredAt` only**, so Adjudication does not receive it over the wire today.
- **`EvidenceEnvelopeRef`** is Evidence-side language (`EvidenceRecorded` carries `envelopeHash`). The PM does admit evidence references — `admitEvidence(string $reference)` — but the concluded record exposes them as `admittedEvidence(): array` and `consideredEvidence(): ?EvidenceSet`, **neither of which is an `EvidenceEnvelopeRef`.**
- **`Jurisdiction`** has **no candidate source in the Adjudication context at all.** It appears only in the command, the aggregate, `DeterminationIssued`, the hydrator and the mapper — **every one of them a consumer, none a producer.**

**Which source is correct for each is a modelling question the repository does not settle. Naming the gap is this investigation's scope; closing it is not.**

## 3. Conclusion

> **The authority-decision intake is not a true implementation prerequisite for the conclude→issue seam, and it is not merely the production trigger either — because the seam has a prerequisite neither WP-4B nor WP-4D as currently described would satisfy.**
>
> **`IssueDeterminationCommand` requires three inputs — `Jurisdiction`, `EvidenceEnvelopeRef`, `ContestedOutcomeRef` — that the concluded process record does not hold and the PM's decision entry point does not accept.**

**Stated at the strength the evidence supports:** the gap is **observable and exact** — three named parameters, one signature, one state class. **Its cause is not.** Whether the three should ride in with the authority decision, be carried forward from the challenge, be derived from admitted evidence, or be sourced some other way is **undetermined by the repository**, and each answer implies a different owner.

## 4. Effect on WP-4B sequencing

| | |
|---|---|
| **WP-4D before WP-4B?** | **Not on this evidence.** The recorded sequencing assumption is **not confirmed** |
| **WP-4B buildable now?** | **No.** The alternative reading is **not confirmed either** |
| **What actually gates WP-4B** | **The provenance of three issuance inputs** — a question that sits *upstream* of both slices |
| **Effect on the discovery package** | **§3 dependency 6 is superseded by this investigation.** §6's verdict — *readiness cannot yet be determined* — **is unchanged, and now rests on a sharper reason** |
| **Effect on §7's next step** | **Answered. It should not be repeated** |

**One consequence worth stating plainly, because it is the useful part:** **the two-slice sequencing debate was the wrong axis.** **Ordering 4D before 4B, or 4B before 4D, resolves nothing on its own** — the missing inputs are absent in both orders.

---

**Traceability:** `app/Contexts/Adjudication/Application/Command/IssueDeterminationCommand.php` · `.../Process/AdjudicationProcessState.php` · `.../Process/AdjudicationProcessManager.php::receiveRulingDecision` · `.../Domain/Determination/{Jurisdiction,EvidenceEnvelopeRef,ContestedOutcomeRef}.php` · `app/Contexts/Contestation/Domain/Challenge/ContestedOutcomeRef.php` · `.../Domain/Events/ChallengeRaised.php` · `app/Contexts/Contestation/Infrastructure/Outbox/ChallengeOutboxAdapter.php` (the `ChallengeRouted` payload) · `engineering/verification/reports/2026-08-02-wp4b-engineering-discovery.md` §3 · §6 · §7 · roadmap §WP-4. **Investigation only — nothing designed, nothing implemented, nothing ruled.**
