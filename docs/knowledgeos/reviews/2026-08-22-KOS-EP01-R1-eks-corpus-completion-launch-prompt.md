# R-1 EKS Baseline Corpus Completion — Launch Prompt (review draft, v1)

> **Status:** ✅ **APPROVED by the Human Principal Architect (2026-08-22) — EXECUTING.**
> **Revision record (2026-08-22):**
> - **v1 — APPROVED CANDIDATE (this instrument):** R-1 = EKS Current Architecture Baseline corpus completion **only**; §1–§20 preserved verbatim + §21–§25 + U-/X- registers supplied; one commit; STOP for HPA review. P4 (Stage-4 Landscape) and P5 (AMENDMENT-6) remain separate, later acts.
> **Commission:** step ① of the binding sequence (HPA decision 2026-08-22, Option 3 — AMENDMENT-6 deferred to P5).
> **Phase (authoritative, plan §8):** completes **P1 — EKS Current Architecture Baseline (Stage 1)** corpus. This is the **same phase's completion**, not a new phase, not P4, not P5.
> **Plan:** `docs/plans/20260821-2118-eks-pks-aip-current-architecture-landscape-knowledgeos-evolution-study-plan.md` (§7 Stage 1 · §8 P1 · §9 DoD).
> **Prior state:** `docs/knowledgeos/architecture/20260821-2140-EKS-Current-Architecture-Baseline-Stage-1.md` — ⛔ **BROKEN/INCOMPLETE** marker at file top; file ends at **§20** (line 1162); its own body cross-references terminal sections that **do not exist**: **§21** (special questions) · **§22** · **§23** (P-rows) · **§24** (UNKNOWN areas **U-***) · **§25** (contradictions **X-***). The plan's Stage-1 terminal sections are not present as such. **Not a valid P1 deliverable as delivered.**
> **HPA framing (binding discipline, carried from the 2026-08-22 ruling):** (1) **mechanism ≠ invariant** — the implementation (`workflow-state.php`, scripts) is history; the invariant it protects ("state is reconstructable from authoritative history") is the candidate material; (2) **no premature unification** — evidence/assessment/observation/decision/authority/workflow-state/projection/diagnostic are recorded with their own semantics; similar structures ≠ same concept (DDD).
> **Do NOT:** launch until the HPA approves this instrument.

---

## The launch prompt (verbatim — for a FRESH isolated session, auto mode)

You are a **fresh, isolated session** executing **R-1 of the approved EP-01 study: EKS Current Architecture Baseline — corpus completion**. This phase is **read-only** and **bounded**. Your task is to **complete the missing corpus of the existing EKS baseline** — not to redesign it, not to rebuild it. If evidence does not establish something, record **UNKNOWN**.

### 0. Your ONLY task — and the explicit DO NOT list

```text
DO NOT rewrite the existing EKS baseline §1–§20.

DO NOT construct the EKS–PKS–AIP landscape (that is P4).

DO NOT classify shared concepts. DO NOT create the reconciliation ledger.

DO NOT identify kernel candidates. DO NOT run the AMENDMENT-6 progression (that is P5).

DO NOT design KnowledgeOS. DO NOT propose evolution.

DO NOT use the proposed KnowledgeOS architecture (Review Set, kernel-extraction
analyses) to interpret EKS current architecture.

DO NOT unify similar-looking concepts (evidence / assessment / observation /
decision / authority / workflow-state / projection / diagnostic) into any single
"KnowledgeObject" — record each with its own semantics (DDD: similar data
structures do not imply the same bounded context).

DO NOT mistake an implementation mechanism for the invariant it protects —
record both, each as its own kind of evidence.

Your only task is:

    complete the EKS Current Architecture Baseline corpus:
    supply the missing terminal sections §21–§25,
    the UNKNOWN register (U-*), and the contradiction register (X-*),
    from the EKS evidence corpus — and re-verify that the §1–§20 claims
    are grounded in a fully-read corpus (P3-F1 lesson).

After producing the completed baseline:

    STOP.

    Wait for Human Principal Architect review.
```

### 1. Deliverable (one commit; then STOP)

Complete the existing file `docs/knowledgeos/architecture/20260821-2140-EKS-Current-Architecture-Baseline-Stage-1.md` **in place**:

- **Preserve §1–§20 verbatim** — the diff must show additions/errata only, no silent rewrite of existing content.
- **Supply the missing terminal sections** as the baseline's own cross-references require: **§21** (special questions) · **§22** · **§23** (P-rows) · **§24** (UNKNOWN register **U-***) · **§25** (contradiction register **X-***), in the same voice and evidence discipline as §1–§20 and the P2/P3 baselines.
- **Replace the BROKEN/INCOMPLETE banner** with a **completed-status banner** only when the corpus is complete and re-verified; the banner records the completion (this commit), not a repair of content.
- If §1–§20 contain claims the fully-read corpus contradicts (the P3-F1 failure mode), apply the **smallest-scope erratum** at the affected site with an E-* record — never a silent rewrite.
- Commit message (verbatim): `docs(knowledgeos): Stage 1 — EKS Current Architecture Baseline corpus completion (READ-ONLY, PROPOSED)`. Pinned path as above. After the commit and bookkeeping, **STOP** for Human Principal Architect review.

### 2. Evidence corpus (the frozen baseline — read exhaustively BEFORE concluding "not found")

- The existing EKS baseline §1–§20 (preserve; its own cross-references and UNKNOWN/contradiction hints are the seed of §21–§25).
- The AMENDMENT-4 EKS corpus (binding inputs):
  - `docs/knowledgeos/brainstorming/20260801_1231_EKS Current Architecture Baseline.md` (60-section older draft = the draft for `KOS-ARCH-BASELINE-001`)
  - `docs/knowledgeos/brainstorming/20260821_2033_what_eks_today.md` (24-section newer supporting baseline)
  - `docs/knowledgeos/architecture/20260821_2032_EKS Current Architecture Baseline.md` (27-section supporting baseline; PKS/EKS identity ambiguity UNRESOLVED)
  - `docs/knowledgeos/brainstorming/20260821_2032_how_to_change_eks_into_kowledge_os_kernel.md` — ⛔ **Stage-4/5 material only, NOT current-architecture evidence**; appears only as *"not established by current EKS evidence"*
- Actual EKS implementation evidence in the repository (scripts, docs, `.claude/runtime/`, governance records, tests) — every artifact §1–§20 cites, plus anything required to complete §21–§25.
- **Corpus-boundary rule (P2/P3 precedent):** §5.3 is the governed starting corpus, not necessarily exhaustive. Any additional EKS evidence admitted is admitted **by role, with a recorded finding** — corpus-boundary decisions are findings, never silent choices.
- **Quarantined (never EKS current-architecture evidence):** the proposed KnowledgeOS architecture — Review Set `docs/knowledgeos/architecture/00…07-*`, kernel-extraction conclusions, the Digitalization Robot, and any other KnowledgeOS-proposal material.
- **P3-F1 lesson (binding):** the MIGRATION-PLAN incident — a 196KB artifact whose unread internal body contained current-state facts (23/30 concurrent-append trials; KOS_MECHANISM_PATH write surface) that overturned the baseline's completeness claim. Distinguish **NOT FOUND ≠ NOT PRESENT ≠ NOT EVIDENCED ≠ UNKNOWN**. Before concluding "not found", the corpus must be **exhaustively checked**. An honest UNKNOWN is valid; an artificial UNKNOWN is a defect.

### 3. What to reconstruct (all from EKS's own evidence)

- **§21–§25** exactly as the baseline's own cross-references and the P2/P3 template require — the section content is derived from the baseline's forward references, **never invented**.
- **UNKNOWN register (U-*)** — carry every UNKNOWN the corpus leaves open, from §1–§20 and the newly-read artifacts.
- **Contradiction register (X-*)** — every EKS-internal contradiction surfaced (implementation vs documentation, proposal vs implementation, current vs historical), each with **X-0n: concepts · evidence · nature · impact · resolution (OPEN / REQUIRES FUTURE VALIDATION)** — never silently reconciled.
- **Current-state facts discovered in previously-unread artifacts** (P3-F1 lesson) — folded in as evidence, and if they contradict a §1–§20 claim, as an E-* erratum.

### 4. Method (P2/P3 discipline carried forward)

- **Evidence-first.** Every substantive claim carries (a) an evidence tier (1–8, strongest = implementation), (b) a reality class **A–G** (A IMPLEMENTED · B IMPLICIT · C PARTIAL · D WRONG-BOUNDARY · E MISSING · F CONTRADICTED · G DOCUMENTED/HYPOTHESIS), or (c) a stable UNKNOWN/register reference. **No B/C/G finding is promoted to A.** Documentation describing a desired behaviour is never evidence that it is implemented.
- **UNKNOWN must remain UNKNOWN** — never fill a gap to make the reconstruction coherent.
- **Contradictions surfaced, never silently reconciled** — record both sides.
- **EKS is frozen (read-only):** no redesign, no frozen-baseline reopening, no technology change. If the corpus is insufficient for a deep reconstruction, record the UNKNOWN — do not invent.
- **DDD-as-analysis:** bounded-context/aggregate identification is candidate-only, derived from language · ownership · invariants · lifecycle · authority · consistency · dependency direction — never from folders/containers/repos/deployment units. No formal DDD validation.
- **Mechanism ≠ invariant (HPA framing):** when recording EKS facts, distinguish the implementation mechanism (e.g., `workflow-state.php`, a specific script) from the invariant it protects (e.g., "state is reconstructable from authoritative history"). Record both; never mistake one for the other; never promote either to a kernel candidate in R-1 (that is P5).

### 5. Verification (plan §9 DoD subset, binding)

- No implementation changes (`git status` shows only study docs + bookkeeping).
- Implemented ≠ conceptual.
- EKS read-only; the commit diff on the baseline shows **append/erratum only** (no rewrite of §1–§20).
- Output marked **PROPOSED · EVIDENCE-BASED · NON-AUTHORITATIVE · NOT ADOPTED**.
- Review Set never used as current architecture; no source content lost; no renaming of existing artifacts.

### 6. Bookkeeping

After the commit, update `.claude/sessions/2026-08-22.md` (R-1 execution record) and `.claude/CONTEXT.md` (NEXT → R-1 delivered, awaits HPA review; **P4 still CLOSED** pending the corrected-P3 HPA re-review). If the session terminates before commit/bookkeeping, the reviewing session completes bookkeeping and records the anomaly transparently — the deliverable text is never altered by the reviewing session.

### Final instruction

Produce the **completed EKS Current Architecture Baseline** (§1–§20 preserved + §21–§25 + U-/X- registers supplied + corpus re-verified), commit it, update bookkeeping, and **STOP for Human Principal Architect review**. **Do not perform Stage-4 landscape analysis in this session** — P4 (EKS–PKS–AIP Landscape) and P5 (AMENDMENT-6) are separate, later acts. **Do not amend the EP-01 plan. Do not modify P4/P5 scope.**
