# P3 AIP Reconstruction — Launch Prompt (review draft, v2)

> **Status:** PROPOSED launch instrument — for Human Principal Architect review. **Not yet executed.**
> **Phase (authoritative, plan §8):** **P3 = Stage 3 — AIP Current Architecture Reconstruction (READ-ONLY).** This fresh session executes **P3 only**.
> **Revision record (2026-08-21):**
> - **v1 — REJECTED by the Human Principal Architect:** a single session combining plan-P3 (AIP) + plan-P4 (Landscape) with two commits. The approved plan's phase boundaries are authoritative and must not be optimized away; an unreviewed AIP interpretation consumed by the same session's landscape would create a self-confirming architecture interpretation. *Any earlier wording suggesting the HPA's "P3 Landscape session" executes plan-P3 + plan-P4 is the rejected v1 framing and is superseded.*
> - **v2 — APPROVED CANDIDATE (this instrument):** P3 = AIP Current Architecture Reconstruction **only**; one commit; STOP for HPA review. **P4 = a separate fresh session, executed only after P3 has been HPA-reviewed and accepted.**
> **Commit message (verbatim, plan §8):** `docs(knowledgeos): Stage 3 — AIP Current Architecture Reconstruction (READ-ONLY, PROPOSED)`.
> **Plan:** `docs/plans/20260821-2118-eks-pks-aip-current-architecture-landscape-knowledgeos-evolution-study-plan.md` (§5.3 AIP inputs · §7 Stage 3 · §8 P3 · §9 DoD).
> **Phase numbering from here on: plan numbering** — P1 EKS · P2 PKS · **P3 AIP** · P4 Landscape · P5 Reconciliation/Kernel/Evolution/DDD-validation.

---

## The launch prompt (verbatim — for a FRESH isolated session, auto mode)

You are a **fresh, isolated session** executing **P3 of the approved EP-01 study: Stage 3 — AIP Current Architecture Reconstruction**. This phase is **read-only**. Your task is to **discover what AIP is, not what we want AIP to become**. If evidence does not establish something, record **UNKNOWN**.

### 0. Your ONLY task — and the explicit DO NOT list

```text
DO NOT compare AIP with EKS or PKS.

DO NOT construct the EKS–PKS–AIP landscape.

DO NOT classify shared concepts.

DO NOT create the reconciliation ledger.

DO NOT identify kernel candidates.

DO NOT design KnowledgeOS.

DO NOT propose evolution.

Your only task is:

    reconstruct the current architecture of AIP.

After producing the AIP Current Architecture Reconstruction:

    STOP.

Wait for Human Principal Architect review.
```

### 1. Deliverable (one commit; then STOP)

Produce the **AIP Current Architecture Reconstruction** — a reconstruction note (a baseline, **not a redesign**). Commit message (verbatim): `docs(knowledgeos): Stage 3 — AIP Current Architecture Reconstruction (READ-ONLY, PROPOSED)`. Pinned output path: `docs/knowledgeos/architecture/20260821-<HHMM>-AIP-Current-Architecture-Reconstruction-Stage-3.md`. After the commit and bookkeeping, **STOP** and wait for Human Principal Architect review.

### 2. Evidence corpus (the frozen baseline — read-only)

- The plan §5.3 AIP inputs (binding, read-only): `AIP-iteration-1-construction.md` (the frozen platform baseline) · `KOS-AIP04-DISCOVERY-001-capability-architecture-analysis.md` + its ADR · `2026-08-19-six-role-operating-model-adoption.md` · `KOS-AIP-GOV-STATE-DURABILITY-{ADR,IMPLEMENTATION-DESIGN,MIGRATION-PLAN}.md` (+ AMD4/5/6) · `20260817-145153-ai-engineering-platform-6-role-model.md`.
- **Corpus-boundary rule (P2 precedent):** §5.3 is the governed starting corpus, not necessarily exhaustive. Any additional AIP evidence admitted is admitted **by role, with a recorded finding** — corpus-boundary decisions are findings, never silent choices.
- **Quarantined (never AIP current-architecture evidence):** the proposed KnowledgeOS architecture — Review Set `docs/knowledgeos/architecture/00…07-*`, kernel-extraction analyses, the Digitalization Robot, and any other KnowledgeOS-proposal material. It appears in this reconstruction only in the form *"Not established by current AIP evidence."*
- **Do not re-derive EKS or PKS.** Their baselines are not inputs to this phase. Record only what **AIP's own evidence states** about them (see §3, relationship rows).

### 3. Reconstruct AIP across (all from AIP's own evidence)

purpose · capabilities · actors · ubiquitous language (AIP's own) · ownership · authority · lifecycle · persistence · runtime · workflows · integrations · dependencies · evidence · governance · **what AIP owns** · **what AIP consumes** · **what AIP produces** · **what AIP explicitly does NOT own** · **relationship to EKS** (as AIP's own evidence states it — recorded as AIP's self-declared stance, **not a comparison**) · **relationship to PKS** (same — recorded as AIP's self-declared stance, **not a comparison**).

Plus the **knowledge-concern vs platform-concern** question: identify which AIP responsibilities are knowledge concerns vs platform concerns — **but do NOT decide the correct boundary yet.** Record the candidate split as evidence/finding, not as a boundary decision.

### 4. Method (P2 discipline carried forward)

- **Evidence-first.** Every substantive claim carries (a) an evidence tier (1–8, strongest = implementation), (b) a reality class **A–G** (A IMPLEMENTED · B IMPLICIT · C PARTIAL · D WRONG-BOUNDARY · E MISSING · F CONTRADICTED · G DOCUMENTED/HYPOTHESIS), or (c) a stable UNKNOWN/register reference. **No B/C/G finding is promoted to A.** Documentation describing a desired behaviour is never evidence that it is implemented.
- **UNKNOWN must remain UNKNOWN** — never fill a gap to make the reconstruction coherent.
- **Contradictions surfaced, never silently reconciled** — record both sides.
- **AIP is frozen (read-only):** no redesign, no frozen-baseline reopening, no technology change (plan §7 Stage 3). If the §5.3 baseline is insufficient for a deep reconstruction, record the UNKNOWN — do not invent.
- **DDD-as-analysis:** bounded-context/aggregate identification is candidate-only, derived from language · ownership · invariants · lifecycle · authority · consistency · dependency direction — never from folders/containers/repos/deployment units. No formal DDD validation.

### 5. Verification (plan §9 DoD subset, binding)

No implementation changes (`git status` shows only study docs + bookkeeping) · implemented ≠ conceptual · AIP read-only · output marked **PROPOSED · EVIDENCE-BASED · NON-AUTHORITATIVE · NOT ADOPTED** · Review Set never used as current architecture · no source content lost · no renaming of existing artifacts.

### 6. Bookkeeping

After the commit, update `.claude/sessions/2026-08-21.md` (P3 execution record) and `.claude/CONTEXT.md` (NEXT → AIP reconstruction awaits HPA review). If the session terminates before commit/bookkeeping (as P2 did at the API-402 boundary), the reviewing session completes bookkeeping and records the anomaly transparently — the deliverable text is never altered by the reviewing session.

### Final instruction

Produce the **AIP Current Architecture Reconstruction**, commit it, update bookkeeping, and **STOP for Human Principal Architect review**. **Do not perform Stage 4 landscape analysis in this session** — after P3 is reviewed and accepted, a separate fresh session will execute P4 (EKS–PKS–AIP Landscape). **Do not amend the EP-01 plan. Do not modify P4/P5 scope.**
