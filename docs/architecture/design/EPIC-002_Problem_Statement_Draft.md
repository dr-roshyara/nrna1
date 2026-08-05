# EPIC-002 — Problem Statement (DRAFT)

**Authority:** generated (AI-produced under Principal Architect directive, 2026-07-11) — **not authoritative until ARB ratification**
**Purpose:** frame EPIC-002 Strategic Discovery *before* it begins, so discovery answers questions instead of defending assumptions. This artifact deliberately precedes any IDD or design.
**Subject:** the **Evidence** operational bounded context (per board sequencing, EPIC-002 = Evidence) — with the decomposition itself held open (see Q0).
**Companion:** `EPIC-002_Context_Dependency_Map_Draft.md` (dependency evidence; one input among several, not a conclusion).

---

## 1. Problem

The current system stores evidence **implicitly**.

- Evidence-shaped types exist but live in a frozen legacy namespace (`app/Domain/Election/Security/Simplified/` — snapshots, envelopes, classifications, observation contexts) with no aggregate roots, no repository, no persistence contract, no published events.
- Five security domain events (`ObservationRecorded`, `LegitimacyGranted`, `LegitimacyEvaluated`, `DivergenceObserved`, `SovereigntyBoundaryCrossed`) exist but are **dispatched nowhere** — dormant code with undefined purpose; the Evidence-Context ARB gate that would have ruled on them was never recorded (blank decision record).
- The implemented constitutional core already **depends on evidence it cannot resolve**: `Determination` carries an `EvidenceEnvelopeRef` ("owned by the Evidence context — reference only"), but no Evidence context exists to own it.
- Evidence-related capabilities are scattered: `SecurityEventRecorder` (recording), `app/Domain/Election/Replay/*` (replay, multi-home), audit logging (infrastructure) — with no single owner of what "evidence" *means*.

Consequence: the platform can *apply* constitutional corrections (proven, PB-004..006) but cannot yet *ground* them — "which observable facts support this determination, and why are they trustworthy" has no owning model.

## 2. Questions discovery must answer

**Q0 (decomposition — asked first, per map §4):** Is "Evidence" the right bounded context at all? Does the certified operational decomposition (Evidence · Appointment · Voting · Read Models) survive contact with the domain literature and the code evidence — or should discovery propose a rename / merge / split (→ BDR re-open trigger with evidence)?

Then, for the Evidence subject:

1. **What is evidence?** (observation vs signal vs evidence vs snapshot — the Round-12-era ubiquitous language is a working hypothesis, not proven)
2. **Who owns evidence?** (recording authority vs interpretation authority — Round 12 ruled Evidence has *no authority over meaning*; does that hold?)
3. **What lifecycle does evidence have?** (recorded → frozen → evaluated → cited → superseded? immutability point? retention?)
4. **What is *constitutional* evidence?** (what distinguishes evidence that can ground a Determination from operational telemetry?)
5. **What is provenance?** (chain of custody for facts — and how does it relate to, without being confused with, the messaging-layer `EventProvenance` correlation/causation lineage, which is Integration-Event-only by invariant?)
6. **What is *admissible* evidence?** (who decides admissibility — Evidence, Adjudication, or a policy between them?)
7. **What is replay?** (BDR-06 left Replay's placement open — Application capability over Evidence is a hypothesis to confirm or refute here)
8. **What belongs *outside* Evidence?** (audit telemetry, legitimacy derivation, verification — the boundary questions VR-1..VR-5 deferred earlier)
9. **What happens to the five dormant security events and the blank ARB gate?** (adopt into the model, redesign, or delete — an explicit ruling, not continued dormancy)

## 3. Deliverables (Stream A, Strategic Discovery ONLY)

- Literature review (see §5 — building on the existing Round 36 base, not repeating it)
- Ubiquitous language (revised or confirmed)
- Context map (Evidence's relationships, in the map's relationship taxonomy)
- Ownership map (which invariants Evidence owns vs preserves)
- Open questions register
- Recommendation to the ARB (decomposition confirm/challenge · Q1–Q9 answers or explicit unknowns · ordering evidence per map §4)

## 4. Explicitly NOT produced

- Aggregates · Repositories · Domain events · APIs · Database schema · IDD · any tactical model

**STOP after the recommendation.** Tactical design begins only after the ARB rules on the discovery output. (Round 12's limited-strategic-authorization boundary remains in force until superseded by that ruling.)

## 5. Literature domains

Already covered (Round 36A/36B — reuse, cite, do not redo): ElectionGuard · Helios/Benaloh · Scantegrity/Prêt-à-Voter · risk-limiting audits · auditability concept families · threat models (36C).

New for EPIC-002 (not yet in the corpus):
- Digital evidence management & chain of custody
- Provenance models (W3C PROV)
- Administrative adjudication (how tribunals treat evidence)
- Constitutional law workflows (admissibility, standards of proof)
- Event sourcing in regulated domains
- Election auditing practice (operational, beyond the RLA theory already reviewed)
- Transparency & accountability system design

Goal: not to find "the answer" but to expose the current model to mature external models and record where it is reinforced and where it should evolve.

## 6. Inputs

`EPIC-002_Context_Dependency_Map_Draft.md` · BDR v1.1 (`Round49-06`) + `Round49-07` (standing order) · `Round50-01` (EvidenceEnvelope contract, "Evidence items VO-vs-Entity not frozen") · Round 12 Evidence authorization (strategic-only) + `EvidenceContext.md` corpus · Round 36A/36B/36C literature + threat models · the implemented core's reference surface (`EvidenceEnvelopeRef`) · legacy `Security\Simplified` + Replay code as *behavioral evidence* (what the system already does implicitly).

---
*Traceability: Principal Architect directives 2026-07-11 (problem-statement-before-discovery; decomposition-before-ordering; literature-first). Draft awaits ARB ratification at/after the EPIC-001 retrospective.*
