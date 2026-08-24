# `KOS-CONTRACT-NEUTRALITY-001` — **Pass 1 AUTHORIZED** · grant `G-KOS-CONTRACT-PASS1-RECONCILE`

**Work item:** `KOS-CONTRACT-NEUTRALITY-001` · **Date:** 2026-08-24 · **Option taken:** **C**
**Recorded by:** Governance — `claude-code-session:5928b9f9-b4d5-46e9-8c71-c295dace18f8` *(recording the PO/ARB's authorization; Governance does not authorize)*

> ⛔ **Authorized ≠ started, and authorized ≠ adopted.** This grant permits one evidence pass. It decides nothing about contract neutrality, adopts nothing, and closes no lane.

---

## 1 · The authorization (verbatim)

> *"I would choose C … Create a new, independently scoped grant for Pass 1, performed by the same lane/actor. Same actor, separate authority, separate deliverable, separate auditability. … The new Pass-1 grant should be very narrowly worded. … The Pass-1 output should remain an evidence determination, not an architecture decision. … I would therefore proceed with Option C, with the new grant explicitly bounded to the reconciliation task."*

Options **A**, **B** and **D** were expressly declined, with reasons recorded by the PO/ARB.

**The governing principle this act rests on, in the PO/ARB's words:**

> **"The grant is the authority; the assignment text cannot manufacture authority."**

That is why a separate grant exists rather than a reinterpretation of the V-3 grant — the authority verification established that the V-3 grant does **not** cover Pass 1.

## 2 · What was written, and what deliberately was not

**Written:** one grant — `G-KOS-CONTRACT-PASS1-RECONCILE` · `AUTHORIZED` · `registeredBy: governance` · `humanActRef` = the verbatim act above. Grant count 27 → 28.

**Deliberately not written:** no lane · no assignment · no transition (still **40**) · **no change to `G-KOS-CONTRACT-V3-ARCH` or `G-KOS-CONTRACT-V3-ARCH-AMD1`**, both verified still `AUTHORIZED` and still bounded to V-3a/V-3b, proposal only.

**The resulting shape — one actor, two authorities:**

```
KOS-CONTRACT-NEUTRALITY-001
  ├── G-KOS-CONTRACT-V3-ARCH (+AMD1)   → V-3 determination · exactly two binding questions · PROPOSAL ONLY
  └── G-KOS-CONTRACT-PASS1-RECONCILE   → Pass 1 evidence reconciliation
        performed by the SAME lane: S4-architecture-v3-determination
```

No second lane and no second actor, so the `EKS-07` coordination hazard is avoided — while each deliverable keeps its own authority and its own audit trail.

## 3 · The bounds, as the PO/ARB set them

**Authorizes exactly one thing:** reconciliation of the **current authoritative state** of `KOS-CONTRACT-NEUTRALITY-001` from the already-identified existing evidence.

**Does not authorize:** changing the contract · modifying LCOM4 or reopening `KOS-LCOM4-CONTRACT-001` · implementing Python · changing the V-3 determination · closing the existing V-3 lane · architecture redesign · deciding the target language · modifying `KOS-ARCH-BASELINE-001`. Governance added, consistent with the standing framing: the EKS baseline, the proposed KnowledgeOS architecture, bounded-context definitions, target technology decisions, platform and governance architecture, any golden fixture, and starting a second lane or actor.

**The containment rule, carried onto the grant because it is the failure mode that matters:**

> **Pass 1 must remain an evidence-reconciliation activity and must not silently become the implementation or contract-correction activity.**

If reconciliation reveals that correction or implementation is needed, **that is a finding to report — not work to perform.** Continuation requires a separate authorization.

## 4 · The V-3 ranking rule, made binding on the pass

The two records are **not symmetric candidates**, and the pass may not treat them as such:

- **Earlier determination** (`99aeac7c`, producer self-declared `1c8b041b`) — already classified by its own governing grant as **evidence and proposal material only, not an authoritative architecture decision**, and *"not a completed architecture act"*, because that process judged its own implementation.
- **Later determination** — scoped **`PROPOSAL ONLY`**, and its lane remains open. **Being later does not make it authoritative.**
- **Therefore:** the pass reads the v3-decisions-registration to establish whether anything was **accepted**. **No averaging, no merging, no "latest therefore authoritative" shortcut.**

## 5 · Deliverable required

An **evidence determination** — explicitly not an architecture decision — that: states the present investigation status with every item classified `OBSERVED`/`DECLARED`/`INFERRED`/`PROPOSED`/`UNKNOWN` and **no gap filled by assumption**; ranks the two V-3 determinations from the record; classifies Deliverables **B–F** as `ALREADY SATISFIED` / `NEEDS CONTINUATION` / `STILL REQUIRED`; and names the **smallest** set of remaining semantic or conformance gaps between the record and a defensible neutrality verdict.

Verdict and failure vocabularies are unchanged. **Do not force a positive verdict** — `NOT YET DETERMINABLE` is a legitimate answer.

## 6 · Review

**None required.** Independent verification and Architecture review are **`OPTIONAL`** for an evidence pass; no binding rule requires either, and neither is commissioned. **Governance added no review.**

## 7 · Mechanism observation — `RECORDED`, not promoted, no item created

`AST-015`'s `authorized --session --scope` check resolves a grant by **exact string match on `scope`** (`workflow-state.php:385`). Because this estate's grants carry multi-thousand-character prose scopes — `G-KOS-CONTRACT-V3-ARCH` is ~2,960 characters — the machine check is **not usable in practice**; grant coverage is established by reading, as it was here. This is an observation about the mechanism, not a defect claim and not a finding against any grant. **Governance did not create a work item for it** (`ES-006.1`; first clean occurrence, and no existing owner was searched for because nothing is being proposed). Recorded only so it is not rediscovered as news.

## 8 · Status

| | |
|---|---|
| Commission | registered · framing amended to continue from the record |
| **Pass 1** | **AUTHORIZED** (`G-KOS-CONTRACT-PASS1-RECONCILE`) — **not yet started** |
| Performer | existing lane `S4-architecture-v3-determination`, same actor, separate authority |
| Existing V-3 grant | **unchanged**, still bounded to V-3a/V-3b, proposal only |
| Active V-3 lane | **unchanged**, still open on its original two questions |
| Review | none required |
| Direction | `…-PASS-1-evidence-reconciliation-direction.md` — banner updated from held to authorized; content unchanged |

**Traceability:** PO/ARB act 2026-08-24 (Option C) · authority verification `…-PASS-1-performer-authority-verification.md` · framing amendment `…-framing-amendment-continue-from-record.md` · registration `…-commission-registration.md` · direction `…-PASS-1-evidence-reconciliation-direction.md` · grant `G-KOS-CONTRACT-PASS1-RECONCILE` · `G-KOS-CONTRACT-V3-ARCH` (+`AMD1`) · `EKS-07` · `G-2`/`R5b` · `ES-006.1`
