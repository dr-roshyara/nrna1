# EPIC-004G Determination Domain Events

**Kind:** Tactical DDD artifact №6 (one artifact → ARB review → refine → freeze → next). **Role:** implementation architect from the frozen baseline.
**Entry condition (ARB, binding):** *every proposed Domain Event must trace to one or more accepted business invariants AND represent a business-significant occurrence. If removing the event would not weaken the communication of a meaningful business occurrence, it does not become a Domain Event.*
**Litmus test (ARB, binding — mirrors the invariant litmus):** *would a domain expert naturally describe this occurrence as something that happened in the business, regardless of how the software is implemented?* The direction is Responsibility → Truth → Invariant → state change → business event — **never** method-executed → event.
**Scope discipline (frozen precedents honored):** Domain Events only. The Domain-Event ≠ Integration-Event separation is a hard invariant (EventProvenance lives only on integration events; enrichment like `resolution` is application-level — the ChallengeResolvedIntegration precedent). The integration counterpart of any domain event (adapter, hydrator, schema versioning) is out of scope here.
**The honest shape of this artifact:** the confirmed aggregate emits exactly **one** domain event — and its *silences* (prepare emits nothing; finalize emits nothing; refusals emit nothing) are themselves frozen design decisions (Round 50-07; ADR-T19). The audit therefore defends the deliberate non-events under the entry condition as much as it confirms the event.

---

## 1. Derivation: business-significant occurrences the truths imply

| Source truth/invariant | Candidate occurrence | Litmus (domain-expert language?) |
|---|---|---|
| T-2/INV-3, T-4/INV-4, T-6/INV-6 | "The binding ruling on the contested outcome was issued" | **Yes — canonical.** The Canonical Event Catalog names it; every consumer speaks it |
| T-1/INV-1 (progression) | "A ruling record was prepared (drafted)" | **No.** The business observes rulings, not drafts — internal readiness, not an occurrence |
| T-1/INV-2 (finality) | "The determination became final" | **Borderline** — finality is a business concept, but see §3 |
| INV-3/INV-B1 (uniqueness) | "A second ruling attempt was refused" | **Weak** — a protective refusal, communicated to the requester, not an occurrence the business announces |
| The declare-failure obligation (COL-5a) | "Adjudication declared the evidence insufficient" | **Yes — genuinely business-significant** — but see §3 (seat) |

## 2. Confirmed: the one domain event

### `DeterminationIssued` — CONFIRMED, unchanged

- **Traces to:** INV-3 (the one act of ruling), INV-4 (the event **is** the fixation — ADR-T19 Model B: ruling content lives in the immutable event, not mutable state), INV-6 (the accountability triple rides inside it), INV-7 (its payload discipline).
- **Business occurrence:** "the binding ruling on a contested election was issued" — the catalog's own words; sole producer Adjudication; consumers Election, Contestation — never Voting (restricted, per catalog).
- **Removal test:** removing it silences the entire correction loop — Election cannot correct, Contestation cannot resolve; the single most consequential communication in the constitutional conversation. **The strongest removal-test result of any artifact so far.**
- **Special standing (recorded):** this event is not merely a notification *about* the ruling — per ADR-T19 Model B it is the ruling's **record**. INV-4's fixation and this event are the same fact. That is why the aggregate needs no second event to "store" content.

## 3. Rejected and deferred — the deliberate silences, defended

- **`DeterminationPrepared` — REJECTED.** Fails the litmus: no domain expert describes drafting an internal record as a business occurrence; nothing downstream may lawfully react to a not-yet-issued ruling (reacting to drafts would undermine INV-4's fixation discipline). Existing design (prepare emits nothing) confirmed as correct, now with grounds on the record.
- **`DeterminationFinalized` — REJECTED, with a recorded reversal condition.** Finality is a real business concept (INV-2), but the entry condition asks what *communication* weakens if absent: today, nothing — no consumer exists, no business process observes the Issued→Final closure, and the ruling's effect was fully communicated at issuance. The existing "Final emits nothing" ruling (Round 50-07 v1.2) is confirmed rather than reopened. **Reversal condition:** if a future business process must observe finality — e.g., an appeal-window closure or the Q-2 window's arithmetic needing a finality timestamp downstream — the candidate returns through ARB review with that consumer as evidence.
- **`IssuanceRefused` — REJECTED for the domain event set.** The refusal is a protective non-creation communicated to the requester (the existing exception), not an occurrence the business announces. **Noted, not dropped:** whether *attempted* duplicate rulings deserve an observability/audit record is a legitimate question — for the audit/security concern, not the aggregate's domain events.
- **`AdjudicationFailureDeclared` — DEFERRED to the Process Manager design.** Genuinely business-significant ("the evidence was insufficient; no ruling can issue" — the ES declare-failure obligation; domain experts absolutely say this) and traceable to the declare-failure truth — but the confirmed aggregate does not conclude proceedings; the occurrence belongs to the judgment half, whose realization (the PM) is designed later. Same parent as the R-4-expanded/EvidenceSet deferrals. When the PM design lands, this is expected to be its **first-class event candidate**.

## 4. Open items carried

- The `AdjudicationFailureDeclared` candidate → PM design (with EvidenceSet and the R-4-expanded seat — the deferrals now visibly cluster around one future design, which is itself information).
- The `DeterminationFinalized` reversal condition → dormant until a consumer exists.
- Q-2 · Jurisdiction semantics — unchanged.

## Self-review

Exactly one event confirmed, with the strongest removal-test result in the artifact series ✅ · every candidate faced the litmus in domain-expert language; the direction was truth → invariant → state change → occurrence, never method → event ✅ · the aggregate's deliberate silences are now *defended with grounds*, not merely inherited — and the frozen Round 50-07 ruling was confirmed, not reopened ✅ · the entry condition discriminated (three rejections, one deferral) — Methodological Fitness Rule satisfied ✅ · the Domain-Event ≠ Integration-Event boundary respected; no integration/schema/adapter content ✅ · the deferral cluster around the PM design is surfaced as a finding ✅ · no code designed or modified ✅.

**Stop condition: STOP.** Domain Events only. Commands (artifact №7) and everything after begin on explicit ARB opening, after this artifact's review → refine → freeze.

---

## FROZEN (ARB, 2026-07-26) — with two principles adopted and one reclassification

**Artifact №6 is accepted and FROZEN.** Adopted as permanent governance (→ MEMORY):
- **Architectural Silence Principle (ASP):** *the absence of an architectural element is a decision, not a default; rejected candidates and deliberate non-events are recorded with rationale and, where appropriate, reversal conditions.*
- **Artifact Derivation Principle (ADP):** *every tactical artifact derives from the immediately preceding frozen artifact; new concepts may not bypass the derivation chain without explicit ARB authorization.*

**Reclassification:** the PM deferrals (FailureDeclared · EvidenceSet · R-4-expanded seat) are recorded as an **Emergent Design Cluster**, not independent TODOs — the clustering is architectural evidence that these are symptoms of one missing concept, to be **opened together** when the Process Manager is designed. Artifact №7 (Commands) is authorized under its entry condition: *business occurrence → business intention → command — never public method → command; a command answers "what business intention is being expressed toward the aggregate?", never "which method exists?"*

---
*Frozen inputs: `EPIC-004E` (invariants) · `EPIC-004F` (VOs) · `EPIC-004D` (truths) · Round 50-07 v1.2 · ADR-T19 Model B · Canonical Event Catalog v1.0 · the Domain-Event ≠ Integration-Event invariant (ADR-MP-06 discipline).*
