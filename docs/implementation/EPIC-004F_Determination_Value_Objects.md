# EPIC-004F Determination Value Objects

**Kind:** Tactical DDD artifact №5 (one artifact → ARB review → refine → freeze → next). **Role:** implementation architect from the frozen baseline.
**Entry condition (ARB, binding):** *every proposed Value Object must trace back to one or more frozen business invariants (artifact №4). If removing the Value Object does not weaken the expression, validation, or protection of an accepted invariant, it should not become a Value Object.*
**Derivation direction (ARB, binding):** Protected Domain Truth → Business Invariant → **concepts needed to express the invariant** → candidate Value Objects. *A Value Object exists because the business needs a concept to express or protect an invariant — never because several fields happen to travel together.*
**The honest shape of this artifact:** the confirmed Determination aggregate already carries **thirteen implemented value concepts** (nine string-backed VOs, four enums). This artifact is therefore a **derivation-and-reconciliation audit**: derive the concept needs from the frozen invariants, test every existing VO against the entry condition's removal test, and evaluate the tempting additions — expecting the entry condition to reject some (Methodological Fitness Rule).
**Inputs (frozen):** artifact №4's eight invariants · artifact №3's truths · Q-1 Resolution + binding constraint · ADR-T16 (local VOs; string crossings) · the implemented VO inventory (EPIC-003; cited as evidence, never extended here — no new code).

---

## 1. Derivation: concepts each invariant needs

| Invariant | Concepts needed to express/protect it |
|---|---|
| INV-1/INV-2 (lawful progression; absolute finality) | the record's lifecycle position |
| INV-3 (one act of ruling) | the determination's identity |
| INV-4 (fixation is one fact) | the ruling's content — outcome, legitimacy, reason — and its basis: the challenge, the evidence, the contested outcome |
| INV-5 (no unattributed record, ever) | concepts that **cannot exist empty**: the authority assertion, the evidence reference, the challenge reference — their non-emptiness IS the invariant's current mechanism |
| INV-6 (accountability triple inseparable) | who issued (authority) · under what jurisdiction · on what evidence |
| INV-7 (no linkage-enabling content) | not a concept — a **constraint on every concept**: no VO may carry or derive voter identity |
| INV-B1 (one per challenge — boundary) | the challenge reference, as the uniqueness key the boundary rule is expressed over |

## 2. Reconciliation: the existing inventory against the entry condition

*Removal test per VO: what invariant weakens if this concept is removed?*

| Existing VO / enum | Parent invariant(s) | Removal test | Verdict |
|---|---|---|---|
| `DeterminationState` (enum) | INV-1, INV-2 | Lawful progression becomes unexpressible — no lifecycle position to guard | **CONFIRMED** |
| `DeterminationId` | INV-3 | "One act of ruling *per determination*" loses its subject | **CONFIRMED** |
| `DeterminationOutcome` (enum, closed set) | INV-4 | The fixed content loses its decision dimension; the closed set (Upheld/Dismissed) is itself protection — no third outcome can be fixed | **CONFIRMED** |
| `Legitimacy` (enum, closed set) | INV-4 | Same — the legitimacy dimension of the fixed content | **CONFIRMED** (bounded by ADR-T17: a received value, never computed here) |
| `Reason` | INV-4, INV-6 | A ruling could be fixed without justification — answerability weakens | **CONFIRMED** |
| `ChallengeRef` | INV-4, INV-5, INV-B1 | Fixation loses its subject-challenge; the boundary uniqueness rule loses its key | **CONFIRMED** (ADR-T16 opaque crossing) |
| `EvidenceEnvelopeRef` | INV-4, INV-5, INV-6 | The ruling's evidence basis becomes unfixed; unattributed-to-evidence rulings become possible | **CONFIRMED** (opaque; its referent is the future Collection contract — unchanged here) |
| `IssuedByAuthority` | INV-5, INV-6 | The accountable-issuer truth loses its concept — the exact anti-truth INV-5 forbids | **CONFIRMED** (presence-only semantics per Q-1; never validity) |
| `Jurisdiction` | INV-6 | The triple's jurisdiction leg weakens | **CONFIRMED — with an honest flag:** it traces (T-6 names jurisdiction), but its business semantics remain underspecified (the discovery marked its meaning "undiscovered"). Kept on traceability; **semantic clarification recorded as an open item** — not blocking, not silently ignored |
| `ContestedOutcomeRef` (composite) | INV-4 | The fixed record loses *what was contested* | **CONFIRMED** — and legitimately composite: ADR-UL-01's documented closed structure (election + target type + target id), not fields-traveling-together |
| `ElectionId` · `TargetId` · `TargetType` (components) | INV-4 via ContestedOutcomeRef | Components of the above; TargetType's CLOSED SET is protection (only ElectionResult/Determination may be contested) | **CONFIRMED as components** |

**Cross-cutting:** INV-7 is honored by all thirteen — the constraint is enforced as the *absence* of any voter-identity-bearing concept plus each VO's recorded discipline. No VO exists to "hold" INV-7; every VO obeys it.

**Result: all thirteen existing concepts survive the removal test.** No orphaned VO was found — consistent with the aggregate's confirmed status; recorded as verification, not assumed.

## 3. Candidates evaluated — where the entry condition discriminates

- **`IssuedAt` timestamp VO — REJECTED.** Tempting wrapper; but time is injected (`DateTimeImmutable $at`, per the no-ambient-time discipline) and no frozen invariant's expression weakens without a wrapper type. Fields-travel-together temptation, precisely what the principle forbids.
- **`RulingContent` composite (outcome + legitimacy + reason bundled) — REJECTED.** The bundle would be convenience packaging: INV-4 is already fully expressed by the three concepts and the fixation act. Bundling neither strengthens expression nor adds protection — the anti-pattern named in the entry condition, caught by it.
- **`AuthorityAssertion` (the Governance published-contract artifact) — DEFERRED.** Real and traceable (Q-1: candidate names AuthorityDecision/AuthorityAssertion/AuthorityGrant), but it belongs to the **crossing's design** — the published contract between Governance and Adjudication — whose realization was explicitly bounded to later artifacts by the Q-1 record. Introducing it now would design the contract prematurely. `IssuedByAuthority` remains the local opaque concept meanwhile.
- **`EvidenceSet` / admission-set concept — DEFERRED.** Traceable to the R-4-expanded rider, which awaits the Process Manager design; same deferral, same parent.

*(Two rejections + two deferrals: the entry condition demonstrated discriminative power on first application — Methodological Fitness Rule satisfied for it.)*

## 4. Open items carried (not resolved here)

- **Jurisdiction semantics** — the one confirmed VO whose business meaning needs domain clarification (new open item, flagged to the ARB; low urgency, non-blocking).
- **AuthorityAssertion naming/shape** — waits on the crossing design (Q-1's bounded deferral).
- **EvidenceSet** — waits on the PM design (R-4-expanded).
- Q-2 unchanged.

## Self-review

Every confirmed VO names its parent invariant(s) and passed the removal test — the entry condition held for all thirteen ✅ · the derivation ran truth → invariant → concept, never data-structure-first ✅ · the entry condition discriminated (two rejections, two deferrals) ✅ · no new code, no VO designed or modified — existing implementation cited as evidence; rejections and deferrals are decisions *not* to design ✅ · Q-1's constraint (presence, never validity), ADR-T16/T17, and the R-4-expanded deferral all preserved ✅ · one honest flag raised (Jurisdiction semantics) rather than silently passed ✅ · INV-7 correctly treated as a constraint on all concepts, not a concept ✅.

**Stop condition: STOP.** Value Objects only. Domain Events (artifact №6) and everything after begin on explicit ARB opening, after this artifact's review → refine → freeze.

---

## FROZEN (ARB, 2026-07-26)

**Artifact №5 is accepted and FROZEN.** The **Value Object Derivation Principle (VODP)** is adopted as permanent governance: *a Value Object is justified only when it strengthens the expression, validation, or protection of one or more accepted business invariants; convenience grouping or data packaging alone is insufficient.* Artifact №6 (Domain Events) is authorized under its entry condition: *every proposed Domain Event must trace to one or more accepted business invariants and represent a business-significant occurrence; if removing the event would not weaken the communication of a meaningful business occurrence, it does not become a Domain Event.*

---
*Frozen inputs: `EPIC-004E` (artifact №4, FROZEN — the eight invariants) · `EPIC-004D` (FROZEN) · `EPIC-004_Q1_Authority_Resolution.md` · ADR-T16/T17/UL-01 · VO inventory evidence: EPIC-003 Adjudication report + `DeterminationValueObjectsTest`.*
