# Registration — dedicated Architecture assignment for Track-1 finding V-3

**Registered by:** Governance, on the delivered PO/ARB act · 2026-08-19
**Work item:** `KOS-CONTRACT-NEUTRALITY-001` · **assignment:** `S4-architecture-v3-determination` (role: architecture)
**Record:** grant `G-KOS-CONTRACT-V3-ARCH` (AUTHORIZED) · `REGISTER` seq 38 · `HANDOFF` seq 39
**⛔ START NOT PERFORMED.** The engine refused a Governance-issued START, as it must: *"START requires the recorded human start act — a handoff alone never yields ACTIVE (G-3)."*

---

## 1 · The load-bearing clause of the act

> **The Track-1 implementation process already produced a V-3 determination. That determination is EVIDENCE AND PROPOSAL MATERIAL ONLY. It is NOT an authoritative Architecture decision, because the process that authored the implementation also authored the determination against its own implementation. DO NOT TREAT IT AS A COMPLETED ARCHITECTURE ACT.**

**The document referred to exists and is now typed by this act:**
`docs/publicdigit/reviews/2026-08-18-KOS-CONTRACT-NEUTRALITY-001-v3-architectural-determination.md` (`99aeac7c`), self-typed *"NARROW ARCHITECTURE DETERMINATION"*, self-declaring its producing process as `claude-code-session:1c8b041b`.

**What changes:** its *type* is not what it declares. It is **input**, not **authority** — an instance of `R-34`/`P-2` (a producer must not adjudicate its own work) applied to a determination rather than to a verification. The act does not withdraw the document, does not fault its reasoning, and does not pre-judge whether the new determination will reach the same conclusion.

**Registered into the assignment's execution context:** the determination must be produced by a process **other than the Track-1 implementer**. Per `INV-ATTR-2` that separation is **DECLARED, NOT ATTESTABLE** — the record cannot corroborate it (this is `G-2`, the accepted root gap), so the attribution is **Declared-Recorded**, and the deliverable must disclose its producing process, self-declared.

## 2 · Scope — exactly two questions

| | Question |
|---|---|
| **V-3a** | Should the PHP binding **emit** an L3 `BehaviourReference` fact for `$this->$m()` / `$this->$p()` with `ComputedTarget` / `Undetermined` relation / `NotDeterminable` determinability — **so that "observed but not determinable" remains distinguishable from "not observed"?** |
| **V-3b** | Should the current PHP binding scope include `call_user_func([$this,'m'])` and related standard-library dynamic dispatch? **If yes:** what explicit knowledge boundary applies. **If no:** what limitation the contract must state. |

**Why V-3a is not a detail:** the verifier's own framing is that `expected.json`'s pinned decision *"requires those calls to be excluded as not determinable — a claim that cannot be made about a reference that was never emitted."* The distinction at issue is the one Decision 13.3 ruled must **never be merged**: *not the own class* vs *not determinable*. A fact that is never emitted cannot carry either.

**Evidence base registered:** the Track-1 independent verification (`50d55d26`) · the implementer's determination **as evidence, not authority** · the accepted L3 model · Decisions 13.3, 13.5, 13.7 · `expected.json`'s pinned decisions · existing PHP binding behaviour.
⛔ **Decision 1 stands: implementation behaviour is not the specification.**

## 3 · Method, as commissioned

**DDD test, V-3a:** what is the domain fact · what is binding responsibility · what invariant is required · **what distinction must L3 preserve** · does the rule already follow from accepted semantics.
**DDD test, V-3b:** **is this language syntax or library semantics** · what bounded responsibility owns it · what is the reason to change · **what is the knowledge boundary** · what follows from including or excluding it.

**Classification duty:** every conclusion marked **OBSERVED / DECIDED / INFERRED / PROPOSED / OPEN**. ⛔ **No silent upgrade of PROPOSED → DECIDED.**

## 4 · Prohibitions registered

⛔ modify the implementation · modify expected evidence · modify fixtures · modify Decision 13.3 · modify Decision 13.5 · perform verification · accept the implementation · close Track 1 · redesign Architecture D broadly.
**If the existing specification is insufficient, that question returns explicitly to PO/ARB rather than being resolved inside the lane.**

## 5 · Deliverable

`2026-08-18-KOS-CONTRACT-NEUTRALITY-001-V3-architecture-determination.md` — ten sections: V-3 evidence · V-3a analysis · V-3b analysis · L3 implications · binding boundary · 13.7 evidence implications · architectural consequences · **proposed invariant wording** · **proposed library-dispatch scope wording** · **PO/ARB decisions required**.

**The document is a PROPOSAL. STOP after delivery. Next actor: PO/ARB.**

## 6 · Two points returned to the PO/ARB rather than decided here

1. **Filename.** The act stamps the deliverable `2026-08-18`; the act was delivered on **2026-08-19**, and a near-identically named file already exists (`…-v3-architectural-determination.md`, lowercase `v3`, *architectural*). The two differ only by case and one word — distinct on disk, confusable to a reader. The name is registered **exactly as the act gave it**; Governance neither renames the commissioned deliverable nor re-dates it.
2. **Handoff, not closure.** `S1-verification-track1-php-adapter` held mutation ownership and has been **HANDED_OFF** to release it. **It is not closed.** Closure of that lane remains a separate PO/ARB act (`G-1`: closure is a governance act).

## 7 · Not done

No START · no determination · no contract change · no implementation · no verification · no Track-1 closure · no ruling on whether the existing determination's conclusion is right or wrong.

**Traceability:** the PO/ARB act 2026-08-19 · Track-1 independent verification `50d55d26` (V-3, report line 313) · existing determination `99aeac7c` (evidence only) · Track-1 delivery `4c6c1dac` · Decisions 13.1 / 13.3 / 13.5 / 13.7 · Decision 1 · `R-34`/`P-2` · `INV-ATTR-2` · `G-2` · `G-3` · `G-1`
