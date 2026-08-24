# `KOS-CONTRACT-NEUTRALITY-001` — **Pass-1 performer authority: VERIFIED · DOES NOT COVER PASS 1**

**Work item:** `KOS-CONTRACT-NEUTRALITY-001` · **Date:** 2026-08-24
**Recorded by:** Governance — `claude-code-session:5928b9f9-b4d5-46e9-8c71-c295dace18f8` *(governance-recording; holds no lane here)*
**Question put by the PO/ARB:** *"do not authorize it yet if you have not verified that the active lane's authority actually covers Pass 1. The commission itself must not manufacture that authority."*

> ### ⛔ **PASS 1 IS NOT AUTHORIZED.** The verification returned **NO**, so the authorization condition the PO/ARB set is **not met**. No grant was written, no assignment amended, no lane started.

---

## 1 · Answer

**The active architecture lane's authority does NOT cover Pass 1.** Determined from the record, not inferred.

## 2 · Evidence

**The assignment** (`seq 38` `REGISTER`, lane `S4-architecture-v3-determination`, role `architecture`, predecessor `S1-verification-track1-php-adapter`) opens:

> *"V-3 ARCHITECTURE DETERMINATION — **proposal only**."*

**The governing grant** `G-KOS-CONTRACT-V3-ARCH` (`AUTHORIZED`) is scoped to **exactly two questions**, verbatim:

> *"**EXACTLY TWO QUESTIONS**: V-3a — should the PHP binding EMIT an L3 BehaviourReference fact for `$this->$m()` and `$this->$p()` … so that 'OBSERVED BUT NOT DETERMINABLE' REMAINS DISTINGUISHABLE FROM 'NOT OBSERVED'? V-3b — should the current PHP binding scope include recognition of `call_user_func([$this,'m'])` and related standard-library dynamic dispatch patterns?"* — **"PROPOSAL ONLY."**

**The amendment** `G-KOS-CONTRACT-V3-ARCH-AMD1` (`AUTHORIZED`) is explicit that it widens nothing:

> *"**SEPARATION ONLY**. It adds a mandatory separation condition … and changes **NOTHING else: no scope, no deliverable, no question**, no semantic decision, no other grant, no assignment, and no START."*

## 3 · Why Pass 1 falls outside it

Pass 1 requires: reading the LCOM4, Stage-2, breadth, Track-1 and V-3 evidence; **ranking the two V-3 determinations** against the authoritative record; **determining the current status of the whole contract-neutrality investigation**; and **classifying Deliverables B–F**.

None of that is one of the two authorized questions, and it is not a proposal about `$this->$m()` binding semantics. It is a **different deliverable at a different altitude** — an investigation-wide state reconciliation rather than a bounded architecture proposal. Authorizing it under this grant would be scope expansion by reinterpretation, which is precisely what *"the commission itself must not manufacture that authority"* forbids.

**A second, independent reason to pause:** the lane is **still `ACTIVE`** — its own two-question determination is not closed by a `STOP` or `COMPLETE`. Adding a new deliverable to an open assignment would also blur what that lane is answerable for.

## 4 · A V-3 ranking finding, delivered early because the record already carries it

The PO/ARB required that the two determinations be ranked **from the records rather than inferred**. Part of that ranking is already recorded, and it is worth having before Pass 1 starts:

- **The earlier determination is expressly NOT authoritative — by grant.** `G-KOS-CONTRACT-V3-ARCH` states, verbatim: *"the existing V-3 determination (`99aeac7c`, self-declared producing process `claude-code-session:1c8b041b`) **IS EVIDENCE AND PROPOSAL MATERIAL ONLY — NOT AN AUTHORITATIVE ARCHITECTURE DECISION** — because the process that authored the implementation also authored the determination **AGAINST ITS OWN IMPLEMENTATION**. **DO NOT TREAT IT AS A COMPLETED ARCHITECTURE ACT.**"*
- **The later determination is scoped `PROPOSAL ONLY`, and its lane is still open.** So on present evidence it is a **proposal**, not an accepted architecture decision.

**Therefore, `OBSERVED`:** the earlier determination's status is settled (evidence only). **`UNKNOWN`:** whether *either* constitutes an accepted decision — `2026-08-19-…-v3-decisions-registration.md` is the artifact that would say, and Governance has **not** read it, because doing so is Pass 1's work.

**The practical effect:** Pass 1's V-3 ranking is narrower than feared. It does not need to adjudicate between two rival determinations — the record already demotes one — but it must still establish whether anything has been **accepted**.

## 5 · A small conflation, corrected so it does not calcify

The PO/ARB's rationale diagram reads *"AST-019 / active architecture lane"*. **These are unrelated.** `AST-019` lives on `KOS-OPERATING-MODEL-001-AMENDMENT-001`, whose lifecycle is **closed** (adopted and authorized 2026-08-24). The active architecture lane here is `S4-architecture-v3-determination` on `KOS-CONTRACT-NEUTRALITY-001`. **No ownership, context, or authority passes between them.** The coordination rationale for preferring an existing actor stands on its own; it just does not derive from `AST-019`.

## 6 · The decision now required

The PO/ARB's preference — *"use the existing active architecture lane, provided that its governing authority permits it"* — has met its **proviso**, and the proviso failed. So the choice is the PO/ARB's, and each option is a human authorization act Governance cannot supply:

| Option | What it means |
|---|---|
| **A · Amend the V-3 grant to add Pass 1** | Keeps one actor and avoids the `EKS-07` hazard. Cost: an open assignment gains a second, different deliverable. |
| **B · Let the V-3 determination close first, then authorize Pass 1** | Cleanest separation of answerability. Cost: Pass 1 waits on a lane whose completion date is not recorded. |
| **C · Authorize Pass 1 as its own grant on the same lane** | One actor, two separately-scoped authorities — auditable, and it keeps each deliverable's authority distinct. |
| **D · Stop** | The commission stays registered and unstarted. |

**Governance recommends nothing among A–D** and asks for no additional review. **Independent verification and Architecture review remain `OPTIONAL`** for an evidence-reconciliation task; no binding rule requires either.

## 7 · Non-actions

No grant · no grant amendment · no assignment change (`seq 38`'s `executionContext` is immutable by construction in any case) · no lane · no appointment · no transition · no Pass-1 work · no reading of the V-3 determinations' content · nothing in the do-not-modify list touched · `KOS-LCOM4-CONTRACT-001` not reopened · the active lane not disturbed.

**Traceability:** PO/ARB direction 2026-08-24 · framing amendment `…-framing-amendment-continue-from-record.md` · prepared direction `…-PASS-1-evidence-reconciliation-direction.md` · `seq 38` `REGISTER` executionContext · grants `G-KOS-CONTRACT-V3-ARCH`, `G-KOS-CONTRACT-V3-ARCH-AMD1` · `EKS-07` · `INV-ATTR-1/2`
